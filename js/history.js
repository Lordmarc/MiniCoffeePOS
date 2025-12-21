document.addEventListener("DOMContentLoaded", () => {
  const historyItems = document.getElementById("history-items");

  const wrapper = document.getElementById("dateRange");
  const dateText = document.getElementById("dateRangeText");
  const start = document.getElementById("startDate");
  const end = document.getElementById("endDate");

  const exportBtn = document.querySelector(".export-file");
  const sort = document.getElementById("sort");
  const searchInput = document.getElementById("search-product");

  let currentSort = "newest";
  let currentSearch = "";

  let currentPage = 1;
  const itemsPerPage = 6;

  renderTransactions({
    sort: currentSort,
    search: currentSearch,
  });

  document.querySelector(".export-file").addEventListener("click", () => {
const { jsPDF } = window.jspdf;
const doc = new jsPDF();

  doc.setFontSize(18);
  doc.setTextColor("#7B542F");
  doc.text("CoffeePOS Order History", 105, 20, { align: "center" });

  const dateRangeText = document.getElementById("dateRangeText").textContent || "All Dates";
  doc.setFontSize(12);
  doc.setTextColor("#000");
  doc.text(`Date Range: ${dateRangeText}`, 105, 28, { align: "center" });

  // Build rows for the PDF table same as preview
  const tableRows = [];
  const previewBody = document.getElementById("previewBody");
  previewBody.querySelectorAll("tr").forEach(tr => {
    const row = [];
    tr.querySelectorAll("td").forEach(td => {
      row.push(td.textContent.trim());
    });
    tableRows.push(row);
  });

  doc.autoTable({
    startY: 35,
    head: [["Order ID", "Date & Time", "Items", "Total Amount"]],
    body: tableRows,
    theme: "grid",
    headStyles: { fillColor: [123, 84, 47], textColor: 255 },
    styles: { fontSize: 10 },
    columnStyles: {
      0: { cellWidth: 25, halign: "center" },
      1: { cellWidth: 50, halign: "left" },
      2: { cellWidth: 80, halign: "left" },
      3: { cellWidth: 30, halign: "right" }
    },
    margin: { top: 35, left: 10, right: 10 },
  });

  doc.save("order-history.pdf");
});


function updatePreviewTable(data, dateRangeText = "All Dates") {
  const preview = document.getElementById("pdfPreview");
  const previewBody = document.getElementById("previewBody");
  const previewDateRange = document.getElementById("previewDateRange");
  
  previewBody.innerHTML = ""; // clear previous
  previewDateRange.textContent = `Date Range: ${dateRangeText}`;
  
  data.forEach(item => {
    const tr = document.createElement("tr");

    const formattedDate = new Date(item.date).toLocaleDateString("en-US", {
      month: "short",
      day: "2-digit",
      year: "numeric",
    });

    const formattedTime = new Date(item.date).toLocaleTimeString("en-US", {
      hour: "2-digit",
      minute: "2-digit",
      hour12: true,
    });

    tr.innerHTML = `
      <td style="padding:8px; border: 1px solid #ddd; text-align:center;">#${String(item.id).padStart(4, "0")}</td>
      <td style="padding:8px; border: 1px solid #ddd; text-align:left;">${formattedDate} ${formattedTime}</td>
      <td style="padding:8px; border: 1px solid #ddd; text-align:left;">${item.items.map(t => t.name).join(", ")}</td>
      <td style="padding:8px; border: 1px solid #ddd; text-align:right;">PHP ${item.total.toFixed(2)}</td>
    `;

    previewBody.appendChild(tr);
  });
  

}


  wrapper.addEventListener("click", () => {
    start.showPicker ? start.showPicker() : start.focus();
  });

  start.addEventListener("change", () => {
    end.showPicker ? end.showPicker() : end.focus();
  });

  end.addEventListener("change", () => {
    if (!start.value || !end.value) return;

    const s = new Date(start.value).toLocaleDateString("en-US", {
      month: "short",
      day: "2-digit",
      year: "numeric",
    });

    const e = new Date(end.value).toLocaleDateString("en-US", {
      month: "short",
      day: "2-digit",
      year: "numeric",
    });

    dateText.textContent = `${s} - ${e}`;

    // filterByDate(start.value, end.value);

    renderTransactions({
      startDate: start.value,
      endDate: end.value,
      sort: currentSort,
      search: currentSearch,
    });
  });

  function renderTransactions({
    startDate = null,
    endDate = null,
    sort = "newest",
    search = "",
  } = {}) {
    historyItems.innerHTML = "";
    fetch("../api/transaction.php")
      .then((res) => res.json())
      .then((data) => {
        if (search.trim() !== "") {
          const searchToLower = search.toLowerCase();
          data = data.filter((item) =>
            item.items.some((i) => i.name.toLowerCase().includes(searchToLower))
          );
        }
        switch (sort) {
          case "newest":
            data.sort((a, b) => new Date(b.date) - new Date(a.date));
            break;
          case "oldest":
            data.sort((a, b) => new Date(a.date) - new Date(b.date));
            break;
        }

        // optional date range filter
        if (startDate && endDate) {
          const s = new Date(startDate);
          const e = new Date(endDate);
          e.setHours(23, 59, 59, 999);

          data = data.filter((t) => {
            const d = new Date(t.date);
            return d >= s && d <= e;
          });
        }
            updatePreviewTable(data, dateText.textContent || "All Dates");

        console.log(data);
        const paginationInfo = document.getElementById("pagination-info");
        const paginationButtons = document.getElementById("pagination-buttons");

        const totalItems = data.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);

        if (currentPage > totalPages) currentPage = 1;

        const startItem = (currentPage - 1) * itemsPerPage;
        const endItem = startItem + itemsPerPage;

        paginatedData = data.slice(startItem, endItem);

        if (paginationInfo) {
          paginationInfo.textContent = `Showing ${startItem + 1}- ${Math.min(
            endItem,
            totalItems
          )} of ${totalItems}`;
        }
        if (paginationButtons) {
          paginationButtons.innerHTML = "";

          for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement("button");
            btn.textContent = i;
            btn.classList.add("px-4", "py-3");
            btn.className = `p-2
     
      ${
        i === currentPage
          ? "bg-[#7B542F] text-white"
          : "bg-white hover:bg-gray-100"
      }
    `;

            btn.addEventListener("click", () => {
              currentPage = i;
              renderTransactions({
                startDate: start.value || null,
                endDate: end.value || null,
                sort: currentSort,
                search: currentSearch,
              });
            });
            paginationButtons.appendChild(btn);
          }
        }

        paginatedData.forEach((item) => {
          const tr = document.createElement("tr");

          const formattedDate = new Date(item.date).toLocaleDateString(
            "en-US",
            {
              month: "short",
              day: "2-digit",
              year: "numeric",
            }
          );

          const formattedTime = new Date(item.date).toLocaleTimeString(
            "en-US",
            {
              hour: "2-digit",
              minute: "2-digit",
              hour12: true,
            }
          );

          tr.innerHTML = `
          <td class="px-4 py-3">#${String(item.id).padStart(4, "0")}</td>
          <td class="flex flex-col px-4 py-3">
            <h3>${formattedDate}</h3>
            <p>${formattedTime}</p>
          </td>
          <td class="min-w-sm px-4 py-3">${item.items
            .map((t) => t.name)
            .join(", ")}</td>
          <td><span>₱ </span> ${item.total.toFixed(2)}</td>
          `;

          historyItems.appendChild(tr);
        });
      });
  }
  sort.addEventListener("change", () => {
    currentSort = sort.value;
    renderTransactions({
      startDate: start.value || null,
      endDate: end.value || null,
      sort: currentSort,
      search: currentSearch,
    });
  });

  searchInput.addEventListener("input", () => {
    currentSearch = searchInput.value;
    renderTransactions({
      startDate: start.value || null,
      endDate: end.value || null,
      sort: currentSort,
      search: currentSearch,
    });
  });


});
