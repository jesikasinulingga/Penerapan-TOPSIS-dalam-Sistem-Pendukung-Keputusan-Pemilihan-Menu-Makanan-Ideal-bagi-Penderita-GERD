<?php
session_start();
require_once(__DIR__ . '/../includes/init.php');

// Mengecek apakah menu telah dipilih
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
                background-image: url("assets/gambar/background.jpg");
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


?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perhitungan Metode TOPSIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url("../assets/gambar/makanan.jpg") no-repeat center center fixed;
            background-size: cover;
        }
        .table-container {
            margin: 30px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
        }
        .table th {
            background-color: #28a745;
            color: white;
        }
        .table td {
            background-color: #fef9f0;
        }
        .btn {
            background-color: #28a745;
            color: white;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #218838;
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container table-container">
    <h3 class="text-center">Perhitungan Metode TOPSIS</h3>

    <!-- Step 1: Matriks Keputusan -->
    <h5>1. Matriks Keputusan</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Alternatif</th>
                <?php foreach ($kriteriaList as $kriteria): ?>
                    <th><?= $kriteria ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($matriksKeputusan as $alternatif => $kriteria): ?>
                <tr>
                    <td><?= $alternatifDetails[$alternatif]['name'] ?></td>
                    <?php foreach ($kriteria as $nilai): ?>
                        <td><?= number_format($nilai, 2) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Step 2: Normalisasi Matriks Keputusan -->
    <h5>2. Matriks Normalisasi</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Alternatif</th>
                <?php foreach ($kriteriaList as $kriteria): ?>
                    <th><?= $kriteria ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($normalisasi as $alternatif => $kriteria): ?>
                <tr>
                    <td><?= $alternatifDetails[$alternatif]['name'] ?></td>
                    <?php foreach ($kriteria as $nilai): ?>
                        <td><?= number_format($nilai, 4) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Step 3: Matriks Normalisasi Bobot -->
    <h5>3. Matriks Normalisasi Bobot</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Alternatif</th>
                <?php foreach ($kriteriaList as $kriteria): ?>
                    <th><?= $kriteria ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($normalisasiBobot as $alternatif => $kriteria): ?>
                <tr>
                    <td><?= $alternatifDetails[$alternatif]['name'] ?></td>
                    <?php foreach ($kriteria as $nilai): ?>
                        <td><?= number_format($nilai, 4) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Step 4: Solusi Ideal -->
    <h5>4. Solusi Ideal Positif dan Negatif</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kriteria</th>
                <th>Solusi Ideal Positif</th>
                <th>Solusi Ideal Negatif</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($idealPositive as $kriteriaId => $nilai): ?>
                <tr>
                    <td><?= $kriteriaList[$kriteriaId] ?></td>
                    <td><?= number_format($nilai, 4) ?></td>
                    <td><?= number_format($idealNegative[$kriteriaId], 4) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Step 5: Jarak ke Solusi Ideal -->
    <h5>5. Jarak ke Solusi Ideal</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Alternatif</th>
                <th>Jarak Positif</th>
                <th>Jarak Negatif</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jarakPositif as $alternatif => $distancePositive): ?>
                <tr>
                    <td><?= $alternatifDetails[$alternatif]['name'] ?></td>
                    <td><?= number_format($distancePositive, 4) ?></td>
                    <td><?= number_format($jarakNegatif[$alternatif], 4) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Step 6: Indeks Preferensi -->
    <h5>6. Indeks Preferensi</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Alternatif</th>
                <th>Indeks Preferensi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($indeksPreferensi as $alternatif => $score): ?>
                <tr>
                    <td><?= $alternatifDetails[$alternatif]['name'] ?></td>
                    <td><?= number_format($score, 4) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Buttons -->
    <div class="btn-container">
        <a href="hasil_rekomendasi.php" class="btn">Lihat Hasil Rekomendasi</a>
        <a href="dashboard-user.php" class="btn">Kembali ke Dashboard</a>
    </div>
</div>
</body>
</html>
