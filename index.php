<?php
session_start();
if (!isset($_SESSION['login'])) {
  header("Location: /login.php");
}

include('./db/mysql.php');
include('./utils/key.php');
include('./helpers/debug.php');
$userId = Key::decrypt($_COOKIE['user']);

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
  $action = $mysql->real_escape_string($_POST['action']);
  
  if ($action == 'DELETE_TRANSACTION') {
    $transactionId = $mysql->real_escape_string($_POST['transaction_id']);
    $queryDeleteTransaction = "DELETE FROM transactions WHERE transaction_id = $transactionId AND user_id = $userId";
    $mysql->query($queryDeleteTransaction);
  }

  if ($action == 'INSERT_TRANSACTION') {
    $eventId = $mysql->real_escape_string($_POST['event_id']);
    $queryInsertTransaction = "INSERT INTO transactions(event_id, user_id) VALUES ('$eventId', '$userId')";
    $mysql->query($queryInsertTransaction);
  }

  header('Location: /');
}

$queryGetEvents = "
SELECT event_id, title FROM events
WHERE events.event_id NOT IN (
	SELECT transactions.event_id FROM transactions
  WHERE transactions.user_id = $userId
)";
$events = $mysql->query($queryGetEvents)->fetch_all(MYSQLI_ASSOC);
$isEventsEmpty = count($events) == 0;

$queryGetTransactions = "
  SELECT events.title, events.description, transactions.transaction_id FROM `events`
  INNER JOIN transactions ON events.event_id = transactions.event_id
  WHERE transactions.user_id = $userId
";
$joinedEvents = $mysql->query($queryGetTransactions)->fetch_all(MYSQLI_ASSOC);

$mysql->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include('./layouts/head.php') ?>
  <title>Dashboard - Eventni</title>
  <style>
  .card .card-title {
    font-size: 20px;
    font-weight: bold;
  }

  .form-heading {
    margin-bottom: 2rem;
  }

  .btn-logout {
    color: #ee6e73;
  }

  @media only screen and (min-width: 992px) {
    nav .nav-wrapper {
      padding: 0 80px;
    }
  }

  nav .sidenav-trigger {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  </style>
</head>
<body>
  <?php include('./layouts/ribbon.php') ?>
  <nav>
    <div class="nav-wrapper">
      <a href="/" class="brand-logo">EventNi</a>
      <a href="#" data-target="mobile-demo" class="sidenav-trigger">
        <span class="material-icons">menu</span>
      </a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li>
          <form action="logout.php" method="POST">
            <button class="btn white waves-effect waves-light btn-logout">
              Logout
            </button>
          </form>
        </li>
      </ul>
    </div>
  </nav>
  <ul class="sidenav" id="mobile-demo">
    <li>
      <button class="btn waves-effect waves-light red">Logout<i class="material-icons right">logout</i></button>
    </li>
  </ul>
  <main class="container">
    <div class="row">
      <form class="col s12 l4 xl3" method="POST">
        <input type="hidden" name="action" value="INSERT_TRANSACTION">
        <h5 class="form-heading">Pendaftaran Event</h5>
        <div class="input-field">
          <select name="event_id" id="" <?= $isEventsEmpty ? 'disabled' : '' ?>>
            <option disabled selected><?= $isEventsEmpty ? "Event Kosong" : "Pilih Event" ?></option>
            <?php foreach ($events as $event) { ?>
            <option value="<?= $event['event_id'] ?>"><?= $event['title'] ?></option>
            <?php } ?>
          </select>
          <label for="">Pilih Event</label>
        </div>
        <div class="input-field">
          <button 
            class="btn waves-effect waves-light" <?= $isEventsEmpty ? 'disabled' : '' ?>
          >
            Daftar Event<i class="material-icons right">done</i>
          </button>
        </div>
      </form>
      <div class="col s12 l8 xl9">
        <div class="row">
          <div class="col s12">
            <h5><?= count($joinedEvents) == 0 ? "Tidak ada event yang kamu ikuti" : "Event yang kamu ikuti" ?></h5>
          </div>
          <?php foreach($joinedEvents as $event) { ?>
          <div class="col s12 l6 xl4">
            <div class="card">
              <div class="card-content">
                <div class="card-title"><?= $event['title'] ?></div>
                <p><?= $event['description'] ?></p>
              </div>
              <div class="card-action">
                <form action="/" method="POST">
                  <input type="hidden" name="transaction_id" value="<?= $event['transaction_id'] ?>">
                  <input type="hidden" name="action" value="DELETE_TRANSACTION">
                  <button class="btn waves-effect waves-light red">
                    <i class="material-icons">delete</i>
                  </button>
                </form>
              </div>
            </div>
          </div>
          <?php }; ?>
        </div>
      </div>
    </div>
  </main>
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const select = document.querySelectorAll('select')
    M.FormSelect.init(select, {})

    const sidebar = document.querySelectorAll('.sidenav');
    M.Sidenav.init(sidebar, {});
  })
  </script>
</body>
</html>