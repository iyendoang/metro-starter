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
} else {
    $titleToolbar = "404";
    $titleToolbarTwo = "404";
    $titleToolbarThree = "404";
}