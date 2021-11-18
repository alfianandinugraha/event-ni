<?php
session_start();
class Auth {
  public static function login($userId) {
    $_SESSION['login'] = true;
    setcookie('user', $userId);
  }
}
?>