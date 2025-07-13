const modal = document.getElementById('image-Modal');
  const modalImg = document.getElementById('modal-Image');
  const closeBtn = document.getElementById('closeBtn');
  const nextBtn = document.getElementById('nextBtn');
  const prevBtn = document.getElementById('prevBtn');

  let currentImages = [];
  let currentIndex = 0;
function openModal(src, postId) {
  const imageNodes = document.querySelectorAll(`img.post-image[data-post-id="${postId}"]`);
  currentImages = Array.from(imageNodes).map(img => img.src);

  currentIndex = currentImages.indexOf(src);
  if (currentIndex === -1) currentIndex = 0;

  modalImg.src = currentImages[currentIndex];
  modal.style.display = 'flex';

  // Show/hide navigation buttons based on number of images
  if (currentImages.length <= 1) {
    nextBtn.style.display = 'none';
    prevBtn.style.display = 'none';
  } else {
    nextBtn.style.display = 'block';
    prevBtn.style.display = 'block';
  }
}


 closeBtn.addEventListener('click', () => {
  modal.style.display = 'none';
  currentImages = [];
  currentIndex = 0;
});


  nextBtn.addEventListener('click', () => {
    if (currentImages.length === 0) return;
    currentIndex = (currentIndex + 1) % currentImages.length;
    modalImg.src = currentImages[currentIndex];
  });

  prevBtn.addEventListener('click', () => {
    if (currentImages.length === 0) return;
    currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
    modalImg.src = currentImages[currentIndex];
  });