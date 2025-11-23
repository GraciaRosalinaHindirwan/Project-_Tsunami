<?php
include 'koneksi.php';

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM wilayah_resiko WHERE id='$id'");
$w = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];

    mysqli_query($conn, "UPDATE wilayah_resiko SET nama_wilayah='$nama', kategori='$kategori' WHERE id='$id'");
    header("Location: petaadmin.php");
    exit;
}
?>

<form method="POST">
    <label>Nama Wilayah</label>
    <input type="text" name="nama" value="<?= $w['nama_wilayah']; ?>" required>

    <label>Kategori Risiko</label>
    <select name="kategori" required>
        <option value="tinggi" <?= $w['kategori']=="tinggi" ? "selected" : ""; ?>>Risiko Tinggi</option>
        <option value="sedang" <?= $w['kategori']=="sedang" ? "selected" : ""; ?>>Risiko Sedang</option>
        <option value="rendah" <?= $w['kategori']=="rendah" ? "selected" : ""; ?>>Risiko Rendah</option>
    </select>

    <button type="submit" name="update">Update</button>
</form>
