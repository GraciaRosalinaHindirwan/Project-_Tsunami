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

        /* PANEL & TABLES */
        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

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
                <button class="nav-btn active" onclick="switchPage('dashboard')">
                    <i class="fas fa-home"></i> Dashboard
                </button>
                <button class="nav-btn" onclick="switchPage('education')">
                    <i class="fas fa-book"></i> Edukasi
                </button>
                <button class="nav-btn" onclick="switchPage('mitigation')">
                    <i class="fas fa-shield-alt"></i> Mitigasi
                </button>
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
                    <div class="change">Live Data BMKG</div>
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

            <div class="main-grid">
                <div class="panel">
                    <h2><i class="fas fa-earthquake"></i> Gempa Terkini (Real-time)</h2>
                    <div class="panel">
                        <h2><i class="fas fa-earthquake"></i> Gempa Terkini (Real-time)</h2>

                        <?php
                        // Ambil 1 data gempa paling baru berdasarkan waktu
                        $q_latest = mysqli_query($conn, "SELECT * FROM data_gempa ORDER BY tanggal_jam DESC LIMIT 1");
                        $latest = mysqli_fetch_assoc($q_latest);

                        if ($latest) {
                            // Logika untuk Potensi: Jika "-", ubah teksnya agar lebih informatif
                            $potensi_text = $latest['potensi'];
                            if ($potensi_text == '-' || $potensi_text == '') {
                                // Jika magnitude kecil (< 5.0), biasanya memang tidak berpotensi
                                if ($latest['magnitude'] < 5.0) {
                                    $potensi_text = "Tidak Berpotensi Tsunami (Gempa Kecil)";
                                } else {
                                    $potensi_text = "Menunggu Pemutakhiran BMKG";
                                }
                            }

                            // URL Gambar Shakemap
                            $img_url = "https://data.bmkg.go.id/DataMKG/TEWS/" . $latest['shakemap'];
                        ?>

                            <div id="latest-earthquake" style="text-align: center; padding: 10px;">
                                <h1 style="color: #00d9ff; font-size: 50px; margin: 0; font-weight:800;">
                                    M <?php echo $latest['magnitude']; ?>
                                </h1>

                                <h3 style="margin: 10px 0; font-size:18px; color: #fff;">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo $latest['wilayah']; ?>
                                </h3>

                                <p style="opacity: 0.8; margin-bottom:15px;">
                                    <i class="fas fa-clock"></i> <?php echo $latest['tanggal_text'] . ' - ' . $latest['jam_text']; ?>
                                </p>

                                <div style="margin-top: 15px; padding: 15px; background: rgba(0, 217, 255, 0.1); border: 1px solid #00d9ff; border-radius: 8px;">
                                    <strong style="color: #00d9ff;">STATUS POTENSI:</strong><br>
                                    <span style="font-size: 16px; font-weight:bold;">
                                        <?php echo $potensi_text; ?>
                                    </span>
                                </div>

                                <?php if (!empty($latest['shakemap'])) { ?>
                                    <div style="margin-top: 20px; border-radius: 10px; overflow: hidden; border:1px solid rgba(255,255,255,0.2);">
                                        <p style="text-align:left; padding:5px 10px; background:rgba(0,0,0,0.5); font-size:12px;">Peta Guncangan (Shakemap):</p>
                                        <img src="<?php echo $img_url; ?>" alt="Shakemap Gempa" style="width:100%; display:block;" onerror="this.style.display='none'">
                                    </div>
                                <?php } ?>
                            </div>

                        <?php } else { ?>
                            <div style="text-align: center; padding: 40px;">
                                <i class="fas fa-cloud-download-alt" style="font-size: 40px; color: #ffd500; margin-bottom:15px;"></i>
                                <h3>Data Belum Tersedia</h3>
                                <p>Silakan klik tombol <b>"Ambil Data BMKG Terbaru"</b> di bawah.</p>
                            </div>
                        <?php } ?>
                    </div>
                    <div id="shakemap-container" style="margin-top: 15px; border-radius: 10px; overflow: hidden;"></div>
                </div>

                <div class="panel">
                    <h2><i class="fas fa-bell"></i> Gempa Dirasakan</h2>
                    <div class="panel">
                        <h2><i class="fas fa-bell"></i> Gempa Dirasakan</h2>

                        <div id="felt-earthquakes" style="max-height: 400px; overflow-y: auto; padding-right:5px;">
                            <?php
                            // Query: Ambil data yang kolom 'dirasakan' TIDAK kosong dan TIDAK '-'
                            $q_felt = mysqli_query($conn, "SELECT * FROM data_gempa 
                                       WHERE dirasakan != '-' 
                                       AND dirasakan IS NOT NULL 
                                       ORDER BY tanggal_jam DESC LIMIT 10");

                            if (mysqli_num_rows($q_felt) > 0) {
                                while ($gf = mysqli_fetch_assoc($q_felt)) {
                                    // Tentukan warna alert berdasarkan magnitude
                                    $mag = (float)$gf['magnitude'];
                                    $alert_class = ($mag >= 5.0) ? 'danger' : 'warning'; // Merah jika >= 5, Kuning jika < 5
                            ?>

                                    <div class="alert-item <?php echo $alert_class; ?>" style="margin-bottom: 10px; border-left: 4px solid #ffd500; background: rgba(255,255,255,0.05); padding:10px; border-radius:5px;">
                                        <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                                            <strong style="color: #ffd500;">M <?php echo $gf['magnitude']; ?></strong>
                                            <small style="opacity:0.7;"><?php echo $gf['jam_text']; ?></small>
                                        </div>
                                        <div style="font-size: 13px; margin-bottom: 5px; font-weight:600;">
                                            <?php echo $gf['wilayah']; ?>
                                        </div>
                                        <div style="font-size: 11px; opacity: 0.8; font-style:italic;">
                                            <i class="fas fa-house-damage"></i> Dirasakan: <?php echo $gf['dirasakan']; ?>
                                        </div>
                                        <div style="font-size: 10px; opacity: 0.5; margin-top:5px; text-align:right;">
                                            <?php echo $gf['tanggal_text']; ?>
                                        </div>
                                    </div>

                            <?php
                                }
                            } else {
                                echo "<p style='text-align:center; opacity:0.6; padding:20px;'>Belum ada data gempa dirasakan.<br>Klik tombol 'Ambil Data BMKG' di atas.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <h2><i class="fas fa-table"></i> Data Gempa M ≥ 5.0 Terkini</h2>
                <a href="sync_gempa.php" class="btn" style="float: right; margin-top: -50px; text-decoration:none;">
                    <i class="fas fa-sync-alt"></i> Ambil Data BMKG Terbaru
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
                            $query_gempa = mysqli_query($conn, "SELECT * FROM data_gempa ORDER BY tanggal_jam DESC LIMIT 10");

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
                        <tr>
                            <td colspan="5" style="text-align:center">Memuat Data...</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="education-page" class="page-section">
            <div class="panel">
                <h2><i class="fas fa-graduation-cap"></i> Materi Edukasi Tsunami</h2>

                <div class="accordion">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <span><i class="fas fa-question-circle"></i> Apa itu Tsunami?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Tsunami adalah gelombang laut dahsyat yang terjadi karena adanya gangguan impulsif terhadap air laut. Penyebab utamanya adalah gempa bumi tektonik di dasar laut.</p>
                    </div>
                </div>

                <div class="accordion">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <span><i class="fas fa-running"></i> Langkah Evakuasi</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <ul style="margin-left: 20px; line-height: 1.8;">
                            <li>Jangan panik, tetap tenang.</li>
                            <li>Jauhi pantai jika merasakan gempa kuat.</li>
                            <li>Ikuti jalur evakuasi ke dataran tinggi.</li>
                            <li>Tunggu arahan dari pihak berwenang (BPBD/BMKG).</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div id="mitigation-page" class="page-section">
            <div class="panel">
                <h2><i class="fas fa-shield-alt"></i> Mitigasi Bencana</h2>
                <div class="accordion">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <span><i class="fas fa-tree"></i> Penanaman Mangrove</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Sabuk hijau (Green Belt) berupa hutan mangrove dapat meredam energi gelombang tsunami sebelum mencapai pemukiman warga.</p>
                    </div>
                </div>
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

        // Jalankan Fetch saat load
        fetchLatestEarthquake();
        fetchTableData();
        fetchFelt();
    </script>
</body>

</html>