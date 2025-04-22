<?php
session_start();
require_once(__DIR__ . '/../includes/init.php');
require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Ambil data dari session
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

// Proses data
while ($row = mysqli_fetch_assoc($result)) {
    $matriksKeputusan[$row['id_alternatif']][$row['id_kriteria']] = $row['nilai_sub_kriteria'];
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
        $sumOfSquares = 0;
        foreach ($matriksKeputusan as $alt => $kriteriaData) {
            $sumOfSquares += pow($kriteriaData[$kriteriaId], 2);
        }
        $normalisasi[$alternatif][$kriteriaId] = $nilai / sqrt($sumOfSquares);
    }
}

// Matriks Normalisasi Bobot
$normalisasiBobot = [];
foreach ($normalisasi as $alternatif => $kriteria) {
    foreach ($kriteria as $kriteriaId => $nilai) {
        $normalisasiBobot[$alternatif][$kriteriaId] = $nilai * $bobot[$kriteriaId];
    }
}

// Menentukan Solusi Ideal Positif dan Negatif
$idealPositive = [];
$idealNegative = [];
$typeQuery = "SELECT id_kriteria, type FROM kriteria";
$typeResult = mysqli_query($koneksi, $typeQuery);
$kriteriaType = [];
while ($row = mysqli_fetch_assoc($typeResult)) {
    $kriteriaType[$row['id_kriteria']] = $row['type'];
}

foreach ($normalisasiBobot as $alternatif => $kriteria) {
    foreach ($kriteria as $kriteriaId => $nilai) {
        if ($kriteriaType[$kriteriaId] == 'Benefit') {
            $idealPositive[$kriteriaId] = max($idealPositive[$kriteriaId] ?? 0, $nilai);
            $idealNegative[$kriteriaId] = min($idealNegative[$kriteriaId] ?? INF, $nilai);
        } else {
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
        $distancePositive += pow($idealPositive[$kriteriaId] - $nilai, 2);
        $distanceNegative += pow($nilai - $idealNegative[$kriteriaId], 2);
    }

    $jarakPositif[$alternatif] = sqrt($distancePositive);
    $jarakNegatif[$alternatif] = sqrt($distanceNegative);
}

// Menghitung Indeks Preferensi
$indeksPreferensi = [];
foreach ($jarakPositif as $alternatif => $distancePositive) {
    $totalDistance = $distancePositive + $jarakNegatif[$alternatif];

    if ($totalDistance == 0) {
        $indeksPreferensi[$alternatif] = 0;
    } else {
        $indeksPreferensi[$alternatif] = $jarakNegatif[$alternatif] / $totalDistance;
    }
}

arsort($indeksPreferensi);

// Menyusun HTML untuk PDF
$html = '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; text-align: center; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 22px; margin-bottom: 5px; }
        .header h2 { font-size: 16px; margin-top: 0; color: #555; }
        .line { width: 100%; height: 2px; background-color: #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; }
        th { background-color: #4CAF50; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        .note { text-align: left; font-size: 14px; color: #555; margin-top: 20px; }
        .date { text-align: right; margin-top: 20px; font-size: 14px; color: #555; }
        p { text-align: justify; } /* Menambahkan properti text-align: left untuk paragraf */
    </style>
</head>
<body>
    <div class="header">
        <h1>Sistem Pendukung Keputusan Pemilihan Menu Makanan</h1>
        <h2>Menggunakan Metode TOPSIS bagi Penderita Penyakit GERD</h2>
        <div class="line"></div>
    </div>
    <h3>Hasil Rekomendasi Menu Makanan</h3>
    <p>Makanan yang tepat dapat membantu mencegah gejala GERD. Berikut adalah perangkingan menu makanan yang menghindari bahan-bahan pemicu refluks dan mendukung kesehatan pencernaan Anda.</p>
    <table>
        <thead>
            <tr>
                <th>Peringkat</th>
                <th>Menu Makanan</th>';
foreach ($kriteriaList as $kriteria) {
    $html .= '<th>' . htmlspecialchars($kriteria) . '</th>';
}
$html .= '
                <th>Indeks Preferensi</th>
            </tr>
        </thead>
        <tbody>';
$rank = 1;
foreach ($indeksPreferensi as $idAlternatif => $score) {
    $html .= '<tr>';
    $html .= '<td>' . $rank . '</td>';
    $html .= '<td>' . htmlspecialchars($alternatifDetails[$idAlternatif]['name']) . '</td>';
    foreach ($kriteriaList as $kriteriaId => $kriteriaName) {
        $html .= '<td>' . htmlspecialchars($alternatifDetails[$idAlternatif]['sub_kriteria'][$kriteriaName] ?? '-') . '</td>';
    }
    $html .= '<td>' . number_format($score, 4) . '</td>';
    $html .= '</tr>';
    $rank++;
}
$html .= '
        </tbody>
    </table>
    <div class="note">Untuk informasi lebih lanjut, disarankan untuk berkonsultasi dengan ahli gizi atau dokter terkait.</div>
    <div class="date">' . date('d F Y') . '</div>
</body>
</html>';


// Konfigurasi DOMPDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Kirim output ke browser
$dompdf->stream("Hasil_Rekomendasi_Menu.pdf", ["Attachment" => 0]);
?>
