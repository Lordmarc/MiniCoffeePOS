document.addEventListener('DOMContentLoaded', () => {
  const tabs = document.querySelectorAll('.category-tab button');
  const tableBody = document.getElementById('category-items');

  

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

  fetch('../api/product_item.php')
    .then(res => res.json())
    .then(data => {

      data.forEach(item => {
        const itemCategory = item.category.toLowerCase().replace(/\s+/g, '-');
  
        if (buttonType === 'all-items' || buttonType === itemCategory) {

          const tableRow = document.createElement('tr');
          tableRow.classList.add("border-b", "border-slate-300");

          tableRow.innerHTML = `
            <td class="flex items-center gap-2 p-4">
              <img src="../${item.img}" alt="" class="w-18 h-18 rounded-md">
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
          `;

          tableBody.appendChild(tableRow); 
        }
      });
    });
}


  loadProducts();
})
