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


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee POS</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body class="min-h-screen flex justify-center items-center w-full">
  <div class="h-screen flex h-full w-full">
  <div class="sidebar flex flex-col gap-4 h-full w-64 bg-[#F9F8F6] p-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-semibold">Coffee</h2>
        <i class="fa-solid fa-mug-hot text-2xl"></i>  
    </div>
    
    <nav class="p-2">
      <ul id="links" class="flex flex-col gap-3">
        <a href="dashboard.php" class="tab flex items-center gap-2 text-lg bg-[#EFE9E3] p-2 rounded text-[#A08963] font-semibold">
          <i class="fa-solid fa-chart-column"></i>
          <p class="">Dashboard</p>
          </a>
         <a href="menu.php" class="tab flex items-center gap-2 text-lg p-2 rounded hover:bg-[#EFE9E3] hover:text-[#A08963]">
          <i class="fa-solid fa-bars-progress"></i>
          <p>Menu Management</p>
        </a>
        
        <a href="orderhistory.php" class=" tab flex items-center gap-2 text-lg p-2 rounded hover:bg-[#EFE9E3] hover:text-[#A08963]" >
          <i class="fa-solid fa-scroll"></i>
          <p>Order History</p>
        </a>
         
      </ul>
    </nav>
    <form action="dashboard.php" method="POST" class="mt-auto">
      <button type="submit">Logout</button>
    </form>
  </div>
  <div class="dashboard h-full flex-1 bg-[#EFE9E3] p-8">
    <div>
      <h2>Admin Dashboard</h2>
      <p>Welcome back, Admin!</p>
    </div>
    <div class="flex flex-col gap-4 h-[calc(100vh-6rem)] flex w-full">

      <div class="flex gap-4">
        <div class="flex-1 bg-white rounded p-2 h-44">
          <h3>Revenue</h3>
          <p>Price</p>
        </div>
        <div class="flex-1 bg-white rounded p-2">
          <h3>Today's Sale</h3>
          <p>Price</p>
        </div>
        <div class="flex-1 bg-white rounded p-2">
          <h3>Today's Order</h3>
          <p>Price</p>
        </div>
      </div>
      <div class="flex gap-4 h-full">
        <div class="flex-1 bg-white rounded">
               <canvas>canvas</canvas>
        </div>
        <div class="popular-items w-[350px]  bg-white rounded">
          asda
        </div>
      </div>
    </div>
      

 

  </div>
  </div>

  <script src="../js/dashboard.js"></script>
</body>
</html>

