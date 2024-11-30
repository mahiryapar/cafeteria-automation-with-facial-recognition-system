<?php 
session_start();
echo json_encode(
    ['nickname' => isset($_SESSION['nickname']) ? $_SESSION['nickname'] : 'defaul_pp',
    'role' => isset($_SESSION['role']) ? $_SESSION['role'] : 'guest']
)

?>