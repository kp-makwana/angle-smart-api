document.addEventListener('DOMContentLoaded', function () {
  const avatar = document.getElementById('uploadedAvatar');
  const fileInput = document.querySelector('.account-file-input');
  const resetBtn = document.querySelector('.account-image-reset');

  if (fileInput && avatar) {
    const defaultImage = avatar.src;

    fileInput.addEventListener('change', () => {
      const file = fileInput.files[0];

      if (file) {
        if (file.size > 5 * 1024 * 1024) {
          Swal.fire('Error', 'Image must be less than 5MB', 'error');
          fileInput.value = '';
          return;
        }
        avatar.src = URL.createObjectURL(file);
      }
    });

    resetBtn.addEventListener('click', () => {
      fileInput.value = '';
      avatar.src = defaultImage;
    });
  }
});
