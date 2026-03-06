<?php
$host = "sql207.infinityfree.com";
$user = "if0_41246865"; 
$pass = "8Cu8dHgV4K";
$db = "if0_41246865_galerifoto";

$conn = mysqli_connect($host, $user, $pass, $db);

if(!$conn) {
    die("koneksi gagal: " . mysqli_connect_error());
}
?>