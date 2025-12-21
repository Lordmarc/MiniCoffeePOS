<?php
  session_start();
  require_once 'classes/ProductStorage.php';
  require_once 'classes/Auth.php';


  if(!isset($_SESSION['user_id']))
  {
    header("Location:login.php");
    exit();
  
  }

  if ($_SESSION['role'] !== 'staff')
  {
    header("Location: admin/dashboard.php");
    exit();
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="min-h-screen w-full bg-gray-200 justify-center items-center">
  <div class=" h-screen w-full flex flex-col">
    <nav class="bg-white h-16 w-full shadow-md relative z-10 flex justify-between items-center p-4">
      <div class="flex items-center">
        <h2 class="text-2xl font-semibold">Coffee</h2>
        <i class="fa-solid fa-mug-hot text-2xl"></i>  
      </div>
      <form action="logout.php" method="POST">
        <button class="bg-[#C9B59C] hover:bg-[#D9CFC7] p-2 rounded cursor-pointer" type="submit">Logout</button>
      </form>
    </nav>
    <div class="h-full w-full  flex flex-wrap">
      <div class="menu flex-1 grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 auto-rows-[200px] md:auto-rows-[250px] w-full items-center gap-5 bg-[#DCC5B2] p-4 overflow-y-auto h-full" style="max-height: calc(100vh - 4rem);">
        <?php
          $file = 'data/products.json';
          $json = file_get_contents($file);

          $products = json_decode($json, true);

          foreach($products as $product)
          {
            if($product['isActive'] !== false){
            echo '<div class="menu-item cursor-pointer  h-full flex flex-col rounded-md overflow-hidden bg-white hover:scale-105 transition-transform duration-300 shadow-md" data-id="'. $product['id'].'" data-img="'. $product['img'] .'" data-name="'. $product['name'] .'" data-price="'. $product['price'] .'">
             <div class="min-h-32 md:min-h-44">
              <img class="h-full w-full" src="' . $product['img'] . '" alt="">
             </div>
             
             <div class="h-32 flex flex-col justify-center items-center text-center p-2">
              <h3 class="text-xs md:text-[15px]">' .$product['name'] .'</h3>
              <p class="text-xs md:text-lg text-slate-700 font-semibold">' . '₱ ' .number_format($product['price'], 2). '' .'</p>
             </div> 
            </div>';
            }
          }
           
        ?>
      
      </div>
      
      <div class="flex flex-col  md:w-62 lg:w-96 bg-[#FAF7F3] h-full">

        <!-- order list -->
         <h3>Order Summary</h3>
         <!-- on this part -->
          <div class="order flex flex-col gap-1 p-2 flex-1 overflow-y-auto 
            lg:max-h-[200px] xl:max-h-[280px] ">
  <!-- Order items -->
</div>


          
          <div class="mt-auto  flex flex-col gap-2 bg-white h-auto p-2">
          <div class="total-container  flex flex-col">
            <div class="flex w-full justify-between items-center">
              <h3>Subtotal:</h3>
              <p class="subtotal-amount"></p>  
            </div>
            <div class="flex w-full justify-between items-center">
              <h3>Discount:</h3>
              <p class="discount"></p>
            </div>
            <div class="flex w-full justify-between items-center">
              <h3>Total:</h3>
              <p class="total-amount"></p>
            </div>
            <div class="flex w-full justify-between items-center">
              <h3>Cash:</h3>
              <div class="ml-auto flex rounded border border-gray-300 px-2 py-1">
              <span>₱</span>
              <input class="cash-amount text-end w-22 outline-none" type="text">
              </div>
              
           
              
            </div>
            <div class="flex w-full justify-between items-center">
              <h3>Change:</h3>
              <p class="change-amount"></p>
            </div>
            <div class="numbers-btn grid grid-cols-4 gap-2 w-full justify-between items-center">
              <button>1</button>
              <button>2</button>
              <button>3</button>
              <button>DEL</button>
              <button>4</button>
              <button>5</button>
              <button>6</button>
              <button>CLR</button>
              <button>7</button>
              <button>8</button>
              <button>9</button>
              <button>VOID</button>
              <button>0</button>
              <button>.</button>
              <button>00</button>
              <button>=</button>
            </div>
            <div class="discount-btn grid grid-cols-3 auto-rows-[30px] gap-2 w-full p-2">
              <button id="student" class=" bg-blue-400 rounded text-white cursor-pointer">Student</button>
              <button id="pwd" class=" bg-blue-500 rounded text-white cursor-pointer">PWD</button>
              <button id="senior" class=" bg-blue-600 rounded text-white cursor-pointer">Senior</button>
            </div>

          </div>
           
          <button id="checkout-btn" class="bg-[#DCC5B2] w-full p-2 rounded hover:bg-[#c4af9d] hover:text-white transition-transform duration-500 cursor-pointer">Place Order</button>

          </div>
          
      </div>

    </div>
      
  </div>

<script src="js/order.js"></script>
</body>
</html>