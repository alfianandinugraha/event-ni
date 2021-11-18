<?php
class Key {
  public static $hashType = 'sha256';
  public static $encryptMethod = 'AES-256-CBC';

  public static function generate($data = "") {
    // Generate random salt on https://www.lastpass.com/features/password-generator
    $secretKey = "dxFFeX8w8VAFGtA0^IbTes0!ay!2t*e8";
    $data = $data . $secretKey;
    return hash(Key::$hashType, $data);
  }

  public static function encrypt($data) {
    return openssl_encrypt($data, Key::$encryptMethod, Key::generate(), 0);
  }

  public static function decrypt($data) {
    return openssl_decrypt($data, Key::$encryptMethod, Key::generate(), 0);
  }

  public static function compare($hash, $data) {
    $isSame = Key::generate($data) == $hash; 
    return $isSame;
  }
}
?>