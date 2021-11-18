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
      <form method="POST" action="login.php" class="col s12 l8 xl4 form-login">
        <h4 class="center-align">Register</h4>
        <div class="input-field">
          <input type="text" name="email" id="input-email">
          <label for="input-email">Nama Lengkap</label>
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
          <input type="password" name="password" id="input-password">
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
</body>