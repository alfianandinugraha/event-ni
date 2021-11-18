<?php
$method = $_SERVER['REQUEST_METHOD'];

$email = "";
$password = "";

$isUserFound = true;
if ($method == "POST") {
  include('./db/mysql.php');
  include('./utils/key.php');
  include('./utils/auth.php');
  include('./helpers/debug.php');

  $email = $_POST['email'];
  $password = $_POST['password'];

  $encryptedPassword = Key::generate($password);
  $query = "SELECT participant_id FROM participants WHERE email = '$email' AND password = '$encryptedPassword'";
  $result = $mysql->query($query)->fetch_all(MYSQLI_ASSOC);

  if (count($result) == 0) {
    $isUserFound = false;
  } else {
    Auth::login(Key::encrypt($email));
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
  <main class="container container-center">
    <div class="row row-center">
      <form method="POST" action="login.php" class="col s12 l8 xl4 form-login">
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
</body>