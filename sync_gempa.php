<?php
include 'koneksi.php';

// Fungsi untuk menyimpan data ke database
function simpanGempa($conn, $url) {
    $json_data = file_get_contents($url);
    $data = json_decode($json_data, true);
    $count_new = 0;

    if (isset($data['Infogempa']['gempa'])) {
        $gempa_list = $data['Infogempa']['gempa'];
        
        foreach ($gempa_list as $gempa) {
            // 1. Bersihkan Tanggal & Jam
            $tanggal_str = $gempa['Tanggal']; 
            $jam_str = $gempa['Jam'];
            $jam_clean = preg_replace('/(WIB|WITA|WIT)/', '', $jam_str); 
            $jam_clean = trim($jam_clean);
            
            // Ubah bulan Indo ke Inggris untuk konversi tanggal
            $bulan_indo = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $bulan_ing  = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $tanggal_ing = str_replace($bulan_indo, $bulan_ing, $tanggal_str);
            
            $datetime_str = $tanggal_ing . ' ' . $jam_clean;
            $datetime_sql = date('Y-m-d H:i:s', strtotime($datetime_str));

            // 2. Ambil Data
            $magnitude  = $gempa['Magnitude'];
            $kedalaman  = $gempa['Kedalaman'];
            $wilayah    = mysqli_real_escape_string($conn, $gempa['Wilayah']);
            $lintang    = $gempa['Lintang'];
            $bujur      = $gempa['Bujur'];
            
            // Cek apakah field 'Potensi' ada (kadang di gempa dirasakan tidak ada field ini)
            $potensi    = isset($gempa['Potensi']) ? mysqli_real_escape_string($conn, $gempa['Potensi']) : '-';
            
            // Cek field 'Dirasakan'
            $dirasakan  = isset($gempa['Dirasakan']) ? mysqli_real_escape_string($conn, $gempa['Dirasakan']) : '-';
            
            // 3. Insert ke Database (INSERT IGNORE agar tidak duplikat)
            $query = "INSERT IGNORE INTO data_gempa 
                      (tanggal_jam, tanggal_text, jam_text, magnitude, kedalaman, wilayah, lintang, bujur, potensi, dirasakan)
                      VALUES 
                      ('$datetime_sql', '$tanggal_str', '$jam_str', '$magnitude', '$kedalaman', '$wilayah', '$lintang', '$bujur', '$potensi', '$dirasakan')";

            if (mysqli_query($conn, $query)) {
                if (mysqli_affected_rows($conn) > 0) $count_new++;
            }
        }
    }
    return $count_new;
}

// Jalankan Sinkronisasi untuk 2 Sumber Data
$total_masuk = 0;

// 1. Ambil Gempa M 5.0+
$total_masuk += simpanGempa($conn, "https://data.bmkg.go.id/DataMKG/TEWS/gempaterkini.json");

// 2. Ambil Gempa Dirasakan (Ini yang membuat panel samping muncul)
$total_masuk += simpanGempa($conn, "https://data.bmkg.go.id/DataMKG/TEWS/gempadirasakan.json");

// Redirect kembali ke home
header("Location: home.php?status=success&new=$total_masuk");
?>