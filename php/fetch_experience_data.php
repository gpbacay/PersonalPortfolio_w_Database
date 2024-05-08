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

    // Perform a SELECT query to fetch data from the experience_section table
    $query = "SELECT icon_path, technology_name, proficiency_level FROM experience_section";
    $result = $conn->query($query);

    // Initialize an array to store the fetched data
    $experienceData = array();

    // Fetch associative array
    while ($row = $result->fetch_assoc()) {
        // Append each row to the experienceData array
        $experienceData[] = $row;
    }

    // Echo the data as JSON
    echo json_encode($experienceData);

    // Close the database connection
    $conn->close();
?>
