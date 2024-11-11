<?php
require_once('db.php');

// Check if studID is passed
if (isset($_GET['studID'])) {
    $studID = $_GET['studID'];
    $approved_by = "Admin"; // Set this to the name or ID of the person approving

    // Insert into student_approval table
    $query = "INSERT INTO student_approval (studID, approved_by) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "is", $studID, $approved_by);

    if (mysqli_stmt_execute($stmt)) {
        echo "Student approved successfully.";
    } else {
        echo "Error approving student: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo "Invalid student ID.";
}
?>
