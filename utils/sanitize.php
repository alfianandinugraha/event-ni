<?php
/**
 * fungsi ini digunakan untuk memfilter teks berbahaya seperti script
 */
function sanitize($text) {
  return filter_var($text, FILTER_SANITIZE_STRING);
}
?>