<?php defined('BASEPATH') or die("ip anda sudah tercatat oleh sistem kami") ?>
<?php
if ($pg == '') {
    include "dashboard/dashboard.php";
} elseif ($pg == 'users') {
    include "users/users.php";
} elseif ($pg == 'provinces') {
    include "provinces/provinces.php";
} elseif ($pg == 'regencies') {
    include "regencies/regencies.php";
} elseif ($pg == 'districts') {
    include "districts/districts.php";
} elseif ($pg == 'villages') {
    include "villages/villages.php";
} elseif ($pg == 'jenjang') {
    include "jenjang/jenjang.php";
} elseif ($pg == 'lembagas') {
    include "lembagas/lembagas.php";
} else {
    include "errors/404.php";
}