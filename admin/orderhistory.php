<?php

session_start();
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/ProductStorage.php';

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
  <title>CoffeePOS</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

  <body class="min-h-screen flex justify-center items-center w-full">
  <div class="flex h-screen w-full h-full">
   <div class="sidebar flex flex-col gap-4 h-full w-64 bg-white p-4">
    <div class="flex items-center gap-2">
  <i class="fa-solid fa-mug-hot text-[#7B542F] text-2xl"></i>  
  <h2 class="text-2xl font-semibold">Coffee</h2>

</div>

<nav class="p-2">
   <ul id="links" class="flex flex-col gap-3">

  <a href="/coffeePOS/admin/dashboard.php"
     class="tab flex items-center gap-2 text-lg p-2 rounded hover:bg-[#EFE9E3] hover:text-[#A08963]">
    <i class="fa-solid fa-chart-column"></i>
    <p>Dashboard</p>
  </a>

  <a href="/coffeePOS/admin/menu.php"
     class="tab flex items-center gap-2 text-lg p-2 rounded hover:bg-[#EFE9E3] hover:text-[#A08963]">
    <i class="fa-solid fa-bars-progress"></i>
    <p>Menu Management</p>
  </a>

  <a href="/coffeePOS/admin/orderhistory.php"
     class="tab flex items-center gap-2 text-lg p-2 rounded bg-[#EFE9E3] text-[#A08963]">
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

  <div class="dashboard h-full w-full flex flex-col flex-1 bg-gray-100 p-8 gap-2 ">

        <div class="w-full flex justify-between">
        <div>
          <h3 class="text-3xl font-bold">Order History</h3>
          <p class="text-slate-500">View and manage past transactions </p>
        </div>
        <button class="export-file self-end bg-[#7B542F] p-2 rounded text-white font-semibold">
        <i class="fa-solid fa-download"></i>
        Export Report
        </button>
      </div>

      <div class="flex items-center gap-10 w-full">

          <div>
            <p>Search Orders</p>
             <div class="flex p-2 gap-2 rounded bg-white shadow items-center w-96">
            <i class="fa-solid fa-magnifying-glass text-slate-500"></i>
            <input id="search-product" type="text" class="outline-none text-slate-500 w-full" placeholder="Search for latte,croissant, etc...">
          </div>

          </div>
         

        <div class="flex flex-col">
          <label class="block text-sm font-medium mb-1">Date Range</label>

          <div
            id="dateRange"
            class="flex items-center gap-2 w-72 rounded-lg border
                  px-3 py-2 text-sm cursor-pointer
                  hover:border-gray-400 transition"
          >
            <i class="fa-regular fa-calendar text-gray-500"></i>
            <span id="dateRangeText" class="text-gray-700">
              Select date range
            </span>
          </div>

          <!-- hidden real inputs -->
          <input type="date" id="startDate" class="hidden">
          <input type="date" id="endDate" class="hidden">

       
      </div>
        <div class="flex flex-col">
        <p class="">Sort By</p>
    
        <select id="sort" name="sort" class="outline-none flex bg-white text-center rounded p-2">
          <option value="newest" selected>Newest First</option>
          <option value="oldest">Oldest First</option>
        </select>
      </div>

 

      </div>

      <div class="relative flex flex-col flex-1 bg-white rounded-md overflow-hidden">
        <table class="w-full text-sm ">
            <thead class="text-sm text-left text-body bg-gray-200">
              <tr>
                  <th scope="col" class="px-6 py-3 rounded-s-base font-medium">
                      ORDER ID
                  </th>
                  <th scope="col" class="px-6 py-3 font-medium">
                      DATE & TIME
                  </th>
                  <th scope="col" class="px-6 py-3 min-w-sm rounded-e-base font-medium">
                      ITEMS SUMMARY
                  </th>
                  <th scope="col" class="px-6 py-3 rounded-e-base font-medium">
                    TOTAL AMOUNT
                  </th>
                  
              </tr>
          </thead>
          <tbody id="history-items">
          
          
        
          </tbody>
        </table>

        <div id="pagination" class="flex items-center justify-end gap-2 p-4 border-t text-sm bg-white mt-auto">
          <p id="pagination-info" class="text-slate-500"></p>
          <div id="pagination-buttons" class="flex gap-2"></div>
        </div>
  <div id="pdfPreview" class="hidden border p-4 bg-white max-w-4xl mx-auto my-6">
  <h2 style="color: #7B542F; text-align:center; font-size: 20px; font-weight: 700;">
    CoffeePOS Order History
  </h2>
  <p id="previewDateRange" style="text-align:center; margin-bottom: 1em; font-size: 14px; color: #333;">
    Date Range: All Dates
  </p>
  
  <table style="width:100%; border-collapse: collapse; font-size: 12px;">
    <thead style="background-color: #7B542F; color:white;">
      <tr>
        <th style="padding:8px; border: 1px solid #ddd;">Order ID</th>
        <th style="padding:8px; border: 1px solid #ddd; text-align:left;">Date & Time</th>
        <th style="padding:8px; border: 1px solid #ddd; text-align:left;">Items</th>
        <th style="padding:8px; border: 1px solid #ddd; text-align:right;">Total Amount</th>
      </tr>
    </thead>
    <tbody id="previewBody">
      <!-- Rows will be generated here -->
    </tbody>
  </table>
</div>

        </div>
      </div>
    </div>
    




  <script src="../js/sidebar.js"></script>
  <script src="../js//history.js"></script>


</body>
</html>