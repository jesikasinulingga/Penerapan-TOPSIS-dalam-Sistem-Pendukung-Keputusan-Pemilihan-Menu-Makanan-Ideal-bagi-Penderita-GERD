<?php
require_once(__DIR__ . '/../includes/init.php');
session_start();
session_destroy();
redirect_to("../public/landingpage.php");
?>
