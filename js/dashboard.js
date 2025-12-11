document.addEventListener("DOMContentLoaded", () => {
  const tabs = document.querySelectorAll("#links .tab");

  const revenue = document.getElementById("revenue");
  const totalOrder = document.getElementById("total_order");
  const topItems = document.getElementById("top-items");
  const weeklyItems = document.querySelector(".weekly-items");

  let chart;



  function loadSales() {
    console.log("LOAD DATA CALLED: " + new Date().toLocaleTimeString());
    fetch("../api/sales_chart_data.php")
      .then((res) => res.json())
      .then((data) => {
        console.log("Fetched data:", data);

        const labels = data.sales.map((item) => item.date);
        const sales = data.sales.map((item) => item.total_sales);
        const ctx = document.getElementById("sales-chart").getContext("2d");
        console.log("order", totalOrder);
        if (!chart) {
          chart = new Chart(ctx, {
            type: "bar",
            data: {
              labels: labels,
              datasets: [
                {
                  label: "Total Sales Per Day",
                  data: sales,
                  borderWidth: 1,
                },
              ],
            },
          });
        } else {
          chart.data.labels = labels;
          chart.data.datasets[0].data = sales;
          chart.update();
        }

        totalOrder.innerText = data.count_order;
      });
  }

  function loadItemSold() {
    const itemSold = document.getElementById("item-sold");
    console.log("Item SOld");

    fetch("../api/todays_item_sold.php")
      .then((res) => res.json())
      .then((data) => {
        console.log("Ito ng ang item sold: ", data);

        itemSold.innerText = data;
      });
  }

  function loadRevenue() {
    console.log("Loading data");

    fetch("../api/todays_revenue.php")
      .then((res) => res.json())
      .then((data) => {
        console.log("Data Received:", data);

        revenue.innerHTML = `
      <p><span>₱</span> ${data.total_revenue.toFixed(2)}</p>
      `;
      });
  }

  function loadTop3Items() {
    fetch("../api/top_three_items.php")
      .then((res) => res.json())
      .then((data) => {
        // Convert current DOM to string
        const currentHTML = topItems.innerHTML;

        // Build new HTML without touching the DOM yet
        let newHTML = "";

        if (data.length === 0) {
          newHTML = `
          <div class="h-full flex justify-center items-center mb-8">
            <p class="text-md font-bold">No best sellers yet.</p>
          </div>
        `;
        } else {
          let count = 0;
          data.forEach((item) => {
            newHTML += `
            <div class="flex gap-2">
              <p class="text-md font-bold"><span>${++count}.</span> ${
              item.name
            }</p>
              <span>-</span>
              <p class="text-md font-bold">${item.total_sold}x</p>
            </div>
          `;
          });
        }

        // Update only if different (prevents flashing)
        if (currentHTML.trim() !== newHTML.trim()) {
          topItems.innerHTML = newHTML;
        }
      });
  }

  function loadWeeklyBestItem() {
    fetch("../api/weekly_best_items.php")
      .then((res) => res.json())
      .then((data) => {
        console.log("Ito ang data sa weeekly", data);

        const currentHtml = weeklyItems.innerHTML;

        let newHtml = "";
        data.forEach((data) => {
          newHtml += `
        <div class="flex-grow flex items-center gap-4 rounded bg-slate-200 md:p-2 p-4">
          <img class="h-18 w-18 rounded" src="../${data.img}">
          <p class="font-semibold">${data.name}</p>
        </div>
        `;
        });

        if (currentHtml.trim() !== newHtml.trim()) {
          weeklyItems.innerHTML = newHtml;
        }
      });
  }

  function initData() {
    loadTop3Items();
    loadSales();
    loadRevenue();
    loadItemSold();
    loadWeeklyBestItem();
  }

  initData();

  setInterval(() => {
    console.log("Interval triggered:", new Date().toLocaleTimeString());
    initData();
  }, 5000);
});
