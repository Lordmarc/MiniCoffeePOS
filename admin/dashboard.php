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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body class="min-h-screen flex justify-center items-center w-full">
  <div class="h-screen flex h-full w-full">
  <div class="sidebar flex flex-col gap-4 h-full w-64 bg-white p-4">
    <div class="flex items-center gap-2">
  <i class="fa-solid fa-mug-hot text-[#7B542F] text-2xl"></i>  
  <h2 class="text-2xl font-semibold">Coffee</h2>

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
<form action="../logout.php" method="POST" class="mt-auto flex items-center gap-2 hover:bg-[#EFE9E3] hover:text-[#A08963] text-lg p-2 cursor-pointer">
    <i class="fa-solid fa-arrow-right-from-bracket rotate-180"></i>

    <button type="submit" class="w-full text-left cursor-pointer">Logout</button>
</form>
  </div>
  <div class="dashboard h-full flex-1 bg-gray-100 p-8">
    <div class="mb-4">
      <h2 class="text-3xl font-semibold">Admin Dashboard</h2>
      <p>Welcome back, Admin!</p>
    </div>
    <div class="flex flex-col gap-4 h-[calc(100vh-7rem)] flex w-full">

      <div class="grid grid-cols-4 gap-4">
        <div class=" flex-1 max-w-sm  flex flex-col justify-center bg-white rounded-md shadow p-6 h-32">
          <h3 class="text-xl text-slate-500">Today's Revenue</h3>
       
          <div id="revenue" class="text-4xl font-bold"></div>
        </div>
        <div class=" flex-1 max-w-sm  flex flex-col justify-center bg-white rounded-md shadow p-6 h-32">
          <h3 class="text-xl text-slate-500">Today's Item Sold</h3>

            <div id="item-sold" class="text-4xl font-bold">0</div>

        </div>
        <div class=" flex-1 max-w-sm  flex flex-col justify-center bg-white rounded-md shadow p-6 h-32">
          <h3 class="text-xl text-slate-500">Today's Order</h3>
          <div id="total_order" class="text-4xl font-bold">0</div>
        </div>
           <div class=" flex-1 max-w-sm  flex flex-col  bg-white rounded-md shadow p-2 h-32">
          <h3 class="text-xl text-slate-500">Best Seller's Today</h3>
          <div id="top-items" class="pl-2">
              
          </div>
        
        </div>
      </div>
      <div class="flex gap-4 h-[650px]">
        <div class="flex-1 bg-white h-full rounded shadow">
               <canvas id="sales-chart"></canvas>
        </div>
        <div class="popular-items w-[350px] p-4 h-full max-h-[650px] bg-white rounded shadow flex flex-col gap-2">
          <h3 class="w-full text-center text-xl">Weekly Best Seller</h3>
          <div class="weekly-items w-full h-full flex flex-1 gap-2  flex-col relative">
          
         
          </div>
        </div>
      </div>
    </div>
      

 

  </div>
  </div>

  <script src="../js/sidebar.js" defer></script>
</body>
</html>

