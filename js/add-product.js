document.addEventListener('DOMContentLoaded', () => {



  const form = document.querySelector('.save-form');
  const saveBtn = document.querySelector('.save-btn');

  const imgContainer = document.querySelector('.img-container');
  const imageInput = document.getElementById('product_image');
  const previewImage = document.getElementById('imgPreview');



imgContainer.addEventListener('click', () => {
  imageInput.click();
})
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


})