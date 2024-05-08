// Function to show modal
document.getElementById('editProfileBtn').addEventListener('click', function() {
  document.getElementById('editProfileModal').style.display = 'block';
});

// Function to hide modal when <span> (x) is clicked
document.querySelector('.close1').addEventListener('click', function() {
  document.getElementById('editProfileModal').style.display = 'none';
});

// Function to hide modal when cancel button is clicked
document.getElementById('cancelProfileBtn').addEventListener('click', function() {
  document.getElementById('editProfileModal').style.display = 'none';
});
