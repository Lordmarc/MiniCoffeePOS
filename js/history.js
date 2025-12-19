document.addEventListener('DOMContentLoaded', () => {
  const historyItems = document.getElementById('history-items');

  const wrapper = document.getElementById('dateRange');
  const dateText = document.getElementById('dateRangeText');
  const start = document.getElementById('startDate');
  const end = document.getElementById('endDate');

  const sort = document.getElementById('sort');
  const searchInput = document.getElementById('search-product');


  let currentSort = "newest";
  let currentSearch = "";

  let currentPage = 1;
  const itemsPerPage = 6;


renderTransactions({
  sort: currentSort,
  search: currentSearch
});


  wrapper.addEventListener('click', () => {
    start.showPicker ? start.showPicker() : start.focus();
  });

  start.addEventListener('change', () => {
    end.showPicker ? end.showPicker() : end.focus();
  });

  end.addEventListener('change', () => {
    if (!start.value || !end.value) return;

    const s = new Date(start.value).toLocaleDateString('en-US', {
      month: 'short',
      day: '2-digit',
      year: 'numeric'
    });

    const e = new Date(end.value).toLocaleDateString('en-US', {
      month: 'short',
      day: '2-digit',
      year: 'numeric'
    });

    dateText.textContent = `${s} - ${e}`;

    // filterByDate(start.value, end.value);

   renderTransactions({
    startDate: start.value,
    endDate: end.value,
    sort: currentSort,
    search: currentSearch
  });
  });

  function renderTransactions({
    startDate = null,
    endDate = null,
    sort = "newest",
    search = "",  } = {}) {
    historyItems.innerHTML = "";
    fetch('../api/transaction.php')
      .then(res => res.json())
      .then(data => {
        

        if(search.trim() !== "")
        {
          const searchToLower = search.toLowerCase();
          data = data.filter(item => 
            item.items.some(i => i.name.toLowerCase().includes(searchToLower))
          );
        }
        switch(sort)
        {
          case 'newest':
            data.sort((a, b) => new Date(b.date) - new Date(a.date));
            break;
          case 'oldest':
            data.sort((a, b) => new Date(a.date) - new Date(b.date));
            break;
        }
      

        // optional date range filter
        if (startDate && endDate) {
          const s = new Date(startDate);
          const e = new Date(endDate);
          e.setHours(23, 59, 59, 999);

          data = data.filter(t => {
            const d = new Date(t.date);
            return d >= s && d <= e;
          });
        }

        console.log(data);
         const paginationInfo = document.getElementById('pagination-info');
          const paginationButtons = document.getElementById('pagination-buttons');
      

          const totalItems = data.length;
          const totalPages = Math.ceil(totalItems/itemsPerPage);

          if(currentPage > totalPages) currentPage = 1;

          const startItem = (currentPage - 1 ) * itemsPerPage;
          const endItem = startItem + itemsPerPage;

          paginatedData = data.slice(startItem, endItem);

          if(paginationInfo)
          {
            paginationInfo.textContent = `Showing ${startItem + 1}- ${Math.min(endItem, totalItems)} of ${totalItems}`
          }
          if(paginationButtons)
          {
            paginationButtons.innerHTML = "";

            for(let i = 1; i <= totalPages; i++)
            {
              const btn = document.createElement('button');
              btn.textContent = i;
              btn.classList.add("px-4", "py-3");
               btn.className = `p-2
     
      ${
        i === currentPage
          ? "bg-[#7B542F] text-white"
          : "bg-white hover:bg-gray-100"
      }
    `;
          
            btn.addEventListener('click', () => {
              currentPage = i;
              renderTransactions({startDate: start.value || null, endDate: end.value || null, sort: currentSort,search: currentSearch});
            });
            paginationButtons.appendChild(btn);
          }
  }
          
        paginatedData.forEach(item => {
          const tr = document.createElement('tr');
       
   
          const formattedDate = new Date(item.date).toLocaleDateString('en-US', {
            month: 'short',
            day: '2-digit',
            year: 'numeric',
          });

          const formattedTime = new Date(item.date).toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
          });

         
        
          tr.innerHTML = `
          <td class="px-4 py-3">#${String(item.id).padStart(4, "0")}</td>
          <td class="flex flex-col px-4 py-3">
            <h3>${formattedDate}</h3>
            <p>${formattedTime}</p>
          </td>
          <td class="max-w-sm px-4 py-3">${item.items.map(t => t.name).join(', ')}</td>
          <td>${item.total}</td>
          `;

          historyItems.appendChild(tr);
        })
      });
  }
    sort.addEventListener('change', () => {
    currentSort = sort.value;
    renderTransactions({startDate: start.value || null, endDate: end.value || null, sort: currentSort,search: currentSearch});
  });

  searchInput.addEventListener('input', () => {
    currentSearch = searchInput.value;
    renderTransactions({startDate: start.value || null, endDate: end.value || null, sort: currentSort,search: currentSearch});
  });

});
