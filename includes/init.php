<?php
// Mulai sesi hanya jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('konek-db.php');
require_once('functions.php');
?>
