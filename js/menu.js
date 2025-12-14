document.addEventListener('DOMContentLoaded', () => {
  const tabs = document.querySelectorAll('.category-tab button');
  const tableBody = document.getElementById('category-items');

  const allItemsTab = document.getElementById('all-items');


renderProducts({ category: 'all-items', sort: 'name-asc' });
  tabs.forEach(t => {
    t.classList.remove("bg-[#7B542F]", "text-white");
  })
  allItemsTab.classList.add("bg-[#7B542F]", "text-white");

  tabs.forEach(tab => {
    tab.addEventListener('click', ()=> {
      renderProducts({ category: tab.id, sort: sortSelect.value });
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

function renderProducts({category = 'all-items', sort = 'name-asc', search = ''} = {}) {
  tableBody.innerHTML = ""; 

  fetch('../../api/product_item.php')
    .then(res => res.json())
    .then(data => {
      console.log(data)

         if (category !== 'all-items') {
          data = data.filter(item => item.category.toLowerCase().replace(/\s+/g, '-') === category);
        }

        if(search.trim() !== '')
        {
          const searchLower = search.toLowerCase();
          data = data.filter(item => item.name.toLowerCase().includes(searchLower));
        }

         switch (sort) {
          case 'name-asc':
            data.sort((a, b) => a.name.localeCompare(b.name));
            break;
          case 'name-desc':
            data.sort((a, b) => b.name.localeCompare(a.name));
            break;
          case 'price-asc':
            data.sort((a, b) => a.price - b.price);
            break;
          case 'price-desc':
            data.sort((a, b) => b.price - a.price);
            break;
          }

      data.forEach(item => {
        const tableRow = document.createElement('tr');
        tableRow.classList.add("border-b", "border-slate-300");

        tableRow.innerHTML = `
          <td class="flex items-center gap-2 p-4">
            <img src="../../${item.img}" alt="" class="w-18 h-18 rounded-md">
            <p>${item.name}</p>
          </td>
          <td><p>${item.category}</p></td>
          <td><p>₱ ${item.price.toFixed(2)}</p></td>
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
              <button type="submit" class="delete-btn cursor-pointer">
                <i class="fa-solid fa-trash text-red-500"></i>
              </button>
            </div>
          </td>
        `;
        tableBody.appendChild(tableRow);

        const deleteBtn = tableRow.querySelector('.delete-btn');
        deleteBtn.addEventListener('click', (event) => deleteProduct(event, item.id));
      });
    });
}

function deleteProduct(event, id)
{
  event.preventDefault();

  const activeTab = document.querySelector('.category-tab button.bg-[#7B542F]');
  const category = activeTab ? activeTab.id : 'all-items';
  const sort = sortSelect.value;

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
    renderProducts({ category, sort });
  });
}



const sortSelect = document.getElementById('sort');
sortSelect.addEventListener('change', () => {
const activeTab = Array.from(document.querySelectorAll('.category-tab button'))
  .find(tab => tab.classList.contains('bg-[#7B542F]'));
  const category = activeTab ? activeTab.id : 'all-items';
  renderProducts({ category, sort: sortSelect.value });
})

const searchInput = document.getElementById('search-product');

searchInput.addEventListener('input', () => {
  const activeTab = Array.from(document.querySelectorAll('.category-tab button'))
  .find(tab => tab.classList.contains('bg-[#7B542F]'));
  const category = activeTab ? activeTab.id : 'all-items';
  const sort = sortSelect.value;
  const search = searchInput.value;
  renderProducts({ category, sort,  search });
})

})
