// Function to show modal
document.getElementById('editContactBtn').addEventListener('click', function() {
  document.getElementById('editContactModal').style.display = 'block';
});

// Function to hide modal when <span> (x) is clicked
document.querySelector('.close5').addEventListener('click', function() {
  document.getElementById('editContactModal').style.display = 'none';
});

// Function to hide modal when cancel button is clicked
document.getElementById('cancelContactBtn').addEventListener('click', function() {
  document.getElementById('editContactModal').style.display = 'none';
});
