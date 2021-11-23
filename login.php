<?php
session_start();
include('./utils/auth.php');

/**
 * Mengecek apakah user sedang login atau belum
 */
if (isset($_SESSION['login']) && $_SESSION['login']) {
  header('Location: /');
} else {
  Auth::logout();
}

$method = $_SERVER['REQUEST_METHOD'];

$email = "";
$password = "";

$isUserFound = true;
if ($method == "POST") {
  include('./db/mysql.php');
  include('./utils/key.php');
  include('./helpers/debug.php');

  $email = $mysql->real_escape_string($_POST['email']);
  $password = $mysql->real_escape_string($_POST['password']);

  $encryptedPassword = Key::generate($password);
  /**
   * Mengecek user menggunakan email dan password
   */
  $query = "SELECT user_id FROM users WHERE email = '$email' AND password = '$encryptedPassword'";
  $result = $mysql->query($query)->fetch_all(MYSQLI_ASSOC);

  if (count($result) == 0) {
    $isUserFound = false;
  } else {
    /**
     * Jika user ada maka akan dilakukan session dan cookie dari class Auth
     * dan dialihkan kehalaman awal
     */
    Auth::login(Key::encrypt($result[0]['user_id']));
    header('Location: /');
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include('./layouts/head.php') ?>
  <title>Login - EventNi</title>
  <style>
    .btn-login {
      width: 100%;
    }
    .row-center {
      display: flex;
    }
    .row-center .col {
      margin-left: auto;
      margin-right: auto;
    }
    .form-login h4 {
      margin-bottom: 2rem;
    }
    .container-center {
      height: 100vh;
      margin-top: 0;
      display: flex;
      align-items: center;
    }
    .container-center .row {
      margin: 0;
      width: 100%;
    }
  </style>
</head>
<body>
  <?php include('./layouts/ribbon.php') ?>
  <main class="container container-center">
    <div class="row row-center">
      <form method="POST" action="login.php" class="col s12 l8 xl4 form-login" id="form-login">
        <h4 class="center-align">Login</h4>
        <div class="input-field">
          <input type="email" name="email" id="input-email" value="<?= $email ?>">
          <label for="input-email">Email</label>
        </div>
        <div class="input-field">
          <input type="password" name="password" id="input-password">
          <label for="input-password">Password</label>
        </div>
        <div class="input-field">
          <button class="btn waves-effect waves-light btn-login">Login</button>
        </div>
        <div class="input-field">
          <p class="center-align">Belum punya akun ? <a href="register.php">Register</a></p>
        </div>
      </form>
    </div>
  </main>
  <?php if(!$isUserFound) { ?>
    <script>
      swal("Error", "User tidak ditemukan", "error");
    </script>
  <?php } ?>
  <script>
    const formEl = document.getElementById('form-login')
    formEl.addEventListener('submit', (e) => {
      e.preventDefault()
      let email = e.target.email.value
      let password = e.target.password.value
      email = DOMPurify.sanitize(email)
      password = DOMPurify.sanitize(password)

      e.target.password.value = password
      e.target.email.value = email
      formEl.submit()
    })
  </script>
</body>