<?php
// Fetching student status and details from the database
$approvalStatus = ''; // Fetch from database
$denyStatus = ''; // Fetch from database
$approvalData = []; // Fetch approved data from the database
$denyData = []; // Fetch denied data from the database

require_once('db.php');

// Assume the student's email is passed via GET or POST
$email = $_GET['email'] ?? '';  // Get the email parameter from URL

// Initialize status messages
$approvalStatus = 'Not Found';
$denyStatus = 'Not Found';

// Query to check if the email exists in the student_approval table
$queryApproval = "
    SELECT sa.*, s.email 
    FROM student_approval sa
    JOIN student s ON sa.studID = s.studID
    WHERE s.email = ?
";
$stmtApproval = mysqli_prepare($conn, $queryApproval);
mysqli_stmt_bind_param($stmtApproval, 's', $email);
mysqli_stmt_execute($stmtApproval);
$resultApproval = mysqli_stmt_get_result($stmtApproval);

// Query to check if the email exists in the student_deny table
$queryDeny = "
    SELECT sd.*, s.email 
    FROM deny_student sd
    JOIN student s ON sd.studID = s.studID
    WHERE s.email = ?
";
$stmtDeny = mysqli_prepare($conn, $queryDeny);
mysqli_stmt_bind_param($stmtDeny, 's', $email);
mysqli_stmt_execute($stmtDeny);
$resultDeny = mysqli_stmt_get_result($stmtDeny);

// Check if the email is found in the approval table
if (mysqli_num_rows($resultApproval) > 0) {
    $approvalStatus = 'Approved';
    $approvalData = mysqli_fetch_assoc($resultApproval);
}

// Check if the email is found in the deny table
if (mysqli_num_rows($resultDeny) > 0) {
    $denyStatus = 'Denied';
    $denyData = mysqli_fetch_assoc($resultDeny);
}

mysqli_stmt_close($stmtApproval);
mysqli_stmt_close($stmtDeny);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Approval Status</title>
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
          <a class="nav-link" href="userview.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="student_status.php?email=student@example.com">Student Approval Status</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.html">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <!-- Title -->
    <h1 class="mb-4">Student Approval Status</h1>


    <!-- Table for Approved Students -->
    <?php if ($approvalStatus === 'Approved'): ?>
    <h3>Approved Student</h3>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Approved By</th>
                <th>Approval Date</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $approvalData['approved_by']; ?></td>
                <td><?php echo $approvalData['approval_date']; ?></td>
            </tr>
        </tbody>
    </table>
    <?php endif; ?>

    <!-- Table for Denied Students -->
    <?php if ($denyStatus === 'Denied'): ?>
    <h3>Denied Student</h3>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Denied By</th>
                <th>Denial Date</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $denyData['denied_by']; ?></td>
                <td><?php echo $denyData['denial_date']; ?></td>
            </tr>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
