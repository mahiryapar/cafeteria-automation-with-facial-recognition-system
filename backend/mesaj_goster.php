<?php
session_start();
$_SESSION['suanki_mesaj'] = $_GET['mesaj_id'];
header("Location: ../pages/iletisim.php");
exit(1);

?>