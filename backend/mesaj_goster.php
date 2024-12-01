<?php
session_start();
$_SESSION['suanki_mesaj'] = $_GET['mesaj_id'];
$_SESSION['gelgit'] = $_GET['gelgit'];
header("Location: ../pages/iletisim.php");
exit(1);

?>