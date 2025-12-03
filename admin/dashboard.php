<?php
session_start();
require_once __DIR__ . '/../classes/Auth.php';
if(!isset($_SESSION['user_id']))
{
  header('Location: login.php');
  exit();
}

$email = "";
if(isset($_SESSION['user_email']))
{
  $email = $_SESSION['user_email'];
}

$auth = new Auth();

if ($_SERVER['REQUEST_METHOD'] === "POST")
{
  $auth->logout();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee POS</title>
</head>
<body>
  <h2>Welcome,<?=  $email; ?></h2>
<form action="dashboard.php" method="POST">
  <button type="submit">Logout</button>
</form>
</body>
</html>