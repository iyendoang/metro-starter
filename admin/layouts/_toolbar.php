<?php
$titleToolbar = "";
$titleToolbarTwo = "";
$titleToolbarThree = "";
$titleToolbarFour = "";

if ($pg == '') {
    $titleToolbar = "Dashboard";
} elseif ($pg == 'users') {
    $titleToolbar = "Users";
    $titleToolbarTwo = "Data Master";
    $titleToolbarThree = "Pengguna";
} elseif ($pg == 'provinces') {
    $titleToolbar = "Provinsi";
    $titleToolbarTwo = "Data Master";
    $titleToolbarThree = "Provinsi Indonesia";
} elseif ($pg == 'regencies') {
    $titleToolbar = "Kota";
    $titleToolbarTwo = "Data Master";
    $titleToolbarThree = "Kota Indonesia";
} elseif ($pg == 'districts') {
    $titleToolbar = "Kecamatan";
    $titleToolbarTwo = "Data Master";
    $titleToolbarThree = "Kecamatan Indonesia";
} elseif ($pg == 'villages') {
    $titleToolbar = "Kelurahan";
    $titleToolbarTwo = "Data Master";
    $titleToolbarThree = "Kelurahan Indonesia";
} elseif ($pg == 'lembagas') {
    $titleToolbar = "Lembaga";
    $titleToolbarTwo = "Data Master";
    $titleToolbarThree = "Lembaga";
} else {
    $titleToolbar = "404";
    $titleToolbarTwo = "404";
    $titleToolbarThree = "404";
}