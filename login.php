<?php
    session_start();

    // Check if the user is already logged in
    if (isset($_SESSION["username"])) {
        // Redirect to admin_view page
        header("Location: admin_view.php");
        exit();
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Establish connection to the database
        $conn = new mysqli("localhost", "root", "", "portfolio_db");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Retrieve form data
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Query database for user
        $sql = "SELECT * FROM credentials WHERE username='$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["password"])) {
                // Password is correct, retrieve profile data
                $sql_profile = "SELECT p.*, a.*, e.*, pr.*, c.* 
                FROM credentials AS c
                LEFT JOIN profile_section AS p ON c.id = p.id
                LEFT JOIN about_section AS a ON c.id = a.id
                LEFT JOIN experience_section AS e ON c.id = e.id
                LEFT JOIN projects_section AS pr ON c.id = pr.id
                WHERE c.username='$username'";
                $result_profile = $conn->query($sql_profile);

                if ($result_profile->num_rows > 0) {
                    $row_profile = $result_profile->fetch_assoc();
                    // Store user data in session variables
                    $_SESSION["username"] = $username;
                    $_SESSION['user_id'] = $row_profile['id'];
                    $_SESSION["full_name"] = $row_profile["name"];
                    $_SESSION["title"] = $row_profile["title"];
                    $_SESSION["biography"] = $row_profile["biography"];
                    $_SESSION["profile_picture1"] = $row_profile["profile_picture1"];
                    $_SESSION["profile_picture2"] = $row_profile["profile_picture2"];
                    // Add other session variables for each section
                    $_SESSION["cv_link"] = $row_profile["cv_link"];
                    $_SESSION["fb_link"] = $row_profile["fb_link"];
                    $_SESSION["tt_link"] = $row_profile["tt_link"];
                    $_SESSION["li_link"] = $row_profile["li_link"];
                    $_SESSION["gh_link"] = $row_profile["gh_link"];
                    $_SESSION["technology_name"] = $row_profile["technology_name"];
                    $_SESSION["proficiency_level"] = $row_profile["proficiency_level"];
                    $_SESSION["icon_path"] = $row_profile["icon_path"];
                    $_SESSION["thumbnail"] = $row_profile["thumbnail"];
                    $_SESSION["projTitle"] = $row_profile["projTitle"];
                    $_SESSION["projDesc"] = $row_profile["projDesc"];
                    $_SESSION["projGh"] = $row_profile["projGh"];
                    $_SESSION["projGdrive"] = $row_profile["projGdrive"];
                    $_SESSION["gmail_link"] = $row_profile["gmail_link"];
                    // Redirect to admin_view page
                    header("Location: admin_view.php");
                    exit();
                } else {
                    echo "Error retrieving profile data.";
                }
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "Account does not exist!";
        }

        // Close the database connection
        $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <h2>Login</h2>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <input type="submit" value="Login">
    </form>

    <br>

    <form action="user_view.php" method="get">
        <button type="submit">Go to User View</button>
    </form>
</body>
</html>




