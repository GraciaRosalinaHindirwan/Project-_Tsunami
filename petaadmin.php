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
        /* WRAPPER KONTEN */
.content-wrapper {
    max-width: 1200px;
    margin: 40px auto;
    background: rgba(255, 255, 255, 0.07);
    padding: 35px;
    border-radius: 18px;
    border: 1px solid rgba(255,255,255,0.15);
    backdrop-filter: blur(6px);
}

/* JUDUL HALAMAN */
.page-title {
    font-size: 24px;
    font-weight: 700;
    color: #00d9ff;
    margin-bottom: 20px;
}

/* TOMBOL TAMBAH */
.btn-add {
    display: inline-block;
    background: #00d9ff;
    color: #001f2b;
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: 700;
    text-decoration: none;
    margin-bottom: 18px;
    transition: 0.3s;
}
.btn-add:hover {
    background: #00b8d4;
    transform: translateY(-2px);
}

/* TABLE */
.table-container {
    overflow-x: auto;
}

.risk-table {
    width: 100%;
    border-collapse: collapse;
}

.risk-table th {
    background: rgba(0, 217, 255, 0.2);
    color: #00d9ff;
    padding: 14px;
    text-align: left;
    font-size: 14px;
    letter-spacing: 0.5px;
}

.risk-table td {
    padding: 12px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    font-size: 14px;
}

/* KATEGORI BADGE */
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 12px;
    text-transform: capitalize;
}

.badge.tinggi {
    background: #ff3838;
}
.badge.sedang {
    background: #ffd500;
    color: #000;
}
.badge.rendah {
    background: #00ff88;
    color: #000;
}

/* TOMBOL AKSI */
.btn-edit,
.btn-delete {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.2s;
}

.btn-edit {
    background: rgba(0, 217, 255, 0.2);
    color: #00d9ff;
}
.btn-edit:hover {
    background: #00d9ff;
    color: #001f2b;
}

.btn-delete {
    background: rgba(255, 56, 56, 0.15);
    color: #ff3838;
}
.btn-delete:hover {
    background: #ff3838;
    color: #fff;
}

/* RESPONSIVE */
@media(max-width: 600px) {
    .page-title {
        font-size: 20px;
    }
    .btn-add {
        font-size: 13px;
        padding: 8px 14px;
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
                <a href="homeadmin.php" class="nav-btn">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="petaadmin.php" class="nav-btn active">
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

    <?php
include 'koneksi.php';
$wilayah = mysqli_query($conn, "SELECT * FROM wilayah_resiko ORDER BY kategori, nama_wilayah");
?>
<div class="content-wrapper">

    <h2 class="page-title">Manajemen Wilayah Risiko Tsunami</h2>

    <a href="tambah-wilayah.php" class="btn-add">+ Tambah Wilayah</a>

    <div class="table-container">
        <table class="risk-table">
            <tr>
                <th>Wilayah</th>
                <th>Kategori</th>
                <th>Aksi</th>
            </tr>

            <?php while ($w = mysqli_fetch_assoc($wilayah)) : ?>
            <tr>
                <td><?= $w['nama_wilayah']; ?></td>
                <td><span class="badge <?= $w['kategori']; ?>"><?= ucfirst($w['kategori']); ?></span></td>
                <td>
                    <a href="edit-wilayah.php?id=<?= $w['id']; ?>" class="btn-edit">Edit</a>
                    <a href="hapus-wilayah.php?id=<?= $w['id']; ?>" class="btn-delete" onclick="return konfirmasiHapus('<?= $w['nama_wilayah']; ?>')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</div>

    <div class="container">                 
    <div class="footer">
        <p>&copy; 2025 SIM TSUNAMI | Logged in as: <?php echo $nama_lengkap; ?> | Data Source: BMKG</p>
    </div>
</body>
<script>
function konfirmasiHapus(nama) {
    return confirm("Yakin ingin menghapus wilayah:\n\n" + nama + " ?");
}
</script>
</html>