// Function to show modal
document.getElementById('editProjectsBtn').addEventListener('click', function() {
  document.getElementById('editProjectsModal').style.display = 'block';
});
  
// Function to hide modal when <span> (x) is clicked
document.querySelector('.close4').addEventListener('click', function() {
  document.getElementById('editProjectsModal').style.display = 'none';
});
  
// Function to hide modal when cancel button is clicked
document.getElementById('cancelProjectsBtn').addEventListener('click', function() {
  document.getElementById('editProjectsModal').style.display = 'none';
});
  