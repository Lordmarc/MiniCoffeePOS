document.addEventListener('DOMContentLoaded', () => {
  const tabs = document.querySelectorAll('.category-tab button');
  const tableBody = document.getElementById('category-items');

  const productDesc = document.getElementById('product_description');
  const letterCount = document.getElementById('count-letters');
  

  tabs.forEach(tab => {
    tab.addEventListener('click', ()=> {
      loadProducts(tab.id)
      tabs.forEach(t => {
        t.classList.remove(
          "bg-[#7B542F]",
          "text-white",
          "bg-white"
        );
      })
      tab.classList.add(
        "bg-[#7B542F]",
        "text-white"
      );
    })
  })

function loadProducts(buttonType = 'all-items') {
  tableBody.innerHTML = ""; 

  fetch('../../api/product_item.php')
    .then(res => res.json())
    .then(data => {
      console.log(data)
      data.forEach(item => {
        const itemCategory = item.category.toLowerCase().replace(/\s+/g, '-');
  
        if (buttonType === 'all-items' || buttonType === itemCategory) {

          const tableRow = document.createElement('tr');
          tableRow.classList.add("border-b", "border-slate-300");

          tableRow.innerHTML = `
            <td class="flex items-center gap-2 p-4">
              <img src="../../${item.img}" alt="" class="w-18 h-18 rounded-md">
              <p>${item.name}</p>
            </td>
            <td>
              <p>${item.category}</p>
            </td>
            <td>
              <p>₱ ${item.price.toFixed(2)}</p>
            </td>
            <td class="px-6 py-3 align-middle">
              <div class="flex items-center gap-2">
                ${item.status === 'In Stock'
                  ? '<div class="h-2 w-2 rounded-full bg-green-500"></div>'
                  : '<div class="h-2 w-2 rounded-full bg-red-500"></div>'}
                <p>${item.status}</p>
              </div>
            </td>
            <td>
            <div class="flex gap-2 items-center text-xl">
        
                  <form action="edit.php" method="POST">
                  <input type="hidden" name="id" value="${item.id}">
                  <input type="hidden" name="name" value="${item.name}">
                  <input type="hidden" name="price" value="${item.price}">
                  <input type="hidden" name="stock" value="${item.stock}">
                  <input type="hidden" name="category" value="${item.category}">
                  <input type="hidden" name="status" value="${item.status}">
                  <input type="hidden" name="img" value="${item.img}">
                  <input type="hidden" name="desc" value="${item.description}">

                  <button type="submit" class="cursor-pointer">
                    <i class="fa-solid fa-pen-to-square"></i>
                  </button>
                  </form>
             
                  <button type="submit" class="delete-btn cursor-pointer"><i class="fa-solid fa-trash text-red-500"></i></button>
            
            </div>
            </td>
          `;

          tableBody.appendChild(tableRow); 

          const deleteBtn = tableRow.querySelector('.delete-btn');
          deleteBtn.addEventListener('click', (event) => deleteProduct(event, item.id));
        }
      });
    });
}

function deleteProduct(event, id)
{
  event.preventDefault();

  fetch('../api/delete_product.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: 'id=' + id
  })
  .then(res => res.text())
  .then(result => {
    console.log(result);
    loadProducts();
  })
}



function updateLetterCount() {
  letterCount.textContent = `${productDesc.value.length} / 200 characters`;
}

// initial count (important kapag may existing value from PHP)
updateLetterCount();

// real-time typing
productDesc.addEventListener("input", updateLetterCount);

const imageInput = document.getElementById('product_image');
const previewImage = document.getElementById('previewImage');

imageInput.addEventListener('change', () => {
  const file = imageInput.files[0];
  if(!file) return;

  if(file.size > 3 * 1024 * 1024)
  {
    alert("Max file size is 3MB");
    imageInput.value = "";
    return;
  }

  const allowedTypes = ["image/jpeg", "image/png", "image/webp"];
  if(!allowedTypes.includes(file.type)) 
  {
    alert("Only JPEG, PNG, and WEBP are allowed");
    imageInput.value = "";
    return;
  }

  const reader = new FileReader();
  reader.onload = () => {
    previewImage.src = reader.result;
  };
  reader.readAsDataURL(file);
})


  loadProducts();
})
