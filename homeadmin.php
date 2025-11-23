<?php
// 1. Mulai Sesi PHP & Koneksi Database
session_start();
include 'koneksi.php';

// Mengambil data Magnitudo gempa paling terakhir untuk Stat Card atas
// --- GANTI BAGIAN INI (Baris 7-11) ---

// Menghitung JUMLAH gempa yang terjadi dalam 2 JAM TERAKHIR dari waktu sekarang
$query_stat = mysqli_query($conn, "SELECT COUNT(*) as total FROM data_gempa WHERE tanggal_jam >= DATE_SUB(NOW(), INTERVAL 2 HOUR)");
$data_stat  = mysqli_fetch_assoc($query_stat);

// Simpan jumlahnya ke variabel
$jumlah_gempa_baru = isset($data_stat['total']) ? $data_stat['total'] : 0;

// -------------------------------------

// Jika ada data, ambil magnitudonya. Jika kosong, tampilkan strip (-)
$magnitudo_terkini = isset($data_stat['magnitude']) ? $data_stat['magnitude'] : "-";

// 2. Cek Keamanan: Jika belum login, tendang ke login page
if (!isset($_SESSION['login_user'])) {
    header("location: login-admin.php");
    exit;
}

// 3. Ambil Data User dari Database untuk ditampilkan
$username_login = $_SESSION['login_user'];
$nama_lengkap   = $_SESSION['nama_lengkap'];

// (Opsional) Contoh mengambil data lain dari DB, misal jumlah admin
$query_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM admins");
$data_count  = mysqli_fetch_assoc($query_count);
$total_admin = $data_count['total'];

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

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SIM TSUNAMI</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
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

        /* Tombol Logout Spesifik */
        .btn-logout {
            border-color: #ff3838;
            color: #ff3838;
            background: rgba(255, 56, 56, 0.1);
        }

        .btn-logout:hover {
            background: #ff3838;
            color: white;
        }

        /* USER PROFILE IN HEADER */
        .user-profile {
            text-align: right;
            font-size: 13px;
        }

        .user-profile strong {
            color: #00ff88;
            font-size: 15px;
        }

        /* CONTAINER & GRID */
        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 40px;
        }

        .page-section {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .page-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        .panel {
            margin-top: 20px;
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
            margin-top: 20px;
        }

        .data-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .data-table th {
            background: rgba(0, 217, 255, 0.2);
            color: #00d9ff;
        }

        /* ACCORDION (EDUCATION & MITIGATION) */
        .accordion-header {
            background: rgba(0, 217, 255, 0.1);
            padding: 15px;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            border: 1px solid rgba(0, 217, 255, 0.3);
            transition: 0.3s;
        }

        .accordion-header:hover {
            background: rgba(0, 217, 255, 0.2);
        }

        .accordion-header.active {
            background: rgba(0, 217, 255, 0.3);
        }

        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: rgba(0, 0, 0, 0.2);
            padding: 0 15px;
            margin-bottom: 10px;
            border-radius: 0 0 10px 10px;
        }

        .accordion-content.active {
            max-height: 2000px;
            padding: 15px;
        }

        /* --- TOMBOL SYNC KEREN (UPDATED) --- */
        .btn-sync {
            background: linear-gradient(90deg, #00d9ff, #007ea7);
            color: #fff;
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 0 15px rgba(0, 217, 255, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        /* Efek Hover */
        .btn-sync:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 25px rgba(0, 217, 255, 0.8);
            background: linear-gradient(90deg, #00ff88, #00d9ff);
            /* Berubah warna jadi hijau-biru */
            color: #000;
        }

        /* Efek Active/Click */
        .btn-sync:active {
            transform: translateY(1px);
            box-shadow: 0 0 10px rgba(0, 217, 255, 0.5);
        }

        /* Animasi Icon Putar saat hover */
        .btn-sync:hover i {
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        /* GEMPA ITEMS */
        .alert-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            border-left: 4px solid #00d9ff;
        }

        .alert-item.warning {
            border-left-color: #ffd500;
        }

        .alert-item.danger {
            border-left-color: #ff3838;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            padding: 30px;
            opacity: 0.7;
            font-size: 14px;
            margin-top: 50px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
            }

            .nav-menu {
                flex-wrap: wrap;
                justify-content: center;
            }

            .main-grid {
                grid-template-columns: 1fr;
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
                    <p>Admin Dashboard Control</p>
                </div>
            </div>

            <div class="nav-menu">
                <a href="homeadmin.php" class="nav-btn active">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <!-- <a href="evakuasi.html" class="nav-btn">
                    <i class="fas fa-running"></i> Evakuasi
                </a>
                <a href="education.html" class="nav-btn">
                    <i class="fas fa-book"></i> Edukasi
                </a>
                <a href="mitigasi.html" class="nav-btn">
                    <i class="fas fa-shield-alt"></i> Mitigasi
                </a>
                <a href="penanggulangan.html" class="nav-btn">
                    <i class="fas fa-hands-helping"></i> Penanggulangan
                </a> -->
                <a href="petaadmin.php" class="nav-btn">
                    <i class="fas fa-map"></i> Peta
                </a>
                <a href="logout.php" class="nav-btn btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>

            <div class="user-profile">
                <div style="margin-bottom: 2px;">Halo, <strong><?php echo htmlspecialchars($nama_lengkap); ?></strong></div>
                <div style="font-size: 11px; opacity: 0.7;" id="datetime"></div>
            </div>
        </div>
    </div>

    <div class="container">

        <div id="dashboard-page" class="page-section active">
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-history" style="color: #ffd500;"></i>
                    <h3>Gempa Terkini</h3>
                    <div class="value"><?php echo $jumlah_gempa_baru; ?></div>
                    <div class="change">2 Jam Terakhir</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-users" style="color: #00ff88;"></i>
                    <h3>Admin Terdaftar</h3>
                    <div class="value"><?php echo $total_admin; ?></div>
                    <div class="change">User Database</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-broadcast-tower" style="color: #00d9ff;"></i>
                    <h3>Status API</h3>
                    <div class="value" id="api-status">ONLINE</div>
                    <div class="change">Koneksi Stabil</div>
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
                        if (stripos($latest['potensi'], 'TSUNAMI') !== false) {
                            echo '<span style="background: rgba(255, 56, 56, 0.2); color: #ff3838; padding: 8px 15px; border-radius: 5px; border: 1px solid #ff3838; font-weight: bold;">BERPOTENSI TSUNAMI</span>';
                        } else {
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
                <h2><i class="fas fa-table"></i> Data Gempa M ≥ 5.0 Terkini</h2>
                <a href="sync_gempa.php" class="btn-sync" onclick="return animateSync(this)">
                    <i class="fas fa-sync-alt" id="icon-sync"></i>
                    <span id="text-sync">Ambil Data BMKG Terbaru</span>
                </a>
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
                        <tbody id="earthquake-data">
                            <?php
                            $query_gempa = mysqli_query($conn, "SELECT * FROM data_gempa WHERE magnitude >= 5.0 ORDER BY tanggal_jam DESC LIMIT 10");

                            if (mysqli_num_rows($query_gempa) > 0) {
                                while ($row = mysqli_fetch_assoc($query_gempa)) {
                                    // Logika Perbaikan Tampilan Potensi
                                    $potensi_tabel = $row['potensi'];
                                    $warna_potensi = "#fff"; // Default putih

                                    if ($potensi_tabel == '-' || $potensi_tabel == '') {
                                        // Jika kosong/strip, ganti teksnya
                                        $potensi_tabel = "<span style='opacity:0.5; font-style:italic;'>Tidak ada info</span>";
                                    } elseif (stripos($potensi_tabel, 'tidak berpotensi') !== false) {
                                        // Jika aman, warna hijau
                                        $warna_potensi = "#00ff88";
                                    } elseif (stripos($potensi_tabel, 'potensi tsunami') !== false) {
                                        // Jika bahaya, warna merah
                                        $warna_potensi = "#ff3838";
                                    }

                                    echo "<tr>";
                                    echo "<td>" . $row['tanggal_text'] . "<br><small>" . $row['jam_text'] . "</small></td>";
                                    echo "<td><strong style='color: #ffd500;'>M " . $row['magnitude'] . "</strong></td>";
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
            <p>&copy; 2025 SIM TSUNAMI | Logged in as: <?php echo $nama_lengkap; ?> | Data Source: BMKG</p>
        </div>

        <script>
            // 1. PROXY & API URL
            const PROXY = 'https://api.allorigins.win/raw?url=';
            const API_BASE = 'https://data.bmkg.go.id/DataMKG/TEWS/';

            // 2. JAM DIGITAL
            function updateDateTime() {
                const now = new Date();
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    timeZone: 'Asia/Jakarta'
                };
                document.getElementById('datetime').textContent = now.toLocaleDateString('id-ID', options);
            }
            setInterval(updateDateTime, 1000);
            updateDateTime();

            // 3. SWITCH PAGE FUNCTION
            function switchPage(pageId) {
                // Sembunyikan semua section
                document.querySelectorAll('.page-section').forEach(sec => sec.classList.remove('active'));
                document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('active'));

                // Tampilkan section yang dipilih
                document.getElementById(pageId + '-page').classList.add('active');

                // Set tombol aktif (looping manual karena 'this' tidak selalu work di onclick inline)
                const btns = document.querySelectorAll('.nav-btn');
                if (pageId === 'dashboard') btns[0].classList.add('active');
                if (pageId === 'education') btns[1].classList.add('active');
                if (pageId === 'mitigation') btns[2].classList.add('active');
            }

            // 4. ACCORDION FUNCTION
            function toggleAccordion(header) {
                const content = header.nextElementSibling;
                header.classList.toggle('active');
                content.classList.toggle('active');
            }

            // 5. FETCH DATA GEMPA TERKINI (AutoGempa)
            async function fetchLatestEarthquake() {
                try {
                    const response = await fetch(PROXY + encodeURIComponent(API_BASE + 'autogempa.json'));
                    const data = await response.json();
                    const gempa = data.Infogempa.gempa;

                    let html = `
                    <h1 style="color: #00d9ff; font-size: 40px; margin: 0;">M ${gempa.Magnitude}</h1>
                    <h3 style="margin: 5px 0;">${gempa.Wilayah}</h3>
                    <p style="opacity: 0.8;">${gempa.Tanggal} - ${gempa.Jam}</p>
                    <div style="margin-top: 15px; padding: 10px; background: rgba(255,255,255,0.1); border-radius: 8px;">
                        <strong>Potensi:</strong> ${gempa.Potensi}
                    </div>
                `;
                    document.getElementById('latest-earthquake').innerHTML = html;

                    // Shakemap
                    if (gempa.Shakemap) {
                        document.getElementById('shakemap-container').innerHTML = `<img src="https://data.bmkg.go.id/DataMKG/TEWS/${gempa.Shakemap}" style="width:100%; display:block;">`;
                    }
                } catch (e) {
                    console.error(e);
                }
            }


            // 7. FETCH GEMPA DIRASAKAN
            async function fetchFelt() {
                try {
                    const response = await fetch(PROXY + encodeURIComponent(API_BASE + 'gempadirasakan.json'));
                    const data = await response.json();
                    const list = data.Infogempa.gempa;

                    let html = '';
                    list.slice(0, 5).forEach(g => {
                        html += `
                        <div class="alert-item warning">
                            <div style="display:flex; justify-content:space-between;">
                                <strong>M ${g.Magnitude}</strong>
                                <small>${g.Jam}</small>
                            </div>
                            <div style="font-size: 13px; margin-top: 5px;">${g.Wilayah}</div>
                            <div style="font-size: 11px; opacity: 0.7;">Dirasakan: ${g.Dirasakan}</div>
                        </div>
                    `;
                    });
                    document.getElementById('felt-earthquakes').innerHTML = html;
                } catch (e) {
                    console.error(e);
                }
            }
            // FUNGSI ANIMASI TOMBOL SINKRONISASI
            function animateSync(btn) {
                const icon = document.getElementById('icon-sync');
                const text = document.getElementById('text-sync');

                // Ubah tampilan agar user tahu proses sedang berjalan
                icon.classList.add('fa-spin'); // Class bawaan fontawesome untuk putar
                text.innerText = "Menyinkronkan...";
                btn.style.opacity = "0.8";
                btn.style.pointerEvents = "none"; // Cegah double click

                return true; // Lanjutkan link ke sync_gempa.php
            }

            // Jalankan Fetch saat load
            fetchLatestEarthquake();
            fetchTableData();
            fetchFelt();
        </script>
</body>

</html>