<?php defined('BASEPATH') or die("ip anda sudah tercatat oleh sistem kami") ?>
<?php
if ($pg == '') {
    include "dashboard/dashboard.php";
    $toolbarContent = "Toolbar untuk dashboard";
} elseif ($pg == 'users') {
    include "users/users.php";
    $toolbarContent = "Toolbar untuk users";
} elseif ($pg == 'daftar') {
    include "mod_daftar/daftar.php";
    $toolbarContent = "Toolbar untuk daftar";
} else {
    include "errors/404.php";
    $toolbarContent = "Toolbar untuk halaman 404";
}