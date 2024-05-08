// Function to show modal
document.getElementById('editExperienceBtn').addEventListener('click', function() {
    document.getElementById('editExperienceModal').style.display = 'block';
  });
  
  // Function to hide modal when <span> (x) is clicked
  document.querySelector('.close3').addEventListener('click', function() {
    document.getElementById('editExperienceModal').style.display = 'none';
  });
  
  // Function to hide modal when cancel button is clicked
  document.getElementById('cancelExperienceBtn').addEventListener('click', function() {
    document.getElementById('editExperienceModal').style.display = 'none';
  });