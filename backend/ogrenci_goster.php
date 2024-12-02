<?php
session_start();
$_SESSION['suanki_mesaj'] = -2;
$_SESSION['gosterilen_ogrenci'] = $_GET['ogrenci_id'];
$_SESSION['gelgit'] = -1;
header("Location: ../pages/".$_GET['sayfa'].".php");
exit(1);

?>