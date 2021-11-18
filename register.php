<?php
include('./helpers/debug.php');
include('./db/mysql.php');

$method = $_SERVER['REQUEST_METHOD'];

$isEmailRegistered = false;
if ($method === 'POST') {
  $fullname = $_POST['fullname'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  $querySelect = "SELECT email FROM participants WHERE email = '$email'";
  $result = $mysql->query($querySelect)->fetch_all(MYSQLI_ASSOC);

  if ($result != 0) {
    $isEmailRegistered = true;
  } else {
    $queryInsert = "INSERT INTO participants(email, password, full_name) VALUES ('$email', '$password', '$fullname')";
    $mysql->query($queryInsert);
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
    const fullname = document.getElementById('input-fullname').value
    const email = document.getElementById('input-email').value
    const password = document.getElementById('input-password').value
    const rePassword = document.getElementById('input-re-password').value

    if (!fullname || !email | !password || !rePassword) {
      swal("Error", "Harap isi form diatas", "error");
      e.preventDefault()
      return
    }

    if (password !== rePassword) {
      swal("Error", "Password tidak sama", "error");
      e.preventDefault()
      return
    }
  })
  </script>
</body>
</html>