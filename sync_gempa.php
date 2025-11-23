<?php
include 'koneksi.php';

// --- LOGIKA SINKRONISASI GEMPA ---

// 1. HAPUS DATA LAMA (Lebih dari 30 Hari) AGAR DATABASE TIDAK PENUH
mysqli_query($conn, "DELETE FROM data_gempa WHERE tanggal_jam < DATE_SUB(NOW(), INTERVAL 30 DAY)");

function simpanGempa($conn, $url) {
    // Ambil data JSON dari BMKG
    $json_data = @file_get_contents($url); 
    if ($json_data === false) return 0;

    $data = json_decode($json_data, true);
    $count_new = 0;

    // Cek apakah struktur JSON valid
    if (isset($data['Infogempa']['gempa'])) {
        $gempa_list = $data['Infogempa']['gempa'];
        
        // Jika data cuma 1 (objek), ubah jadi array agar loop foreach tetap jalan
        // (BMKG kadang mengembalikan object untuk single data, array untuk list)
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
            // Hapus "WIB", "WITA", dll untuk konversi waktu
            $jam_clean   = preg_replace('/(WIB|WITA|WIT)/', '', $jam_str); 
            $jam_clean   = trim($jam_clean);
            
            // Konversi Nama Bulan (Indo -> Inggris) untuk format SQL
            $bulan_indo  = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $bulan_ing   = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $tanggal_ing = str_replace($bulan_indo, $bulan_ing, $tanggal_str);
            
            $datetime_str = $tanggal_ing . ' ' . $jam_clean;
            $datetime_sql = date('Y-m-d H:i:s', strtotime($datetime_str));
            
            // C. PERBAIKAN POTENSI
            $potensi_raw = isset($gempa['Potensi']) ? $gempa['Potensi'] : '';
            if (empty($potensi_raw) || $potensi_raw == '-') {
                $potensi_raw = "Tidak ada keterangan potensi"; 
            }
            $potensi = mysqli_real_escape_string($conn, $potensi_raw);
            
            $dirasakan = isset($gempa['Dirasakan']) ? mysqli_real_escape_string($conn, $gempa['Dirasakan']) : '-';

            // D. AMBIL SHAKEMAP (GAMBAR) - BAGIAN YANG DITAMBAHKAN
            // Cek apakah field Shakemap ada di JSON
            $shakemap = '';
            if (isset($gempa['Shakemap']) && !empty($gempa['Shakemap'])) {
                $shakemap = mysqli_real_escape_string($conn, $gempa['Shakemap']);
            }

            // E. INSERT KE DATABASE (Termasuk kolom shakemap)
            // Menggunakan INSERT IGNORE agar jika data sudah ada (berdasarkan unique key tanggal_jam+lintang+bujur), tidak error.
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

// 1. Ambil Gempa TERKINI (1 Gempa Terbaru - Biasanya ada Shakemap)
// URL ini penting karena biasanya berisi gempa paling update dengan gambar
$total_masuk += simpanGempa($conn, "https://data.bmkg.go.id/DataMKG/TEWS/autogempa.json");

// 2. Ambil Gempa Dirasakan (Biasanya ada Shakemap)
$total_masuk += simpanGempa($conn, "https://data.bmkg.go.id/DataMKG/TEWS/gempadirasakan.json");

// 3. Ambil Gempa M 5.0+ (List 15 gempa, kadang ada shakemap kadang tidak)
$total_masuk += simpanGempa($conn, "https://data.bmkg.go.id/DataMKG/TEWS/gempaterkini.json");

// Redirect kembali ke dashboard/home dengan pesan sukses
header("Location: homeadmin.php?status=success&new=$total_masuk");
?>