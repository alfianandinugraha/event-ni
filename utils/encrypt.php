<?php
class Encrypt {
  public static $hashType = 'sha256';

  public static function generate($data) {
    // Generate random salt on https://www.lastpass.com/features/password-generator
    $salt = "dxFFeX8w8VAFGtA0^IbTes0!ay!2t*e8";
    $data = $data . $salt;
    return hash(Encrypt::$hashType, $data);
  }

  public static function compare($hash, $data) {
    $isSame = Encrypt::generate($data) == $hash; 
    return $isSame;
  }
}
?>