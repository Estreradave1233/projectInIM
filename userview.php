<?php
// Start the session
session_start();

// Assuming the email was saved in the session when the user logged in
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email']; // Get the email from session
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "improject";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get student information by email
$sql = "SELECT * FROM student WHERE email = '$email'";
$result = $conn->query($sql);

// Check if the student exists
if ($result->num_rows > 0) {
    // Fetch student data
    $student = $result->fetch_assoc();
} else {
    $student = null;
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Student Portal</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="home.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="student_status.php?email=<?php echo urlencode($email); ?>">Student Approval Status</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.html">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <h1 class="mb-4">Welcome, Student!</h1>

    <!-- User Info -->
    <?php if ($student): ?>
    <h3>Your Profile Information</h3>
    <p><strong>Email:</strong> <?php echo $student['email']; ?></p>
    <p><strong>Name:</strong> <?php echo  ucwords(strtolower($student['lname'])) . ' ' .ucwords(strtolower($student['firstName'])) .' ' . ucwords(strtolower($student['mname'])) ; ?></p>
    <p><strong>Course:</strong> <?php echo strtoupper($student['course']); ?></p>
    <p><strong>Student ID:</strong> <?php echo $student['studID']; ?></p>
    <?php else: ?>
    <p>Student not found.</p>
    <?php endif; ?>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
