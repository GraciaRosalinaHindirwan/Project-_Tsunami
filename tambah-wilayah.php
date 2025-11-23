<?php
// 1. Mulai Sesi PHP & Koneksi Database
session_start();
include 'koneksi.php';

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

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];

    mysqli_query($conn, "INSERT INTO wilayah_resiko (nama_wilayah, kategori) VALUES ('$nama', '$kategori')");
    header("Location: petaadmin.php");
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
    max-width: 550px;
    margin: 50px auto;
    background: rgba(255, 255, 255, 0.07);
    padding: 35px 40px;
    border-radius: 18px;
    border: 1px solid rgba(255,255,255,0.15);
    backdrop-filter: blur(6px);
}

/* JUDUL HALAMAN */
.page-title {
    font-size: 24px;
    font-weight: 700;
    color: #00d9ff;
    margin-bottom: 25px;
    text-align: center;
}

/* FORM */
.form-box {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

/* LABEL */
.form-box label {
    font-size: 14px;
    font-weight: 600;
    opacity: 0.9;
}

/* INPUT & SELECT */
.form-box input,
.form-box select {
    padding: 12px 15px;
    border-radius: 10px;
    border: none;
    width: 100%;
    background: rgba(0,0,0,0.25);
    color: #fff;
    font-size: 14px;
    transition: 0.25s;
}

.form-box input:focus,
.form-box select:focus {
    outline: none;
    border: 1px solid #00d9ff;
    background: rgba(0, 217, 255, 0.08);
}

/* SUBMIT BUTTON */
.btn-submit {
    background: #00d9ff;
    color: #001f2b;
    padding: 13px;
    border-radius: 10px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    font-size: 15px;
    transition: 0.3s;
    display: flex;
    justify-content: center;
    gap: 8px;
    align-items: center;
}

.btn-submit:hover {
    background: #00b8d4;
    transform: translateY(-2px);
}

/* RESPONSIVE */
@media (max-width: 600px) {
    .content-wrapper {
        margin: 30px 20px;
        padding: 25px;
    }
    .page-title {
        font-size: 20px;
    }
}
/* --- FIX KONTRAS DROPDOWN --- */
#kategori {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;

    padding-right: 28px; /* dulu kebesaran */
    
    background-image: url("data:image/svg+xml;utf8,<svg fill='%23ffffff' height='18' width='18' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
    background-repeat: no-repeat;
    background-position: right 10px center; /* <<< geser arrow ke kiri */
    background-size: 16px;
}

/* Warna background list dropdown */
#kategori option {
    background: #0b1e26 !important; /* navy gelap */
    color: #ffffff !important;      /* teks putih */
    font-weight: 600;
}

/* Hover lebih jelas */
#kategori option:hover {
    background: #00d9ff !important;
    color: #000 !important;
}
#kategori option[value="tinggi"] {
    color: #ff3838;
    font-weight: 600;
}
#kategori option[value="sedang"] {
    color: #ffd500;
    font-weight: 600;
}
#kategori option[value="rendah"] {
    color: #00ff88;
    font-weight: 600;
}
#kategori {
    border-left: 8px solid transparent;
    transition: .25s;
    padding-left: 12px;
}
</style>
</head>
<script>
    $("#kategori").on("change", function(){
    const val = $(this).val();
    let warna = "#fff";

    if(val === "tinggi") warna = "#ff3838";
    if(val === "sedang") warna = "#e6b800";
    if(val === "rendah") warna = "#00c96b";

    $(this).css("border-left", `8px solid ${warna}`);
});
</script>

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
                <a href="petaadmin.php" class="nav-btn">
                    <i class="fas fa-map"></i> Peta
                </a>
                <a href="tambah-wilayah.php" class="nav-btn active">
                <i class="fas fa-map-marked-alt"></i> Kelola Wilayah
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
<div class="content-wrapper">
    <h2 class="page-title">Tambah Wilayah Risiko Tsunami</h2>

    <form action="" method="POST" class="form-box">
        <label>Nama Wilayah</label>
        <input type="text" name="nama" placeholder="Masukkan nama wilayah..." required>

       <label>Kategori Risiko</label>
        <select name="kategori" id="kategori" required>
            <option value="" selected disabled>Pilih kategori...</option>
            <option value="tinggi" data-color="#ff3838">🔴 Risiko Tinggi</option>
            <option value="sedang" data-color="#ffd500">🟡 Risiko Sedang</option>
            <option value="rendah" data-color="#00ff88">🟢 Risiko Rendah</option>
        </select>

        <button type="submit" name="simpan" class="btn-submit">
            <i class="fas fa-save"></i> Simpan Wilayah
        </button>
    </form>
</div>
    <div class="container">                 
    <div class="footer">
        <p>&copy; 2025 SIM TSUNAMI | Logged in as: <?php echo $nama_lengkap; ?> | Data Source: BMKG</p>
    </div>
</body>

</html>
