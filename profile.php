<?php
    session_start();

    // Check if the session username is not set
    if (!isset($_SESSION["username"])) {
        // Redirect the user to the login page
        header("Location: login.php");
        exit(); // Ensure that script execution stops after redirection
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
    $conn = new mysqli("localhost", "root", "", "portfolio_db");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_SESSION["username"];

    // Query database for user information
    $sql = "SELECT * FROM profile WHERE username='$username'";
    $result = $conn->query($sql);

    // Initialize global variables
    $name = "";
    $title = "";
    $biography = "";
    $profile_picture = "";

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Assign retrieved user information to global variables
        $name = $row["name"];
        $title = $row["title"];
        $biography = $row["biography"];
        $profile_picture = $row["profile_picture"];
    } else {
        echo "User not found";
    }

    // Close the database connection
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
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/login.css">
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
    <div class="logo"><img src="assets/logo-white.png"><?php echo $name; ?></div>
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
    <div class="logo">
      <a href="#"><img src="assets/logo-white.png" alt=""></a> <?php echo $name; ?>
    </div>
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
      <img src="<?php echo $profile_picture; ?>" alt="Gianne Bacay's profile picture" />
    </div>
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
      </div> <!-- end of div socials-container -->
    </div> <!-- end of div section__text -->
  </section> <!-- end of profile section -->

  <section id="about">
    <p class="section__text__p1">Get To Know More</p>
    <h1 class="title">ABOUT ME</h1>
    <div class="section-container">
      <div class="section__pic-container">
        <img src="<?php echo $profile_picture; ?>" alt="Profile picture" class="about-pic" />
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
          <h2 class="experience-sub-title">Web Development</h2>
          <div class="article-container">
            <article>
              <img src="./assets/html.png" alt="Experience icon" class="icon" />
              <div>
                <h3>HTML</h3>
                <p>Basic</p>
              </div>
            </article>
            <article>
              <img src="./assets/css.png" alt="Experience icon" class="icon" />
              <div>
                <h3>CSS</h3>
                <p>Basic</p>
              </div>
            </article>
            <article>
              <img src="./assets/javascript.png" alt="Experience icon" class="icon" />
              <div>
                <h3>JavaScript</h3>
                <p>Basic</p>
              </div>
            </article>
            <article>
              <img src="./assets/php.png" alt="Experience icon" class="icon" />
              <div>
                <h3>PHP</h3>
                <p>Basic</p>
              </div>
            </article>
            <article>
              <img src="./assets/laravel.png" alt="Experience icon" class="icon" />
              <div>
                <h3>Laravel</h3>
                <p>Basic</p>
              </div>
            </article>
          </div>
        </div>

        <div class="details-container">
          <h2 class="experience-sub-title">AI Systems / Desktop App Development</h2>
          <div class="article-container">
            <article>
              <img src="./assets/python.png" alt="Experience icon" class="icon" />
              <div>
                <h3>Python</h3>
                <p>Intermediate</p>
              </div>
            </article>
            <article>
              <img src="./assets/java.png" alt="Experience icon" class="icon" />
              <div>
                <h3>Java</h3>
                <p>Intermediate</p>
              </div>
            </article>
            <article>
              <img src="./assets/palm2.png" alt="Experience icon" class="icon" />
              <div>
                <h3>PaLM 2</h3>
                <p>Intermediate</p>
              </div>
            </article>
            <article>
              <img src="./assets/opencv.png" alt="Experience icon" class="icon" />
              <div>
                <h3>OpenCV</h3>
                <p>Basic</p>
              </div>
            </article>
            <article>
              <img src="./assets/mediapipe.png" alt="Experience icon" class="icon" />
              <div>
                <h3>MediaPipe</h3>
                <p>Basic</p>
              </div>
            </article>
            <article>
              <img src="./assets/vertexai.png" alt="Experience icon" class="icon" />
              <div>
                <h3>Vertex AI</h3>
                <p>Basic</p>
              </div>
            </article>
          </div>
        </div>

        <div class="details-container">
          <h2 class="experience-sub-title">Database Management</h2>
          <div class="article-container">
            <article>
              <img src="./assets/mysql.png" alt="Experience icon" class="icon" />
              <div>
                <h3>MySQL</h3>
                <p>Intermediate</p>
              </div>
            </article>
            <article>
              <img src="./assets/wampserver.png" alt="Experience icon" class="icon" />
              <div>
                <h3>Wamp server</h3>
                <p>Basic</p>
              </div>
            </article>
            <article>
              <img src="./assets/sqlyog.png" alt="Experience icon" class="icon" />
              <div>
                <h3>SQLyog</h3>
                <p>Basic</p>
              </div>
            </article>
            <article>
              <img src="./assets/phpmyadmin.png" alt="Experience icon" class="icon" />
              <div>
                <h3>phpMyAdmin</h3>
                <p>Intermediate</p>
              </div>
            </article>
          </div>
        </div>
        
        <div class="details-container">
          <h2 class="experience-sub-title">Development Operations</h2>
          <div class="article-container">
            <article>
              <img src="./assets/googlecloud.png" alt="Experience icon" class="icon" />
              <div>
                <h3>Google Cloud</h3>
                <p>Basic</p>
              </div>
            </article>
            <article>
              <img src="./assets/githubdesktop.png" alt="Experience icon" class="icon" />
              <div>
                <h3>Github</h3>
                <p>Intermediate</p>
              </div>
            </article>
            <article>
              <img src="./assets/jira.png" alt="Experience icon" class="icon" />
              <div>
                <h3>Jira</h3>
                <p>Basic</p>
              </div>
            </article>
            <article>
              <img src="./assets/adobexd.png" alt="Experience icon" class="icon" />
              <div>
                <h3>Adobe Xd</h3>
                <p>Basic</p>
              </div>
            </article>
            <article>
              <img src="./assets/netbeans.png" alt="Experience icon" class="icon" />
              <div>
                <h3>Net Beans</h3>
                <p>Intermediate</p>
              </div>
            </article>
            <article>
              <img src="./assets/vsc.png" alt="Experience icon" class="icon" />
              <div>
                <h3>Visul Studio Code</h3>
                <p>Intermediate</p>
              </div>
            </article>
          </div>
        </div>
      </div>
    </div>
  </section> <!-- end of experience section -->

  <section id="projects">
    <p class="section__text__p1">Browse My Notable</p>
    <h1 class="title">PROJECTS</h1>
    <div class="experience-details-container">
      <div class="about-containers">
        <div class="details-container color-container">
          <div class="article-container">
            <img src="./assets/project-1.png" alt="Project 1" class="project-img" />
          </div>
          <h2 class="experience-sub-title project-title">H.A.R.A.Y.A</h2>
          <p class="project-description">
            A personal AI assistant that integrates cutting-edge technologies such as computer vision, 
            web data scraping, computer automation, natural language processing (NLP), and more.
          </p>
          <div class="btn-container">
            <button class="btn btn-color-2 project-btn"
              onclick="window.open('https://github.com/gpbacay/PROJECT_H.A.R.A.Y.A.git', '_blank')">
              Github
            </button>
            <button class="btn btn-color-2 project-btn"
              onclick="window.open('https://drive.google.com/drive/folders/1Ie63RLSyFu2rba0cxWoZIdQeHdSJqsPb?usp=sharing', '_blank')">
              See More...
            </button>
          </div>
        </div>
        <div class="details-container color-container">
          <div class="article-container">
            <img src="./assets/project-2.png" alt="Project 2" class="project-img" />
          </div>
          <h2 class="experience-sub-title project-title">G.O.D.S.E.Y.E.S</h2>
          <p class="project-description">
            An intelligent system designed to use advanced computer vision algorithms for face and pose detection.
          </p>
          <div class="btn-container">
            <button class="btn btn-color-2 project-btn"
              onclick="window.open('https://github.com/gpbacay/PROJECT-G.O.D.S.E.Y.E.S.git', '_blank')">
              Github
            </button>
            <button class="btn btn-color-2 project-btn"
              onclick="window.open('https://drive.google.com/drive/folders/13nPVQohEFfmCp4Mq65eRB3_zngs4sU3e?usp=sharing', '_blank')">
              See More...
            </button>
          </div>
        </div>
        <div class="details-container color-container">
          <div class="article-container">
            <img src="./assets/project-3.png" alt="Project 3" class="project-img" />
          </div>
          <h2 class="experience-sub-title project-title">ADF I.B.M.S</h2>
          <p class="project-description">
            A barbershop booking management system that simplifies appointments and enhances the customer experience.
          </p>
          <div class="btn-container">
            <button class="btn btn-color-2 project-btn"
              onclick="window.open('https://github.com/gpbacay/Data_Structures/tree/3e4d95ca9316038df88fa1af2fecc2530d90923f/LE1/ADF_IBMS', '_blank')">
              Github
            </button>
            <button class="btn btn-color-2 project-btn"
              onclick="window.open('https://drive.google.com/drive/folders/1jZvInutkbHSL0apMPt2M8YTLmWdrDDaS?usp=sharing', '_blank')">
              See More...
            </button>
          </div>
        </div>
        <div class="details-container color-container">
          <div class="article-container">
            <img src="./assets/project-4.png" alt="Project 1" class="project-img" />
          </div>
          <h2 class="experience-sub-title project-title">CLINITRACK B.C.I.M.S</h2>
          <p class="project-description">
            A platform for barangay clinics to track children's immunization, ensuring timely vaccinations.
          </p>
          <div class="btn-container">
            <button class="btn btn-color-2 project-btn"
              onclick="window.open('https://github.com/gpbacay/Pogramming-Paradigm-2/tree/138eed8ef272475a55a917c403b74cb6a0c3c127/LE1', '_blank')">
              Github
            </button>
            <button class="btn btn-color-2 project-btn"
              onclick="window.open('https://drive.google.com/drive/folders/1bLXSfHsxf1NPJoynW7Y9Wqtr0v18UT2l?usp=sharing', '_blank')">
              See More...
            </button>
          </div>
        </div>
      </div> <!-- end of about-containers -->
    </div>
  </section> <!-- end of prohjects section -->

  <section id="contact">
    <p class="section__text__p1">Get in Touch</p>
    <h1 class="title">CONTACT ME</h1>
    <div class="contact-info-upper-container">
      <div class="contact-info-container">
        <img src="./assets/email.png" alt="Email icon" class="icon contact-icon email-icon" />
        <p><a href="mailto:examplemail@gmail.com">giannebacay2004@gmail.com</a></p>
      </div>
      <div class="contact-info-container">
        <img src="./assets/linkedin.png" alt="LinkedIn icon" class="icon contact-icon" />
        <p>
          <a onclick="window.open('https://www.linkedin.com/in/gianne-bacay-195527290/', '_blank')">LinkedIn</a>
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

