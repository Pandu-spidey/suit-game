<?php
$conn = new mysqli("localhost", "root", "", "suit_game");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
