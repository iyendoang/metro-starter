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
    $titleToolbarTwo = "Data Master - Wilayah";
    $titleToolbarThree = "Provinsi Indonesia";
} elseif ($pg == 'regencies') {
    $titleToolbar = "Kota";
    $titleToolbarTwo = "Data Master - Wilayah";
    $titleToolbarThree = "Kota Indonesia";
} elseif ($pg == 'districts') {
    $titleToolbar = "Kecamatan";
    $titleToolbarTwo = "Data Master - Wilayah";
    $titleToolbarThree = "Kecamatan Indonesia";
} elseif ($pg == 'villages') {
    $titleToolbar = "Kelurahan";
    $titleToolbarTwo = "Data Master - Wilayah";
    $titleToolbarThree = "Kelurahan Indonesia";
} elseif ($pg == 'schools') {
    $titleToolbar = "Sekolah";
    $titleToolbarTwo = "Data Master";
    $titleToolbarThree = "Sekolah Indonesia";
} elseif ($pg == 'lembagas') {
    $titleToolbar = "Lembaga";
    $titleToolbarTwo = "Data Master";
    $titleToolbarThree = "Lembaga";
} else {
    $titleToolbar = "404";
    $titleToolbarTwo = "404";
    $titleToolbarThree = "404";
}