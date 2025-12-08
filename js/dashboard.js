document.addEventListener("DOMContentLoaded", () => {
  const tabs = document.querySelectorAll('#links .tab');

  const revenue = document.getElementById('revenue');
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
    
    const labels = data.map(item => item.date);
    const sales = data.map(item => item.total_sales);

    const ctx = document.getElementById('sales-chart').getContext('2d');

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

  })
  }

  function loadRevenue()
  {
    console.log("Loading data");

    fetch('../api/todays_revenue.php')
    .then(res => res.json())
    .then(data => {
      console.log("Data Received:", data);

      revenue.innerText = data.total_revenue.toFixed(2);
    })
  }


  loadSales();
  loadRevenue();
  setInterval(() => {
    
  console.log("Interval triggered:", new Date().toLocaleTimeString());
    loadSales();
    loadRevenue();
  }, 5000)
});