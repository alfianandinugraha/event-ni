<?php
class Auth {
  /**
   * Method login untuk memasukan user kedalam aplikasi
   * Menggunakan cookie dan session
   */
  public static function login($userId) {
    session_start();
    $_SESSION['login'] = true;
    setcookie('user', $userId);
  }

  /**
   * Method logout untuk mengeluarkan user dari aplikasi
   * Dengan cara menghapus cookie dan session
   */
  public static function logout() {
    session_destroy();
    setcookie('PHPSESSID');
    setcookie('user'); 
  }
}
?>