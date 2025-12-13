<?php


require_once __DIR__ . '/../../classes/Product.php'; //
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../classes/ProductStorage.php';

session_start();

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

$itemProduct = $_SESSION['edit_product'];

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
    <a href="dashboard.php" class="tab flex items-center gap-2 text-lg  p-2 rounded hover:bg-[#EFE9E3] hover:text-[#A08963 font-semibold">
      <i class="fa-solid fa-chart-column"></i>
      <p class="">Dashboard</p>
      </a>
      <a href="menu.php" class="tab flex items-center gap-2 text-lg p-2 rounded bg-[#EFE9E3] text-[#A08963]">
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

  <div class="dashboard h-full w-full flex flex-col flex-1 bg-gray-100 gap-2 ">

        <div class="w-full flex justify-between border-b  border-b-[#D0BB95] p-8">
        <div>
          <span class="text-[#A08963]">Menu Management</span> <span class="text-md text-[#A08963]">></span> <span class="text-black font-semibold">Edit Product</span>
          <h3 class="text-3xl font-bold">Edit Product</h3>
          <p class="text-slate-500">Update the details for <?php echo  htmlspecialchars($itemProduct->getName()); ?></p>
        </div>
        <div class="self-end flex gap-4">
          <button class="rounded-md border border-[#D0BB95] p-2">
            Cancel
          </button>
          <button class=" bg-[#7B542F] p-2 rounded text-white font-semibold">
           
            Save Changes
          </button>
        </div>
 
      </div>

      <div class="flex items-center gap-10 w-full p-8 ">
        <div class="bg-white rounded shadow p-4 border border-[#D0BB95] rounded-lg h-full flex-1">
          <form action="">
            <div class="flex items-center gap-2">
              <i class="fa-solid fa-pen-to-square text-[#ee9d2b]"></i>
              <h3 class="font-semibold">Product Details</h3>
            </div>
            <div class="product-data grid grid-cols-2 gap-4">
              <div class="flex flex-col">
                <label for="product_name">Product Name</label>
                <input class="outline-none border border-[#D0BB95] rounded-md p-2 text-slate-600" type="text" name="product_name" value="<?=  htmlspecialchars($itemProduct->getName()); ?>">
              </div>
              <div class="flex flex-col">
                <label for="product_category">Category</label>
                <input  class="outline-none border border-[#D0BB95] rounded-md p-2 text-slate-600" type="text" name="product_category" value="<?= htmlspecialchars($itemProduct->getCategory()); ?>">
              </div>
              <div class="flex flex-col">
                <label for="product_price">Price (₱)</label>
                <input  class="outline-none border border-[#D0BB95] rounded-md p-2 text-slate-600"  type="text" name="product_price" value="<?= htmlspecialchars(number_format($itemProduct->getPrice(),2)); ?>">
              </div>
              <div class="flex flex-col">
                <label for="product_stock">Stock</label>
                <input  class="outline-none border border-[#D0BB95] rounded-md p-2 text-slate-600"  type="text" name="product_stock" value="<?= htmlspecialchars($itemProduct->getStock()); ?>">
              </div>
              <div class="col-span-2  border border-[#D0BB95] rounded-md p-2">
                <textarea class="w-full h-34 outline-none text-slate-600" name="product_description" id="product_description" maxlength="200"><?= htmlspecialchars($itemProduct->getDescription()); ?></textarea>
              
              </div>
            </div>
              <small class=" text-end block text-sm text-[#D0BB95]" id="count-letters"> 0 / 200 characters</small>
           
          </form>
        </div>

        <div class="h-full flex flex-col gap-4 bg-white rounded shadow p-8 border border-[#D0BB95] rounded-lg w-96">
          <div class="flex items-center gap-2">
            <i class="fa-solid fa-image text-[#ee9d2b]"></i>
            <h3 class="font-semibold">Product Image</h3>

          
          </div>

            <div class="img-container h-62 w-full rounded-lg overflow-hidden">
              <label for="product_image">
                <img id="previewImage" src="../../<?= $itemProduct->getImg(); ?>" class="h-full w-full" alt="Product Image">
              </label>
              
            </div>

            <input
              type="file"
              id="product_image"
              name="product_image"
              class="hidden"
              accept="image/jpeg,image/png,image/webp"
            />

            <small class="text-sm text-[#D0BB95]">
              Click image to change (JPEG, PNG, WEBP – max 3MB)
            </small>
           
        </div>
      </div>
    </div>
    




  <script src="../../js/sidebar.js"></script>
  <script src="../../js/menu.js"></script>
</body>
</html>