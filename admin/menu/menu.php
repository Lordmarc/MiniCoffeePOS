<?php

session_start();
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../classes/ProductStorage.php';

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
    <a href="../dashboard.php" class="tab flex items-center gap-2 text-lg  p-2 rounded hover:bg-[#EFE9E3] hover:text-[#A08963 font-semibold">
      <i class="fa-solid fa-chart-column"></i>
      <p class="">Dashboard</p>
      </a>
      <a href="#" class="tab flex items-center gap-2 text-lg p-2 rounded bg-[#EFE9E3] text-[#A08963]">
      <i class="fa-solid fa-bars-progress"></i>
      <p>Menu Management</p>
    </a>

    <a href="../orderhistory.php" class=" tab flex items-center gap-2 text-lg p-2 rounded hover:bg-[#EFE9E3] hover:text-[#A08963]" >
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
          <h3 class="text-3xl font-bold">Menu Management</h3>
          <p class="text-slate-500">Manage your coffee and pastry </p>
        </div>
        <button class="add-product self-end bg-[#7B542F] p-2 rounded text-white font-semibold">
        <i class="fa-solid fa-plus"></i>
        Add New Product
        </button>
      </div>

      <div class="flex items-center gap-10 w-full">
          <div class="flex p-2 gap-2 rounded bg-white shadow items-center w-96">
            <i class="fa-solid fa-magnifying-glass text-slate-500"></i>
            <input id="search-product" type="text" class="outline-none text-slate-500 w-full" placeholder="Search for latte,croissant, etc...">
          </div>
        <div class="flex bg-white text-center rounded p-2">

        <select id="sort" class="outline-none">
          <option value="name-asc" selected>Name A → Z</option>
          <option value="name-desc">Name Z → A</option>
          <option value="price-asc">Price Low → High</option>
          <option value="price-desc">Price High → Low</option>
          <option value="newest">Newest First</option>
          <option value="oldest">Oldest First</option>
        </select>
      </div>

      <div class="category-tab flex items-center flex-1 gap-4">
        <button id="all-items" class="bg-[#7B542F] text-white rounded-full p-2 font-semibold ">All Items</button>
        <button id="hot-coffee" class="bg-white rounded-full p-2 font-semibold text-slate-600">Hot Coffee</button>
        <button id="iced-coffee" class="bg-white rounded-full p-2 font-semibold text-slate-600">Iced Coffee</button>
        <button id="pastries" class="bg-white rounded-full p-2 font-semibold text-slate-600">Pastries</button>

      </div>

      </div>

      <div class="relative flex flex-col flex-1 bg-white rounded-md overflow-hidden">
        <table class="w-full text-sm ">
            <thead class="text-sm text-left text-body bg-gray-200">
              <tr>
                  <th scope="col" class="px-6 py-3 rounded-s-base font-medium">
                      PRODUCT
                  </th>
                  <th scope="col" class="px-6 py-3 font-medium">
                      CATEGORY
                  </th>
                  <th scope="col" class="px-6 py-3 rounded-e-base font-medium">
                      PRICE
                  </th>
                  <th scope="col" class="px-6 py-3 rounded-e-base font-medium">
                    STATUS
                  </th>
                  <th scope="col" class="px-6 py-3 rounded-e-base font-medium">
                    ACTIONS
                  </th>
              </tr>
          </thead>
          <tbody id="category-items">
          
          
        
          </tbody>
        </table>

        <div id="pagination" class="flex justify-end gap-2 p-4 border-t text-sm bg-white mt-auto">
          <p id="pagination-info" class="text-slate-500"></p>
          <div id="pagination-buttons" class="flex gap-2"></div>
        </div>
        </div>
      </div>
    </div>
    




  <script src="../../js/sidebar.js"></script>
  <script src="../../js//menu.js"></script>

</body>
</html>