document.addEventListener("DOMContentLoaded", () => {
  const tabs = document.querySelectorAll("#links .tab");

  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      // Remove classes from all tabs
      tabs.forEach((t) => {
        t.classList.remove("bg-[#EFE9E3]", "text-[#A08963]", "font-semibold");
      });

      // Add classes to the clicked tab
      tab.classList.add("bg-[#EFE9E3]", "text-[#A08963]", "font-semibold");
    });
  });
});
