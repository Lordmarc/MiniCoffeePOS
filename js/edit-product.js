document.addEventListener('DOMContentLoaded', () => {
  const productDesc = document.getElementById('product_description');
  const letterCount = document.getElementById('count-letters');
  
  const visibleContainer = document.querySelector('.visible-container');

  const saveBtn = document.querySelector('.save-btn');
  const cancelBtn = document.querySelector('.cancel-btn');

  const form = document.querySelector('.save-form');
  const visibleBtn = document.querySelector('.visible-btn');
  const activeIcon = document.querySelector('.active-icon');
  const visibleItem = document.getElementById("visible-item");

// --- Initialize Available Toggle based on PHP hidden input ---
const statusInput = document.getElementById('product-status');
const availableIcon = document.querySelector('.available-icon');
const availableContainer = document.querySelector('.available-container');
const availableBtn = document.querySelector('.available-btn');

function updateAvailableUI() {
  const isAvailable = statusInput.value === "1";
  availableIcon.classList.toggle("translate-x-8", isAvailable);
  availableContainer.classList.toggle("bg-[#ee9d2b]", isAvailable);
  availableContainer.classList.toggle("bg-gray-400", !isAvailable);
}

// Initialize on page load
updateAvailableUI();

// Toggle on click
availableBtn.addEventListener('click', () => {
  statusInput.value = statusInput.value === "1" ? "0" : "1";
  updateAvailableUI();
});



function updateLetterCount() {
  letterCount.textContent = `${productDesc.value.length} / 200 characters`;
}

if (productDesc && letterCount) {
  updateLetterCount();
  productDesc.addEventListener("input", updateLetterCount);
}


const imageInput = document.getElementById('product_image');
const previewImage = document.getElementById('previewImage');

imageInput.addEventListener('change', () => {
  const file = imageInput.files[0];
  if(!file) return;

  if(file.size > 3 * 1024 * 1024)
  {
    alert("Max file size is 3MB");
    imageInput.value = "";
    return;
  }

  const allowedTypes = ["image/jpeg", "image/png", "image/webp"];
  if(!allowedTypes.includes(file.type)) 
  {
    alert("Only JPEG, PNG, and WEBP are allowed");
    imageInput.value = "";
    return;
  }

  const reader = new FileReader();
  reader.onload = () => {
    previewImage.src = reader.result;
  };
  reader.readAsDataURL(file);
})

saveBtn.addEventListener('click', () => {

  form.submit();
});

cancelBtn.addEventListener('click', () => {
  window.location.href = "menu.php";
});

visibleBtn.addEventListener('click', () => {

  const isActive = activeIcon.classList.toggle("translate-x-8");
  visibleItem.value = isActive ? "1" : "0";

  if(isActive)
  {
    visibleContainer.classList.add("bg-[#ee9d2b]");
    visibleContainer.classList.remove("bg-gray-400");
    
    console.log(visibleItem)
  }else {
    visibleContainer.classList.add("bg-gray-400");
    visibleContainer.classList.remove("bg-[#ee9d2b]");
    
    console.log(visibleItem)
  }
});

function setAvailableToggle(status) {
  if (status === true) {
    availableIcon.classList.add("translate-x-8");
    availableContainer.classList.add("bg-[#ee9d2b]");
    availableContainer.classList.remove("bg-gray-400");
    statusInput.value = "1";
  } else {
    availableIcon.classList.remove("translate-x-8");
    availableContainer.classList.add("bg-gray-400");
    availableContainer.classList.remove("bg-[#ee9d2b]");
    statusInput.value = "0";
  }
}

function visibility()
{
  const id = Number(document.getElementById("product-id").value);

  fetch('../../api/product_item.php')
  .then(res => res.json())
  .then(data => {

    const product = data.find(item => item.id === id);

    if(!product) return;

    setAvailableToggle(product.status);
  })

}






});