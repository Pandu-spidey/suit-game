<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php?mode=login");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Suit vs Komputer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Game Suit (Batu Gunting Kertas)</h1>

    <form action="process.php" method="POST">
        <label><input type="radio" name="player" value="batu" required> 🪨 Batu</label><br>
        <label><input type="radio" name="player" value="gunting"> ✂️ Gunting</label><br>
        <label><input type="radio" name="player" value="kertas"> 📄 Kertas</label><br>

        <button type="submit">Mainkan!</button>
    </form>

    <a href="result.php">Lihat Riwayat & Skor</a> |
    <a href="reset.php">Reset Skor</a>
</div>
</body>
</html>
