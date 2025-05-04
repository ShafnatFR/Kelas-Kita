<?php
session_start(); // Mulai session

// Hapus semua data session
session_unset();
session_destroy();

// Redirect ke halaman login atau home
header("Location: HalamanSignIn.php");
exit;
