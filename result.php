<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php?mode=login");
    exit();
}

$uid = $_SESSION['user_id'];
$data = $_SESSION['hasil'] ?? null;

// statistik menang/kalah
$stats = ['menang' => 0, 'kalah' => 0, 'seri' => 0];
$q1 = $conn->query("SELECT result, COUNT(*) as total FROM scores WHERE user_id = $uid GROUP BY result");
while ($row = $q1->fetch_assoc()) {
    $stats[$row['result']] = $row['total'];
}

// grafik pilihan suit
$chartData = [];
$q2 = $conn->query("SELECT player_choice, COUNT(*) as total FROM scores WHERE user_id = $uid GROUP BY player_choice");
while ($row = $q2->fetch_assoc()) {
    $chartData[$row['player_choice']] = $row['total'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hasil Permainan</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
    <h2>Hasil Terakhir:</h2>
    <?php if ($data): ?>
        <p>Kamu pilih: <strong><?= $data['player'] ?></strong></p>
        <p>Komputer pilih: <strong><?= $data['computer'] ?></strong></p>
        <p><strong><?= strtoupper($data['result']) ?></strong></p>
    <?php else: ?>
        <p>Belum main...</p>
    <?php endif; ?>

    <?php if (!empty($_SESSION['game_done'])): ?>
        <h3>🏁 Sesi Berakhir!</h3>
        <p><?= $_SESSION['best5']['menang'] == 3 ? 'Kamu MENANG sesi ini!' : 'Kamu KALAH sesi ini.' ?></p>
        <a href="reset.php">Mainkan ulang sesi best-of-5</a>
    <?php else: ?>
        <p>Progress Best of 5: Menang <?= $_SESSION['best5']['menang'] ?? 0 ?> - Kalah <?= $_SESSION['best5']['kalah'] ?? 0 ?></p>
    <?php endif; ?>

    <h3>Statistik Total:</h3>
    <ul>
        <li>Menang: <?= $stats['menang'] ?></li>
        <li>Kalah: <?= $stats['kalah'] ?></li>
        <li>Seri: <?= $stats['seri'] ?></li>
    </ul>

    <h3>Grafik Pilihan Suit:</h3>
    <canvas id="suitChart" width="300" height="300"></canvas>
    <script>
        const ctx = document.getElementById('suitChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_keys($chartData)) ?>,
                datasets: [{
                    label: 'Jumlah Dipilih',
                    data: <?= json_encode(array_values($chartData)) ?>,
                    backgroundColor: ['#ff6384', '#36a2eb', '#ffce56']
                }]
            }
        });
    </script>

    <br><a href="index.php">Main Lagi</a>
</div>
</body>
</html>
