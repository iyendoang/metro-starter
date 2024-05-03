<?php
$server   = "localhost";
$username = "root";
$password = "";
$database = "sidoel_api";
$koneksi = mysqli_connect($server, $username, $password, $database);
if (!$koneksi) {
    die('Koneksi Database Gagal : ');
}
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
(isset($_GET['ac'])) ? $ac = $_GET['ac'] : $ac = '';
date_default_timezone_set("Asia/Jakarta");
define('BASEPATH', str_replace("config", "", dirname(__FILE__)));
