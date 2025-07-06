<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php?mode=login");
    exit();
}

$player = $_POST['player'];
$opsi = ['batu', 'gunting', 'kertas'];
$computer = $opsi[array_rand($opsi)];

function cekHasil($p, $c) {
    if ($p === $c) return 'seri';
    if (
        ($p === 'batu' && $c === 'gunting') ||
        ($p === 'gunting' && $c === 'kertas') ||
        ($p === 'kertas' && $c === 'batu')
    ) return 'menang';
    return 'kalah';
}

$hasil = cekHasil($player, $computer);

// simpan skor
$uid = $_SESSION['user_id'];
$stmt = $conn->prepare("INSERT INTO scores (user_id, player_choice, computer_choice, result) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $uid, $player, $computer, $hasil);
$stmt->execute();

// simpan sesi hasil
$_SESSION['hasil'] = [
    'player' => $player,
    'computer' => $computer,
    'result' => $hasil
];

// handle best-of-5
if (!isset($_SESSION['best5'])) $_SESSION['best5'] = ['menang' => 0, 'kalah' => 0];
if ($hasil === 'menang') $_SESSION['best5']['menang']++;
if ($hasil === 'kalah') $_SESSION['best5']['kalah']++;

if ($_SESSION['best5']['menang'] == 3 || $_SESSION['best5']['kalah'] == 3) {
    $_SESSION['game_done'] = true;
}

header("Location: result.php");
