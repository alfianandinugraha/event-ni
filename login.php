<!DOCTYPE html>
<html lang="en">
<head>
  <?php include('./layouts/head.php') ?>
  <title>Login Admin - Eventni</title>
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
        <h4 class="center-align">Login Admin</h4>
        <div class="input-field">
          <input type="text" name="input-username" id="input-username">
          <label for="input-username">Username</label>
        </div>
        <div class="input-field">
          <input type="password" name="input-password" id="input-password">
          <label for="input-password">Password</label>
        </div>
        <button class="btn waves-effect waves-light btn-login">Login</button>
      </form>
    </div>
  </main>
</body>