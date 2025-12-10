document.addEventListener("DOMContentLoaded", () => {
  const tabs = document.querySelectorAll('#links .tab');

  const revenue = document.getElementById('revenue');
  const totalOrder = document.getElementById('total_order');
  const topItems = document.getElementById('top-items');
  let chart;

  tabs.forEach(tab => {
    tab.addEventListener('click', ()=>{
      
      tabs.forEach(t => 
        t.classList.remove("bg-[#EFE9E3]",
                           "text-[#A08963]",
                           "font-semibold",
                           "hover:bg-[#EFE9E3]",
                           "hover:text-[#A08963]")
   
      );
      tab.classList.add("bg-[#EFE9E3]",
                           "text-[#A08963]",
                           "font-semibold",
                           "hover:bg-[#EFE9E3]",
                           "hover:text-[#A08963]");
    });
  });

  function loadSales()
  {
  console.log("LOAD DATA CALLED: " + new Date().toLocaleTimeString());
  fetch('../api/sales_chart_data.php')
  .then(res => res.json())
  .then(data => {
    console.log("Fetched data:", data);
    
    const labels = data.sales.map(item => item.date);
    const sales = data.sales.map(item => item.total_sales);
    const ctx = document.getElementById('sales-chart').getContext('2d');
    console.log("order",totalOrder)
    if(!chart)
    {
        chart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Total Sales Per Day',
            data: sales,
            borderWidth: 1
          }]
        }
      })
    }else {
      chart.data.labels = labels;
      chart.data.datasets[0].data = sales;
      chart.update();
    }

    totalOrder.innerText = data.count_order;
  })
  }

  function loadItemSold()
  {
    const itemSold = document.getElementById('item-sold');
    console.log("Item SOld")

    fetch('../api/todays_item_sold.php')
    .then(res => res.json())
    .then(data => {
      console.log('Ito ng ang item sold: ', data)  

      itemSold.innerText = data;
    })
  }

  function loadRevenue()
  {
    console.log("Loading data");

    fetch('../api/todays_revenue.php')
    .then(res => res.json())
    .then(data => {
      console.log("Data Received:", data);

      revenue.innerHTML = `
      <p><span>₱</span> ${data.total_revenue.toFixed(2)}</p>
      `;
    })
  }

  function loadTop3Items()
  {
    console.log('Top 3 Items')
    let count = 0;
    topItems.innerHTML = "";
    fetch('../api/top_three_items.php')
    .then(res => res.json())
    .then(data => {
      
      if(data.length === 0)
      {
        console.log("Wala pang best sellers");
        const noData = document.createElement('div');
        noData.classList.add("h-full","flex", "justify-center", "items-center", "mb-8");
        noData.innerHTML =`
        <p class="text-md  font-bold">No best sellers yet.</p>
        `;
        
        topItems.appendChild(noData);
      }else {
        data.forEach(item => {
        const div = document.createElement('div');
        div.classList.add("flex", "gap-2")
        div.innerHTML = `
          <p class="text-md  font-bold"><span>${count += 1}.</span> ${item.name}</p>
          <span>-</span>
          <p class="text-md  font-bold">${item.total_sold}x</p>
        `;
        topItems.appendChild(div);
      })
      }


      
  
    })
  }

  function initData()
  {
      loadTop3Items();
      loadSales();
      loadRevenue();
      loadItemSold();
  }

  initData();


  setInterval(() => {
    
  console.log("Interval triggered:", new Date().toLocaleTimeString());
 initData();
  }, 5000)
});