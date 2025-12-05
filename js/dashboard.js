document.addEventListener("DOMContentLoaded", () => {
  const tabs = document.querySelectorAll('#links .tab');

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

  fetch('../sales_chart_data.php')
  .then(res => res.json())
  .then(data => {
    console.log("Fetched data:", data);
    
    const label = data.map(item => item.date);
    const sales = data.map(item => item.total_sales);

    const ctx = document.getElementById('sales-chart').getContext('2d');

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: label,
        datasets: [{
          label: 'Total Sales Per Day',
          data: sales,
          borderWidth: 1
        }]
      }
    })
  })
});