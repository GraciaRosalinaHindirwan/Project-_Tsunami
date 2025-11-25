<?php
include 'koneksi.php';

// --- LOGIKA SINKRONISASI GEMPA ---

// 1. HAPUS DATA LAMA (Lebih dari 30 Hari)
mysqli_query($conn, "DELETE FROM data_gempa WHERE tanggal_jam < DATE_SUB(NOW(), INTERVAL 30 DAY)");

function simpanGempa($conn, $url) {
    // Ambil data JSON dari BMKG
    $json_data = @file_get_contents($url); 
    if ($json_data === false) return 0;

    $data = json_decode($json_data, true);
    $count_new = 0;

    // Cek validitas JSON
    if (isset($data['Infogempa']['gempa'])) {
        $gempa_list = $data['Infogempa']['gempa'];
        
        // Normalisasi format jika data tunggal
        if (isset($gempa_list['Tanggal'])) {
            $gempa_list = [$gempa_list];
        }
        
        foreach ($gempa_list as $gempa) {
            // A. AMBIL DATA DASAR
            $magnitude = $gempa['Magnitude'];
            $kedalaman = $gempa['Kedalaman'];
            $wilayah   = mysqli_real_escape_string($conn, $gempa['Wilayah']);
            $lintang   = $gempa['Lintang'];
            $bujur     = $gempa['Bujur'];

            // B. BERSIHKAN TANGGAL & JAM
            $tanggal_str = $gempa['Tanggal']; 
            $jam_str     = $gempa['Jam'];
            $jam_clean   = preg_replace('/(WIB|WITA|WIT)/', '', $jam_str); 
            $jam_clean   = trim($jam_clean);
            
            $bulan_indo  = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $bulan_ing   = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $tanggal_ing = str_replace($bulan_indo, $bulan_ing, $tanggal_str);
            
            $datetime_str = $tanggal_ing . ' ' . $jam_clean;
            $datetime_sql = date('Y-m-d H:i:s', strtotime($datetime_str));
            
            // --- C. PERBAIKAN POTENSI (LOGIKA BARU DI SINI) ---
            $potensi_raw = isset($gempa['Potensi']) ? $gempa['Potensi'] : '';
            $potensi_upper = strtoupper($potensi_raw); // Ubah ke huruf besar semua biar mudah dicek

            // Logic: Default-nya "TIDAK BERPOTENSI TSUNAMI"
            // Kecuali jika ada kata "TSUNAMI" tapi TIDAK ADA kata "TIDAK"
            $potensi_fix = "TIDAK BERPOTENSI TSUNAMI";

            if (strpos($potensi_upper, 'TSUNAMI') !== false && strpos($potensi_upper, 'TIDAK') === false) {
                // Jika mengandung kata TSUNAMI dan tidak ada kata TIDAK -> BAHAYA
                $potensi_fix = "BERPOTENSI TSUNAMI";
            }
            // Jika kalimatnya "Gempa ini dirasakan...", otomatis akan masuk ke default "TIDAK BERPOTENSI TSUNAMI"

            $potensi = mysqli_real_escape_string($conn, $potensi_fix);
            // -----------------------------------------------------
            
            $dirasakan = isset($gempa['Dirasakan']) ? mysqli_real_escape_string($conn, $gempa['Dirasakan']) : '-';

            // D. AMBIL SHAKEMAP
            $shakemap = '';
            if (isset($gempa['Shakemap']) && !empty($gempa['Shakemap'])) {
                $shakemap = mysqli_real_escape_string($conn, $gempa['Shakemap']);
            }

            // E. INSERT KE DATABASE
            $query = "INSERT IGNORE INTO data_gempa 
                      (tanggal_jam, tanggal_text, jam_text, magnitude, kedalaman, wilayah, lintang, bujur, potensi, dirasakan, shakemap)
                      VALUES 
                      ('$datetime_sql', '$tanggal_str', '$jam_str', '$magnitude', '$kedalaman', '$wilayah', '$lintang', '$bujur', '$potensi', '$dirasakan', '$shakemap')";

            if (mysqli_query($conn, $query)) {
                if (mysqli_affected_rows($conn) > 0) $count_new++;
            }
        }
    }
    return $count_new;
}

// --- EKSEKUSI SINKRONISASI ---
$total_masuk = 0;
$total_masuk += simpanGempa($conn, "https://data.bmkg.go.id/DataMKG/TEWS/autogempa.json");
$total_masuk += simpanGempa($conn, "https://data.bmkg.go.id/DataMKG/TEWS/gempadirasakan.json");
$total_masuk += simpanGempa($conn, "https://data.bmkg.go.id/DataMKG/TEWS/gempaterkini.json");

header("Location: homeadmin.php?status=success&new=$total_masuk");
exit();
?>