<?php
function sanitize($text) {
  return filter_var($text, FILTER_SANITIZE_STRING);
}
?>