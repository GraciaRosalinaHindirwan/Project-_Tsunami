<?php
include 'koneksi.php';

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM wilayah_resiko WHERE id='$id'");
header("Location: petaadmin.php");
exit;
?>
