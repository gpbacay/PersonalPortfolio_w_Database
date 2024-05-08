<?php
  session_start();

  // Check if the session username is not set
  if (!isset($_SESSION["username"])) {
      header("Location: login.php");
      exit();
  }

  // Check if the logout button is clicked
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["logout"])) {
      // Clear all session variables
      session_unset();
      
      // Destroy the session
      session_destroy();

      // Redirect to the login page
      header("Location: login.php");
      exit();
  }

  // Establish connection to the database
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "portfolio_db";

  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $username = $_SESSION["username"];

  // Query database for user information from credentials table
  $sql = "SELECT credentials.*, profile_section.*, about_section.*, experience_section.*, projects_section.*, contact_section.gmail_link
          FROM credentials
          LEFT JOIN profile_section ON credentials.id = profile_section.id
          LEFT JOIN about_section ON credentials.id = about_section.id
          LEFT JOIN experience_section ON credentials.id = experience_section.id
          LEFT JOIN projects_section ON credentials.id = projects_section.id
          LEFT JOIN contact_section ON credentials.id = contact_section.id
          WHERE credentials.username=?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  // Initialize global variables
  // Profile Section
  $profile_picture_profile = "";
  $name = "";
  $title = "";
  $cv_link = "";
  $fb_link = "";
  $tt_link = "";
  $li_link = "";
  $gh_link = "";
  // About Section
  $profile_picture_about = "";
  $biography = "";
  // Experience Section
  $icon_path = "";
  $technology_name = "";
  $proficiency_level = "";
  // Projects Section
  $thumbnail = "";
  $projTitle = "";
  $projDesc = "";
  $projGh = "";
  $projGdrive = "";
  // Contact Section
  $gmail_link = "";


  // Fetch user information
  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      
      // Assign retrieved user information to global variables
      // Profile Section
      $profile_picture_profile = $row["profile_picture1"];
      $name = $row["name"];
      $title = $row["title"];
      $cv_link = $row["cv_link"];
      $fb_link = $row["fb_link"];
      $tt_link = $row["tt_link"];
      $li_link = $row["li_link"];
      $gh_link = $row["gh_link"];
      // About Section
      $profile_picture_about = $row["profile_picture2"];
      $biography = $row["biography"];
      // Experience Section
      $icon_path = $row["icon_path"];
      $technology_name = $row["technology_name"];
      $proficiency_level = $row["proficiency_level"];
      // Projects Section
      $thumbnail = $row["thumbnail"];
      $projTitle = $row["projTitle"];
      $projDesc = $row["projDesc"];
      $projGh = $row["projGh"];
      $projGdrive = $row["projGdrive"];
      // Contact Section
      $gmail_link = $row["gmail_link"];
  } else {
      echo "User not found";
      header("Location: login.php");
      exit();
  }

  //UPDATE SECTIONS
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // UPDATE PROFILE SECTION 
    // Check if the form is submitted for updating the profile section
    if (isset($_POST['saveProfileBtn'])) {
      if(isset($_SESSION['username'])) {
          $name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
          $title = isset($_POST['title']) ? mysqli_real_escape_string($conn, $_POST['title']) : '';
          $fb_link = isset($_POST['fb_link']) ? mysqli_real_escape_string($conn, $_POST['fb_link']) : '';
          $tt_link = isset($_POST['tt_link']) ? mysqli_real_escape_string($conn, $_POST['tt_link']) : '';
          $li_link = isset($_POST['li_link']) ? mysqli_real_escape_string($conn, $_POST['li_link']) : '';
          $gh_link = isset($_POST['gh_link']) ? mysqli_real_escape_string($conn, $_POST['gh_link']) : '';

          // File upload handling
          $target_dir = "assets/";
          $cv_file = isset($_FILES["cv_file"]["name"]) ? basename($_FILES["cv_file"]["name"]) : '';
          $profile_picture1 = isset($_FILES["profile_picture1"]["name"]) ? basename($_FILES["profile_picture1"]["name"]) : '';

          // Move uploaded files to the appropriate directory
          if (!empty($cv_file) && !empty($profile_picture1)) {
              if (move_uploaded_file($_FILES["cv_file"]["tmp_name"], $target_dir . $cv_file) &&
                  move_uploaded_file($_FILES["profile_picture1"]["tmp_name"], $target_dir . $profile_picture1)) {

                  // Update user data in the database
                  $sql = "UPDATE profile_section SET profile_picture1=?, name=?, title=?, cv_link=?, fb_link=?, tt_link=?, li_link=?, gh_link=? WHERE id=?";
                  $stmt = $conn->prepare($sql);
                  $stmt->bind_param("ssssssssi", $profile_picture1, $name, $title, $cv_file, $fb_link, $tt_link, $li_link, $gh_link, $_SESSION['user_id']);

                  if ($stmt->execute()) {
                      echo "Record updated successfully";
                      // Redirect to the updated page after successful update
                      header("Location: admin_view.php");
                      exit();
                  } else {
                      echo "Error updating record: " . $stmt->error;
                  }

                  $stmt->close();
              } else {
                  echo "File upload failed";
              }
          } else {
              echo "Please provide both CV file and profile picture";
          }
      }
    }

    // UPDATE ABOUT SECTION
    // Check if the form is submitted
    if (isset($_POST['saveAboutBtn'])) {
        if(isset($_SESSION['username'])) {
            // Escape user inputs for security
            $biography = isset($_POST['biography']) ? mysqli_real_escape_string($conn, $_POST['biography']) : '';

            // File upload handling for profile picture about
            $target_dir = "assets/";
            $profile_picture_about = isset($_FILES["profile_picture_about"]["name"]) ? basename($_FILES["profile_picture_about"]["name"]) : '';

            // Move uploaded file to the appropriate directory
            if (!empty($profile_picture_about)) {
                if (move_uploaded_file($_FILES["profile_picture_about"]["tmp_name"], $target_dir . $profile_picture_about)) {
                    // Update user data in the database
                    $sql = "UPDATE about_section SET biography=?, profile_picture2=? WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssi", $biography, $profile_picture_about, $_SESSION['user_id']);

                    if ($stmt->execute()) {
                        echo "Record updated successfully";
                        // Redirect to the updated page after successful update
                        echo "<script>window.location.replace(window.location.href);</script>";
                        exit();
                    } else {
                        echo "Error updating record: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    echo "File upload failed";
                }
            } else {
                // Update only biography if profile picture is not provided
                $sql = "UPDATE about_section SET biography=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $biography, $_SESSION['user_id']);

                if ($stmt->execute()) {
                    echo "Record updated successfully";
                    // Redirect to the updated page after successful update
                    header("Location: admin_view.php");
                } else {
                    echo "Error updating record: " . $stmt->error;
                }

                $stmt->close();
            }
        }
    }

    // INSERT INTO EXPERIENCE SECTION
    // Check if the form is submitted
    if (isset($_POST['saveExperienceBtn'])) {
      if (isset($_SESSION['username'])) {
          // Escape user inputs for security
          $technology_name = isset($_POST['technology_name']) ? mysqli_real_escape_string($conn, $_POST['technology_name']) : '';
          $proficiency_level = isset($_POST['proficiency_level']) ? mysqli_real_escape_string($conn, $_POST['proficiency_level']) : '';

          // File upload handling for icon
          $target_dir = "assets/";
          $icon_path = isset($_FILES["iconPath"]["name"]) ? basename($_FILES["iconPath"]["name"]) : '';

          // Move uploaded file to the appropriate directory
          if (!empty($icon_path)) {
              if (move_uploaded_file($_FILES["iconPath"]["tmp_name"], $target_dir . $icon_path)) {
                  // Insert data into the database
                  $sql = "INSERT INTO experience_section (icon_path, technology_name, proficiency_level) VALUES (?, ?, ?)";
                  $stmt = $conn->prepare($sql);
                  $stmt->bind_param("sss", $icon_path, $technology_name, $proficiency_level);

                  if ($stmt->execute()) {
                      echo "Record inserted successfully";
                      // Redirect to the updated page after successful insertion
                      header("Location: admin_view.php");
                      exit();
                  } else {
                      echo "Error inserting record: " . $stmt->error;
                  }

                  $stmt->close();
              } else {
                  echo "File upload failed";
              }
          } else {
              echo "Please provide the icon path";
          }
      }
    }

    // UPDATE PROJECTS SECTION
    // Check if the form is submitted
    if (isset($_POST['saveProjectsBtn'])) {
      if (isset($_SESSION['username'])) {
          // Escape user inputs for security
          $projTitle = isset($_POST['projTitle']) ? mysqli_real_escape_string($conn, $_POST['projTitle']) : '';
          $projDesc = isset($_POST['projDesc']) ? mysqli_real_escape_string($conn, $_POST['projDesc']) : '';
          $projGh = isset($_POST['projGh']) ? mysqli_real_escape_string($conn, $_POST['projGh']) : '';
          $projGdrive = isset($_POST['projGdrive']) ? mysqli_real_escape_string($conn, $_POST['projGdrive']) : '';

          // File upload handling for project thumbnail
          $target_dir = "assets/";
          $thumbnail = isset($_FILES["thumbnail"]["name"]) ? basename($_FILES["thumbnail"]["name"]) : '';

          // Move uploaded file to the appropriate directory
          if (!empty($thumbnail)) {
              if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_dir . $thumbnail)) {
                  // Insert data into the database
                  $sql = "INSERT INTO projects_section (thumbnail, projTitle, projDesc, projGh, projGdrive) VALUES (?, ?, ?, ?, ?)";
                  $stmt = $conn->prepare($sql);
                  $stmt->bind_param("sssss", $thumbnail, $projTitle, $projDesc, $projGh, $projGdrive);

                  if ($stmt->execute()) {
                      echo "Record inserted successfully";
                      // Redirect to the updated page after successful insertion
                      header("Location: admin_view.php");
                      exit();
                  } else {
                      echo "Error inserting record: " . $stmt->error;
                  }

                  $stmt->close();
              } else {
                  echo "File upload failed";
              }
          } else {
              echo "Please provide the project thumbnail";
          }
      }
    }

    // UPDATE CONTACT SECTION
    // Check if the form is submitted
    if (isset($_POST['saveContactBtn'])) {
      if (isset($_SESSION['username'])) {
          // Escape user inputs for security
          $gmail_link = isset($_POST['gmail_link']) ? mysqli_real_escape_string($conn, $_POST['gmail_link']) : '';

          // Update user data in the database
          $sql = "UPDATE contact_section SET gmail_link=? WHERE id=?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("si", $gmail_link, $_SESSION['user_id']);

          if ($stmt->execute()) {
              echo "Record updated successfully";
              // Redirect to the updated page after successful update
              header("Location: admin_view.php");
              exit();
          } else {
              echo "Error updating record: " . $stmt->error;
          }

          $stmt->close();
      }
    }

  }

  // Close database connection
  $conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $name; ?>'s Portfolio</title>
  <link rel="icon" href="assets/logo.png" type="image/x-icon">
  <style>
    .chatbot-toggler:hover span img {
      content: url('./assets/chatbox_icon_hovered.png');
    }
    .show-chatbot .chatbot-toggler span img {
      content: url('./assets/close_icon.png');
    }
    .scroll-down-button:hover img {
      content: url('./assets/scroll_down_hover.png');
    }
    .logo img:hover {
      content: url('./assets/logo.png');
    }

    #profile {
      background-image: url('./assets/bg4.png');
    }
    #about {
      background-image: url('./assets/bg2.png');
    }
    #experience {
      background-image: url('./assets/bg1.png');
    }
    #projects {
      background-image: url('./assets/bg4.png');
    }
    #contact {
      background-image: url('./assets/bg2.png');
    }
  </style>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/mediaqueries.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Product+Sans:wght@400;700&display=swap">
</head>
<span class="chatbot-toggler">
  <span class="chatbox-icon"><img src="assets/chatbox_icon.png" /></span>
  <span class="close-icon"><img src="assets/close_icon.png" /></span>
</span>

<body class="show-chatbot">
  <div class="chatbot">
    <header>
      <h2>HARAYA</h2>
      <span class="close-icon1"><img src="assets/close_icon1.png" /></span>
    </header>
    <ul class="chatbox">
      <li class="chat incoming">
        <span class="bot-icon"><img src="assets/bot_icon.gif" /></span>
        <p>Hi there! I am Haraya, Gianne's personal AI Assistant, How can I help you today?</p>
      </li>
    </ul>
    <div class="scroll-down-button">
      <span><img src="assets/scroll_down_icon.png" title="recent" /></span>
    </div>
    <div class="chat-input">
      <textarea placeholder="Ask me anything..." required></textarea>
      <span><img src="assets/send_icon.png" title="submit" /></span>
    </div>
  </div>

  <nav id="desktop-nav">
    <div class="logo"><a href="user_view.php"><img src="assets/logo-white.png" alt="logo icon"></a>Welcome, <?php echo $name; ?>!</div>
    <div>
      <ul class="nav-links">
        <li><a href="#about">About</a></li>
        <li><a href="#experience">Experience</a></li>
        <li><a href="#projects">Projects</a></li>
        <li><a href="#contact">Contact</a></li>
        <form action="" method="post" class="nav-links-form">
            <input type="submit" name="logout" value="Logout" >
        </form>
      </ul>
    </div>
  </nav>

  <nav id="hamburger-nav">
    <div class="logo"><a href="user_view.php"><img src="assets/logo-white.png" alt="logo icon"></a>Welcome, <?php echo $name; ?>!</div>
    <div class="hamburger-menu">
      <div class="hamburger-icon" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
      </div>
      <div class="menu-links">
        <li><a href="#about" onclick="toggleMenu()">About</a></li>
        <li><a href="#experience" onclick="toggleMenu()">Experience</a></li>
        <li><a href="#projects" onclick="toggleMenu()">Projects</a></li>
        <li><a href="#contact" onclick="toggleMenu()">Contact</a></li>
        <form action="" method="post" class="nav-links-form">
            <input type="submit" name="logout" value="Logout">
        </form>
      </div>
    </div>
  </nav>

  <section id="profile">
      <div class="section__pic-container">
        <img src="./assets/<?php echo $profile_picture_profile; ?>" alt="Profile picture" />
      </div> <!-- end of section__pic-container -->
      <div class="section__text">
        <p class="section__text__p1">Hello, I'm</p>
        <h1 class="title"><?php echo $name; ?></h1>
        <p class="section-text p2"><?php echo $title; ?></p>
        <div class="btn-container">
          <button class="btn btn-color-2" onclick="window.open('./assets/resume.pdf')">
            Download CV
          </button>
          <button class="btn btn-color-1" onclick="navigateToContact()">
            Contact Info
          </button>
        </div>
        <div id="socials-container">
          <img src="./assets/facebook.png" alt="My LinkedIn profile" class="icon"
            onclick="window.open('https://www.facebook.com/GianneBacay', '_blank')" />
          <img src="./assets/tiktok.png" alt="My LinkedIn profile" class="icon"
            onclick="window.open('https://www.tiktok.com/@gpbacay', '_blank')" />
          <img src="./assets/linkedin.png" alt="My LinkedIn profile" class="icon"
            onclick="window.open('https://www.linkedin.com/in/gianne-bacay-195527290/', '_blank')" />
          <img src="./assets/github.png" alt="My Github profile" class="icon"
            onclick="window.open('https://github.com/gpbacay', '_blank')" />
        </div> <!-- end of socials-container -->
        <div class="profile-btn-div">
          <button id="editProfileBtn" class="btn btn-color-1">Edit Profile</button>
        </div>
      </div> <!-- end of section__text -->

    <!-- Hidden modal for editing profile -->
    <div id="editProfileModal" class="modal">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <div class="modal-content">
          <div class="editProfileModal_header">
            <h1>Edit Profile Section</h1>
            <span class="close1">&times;</span>
          </div>
          <!-- Input fields for profile section -->
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" class="profile-inputs" placeholder="<?php echo $name; ?>" required>
          <label for="title">Title:</label>
          <input type="text" id="title" name="title" class="profile-inputs" placeholder="<?php echo $title; ?>" required>
          <label for="cv_link">CV/Resume File:</label>
          <input type="file" id="cv_link" name="cv_file" class="profile-inputs" placeholder="<?php echo $cv_link; ?>" required>
          <label for="fb_link">Facebook Link:</label>
          <input type="text" id="fb_link" name="fb_link" class="profile-inputs" placeholder="<?php echo $fb_link; ?>" required>
          <label for="tt_link">TikTok Link:</label>
          <input type="text" id="tt_link" name="tt_link" class="profile-inputs" placeholder="<?php echo $tt_link; ?>" required>
          <label for="li_link">LinkedIn Link:</label>
          <input type="text" id="li_link" name="li_link" class="profile-inputs" placeholder="<?php echo $li_link; ?>" required>
          <label for="gh_link">GitHub Link:</label>
          <input type="text" id="gh_link" name="gh_link" class="profile-inputs" placeholder="<?php echo $gh_link; ?>" required>
          <!-- Photo upload for profile picture 1 -->
          <label for="profile_picture1">Profile Picture 1:</label>
          <input type="file" id="profile_picture1" name="profile_picture1" class="profile-inputs" onchange="previewImage(event)" required>
          <div class="details-container">
            <img id="preview1" class="preview-img" src="./assets/<?php echo $profile_picture_profile; ?>" alt="Profile Picture 1 Preview">
          </div>
          <script>
            function previewImage(event) {
              var reader = new FileReader();
              reader.onload = function() {
                var preview = document.getElementById('preview1');
                preview.src = reader.result;
              }
              reader.readAsDataURL(event.target.files[0]);
            }
          </script>
          <br>
          <div class="modal-buttons">
            <button type="submit" name="saveProfileBtn">Save</button>
            <button type="button" id="cancelProfileBtn">Cancel</button>
          </div>
        </div> <!-- end of modal-content -->
      </form>
    </div> <!-- end of #editProfileModal .modal -->
    <script src="scripts/profile.js"></script>
  </section> <!-- end of profile section -->

  <section id="about">
    <p class="section__text__p1">Get To Know More</p>
    <h1 class="title">ABOUT ME</h1>
    <div class="section-container">
      <div class="section__pic-container">
        <img src="./assets/<?php echo $profile_picture_about; ?>" alt="Profile picture" class="about-pic" />
      </div>
      <div class="about-details-container">
        <div class="text-container">
          <p>
            <?php echo $biography; ?>
          </p>
        </div> <!-- end of text-container -->
      </div> <!-- end of about-details-container -->
    </div> <!-- end of section-contatiner -->

    <div class="about-btn-div">
      <button id="editAboutBtn" class="btn btn-color-1">Edit About Info</button>
    </div>

    <!-- Hidden modal for editing about section -->
    <div id="editAboutModal" class="modal">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <div class="modal-content">
          <div class="editAboutModal_header">
            <h1>Edit About Section</h1>
            <span class="close2">&times;</span>
          </div>
          <!-- Photo upload for profile picture about -->
          <label for="profile_picture_about">Profile Picture About:</label>
          <input type="file" id="profile_picture_about" name="profile_picture_about" class="about-inputs" onchange="previewImageAbout(event)" required>
          <div class="details-container">
            <img id="preview2" class="preview-img" src="./assets/<?php echo $profile_picture_about; ?>" alt="Profile Picture About Preview">
          </div>
          <script>
            function previewImageAbout(event) {
              var reader = new FileReader();
              reader.onload = function() {
                var preview = document.getElementById('preview2');
                preview.src = reader.result;
              }
              reader.readAsDataURL(event.target.files[0]);
            }
          </script>
          <!-- Input fields for about section -->
          <label for="biography">Biography:</label>
          <textarea id="biography" name="biography" class="about-inputs" placeholder="<?php echo $biography; ?>" required><?php echo $biography; ?></textarea> <br>
          <div class="modal-buttons">
            <button type="submit" name="saveAboutBtn">Save</button>
            <button type="button" id="cancelAboutBtn">Cancel</button>
          </div>
        </div> <!-- end of modal-content -->
      </form>
    </div> <!-- end of #editAboutModal .modal -->

    <script src="scripts/about.js"></script>
  </section> <!-- end of about section -->

  <section id="experience">
    <p class="section__text__p1">Explore My</p>
    <h1 class="title">EXPERIENCE</h1>
    <div class="experience-details-container">
        <div class="about-containers">
            <div class="details-container">
                <h2 class="experience-sub-title">Tech Stack</h2>
                <div class="article-container" id="experience-container">
                    <!-- Content will be dynamically added here -->
                </div>
            </div> <!-- end of details-container -->

            <div class="add-experience-btn-div">
                <button id="editExperienceBtn" class="btn btn-color-1" onclick="addExperience()">
                    Add Experience
                </button>
            </div> <!-- end of add-experience-btn-div -->
            
            <!-- Hidden modal for editing experience section -->
            <div id="editExperienceModal" class="modal">
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                <div class="modal-content">
                  <div class="editExperienceModal_header">
                    <h1>Add Technology Used</h1>
                    <span class="close3">&times;</span>
                  </div>
                  <!-- Input fields for experience section -->
                  <label for="iconPath">Tech Icon:</label>
                  <input type="file" id="iconPath" name="iconPath" class="experience-inputs" onchange="previewIcon(event)" required>
                  <div class="details-container">
                    <img id="iconPreview" class="preview-img" src="./assets/<?php echo $icon_path; ?>" alt="Icon Preview">
                  </div>
                  <script>
                    function previewIcon(event) {
                      var reader = new FileReader();
                      reader.onload = function() {
                        var preview = document.getElementById('iconPreview');
                        preview.src = reader.result;
                      }
                      reader.readAsDataURL(event.target.files[0]);
                    }
                  </script>
                  <label for="technology_name">Tech Name:</label>
                  <input type="text" id="technology_name" name="technology_name" class="experience-inputs" placeholder="<?php echo $technology_name; ?>" required>
                  <label for="proficiency_level">Proficiency Level:</label>
                  <input type="text" id="proficiency_level" name="proficiency_level" class="experience-inputs" placeholder="<?php echo $proficiency_level; ?>" required>
                  <!-- Buttons -->
                  <div class="modal-buttons">
                    <button type="submit" name="saveExperienceBtn">Save</button>
                    <button type="button" id="cancelExperienceBtn">Cancel</button>
                  </div>
                </div> <!-- end of modal-content -->
              </form>
            </div> <!-- end of #editExperienceModal .modal -->
            <script src="scripts/experience.js"></script>
        </div> <!-- end of about-containers -->
    </div> <!-- end of experience-details-container -->
    <script src="scripts/populate_experience_data.js"></script>
  </section> <!-- end of experience section -->

  
  <section id="projects">
    <p class="section__text__p1">Browse My Notable</p>
    <h1 class="title">PROJECTS</h1>

    <div class="experience-details-container">
      <div class="about-containers">
        <div class="about-containers" id="projects-container">
          <!-- Content will be dynamically added here -->
        </div>

        <div class="details-container color-container">
          <div class="add-project-btn-div">
            <button id="editProjectsBtn" class="btn btn-color-1">Add Project</button>
          </div> <!-- end of add-project-btn-div -->
        </div> <!-- end of experience-details-container -->

        <!-- Hidden modal for editing projects section -->
        <div id="editProjectsModal" class="modal">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <div class="modal-content">
              <div class="editProjectsModal_header">
                <h1>Add Project</h1>
                <span class="close4">&times;</span>
              </div>
              <!-- Input fields for projects section -->
              <label for="thumbnail">Project Thumbnail:</label>
              <input type="file" id="thumbnail" name="thumbnail" class="projects-inputs" onchange="previewThumbnail(event)" required>
              <div class="details-container">
                <img id="thumbnailPreview" class="preview-img" src="./assets/<?php echo $thumbnail; ?>" alt="Thumbnail Preview">
              </div>
              <script>
                function previewThumbnail(event) {
                  var reader = new FileReader();
                  reader.onload = function() {
                    var preview = document.getElementById('thumbnailPreview');
                    preview.src = reader.result;
                  }
                  reader.readAsDataURL(event.target.files[0]);
                }
              </script>
              <label for="projTitle">Project Title:</label>
              <input type="text" id="projTitle" name="projTitle" class="projects-inputs" placeholder="<?php echo $projTitle; ?>" required>
              <label for="projDesc">Project Description:</label>
              <textarea id="projDesc" name="projDesc" class="projects-inputs" placeholder="<?php echo $projDesc; ?>" required><?php echo $projDesc; ?></textarea>
              <label for="projGh">Github Link:</label>
              <input type="text" id="projGh" name="projGh" class="projects-inputs" placeholder="<?php echo $projGh; ?>" required>
              <label for="projGdrive">Drive Link:</label>
              <input type="text" id="projGdrive" name="projGdrive" class="projects-inputs" placeholder="<?php echo $projGdrive; ?>" required>
              <!-- Buttons -->
              <div class="modal-buttons">
                <button type="submit" name="saveProjectsBtn">Save</button>
                <button type="button" id="cancelProjectsBtn">Cancel</button>
              </div>
            </div> <!-- end of modal-content -->
          </form>
        </div> <!-- end of #editProjectsModal .modal -->
        <script src="scripts/projects.js"></script>

      </div> <!-- end of about-containers -->
    </div> <!-- end of experience-details-container -->
    <script src="scripts/populate_projects_data.js"></script>
  </section> <!-- end of prohjects section -->


  <section id="contact">
    <p class="section__text__p1">Get in Touch</p>
    <h1 class="title">CONTACT ME</h1>
    <div class="contact-info-upper-container">
      <div class="contact-info-container">
        <img src="./assets/email.png" alt="Email icon" class="icon contact-icon email-icon" />
        <p><a href="mailto:<?php echo $gmail_link; ?>"><?php echo $gmail_link; ?></a></p>
      </div>
      <div class="contact-info-container">
        <img src="./assets/linkedin.png" alt="LinkedIn icon" class="icon contact-icon" />
        <p>
          <a onclick="window.open('<?php echo $li_link; ?>', '_blank')">LinkedIn</a>
        </p>
      </div>
    </div>

    <div class="contact-btn-div">
      <button id="editContactBtn" class="btn btn-color-1">Edit Contact Info</button>
    </div>

    <div id="editContactModal" class="modal">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <div class="modal-content">
          <div class="editContactModal_header">
            <h1>Edit Contact Information</h1>
            <span class="close5">&times;</span>
          </div>
          <!-- Input field for Gmail -->
          <label for="gmail_link">Gmail:</label>
          <input type="email" id="gmail_link" name="gmail_link" class="contact-inputs" value="<?php echo $gmail_link; ?>" required><br><br>
          <!-- Buttons for saving or canceling changes -->
          <div class="modal-buttons">
            <button type="submit" name="saveContactBtn">Save</button>
            <button type="button" id="cancelContactBtn">Cancel</button>
          </div>
        </div> <!-- end of modal-content -->
      </form>
    </div> <!-- end of #editContactModal .modal -->
    <script src="scripts/contact.js"></script>

    <div class="contact-info-lower-container">
      <form action="https://api.web3forms.com/submit" method="POST" class="contact-form">
        <div class="contact-form-title">
          <h2>Get in touch</h2>
          <hr>
        </div>
        <input type="hidden" name="access_key" value="a013203e-9497-4327-9458-407ae1c7aa57">
        <input type="text" name="name" placeholder="Your Name" class="contact-inputs" required/>
        <input type="email" name="email" placeholder="Your Email" class="contact-inputs" required/>
        <textarea name="messsage" placeholder="Your Message" class="contact-inputs" required></textarea>
        <button type="submit">Submit <img src="assets/send_email_icon.png" alt=""></button>
      </form>
    </div>
  </section> <!-- end of contact section -->

  <footer id="footer-container">
    <nav class="footer-nav">
      <div class="footer-nav-links-container">
        <ul class="footer-nav-links">
          <div class="logo"><a href="#"><img src="assets/logo-white.png"></a></div>
          <li><a href="#about">About</a></li>
          <li><a href="#experience">Experience</a></li>
          <li><a href="#projects">Projects</a></li>
          <li><a href="#contact">Contact</a></li>
          <form action="" method="post" class="nav-links-form">
            <a><input type="submit" name="logout" value="Logout"></input></a>
          </form>
        </ul>
      </div>
    </nav>
    <p>&#169; 2024 GIANNE BACAY. ALL RIGHTS RESERVED.</p>
  </footer> <!-- end of footer -->

  <script src="scripts/script.js"></script>
</body>
</html>