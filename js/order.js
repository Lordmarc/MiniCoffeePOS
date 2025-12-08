document.addEventListener("DOMContentLoaded", () => {
  const items = document.querySelectorAll(".menu-item");
  const order = document.querySelector(".order");
  const numbersBtn = document.querySelectorAll('.numbers-btn button');

  const subtotal = document.querySelector(".subtotal-amount");
  const discount = document.querySelector(".discount");
  const totalAmount = document.querySelector(".total-amount");
  const cashAmount = document.querySelector('.cash-amount');
  const changeAmount = document.querySelector('.change-amount');

  const studentBtn = document.getElementById('student');
  const pwdBtn = document.getElementById('pwd');
  const seniorBtn = document.getElementById('senior');
  
  const checkout = document.getElementById("checkout-btn");

  let total = 0;
  let discountRate = 0;
  let discountValue = 0;
  let discountAmount = 0;

  numbersBtn.forEach(btn => {
    btn.classList.add('bg-slate-300', 'rounded', 'hover:bg-slate-400', 'hover:text-white', 'cursor-pointer')
    btn.addEventListener('click', ()=> {
      const value = btn.innerText;

      if(value === 'CLR'){
        cashAmount.value = '';

      }else if (value === "DEL")
      {
        cashAmount.value = cashAmount.value.slice(0,-1);
      }else if(value === 'VOID')
      {
        order.innerHTML = "";
        total = 0;
        cashAmount.value = '';
        changeAmount.innerHTML = '';
        updateTotalUI();
      }else if( value === '.') {
        if(!cashAmount.value.includes('.')){
          cashAmount.value += '.';
        }
      }else if (value === 'Calculate') {
        calculateChange();
      } else {
        cashAmount.value += value;
      }

      console.log('clicked!');
    })
  })

  function calculateChange()
  {
    const cash = parseFloat(cashAmount.value);

    if(!isNaN(cash))
    {
      const change = cash - discountAmount;
      changeAmount.innerHTML = `₱ ${change.toFixed(2)}`;
    }
  }


  function discounted(dc)
  {
    discountRate = 0;
    switch(dc){
      case 'student':
        discountRate = 0.05;
        break;
      case 'pwd':
        discountRate = 0.1;
        break;
      case 'senior':
        discountRate = 0.15;
        break;
      default:
         discountRate = 0;
    }
  }

  studentBtn.addEventListener('click', () => {
    discounted('student');
    updateTotalUI();
    console.log('click');
  });

  pwdBtn.addEventListener('click', () => {
    discounted('pwd');
    updateTotalUI();
    console.log('click');
  })

  seniorBtn.addEventListener('click', () => {
    discounted('senior');
    updateTotalUI();
    console.log('click');
  })

  function updateTotalUI() {

    total = Math.round(total * 100) / 100;

    discountValue = Math.round((total * discountRate) * 100) / 100;
    discountAmount = Math.round((total - discountValue) * 100) / 100;



    subtotal.innerHTML = `₱ ${total.toFixed(2)}`;
    discount.innerHTML = `₱ ${discountValue.toFixed(2)}`;
    totalAmount.innerHTML = `₱ ${discountAmount.toFixed(2)}`;

  }
  


  items.forEach((item) => {
    item.addEventListener("click", () => {
      const id = item.dataset.id;
      const img = item.dataset.img;
      const name = item.dataset.name;
      const price = parseFloat(item.dataset.price);

      const existingItem = order.querySelector(`.order-item[data-id="${id}"]`);

      if (existingItem) {
        const qtyInput = existingItem.querySelector(".item-qty");

        qtyInput.value = parseInt(qtyInput.value) + 1;

        total += price;
        updateTotalUI();

        return;
      }

      const orderContainer = document.createElement("div");
      orderContainer.dataset.id = id;
      orderContainer.dataset.name = name;
      orderContainer.classList.add(
        "order-item",
        "flex",
        "items-center",
        "gap-3"
      );

      const wrapper = document.createElement("div");
      wrapper.classList.add("rounded", "overflow-hidden", "h-18", "w-18");

      // coffee image
      const imgEl = document.createElement("img");
      imgEl.src = img;
      imgEl.classList.add("h-full", "w-full");
      wrapper.appendChild(imgEl);

      const coffeeData = document.createElement("div");
      coffeeData.classList.add("flex", "flex-col");

      // coffee name
      const h3 = document.createElement("h3");
      h3.classList.add("max-w-24", "flex-wrap", "text-sm");
      h3.innerText = name;

      //coffee price
      const p = document.createElement("p");
      p.innerText = `₱ ${price.toFixed(2)}`;

      const quantity = document.createElement("div");
      quantity.classList.add("flex", "gap-1", "ml-auto");
      quantity.innerHTML = `
        <button class="add-qty bg-[#DCC5B2] p-2 h-8 w-8 flex justify-center items-center font-semibold cursor-pointer"><i class="fa-solid fa-plus"></i></button>
        <input type="text" value="1"  class="item-qty text-center bg-white p-2 h-8 w-8 flex justify-center items-center text-slate-600">
        <button class="minus-qty bg-[#DCC5B2] p-2 h-8 w-8 flex justify-center items-center font-semibold cursor-pointer"><i class="fa-solid fa-minus"></i></button>
      `;

      coffeeData.appendChild(h3);
      coffeeData.appendChild(p);

      orderContainer.appendChild(wrapper);
      orderContainer.appendChild(coffeeData);
      orderContainer.appendChild(quantity);
      order.appendChild(orderContainer);

      total += price;
      updateTotalUI();

      const addQty = orderContainer.querySelector(".add-qty");
      const minusQty = orderContainer.querySelector(".minus-qty");
      const itemQty = orderContainer.querySelector(".item-qty");

      // Adding Item Quantity

      addQty.addEventListener("click", () => {
        itemQty.value = parseInt(itemQty.value) + 1;
        total += price;
        updateTotalUI();
      });

      minusQty.addEventListener("click", () => {
        let currentValue = parseInt(itemQty.value);

        if (currentValue <= 1) {
          total -= price;
          orderContainer.remove();
          updateTotalUI();
          return;
        }

        itemQty.value = currentValue - 1;
        total -= price;
        updateTotalUI();
      });
    });
  });

  checkout.addEventListener("click", () => {
    const cartItems = [];

    const orderItems = document.querySelectorAll(".order-item");

    orderItems.forEach((item) => {
      cartItems.push({
        id: item.dataset.id,
        name: item.dataset.name,
        price: parseFloat(item.querySelector("p").innerText.replace("₱", "")),
        quantity: parseInt(item.querySelector(".item-qty").value),
      });
    });

    const payload = {
      items: cartItems,
      subtotal: total,
      discount: discountValue,
      total: discountAmount
    }

    fetch("classes/checkout.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(payload)
    })
      .then((res) => res.json())
      .then((data) => console.log(data))
      .catch((err) => console.error(err));

    order.innerHTML = "";
    total = 0;
    cashAmount.blur();
    cashAmount.value = '';
    setTimeout(() => cashAmount.value = '', 0);
    changeAmount.innerHTML = '';
    discountRate = 0;
    discountValue = 0;
    discountAmount = 0;

    updateTotalUI();
    Swal.fire({
      title: "Order Complete",
      text: "Your coffee is on its way. Enjoy your day! ",
      icon: "success",
    });
  });
});
