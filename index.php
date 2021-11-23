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
  $action = $_POST['action'];
  
  if ($action == 'DELETE_TRANSACTION') {
    $transactionId = $_POST['transaction_id'];
    $queryDeleteTransaction = "DELETE FROM transactions WHERE transaction_id = $transactionId AND user_id = $userId";
    $mysql->query($queryDeleteTransaction);
  }

  if ($action == 'INSERT_TRANSACTION') {
    $eventId = $_POST['event_id'];
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
  <a href="https://github.com/alfianandinugraha/event-ni" target="_blank" class="github-corner" aria-label="View source on GitHub"><svg width="80" height="80" viewBox="0 0 250 250" style="fill:#151513; color:#fff; position: absolute; top: 0; border: 0; right: 0;" aria-hidden="true"><path d="M0,0 L115,115 L130,115 L142,142 L250,250 L250,0 Z"></path><path d="M128.3,109.0 C113.8,99.7 119.0,89.6 119.0,89.6 C122.0,82.7 120.5,78.6 120.5,78.6 C119.2,72.0 123.4,76.3 123.4,76.3 C127.3,80.9 125.5,87.3 125.5,87.3 C122.9,97.6 130.6,101.9 134.4,103.2" fill="currentColor" style="transform-origin: 130px 106px;" class="octo-arm"></path><path d="M115.0,115.0 C114.9,115.1 118.7,116.5 119.8,115.4 L133.7,101.6 C136.9,99.2 139.9,98.4 142.2,98.6 C133.8,88.0 127.5,74.4 143.8,58.0 C148.5,53.4 154.0,51.2 159.7,51.0 C160.3,49.4 163.2,43.6 171.4,40.1 C171.4,40.1 176.1,42.5 178.8,56.2 C183.1,58.6 187.2,61.8 190.9,65.4 C194.5,69.0 197.7,73.2 200.1,77.6 C213.8,80.2 216.3,84.9 216.3,84.9 C212.7,93.1 206.9,96.0 205.4,96.6 C205.1,102.4 203.0,107.8 198.3,112.5 C181.9,128.9 168.3,122.5 157.7,114.1 C157.9,116.9 156.7,120.9 152.7,124.9 L141.0,136.5 C139.8,137.7 141.6,141.9 141.8,141.8 Z" fill="currentColor" class="octo-body"></path></svg></a>
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