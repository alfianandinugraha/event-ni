<?php
session_start();
include('./utils/auth.php');

if (isset($_SESSION['login']) && $_SESSION['login']) {
  header('Location: /');
} else {
  Auth::logout();
}

$method = $_SERVER['REQUEST_METHOD'];

$isEmailRegistered = false;
if ($method === 'POST') {
  include('./db/mysql.php');
  include('./utils/key.php');

  $email = $_POST['email'];
  
  $querySelect = "SELECT email FROM users WHERE email = '$email'";
  $result = $mysql->query($querySelect)->fetch_all(MYSQLI_ASSOC);

  if (count($result) != 0) {
    $isEmailRegistered = true;
  } else {
    $password = $_POST['password'];
    $fullname = $_POST['fullname'];
    $encryptedPassword = Key::generate($password);
    $queryInsert = "
      INSERT INTO users(email, password, full_name) VALUES ('$email', '$encryptedPassword', '$fullname')
    ";
    $mysql->query($queryInsert);

    $queryGetUser = "SELECT user_id FROM users WHERE email = '$email'";
    $result = $mysql->query($queryGetUser)->fetch_all(MYSQLI_ASSOC);

    $participantId = $result[0]['user_id'];
    Auth::login(Key::encrypt($participantId));

    header('Location: /');
  }

  $mysql->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include('./layouts/head.php') ?>
  <title>Register - EventNi</title>
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
      <form method="POST" action="register.php" class="col s12 l8 xl4 form-login" id="form-register">
        <h4 class="center-align">Register</h4>
        <div class="input-field">
          <input type="text" name="fullname" id="input-fullname">
          <label for="input-fullname">Nama Lengkap</label>
        </div>
        <div class="input-field">
          <input type="email" name="email" id="input-email">
          <label for="input-email">Email</label>
        </div>
        <div class="input-field">
          <input type="password" name="password" id="input-password">
          <label for="input-password">Password</label>
        </div>
        <div class="input-field">
          <input type="password" name="re-password" id="input-re-password">
          <label for="input-password">Ulangi Password</label>
        </div>
        <div class="input-field">
          <button class="btn waves-effect waves-light btn-login">Register</button>
        </div>
        <div class="input-field">
          <p class="center-align">Sudah punya akun ? <a href="login.php">Login</a></p>
        </div>
      </form>
    </div>
  </main>
  <?php if($isEmailRegistered) { ?>
    <script>
      swal("Error", "Email sudah terdaftar", "error");
    </script>
  <?php } ?>
  <script>
  const form = document.getElementById('form-register')
  form.addEventListener('submit', (e) => {
    e.preventDefault()
    let fullname = document.getElementById('input-fullname').value
    let email = document.getElementById('input-email').value
    let password = document.getElementById('input-password').value
    let rePassword = document.getElementById('input-re-password').value

    if (!fullname || !email | !password || !rePassword) {
      swal("Error", "Harap isi form diatas", "error");
      return
    }

    if (password !== rePassword) {
      swal("Error", "Password tidak sama", "error");
      return
    }

    fullname = DOMPurify.sanitize(fullname)
    email = DOMPurify.sanitize(email)
    password = DOMPurify.sanitize(password)

    e.target.fullname.value = fullname
    e.target.password.value = password
    e.target.email.value = email
    form.submit()
  })
  </script>
</body>
</html>