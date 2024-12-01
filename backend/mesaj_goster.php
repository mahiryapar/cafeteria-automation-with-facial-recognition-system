<?php
session_start();
$_SESSION['suanki_mesaj'] = $_GET['mesaj_id'];
$_SESSION['gelgit'] = $_GET['gelgit'];
header("Location: ../pages/".$_GET['sayfa'].".php");
exit(1);

?>