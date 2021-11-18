<?php
include('./utils/auth.php');

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
  Auth::logout();
}

header("Location: /login.php");
?>