<?php
session_start();
require_once 'classes/Auth.php';

if($_SERVER['REQUEST_METHOD'] === "POST")
{
  
  $email    = trim($_POST['email']);
  $password = trim($_POST['password']);

  $auth = new Auth();

  $auth->loginUser($email, $password);

  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

</head>
<body class="w-full min-h-screen flex justify-center items-center">
  <div class="flex h-96 mx-auto w-2xl overflow-hidden bg-yellow-50 rounded shadow-md">
    <div class="flex-1">
      <img src="images/coffee.png" alt="" class="h-full w-full">
    </div>
    <div class="flex-1 p-4">
      <form class="flex flex-col justify-center items-center w-full h-full" action="login.php" method="POST">
        <h3>
          <?php 
          if(isset($_SESSION['error'])) {
            echo $_SESSION['error'];
            unset($_SESSION['error']);
          }
          ?>
        </h3>
        <h2 class="text-2xl mb-3">Sign In</h2>
        <div class="mb-3 w-full" >
          <label for="email">Email</label>
          <input class="block outline-none border border-slate-500 rounded p-2 w-full text-slate-500" type="email" name="email" required>
        </div>

        <div class="mb-3 w-full">
         <label for="password">Password</label>
         <input class="block outline-none border border-slate-500 rounded p-2 w-full text-slate-500" type="password" name="password" required>
        </div>
        <button class="mt-auto bg-yellow-900 text-yellow-50 w-52 py-2 rounded hover:bg-yellow-800 cursor-pointer" type="submit">Login</button>
      </form>
    </div>

  </div>
  
</body>
</html>