<?php
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

  // Query database for user information
  $sql = "SELECT profile_section.*, about_section.*, experience_section.*, projects_section.*, contact_section.gmail_link
          FROM profile_section
          LEFT JOIN about_section ON profile_section.id = about_section.id
          LEFT JOIN experience_section ON profile_section.id = experience_section.id
          LEFT JOIN projects_section ON profile_section.id = projects_section.id
          LEFT JOIN contact_section ON profile_section.id = contact_section.id";

  $result = $conn->query($sql);

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
      exit();
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
    <div class="logo"><a href="admin_view.php"><img src="assets/logo-white.png" alt="logo icon"></a><?php echo $name; ?></div>
    <div>
      <ul class="nav-links">
        <li><a href="#about">About</a></li>
        <li><a href="#experience">Experience</a></li>
        <li><a href="#projects">Projects</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>
    </div>
  </nav>

  <nav id="hamburger-nav">
    <div class="logo"><a href="admin_view.php"><img src="assets/logo-white.png" alt="logo icon"></a><?php echo $name; ?></div>
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
            onclick="window.open('<?php echo $fb_link; ?>', '_blank')" />
          <img src="./assets/tiktok.png" alt="My LinkedIn profile" class="icon"
            onclick="window.open('<?php echo $tt_link; ?>', '_blank')" />
          <img src="./assets/linkedin.png" alt="My LinkedIn profile" class="icon"
            onclick="window.open('<?php echo $li_link; ?>', '_blank')" />
          <img src="./assets/github.png" alt="My Github profile" class="icon"
            onclick="window.open('<?php echo $gh_link; ?>', '_blank')" />
        </div> <!-- end of socials-container -->
      </div> <!-- end of section__text -->
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
        </div> <!-- end of about-containers -->
    </div> <!-- end of experience-details-container -->
    <script src="./scripts/populate_experience_data.js"></script>
  </section> <!-- end of experience section -->

  
  <section id="projects">
    <p class="section__text__p1">Browse My Notable</p>
    <h1 class="title">PROJECTS</h1>

    <div class="experience-details-container">
      <div class="about-containers">
        <div class="about-containers" id="projects-container">
          <!-- Content will be dynamically added here -->
        </div>
      </div> <!-- end of about-containers -->
    </div> <!-- end of experience-details-container -->
    <script src="./scripts/populate_projects_data.js"></script>
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
          <div class="logo">
            <a href="#"><img src="assets/logo-white.png"></a>
          </div>
          <li><a href="#about">About</a></li>
          <li><a href="#experience">Experience</a></li>
          <li><a href="#projects">Projects</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </div>
    </nav>
    <p>&#169; 2024 GIANNE BACAY. ALL RIGHTS RESERVED.</p>
  </footer> <!-- end of footer -->

  <script src="scripts/script.js"></script>
</body>
</html>
