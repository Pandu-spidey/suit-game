<?php
session_start();
unset($_SESSION['hasil']);
unset($_SESSION['best5']);
unset($_SESSION['game_done']);
header("Location: index.php");
