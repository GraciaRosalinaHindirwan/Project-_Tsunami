<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['login_user'])) {
    header("location: login-admin.php");
    exit;
}

$nama_lengkap = $_SESSION['nama_lengkap'];

// Ambil ID wilayah
$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM wilayah_resiko WHERE id='$id'");
$w = mysqli_fetch_assoc($data);

// Update Data
if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];

    mysqli_query($conn, "UPDATE wilayah_resiko 
        SET nama_wilayah='$nama', kategori='$kategori' 
        WHERE id='$id'");

    echo "<script>
        window.onload = function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data wilayah berhasil diperbarui!',
                confirmButtonColor: '#00d9ff'
            }).then(() => {
                window.location.href = 'petaadmin.php';
            });
        }
    </script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Wilayah - SIM TSUNAMI</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
*{margin:0;padding:0;box-sizing:border-box}
body{
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    color:#fff;
    min-height:100vh;
}

/* FORM WRAPPER */
.content-wrapper{
    max-width:550px;
    margin:50px auto;
    background:rgba(255,255,255,0.07);
    padding:35px 40px;
    border-radius:18px;
    border:1px solid rgba(255,255,255,0.15);
    backdrop-filter:blur(6px);
}

/* TITLE */
.page-title{
    font-size:24px;
    font-weight:700;
    color:#00d9ff;
    text-align:center;
    margin-bottom:25px;
}

/* FORM */
.form-box{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.form-box input,
.form-box select{
    padding:12px 15px;
    border-radius:10px;
    border:none;
    background:rgba(0,0,0,0.25);
    color:#fff;
    font-size:14px;
    transition:.25s;
}

.form-box input:focus,
.form-box select:focus{
    outline:none;
    border:1px solid #00d9ff;
    background:rgba(0,217,255,0.08);
}

/* DROPDOWN ARROW */
#kategori{
    appearance:none;
    -webkit-appearance:none;
    -moz-appearance:none;
    padding-right:28px;
    background-image:url("data:image/svg+xml;utf8,<svg fill='%23ffffff' width='18' height='18' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
    background-repeat:no-repeat;
    background-position:right 11px center;
}

/* BORDER WARNA */
#kategori{
    border-left:8px solid transparent;
}

/* BUTTON */
.btn-submit{
    background:#00d9ff;
    color:#001f2b;
    padding:13px;
    border:none;
    font-weight:700;
    border-radius:10px;
    font-size:15px;
    cursor:pointer;
    transition:.3s;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:8px;
}
.btn-submit:hover{
    background:#00b8d4;
    transform:translateY(-2px);
}

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

<body>

<div class="content-wrapper">
    <h2 class="page-title">Edit Wilayah Risiko</h2>

    <form method="POST" class="form-box">
        <label>Nama Wilayah</label>
        <input type="text" name="nama" value="<?= $w['nama_wilayah']; ?>" required>

        <label>Kategori Risiko</label>
        <select name="kategori" id="kategori" required>
            <option value="tinggi" <?= $w['kategori']=="tinggi" ? "selected" : ""; ?>>🔴 Risiko Tinggi</option>
            <option value="sedang" <?= $w['kategori']=="sedang" ? "selected" : ""; ?>>🟡 Risiko Sedang</option>
            <option value="rendah" <?= $w['kategori']=="rendah" ? "selected" : ""; ?>>🟢 Risiko Rendah</option>
        </select>

        <button type="submit" name="update" class="btn-submit">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>
    </form>
</div>

<script>
// Warna border dropdown dinamis
document.getElementById("kategori").addEventListener("change", function(){
    const warna = {
        tinggi: "#ff3838",
        sedang: "#ffd500",
        rendah: "#00ff88"
    };
    this.style.borderLeft = "8px solid " + warna[this.value];
});

// Set warna awal sesuai data
document.getElementById("kategori").dispatchEvent(new Event("change"));

$("#kategori").on("change", function(){
    const val = $(this).val();
    let warna = "#fff";

    if(val === "tinggi") warna = "#ff3838";
    if(val === "sedang") warna = "#e6b800";
    if(val === "rendah") warna = "#00c96b";

    $(this).css("border-left", `8px solid ${warna}`);
});
</script>

</body>
</html>
