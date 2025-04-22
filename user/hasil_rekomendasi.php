<?php
session_start();
require_once(__DIR__ . '/../includes/init.php');

// Fungsi untuk reset data menu yang dipilih
if (isset($_GET['reset'])) {
    unset($_SESSION['selected_menus']);
    header("Location: dashboard-user.php");
    exit;
}

// Pastikan menu sudah dipilih dan validasi jumlah pilihan
if (!isset($_SESSION['selected_menus']) || count($_SESSION['selected_menus']) < 2) {
    echo '
    
<!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error: Pilihan Menu</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                font-family: Poppins, sans-serif;
                background-image: url("../assets/gambar/makanan.jpg");
                background-size: cover;
                color: #fff;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .error-container {
                background: rgba(0, 0, 0, 0.7);
                padding: 40px;
                border-radius: 12px;
                text-align: center;
                
            }
            .error-container h1 {
                font-size: 2.5rem;
                color: #ffc107;
            }
            .error-container p {
                font-size: 1.2rem;
            }
            .error-container a {
                padding: 10px 20px;
                text-decoration: none;
                color: #fff;
                background: #28a745;
                border-radius: 5px;
                font-weight: bold;
            }
            .error-container a:hover {
                
                background: #218838;
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <h1>Oops!</h1>
            <p>Silahkan Pilih Lebih dari 1 Menu Makanan untuk Melanjutkan.</p>
            <a href="dashboard-user.php">Kembali ke Dashboard</a>
        </div>
    </body>
    </html>';
    exit;
}


// Mengambil data menu yang dipilih oleh user
$selectedMenus = $_SESSION['selected_menus'];

// Query untuk mendapatkan data penilaian
$query = "
    SELECT p.id_alternatif, p.id_kriteria, p.nilai, k.bobot, k.kriteria, a.alternatif, 
           s.sub_kriteria, s.nilai AS nilai_sub_kriteria
    FROM penilaian p
    JOIN kriteria k ON p.id_kriteria = k.id_kriteria
    JOIN alternatif a ON p.id_alternatif = a.id_alternatif
    LEFT JOIN sub_kriteria s ON p.nilai = s.id_sub_kriteria
    WHERE p.id_alternatif IN (" . implode(",", $selectedMenus) . ")
";
$result = mysqli_query($koneksi, $query);

// Inisialisasi variabel
$matriksKeputusan = [];
$alternatifDetails = [];
$kriteriaList = [];
$bobot = [];

// Mengelompokkan data ke matriks keputusan
while ($row = mysqli_fetch_assoc($result)) {
    $matriksKeputusan[$row['id_alternatif']][$row['id_kriteria']] = $row['nilai_sub_kriteria']; // Menyimpan nilai subkriteria
    $bobot[$row['id_kriteria']] = $row['bobot'];
    $alternatifDetails[$row['id_alternatif']]['name'] = $row['alternatif'];
    $alternatifDetails[$row['id_alternatif']]['sub_kriteria'][$row['kriteria']] = $row['sub_kriteria'];
    $kriteriaList[$row['id_kriteria']] = $row['kriteria'];
}

// Mulai perhitungan TOPSIS
// Normalisasi Matriks Keputusan
$normalisasi = [];
foreach ($matriksKeputusan as $alternatif => $kriteria) {
    foreach ($kriteria as $kriteriaId => $nilai) {
        // Menghitung normalisasi per nilai
        $sumOfSquares = 0;
        foreach ($matriksKeputusan as $alt => $kriteriaData) {
            $sumOfSquares += pow($kriteriaData[$kriteriaId], 2);
        }
        // Tanpa pembulatan hasil normalisasi
        $normalisasi[$alternatif][$kriteriaId] = $nilai / sqrt($sumOfSquares); 
    }
}

// Matriks Normalisasi Bobot
$normalisasiBobot = [];
foreach ($normalisasi as $alternatif => $kriteria) {
    foreach ($kriteria as $kriteriaId => $nilai) {
        // Mengalikan nilai normalisasi dengan bobot tanpa pembulatan
        $normalisasiBobot[$alternatif][$kriteriaId] = $nilai * $bobot[$kriteriaId]; 
    }
}

// Menentukan Solusi Ideal Positif dan Negatif
$idealPositive = [];
$idealNegative = [];

// Query untuk mendapatkan data type kriteria
$typeQuery = "SELECT id_kriteria, type FROM kriteria";
$typeResult = mysqli_query($koneksi, $typeQuery);
$kriteriaType = [];
while ($row = mysqli_fetch_assoc($typeResult)) {
    $kriteriaType[$row['id_kriteria']] = $row['type'];
}

foreach ($normalisasiBobot as $alternatif => $kriteria) {
    foreach ($kriteria as $kriteriaId => $nilai) {
        // Cek apakah kriteria adalah jenis 'Benefit' atau 'Cost'
        if ($kriteriaType[$kriteriaId] == 'Benefit') {
            // Untuk kriteria 'Benefit', solusi ideal positif adalah nilai terbesar, negatif adalah nilai terkecil
            $idealPositive[$kriteriaId] = max($idealPositive[$kriteriaId] ?? 0, $nilai);
            $idealNegative[$kriteriaId] = min($idealNegative[$kriteriaId] ?? INF, $nilai);
        } else {
            // Untuk kriteria 'Cost', solusi ideal positif adalah nilai terkecil, negatif adalah nilai terbesar
            $idealPositive[$kriteriaId] = min($idealPositive[$kriteriaId] ?? INF, $nilai);
            $idealNegative[$kriteriaId] = max($idealNegative[$kriteriaId] ?? 0, $nilai);
        }
    }
}

// Menghitung Jarak ke Solusi Ideal Positif dan Negatif
$jarakPositif = [];
$jarakNegatif = [];

foreach ($normalisasiBobot as $alternatif => $kriteria) {
    $distancePositive = 0;
    $distanceNegative = 0;

    foreach ($kriteria as $kriteriaId => $nilai) {
        // Menghitung selisih kuadrat antara nilai normalisasi bobot dan solusi ideal positif
        // (Ideal Positif - Nilai)
        $distancePositive += pow($idealPositive[$kriteriaId] - $nilai, 2);  // Ideal Positif - Nilai
        
        // Menghitung selisih kuadrat antara nilai normalisasi bobot dan solusi ideal negatif
        // (Nilai - Ideal Negatif)
        $distanceNegative += pow($nilai - $idealNegative[$kriteriaId], 2);  // Nilai - Ideal Negatif
    }

    // Menghitung jarak ke solusi ideal positif (akar kuadrat dari jumlah kuadrat selisih)
    $jarakPositif[$alternatif] = sqrt($distancePositive); 
    
    // Menghitung jarak ke solusi ideal negatif (akar kuadrat dari jumlah kuadrat selisih)
    $jarakNegatif[$alternatif] = sqrt($distanceNegative); 
}

// Menghitung Indeks Preferensi
$indeksPreferensi = [];
foreach ($jarakPositif as $alternatif => $distancePositive) {
    // Menghitung indeks preferensi dengan rumus D^- / (D^+ + D^-)
    $totalDistance = $distancePositive + $jarakNegatif[$alternatif];

    // Menangani kemungkinan pembagian dengan 0 (jika total distance adalah 0)
    if ($totalDistance == 0) {
        $indeksPreferensi[$alternatif] = 0;  // Atau bisa set ke nilai default lainnya
    } else {
        // Jika total distance tidak nol, hitung indeks preferensi
        $indeksPreferensi[$alternatif] = $jarakNegatif[$alternatif] / $totalDistance;
    }
}

// Urutkan hasil preferensi (alternatif dengan indeks tertinggi akan diprioritaskan)
arsort($indeksPreferensi);

// Ambil menu dengan indeks tertinggi
$topMenu = key($indeksPreferensi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Rekomendasi Menu Makanan</title>
    <link rel="stylesheet" href="../fontawesome/css/all.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --dark-green: #004d40;
            --medium-green: #00695c;
            --soft-yellow: #ffe0b2;
            --cream: #f4f4f4;
            --brown: #8d6e63;
            --orange: #d84315;
        }
        body {
            background-image: url('../assets/gambar/makanan.jpg'); /* Path gambar background */
            background-size: cover; /* Membuat gambar menutupi seluruh layar */
            background-repeat: no-repeat; /* Menghindari pengulangan gambar */
            background-attachment: fixed; /* Gambar tetap pada posisi saat halaman di-scroll */
            background-position: center; /* Gambar dipusatkan */
            font-family: 'Poppins', sans-serif; /* Font modern */
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: var(--soft-yellow);
            padding: 20px;
        }

        .header img {
            max-width: 150px; /* Ukuran diperbesar */
            height: auto; /* Proporsi tetap terjaga */
            margin-right: 20px;

        }

        .header-text {
            flex: 1;
            padding-left: 20px;
        }

        .header-text h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--orange);
            margin: 0;
        }

        .header-text p {
            font-size: 1rem;
            color: var(--dark-green);
            margin: 5px 0 0;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        /* Custom button style for print */
        .btn-print {
            background-color: #00695c; /* Sesuaikan dengan warna palet */
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
            font-weight: bold;
            border: none;
            display: flex;
            align-items: center; /* Vertikal center untuk teks dan ikon */
            transition: background-color 0.3s ease;
            margin-top: 0px;
        }

        /* Warna saat hover */
        .btn-print:hover {
            background-color: #004d40;
        }

        /* Warna tetap sama saat ditekan */
        .btn-print:active {
            background-color: #00695c;
            box-shadow: none;
        }

        /* Menambahkan margin kanan pada ikon untuk memberi jarak dengan teks */
        .btn-print i {
            margin-right: 8px;
        }

        .table-container {
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            max-width: 1200px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 2s ease-in-out;
        }

        .table {
            margin-top: 20px;
        }

        .table th {
            background-color: var(--dark-green);
            color: white;
            text-align: center;
            font-size: 0.9rem;
        }

        .table td {
            text-align: center;
            font-size: 0.85rem;
        }

        .table tr:hover {
            background-color: rgba(0, 0, 0, 0.05);
            transition: background-color 0.3s ease;
        }

        .action-buttons {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 8px 16px;
            text-transform: uppercase;
            border-radius: 5px;
            font-weight: 600;
            color: white;
            font-size: 0.85rem;
        }

        .btn-success {
            background-color: var(--medium-green);
        }

        .btn-success:hover {
            background-color: var(--dark-green);
            transform: scale(1.05);
            transition: all 0.3s ease;
        }

        .btn-danger {
            background-color: var(--brown);
        }

        .btn-danger:hover {
            background-color: #a1887f;
            transform: scale(1.05);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>

<!-- Modal -->
<div class="modal fade" id="recommendationModal" tabindex="-1" aria-labelledby="recommendationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recommendationModalLabel">Menu yang Paling Direkomendasikan!!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Menu makanan yang paling direkomendasikan untuk Anda konsumsi adalah <strong><?= $alternatifDetails[$topMenu]['name'] ?></strong>. Menu ini sangat sesuai untuk membantu mencegah gejala GERD dan mendukung kesehatan pencernaan Anda.</p>
            </div>
        </div>
    </div>
</div>

<div class="header">
    <img src="../assets/gambar/sehat.png" alt="Logo Sehat">
    <div class="header-text">
        <h1>Rekomendasi Menu Makanan</h1>
        <p>Makanan yang tepat dapat membantu mencegah gejala GERD. Berikut adalah pilihan menu yang menghindari bahan-bahan pemicu refluks dan mendukung kesehatan pencernaan Anda.</p>
    </div>
</div>

<div class="container table-container">
<div class="d-flex justify-content-end mb-3">
            <!-- Tombol Cetak dengan icon di sebelah kiri teks -->
            <a href="cetak.php" class="btn btn-print" style="text-transform: none;">
                <i class="fas fa-print"></i> Cetak
            </a>
    </div>
    <h3 class="text-center">Hasil Rekomendasi Menu Makanan</h3>
    <p class="text-left">Jumlah Per Sajian (100 g)<p/>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Peringkat</th>
                <th>Menu Makanan</th>
                <?php foreach ($kriteriaList as $kriteria): ?>
                    <th><?= htmlspecialchars($kriteria) ?></th>
                <?php endforeach; ?>
                <th>Indeks Preferensi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rank = 1;
            foreach ($indeksPreferensi as $idAlternatif => $score) {
                echo "<tr>";
                echo "<td>{$rank}</td>";
                echo "<td>{$alternatifDetails[$idAlternatif]['name']}</td>";
                foreach ($kriteriaList as $kriteriaId => $kriteriaName) {
                    echo "<td>" . htmlspecialchars($alternatifDetails[$idAlternatif]['sub_kriteria'][$kriteriaName] ?? '-') . "</td>";
                }
                echo "<td>" . number_format($score, 4) . "</td>";
                echo "</tr>";
                $rank++;
            }
            ?>
        </tbody>
    </table>

    <div class="action-buttons">
        <a href="dashboard-user.php" class="btn btn-success">Kembali ke Dashboard</a>
    <!--<a href="perhitungan-user.php" class="btn btn-success">Lihat Perhitungan</a> -->
        <a href="hasil_rekomendasi.php?reset=true" class="btn btn-danger">Reset Pilihan</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Menampilkan modal saat halaman dimuat
    window.onload = function() {
        var modal = new bootstrap.Modal(document.getElementById('recommendationModal'));
        modal.show();
    }
</script>
</div>
</body>
</html>
