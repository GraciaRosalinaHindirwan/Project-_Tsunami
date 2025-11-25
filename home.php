<?php
// 1. Mulai Sesi PHP & Koneksi Database
session_start();
include 'koneksi.php';

// A. HITUNG GEMPA TERKINI (2 JAM TERAKHIR) - Untuk Card 1
$query_stat = mysqli_query($conn, "SELECT COUNT(*) as total FROM data_gempa WHERE tanggal_jam >= DATE_SUB(NOW(), INTERVAL 2 HOUR)");
$data_stat  = mysqli_fetch_assoc($query_stat);
$jumlah_gempa_baru = isset($data_stat['total']) ? $data_stat['total'] : 0;

// B. HITUNG TOTAL GEMPA (24 JAM TERAKHIR) - Untuk Card 2 (Permintaan Anda)
$query_24h = mysqli_query($conn, "SELECT COUNT(*) as total FROM data_gempa WHERE tanggal_jam >= DATE_SUB(NOW(), INTERVAL 24 HOUR)");
$data_24h  = mysqli_fetch_assoc($query_24h);
$total_gempa_24h = isset($data_24h['total']) ? $data_24h['total'] : 0;

// C. AMBIL 1 DATA GEMPA TERAKHIR (REAL-TIME) - Untuk Tampilan Utama
$q_latest = mysqli_query($conn, "SELECT * FROM data_gempa ORDER BY tanggal_jam DESC LIMIT 1");
$latest = mysqli_fetch_assoc($q_latest);

// Jika database kosong, beri data dummy
if (!$latest) {
    $latest = [
        'magnitude' => '0.0',
        'wilayah' => 'Belum ada data',
        'tanggal_text' => '-',
        'jam_text' => '-',
        'potensi' => '-',
        'dirasakan' => '-',
        'shakemap' => '',
        'kedalaman' => '-',
        'lintang' => '-',
        'bujur' => '-'
    ];
}

// Cek Login (Opsional, sesuaikan dengan kebutuhan)
$username_login = isset($_SESSION['login_user']) ? $_SESSION['login_user'] : 'Guest';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM TSUNAMI - Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* --- GLOBAL STYLES --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            color: #fff;
            min-height: 100vh;
            padding-bottom: 50px;
        }

        /* HEADER */
        .header {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            padding: 15px 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            border-bottom: 2px solid #00d9ff;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo i {
            font-size: 35px;
            color: #00d9ff;
            animation: wave 2s ease-in-out infinite;
        }

        @keyframes wave {

            0%,
            100% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(-5deg);
            }

            75% {
                transform: rotate(5deg);
            }
        }

        .logo h1 {
            font-size: 24px;
            font-weight: 700;
            line-height: 1.2;
        }

        .logo p {
            font-size: 12px;
            color: #00d9ff;
            margin-top: 0;
        }

        /* NAV MENU */
        .nav-menu {
            display: flex;
            gap: 15px;
        }

        .nav-btn {
            background: rgba(0, 217, 255, 0.1);
            color: #00d9ff;
            border: 1px solid #00d9ff;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-btn:hover,
        .nav-btn.active {
            background: #00d9ff;
            color: #000;
        }

        /* CONTAINER */
        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 40px;
        }

        /* --- STATS GRID --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: #00d9ff;
        }

        .stat-card h3 {
            font-size: 14px;
            color: #00d9ff;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .stat-card .value {
            font-size: 32px;
            font-weight: 700;
        }

        .stat-card i {
            font-size: 30px;
            float: right;
            opacity: 0.5;
        }

        .stat-card .change {
            font-size: 12px;
            opacity: 0.7;
            margin-top: 5px;
        }

        /* --- HERO SECTION (LAYOUT BARU) --- */
        .hero-card {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(0, 217, 255, 0.3);
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            margin-bottom: 30px;
        }

        /* Kiri: Peta */
        .hero-map {
            flex: 1 1 500px;
            background: #000;
            position: relative;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .hero-map img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .map-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(0, 0, 0, 0.7);
            color: #00d9ff;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 12px;
            border: 1px solid #00d9ff;
        }

        /* Kanan: Info */
        .hero-info {
            flex: 1 1 400px;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hero-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .hero-title {
            color: #00d9ff;
            letter-spacing: 2px;
            font-size: 14px;
            font-weight: 600;
        }

        .mag-display {
            font-size: 80px;
            font-weight: 800;
            line-height: 1;
            color: #fff;
            margin-bottom: 10px;
            text-shadow: 0 0 30px rgba(0, 217, 255, 0.4);
        }

        .mag-label {
            font-size: 20px;
            color: #00d9ff;
            margin-left: 5px;
        }

        .date-display {
            font-size: 16px;
            color: #ccc;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .location-box {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 4px solid #00d9ff;
        }

        .location-box h4 {
            font-size: 12px;
            color: #aaa;
            margin-bottom: 5px;
        }

        .location-box p {
            font-size: 15px;
            font-weight: 500;
            color: #fff;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .d-item h5 {
            font-size: 12px;
            color: #aaa;
            margin-bottom: 4px;
        }

        .d-item span {
            font-size: 16px;
            font-weight: 600;
            color: #00d9ff;
        }

        /* --- TABEL --- */
        .panel {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .panel h2 {
            font-size: 20px;
            color: #00d9ff;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .data-table {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        th {
            background: rgba(0, 217, 255, 0.2);
            color: #00d9ff;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 30px;
            opacity: 0.7;
            font-size: 14px;
            margin-top: 50px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Responsif */
        @media (max-width: 900px) {
            .hero-card {
                flex-direction: column;
            }

            .hero-info {
                border-left: none;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                padding: 25px;
            }

            .mag-display {
                font-size: 60px;
            }

            .header-content {
                flex-direction: column;
                gap: 15px;
            }

            .nav-menu {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-water"></i>
                <div>
                    <h1>SIM TSUNAMI</h1>
                    <p>Sistem Informasi Manajemen Tsunami Indonesia</p>
                </div>
            </div>
            <div class="nav-menu">
                <a href="home.php" class="nav-btn active"><i class="fas fa-home"></i> Dashboard</a>
                <a href="evakuasi.html" class="nav-btn"><i class="fas fa-running"></i> Evakuasi</a>
                <a href="education.html" class="nav-btn"><i class="fas fa-book"></i> Edukasi</a>
                <a href="mitigasi.html" class="nav-btn"><i class="fas fa-shield-alt"></i> Mitigasi</a>
                <a href="penanggulangan.html" class="nav-btn"><i class="fas fa-hands-helping"></i> Penanggulangan</a>
                <a href="peta.php" class="nav-btn"><i class="fas fa-map"></i> Peta</a>
            </div>
        </div>
    </div>

    <div class="container">

        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-exclamation-triangle" style="color: #ffd500;"></i>
                <h3>Gempa Terkini</h3>
                <div class="value"><?php echo $jumlah_gempa_baru; ?></div>
                <div class="change">Dalam 2 Jam Terakhir</div>
            </div>

            <div class="stat-card">
                <i class="fas fa-chart-line" style="color: #00ff88;"></i>
                <h3>Total Gempa (24 Jam)</h3>
                <div class="value"><?php echo $total_gempa_24h; ?></div>
                <div class="change">Total kejadian terekam</div>
            </div>

            <div class="stat-card">
                <i class="fas fa-broadcast-tower" style="color: #00d9ff;"></i>
                <h3>Sensor Aktif</h3>
                <div class="value" id="sensor-aktif">47/50</div>
                <div class="change">94% operasional</div>
            </div>

            <div class="stat-card">
                <i class="fas fa-shield-alt" style="color: #ff9f43;"></i>
                <h3>Status Sistem</h3>
                <div class="value" id="system-status">AKTIF</div>
                <div class="change">Monitoring 24/7</div>
            </div>
        </div>

        <div class="hero-card">
            <div class="hero-map">
                <div class="map-badge"><i class="fas fa-satellite"></i> LIVE SHAKEMAP</div>
                <?php if (!empty($latest['shakemap'])): ?>
                    <img src="https://data.bmkg.go.id/DataMKG/TEWS/<?php echo $latest['shakemap']; ?>"
                        alt="Peta Guncangan"
                        onerror="this.src='https://via.placeholder.com/600x400?text=Peta+Belum+Tersedia';">
                <?php else: ?>
                    <div style="color: #666;">Peta belum tersedia</div>
                <?php endif; ?>
            </div>

            <div class="hero-info">
                <div class="hero-header">
                    <div class="hero-title">GEMPA BUMI TERKINI</div>
                    <i class="fas fa-share-alt" style="color: #00d9ff; cursor: pointer;"></i>
                </div>

                <div class="mag-display">
                    <?php echo $latest['magnitude']; ?><span class="mag-label">SR</span>
                </div>

                <div class="date-display">
                    <i class="far fa-clock" style="color: #00d9ff;"></i>
                    <?php echo $latest['tanggal_text'] . ' ' . $latest['jam_text']; ?>
                </div>

                <div class="location-box">
                    <h4><i class="fas fa-map-marker-alt"></i> LOKASI PUSAT GEMPA</h4>
                    <p><?php echo $latest['wilayah']; ?></p>
                </div>

                <div style="margin-bottom: 20px;">
                    <?php
                    // Ambil teks potensi dan ubah ke huruf besar semua agar mudah dicek
                    $potensi_text = strtoupper($latest['potensi']);

                    // LOGIKA PENENTU WARNA BADGE
                    // 1. Cek apakah ada kata "TSUNAMI"?
                    if (strpos($potensi_text, 'TSUNAMI') !== false) {
                        // 2. Jika ada "TSUNAMI", cek apakah ada kata "TIDAK"?
                        if (strpos($potensi_text, 'TIDAK') !== false) {
                            // Ada TSUNAMI + Ada TIDAK = AMAN (Hijau)
                            echo '<span style="background: rgba(0, 255, 136, 0.2); color: #00ff88; padding: 8px 15px; border-radius: 5px; border: 1px solid #00ff88; font-weight: bold;">TIDAK BERPOTENSI TSUNAMI</span>';
                        } else {
                            // Ada TSUNAMI + TIDAK ada kata TIDAK = BAHAYA (Merah)
                            echo '<span style="background: rgba(255, 56, 56, 0.2); color: #ff3838; padding: 8px 15px; border-radius: 5px; border: 1px solid #ff3838; font-weight: bold;">BERPOTENSI TSUNAMI</span>';
                        }
                    } else {
                        // Tidak ada kata "TSUNAMI" sama sekali (misal: "Gempa ini dirasakan...") = AMAN (Hijau)
                        echo '<span style="background: rgba(0, 255, 136, 0.2); color: #00ff88; padding: 8px 15px; border-radius: 5px; border: 1px solid #00ff88; font-weight: bold;">TIDAK BERPOTENSI TSUNAMI</span>';
                    }
                    ?>
                </div>

                <div class="detail-grid">
                    <div class="d-item">
                        <h5>KEDALAMAN</h5>
                        <span><?php echo $latest['kedalaman']; ?></span>
                    </div>
                    <div class="d-item">
                        <h5>KOORDINAT</h5>
                        <span style="font-size: 14px;"><?php echo $latest['lintang'] . ' / ' . $latest['bujur']; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel">
            <h2><i class="fas fa-table"></i> Riwayat Gempa M ≥ 5.0 Terkini</h2>
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Magnitudo</th>
                            <th>Kedalaman</th>
                            <th>Wilayah</th>
                            <th>Potensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_gempa = mysqli_query($conn, "SELECT * FROM data_gempa WHERE magnitude >= 5.0 ORDER BY tanggal_jam DESC LIMIT 10");

                        if (mysqli_num_rows($query_gempa) > 0) {
                            while ($row = mysqli_fetch_assoc($query_gempa)) {
                                // Warna Potensi
                                $potensi_tabel = $row['potensi'];
                                $warna_potensi = "#fff";
                                if (stripos($potensi_tabel, 'tidak berpotensi') !== false) {
                                    $warna_potensi = "#00ff88";
                                } elseif (stripos($potensi_tabel, 'tsunami') !== false) {
                                    $warna_potensi = "#ff3838";
                                }

                                echo "<tr>";
                                echo "<td>" . $row['tanggal_text'] . "<br><small style='opacity:0.7'>" . $row['jam_text'] . "</small></td>";
                                echo "<td><strong style='color: #ffd500; font-size:16px;'>M " . $row['magnitude'] . "</strong></td>";
                                echo "<td>" . $row['kedalaman'] . "</td>";
                                echo "<td>" . $row['wilayah'] . "</td>";
                                echo "<td style='color: $warna_potensi;'>" . $potensi_tabel . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center; padding:20px;'>Database kosong. Klik tombol sinkronisasi.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2025 Sistem Informasi Manajemen Tsunami Indonesia</p>
        <p style="margin-top: 10px; font-size: 12px;">
            <i class="fas fa-phone"></i> Hotline Darurat: 119 |
            <i class="fas fa-envelope"></i> SIM-B-TSUNAMI@gmail.com
        </p>
    </div>

</body>

</html>