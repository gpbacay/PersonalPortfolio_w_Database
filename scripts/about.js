// Function to show modal
document.getElementById('editAboutBtn').addEventListener('click', function() {
  document.getElementById('editAboutModal').style.display = 'block';
});

// Function to hide modal when <span> (x) is clicked
document.querySelector('.close2').addEventListener('click', function() {
  document.getElementById('editAboutModal').style.display = 'none';
});

// Function to hide modal when cancel button is clicked
document.getElementById('cancelAboutBtn').addEventListener('click', function() {
  document.getElementById('editAboutModal').style.display = 'none';
});