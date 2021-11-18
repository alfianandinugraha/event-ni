<?php
class Auth {
  public static function login($userId) {
    session_start();
    $_SESSION['login'] = true;
    setcookie('user', $userId);
  }

  public static function logout() {
    session_destroy();
    setcookie('PHPSESSID');
    setcookie('user'); 
  }
}
?>