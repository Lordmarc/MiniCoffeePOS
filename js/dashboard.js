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
});