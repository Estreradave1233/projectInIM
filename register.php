<?php
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $city = $_POST['city'];
    $municipality = $_POST['municipality'];
    $barangay = $_POST['barangay'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $password = $_POST['password'];

    //DATABASE CONNECTION
    $conn = new mysqli('localhost', 'root', '', 'improject');
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    } else {
        // Step 1: Check if the email already exists
        $check_query = $conn->prepare("SELECT email FROM student WHERE email = ?");
        $check_query->bind_param("s", $email);
        $check_query->execute();
        $check_query->store_result();

        if ($check_query->num_rows > 0) {
            // Email already exists in the database
            echo "Error: Email already registered. Please use a different email.";
        } else {
            // Step 2: Proceed with registration if email does not exist
            $stmt = $conn->prepare("INSERT INTO student (firstName, mname, lname, email, birthdate, gender, city, municipality, barangay, course, year ,password)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssss", $firstName, $middleName, $lastName, $email, $birthdate, $gender, $city, $municipality, $barangay, $course, $year , $password);
            
            if ($stmt->execute()) {
                // Redirect to login page after successful registration
                header("Location: index.html");
                exit();
            } else {
                echo "Registration failed. Please try again.";
            }
            
            $stmt->close();
        }

        $check_query->close();
        $conn->close();
    }
?>
