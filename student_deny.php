<?php
require_once('db.php');

// Check if studID is passed
if (isset($_GET['studID'])) {
    $studID = $_GET['studID'];
    $denied_by = "Admin"; // Set this to the name or ID of the person denying

    // Insert into deny_student table
    $query = "INSERT INTO deny_student (studID, denied_by) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "is", $studID, $denied_by);

    if (mysqli_stmt_execute($stmt)) {
        echo "Student denied successfully.";
    } else {
        echo "Error denying student: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo "Invalid student ID.";
}
?>
