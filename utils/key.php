<?php
class Key {
  /**
   * Jenis hash digunakan
   */
  public static $hashType = 'sha256';
  /**
   * Metode enkripsi yang digunakan
   */
  public static $encryptMethod = 'AES-256-CBC';

  /**
   * Membuat password yang tidak bisa dibaca / bukan plain text
   */
  public static function generate($data = "") {
    // Generate random salt on https://www.lastpass.com/features/password-generator
    $secretKey = "dxFFeX8w8VAFGtA0^IbTes0!ay!2t*e8";
    $data = $data . $secretKey;
    return hash(Key::$hashType, $data);
  }

  /**
   * Melakukan enkripsi pada teks
   */
  public static function encrypt($data) {
    return openssl_encrypt($data, Key::$encryptMethod, Key::generate(), 0);
  }

  /**
   * Melakukan dekripsi pada teks
   */
  public static function decrypt($data) {
    return openssl_decrypt($data, Key::$encryptMethod, Key::generate(), 0);
  }

  /**
   * Membandingkan dua hash
   */
  public static function compare($hash, $data) {
    $isSame = Key::generate($data) == $hash; 
    return $isSame;
  }
}
?>