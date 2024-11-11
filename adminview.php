<?php
require_once('db.php');

// Fetch all students
$query = "SELECT * FROM student WHERE studID NOT IN (SELECT studID FROM student_approval) and studID not in (select studID from deny_student)";
$result = mysqli_query($conn, $query);

$querys = "SELECT s.studID, s.firstName, s.mname, s.lname, s.email, s.course, s.year, sa.approval_date, sa.approved_by 
          FROM student s
          INNER JOIN student_approval sa ON s.studID = sa.studID
          WHERE sa.approved_by IS NOT NULL";
$results = mysqli_query($conn, $querys);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .content-section {
            display: none; /* Hide all content sections by default */
            padding: 20px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="d-flex">
        <div class="bg-dark text-white vh-100 p-3" style="width: 250px;">
            <h2 class="text-center">Admin Dashboard</h2>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white" href="#" onclick="showSection('home')">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#" onclick="showSection('manageStudents')">Manage Students</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#" onclick="showSection('manageSection')">Manage Section</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="index.html" onclick="showSection('logout')">Logout</a>
                </li>
            </ul>
        </div>
        
        <!-- Main content area -->
        <div class="flex-grow-1">
            <div id="home" class="content-section">
                <h1>Home</h1>
                <p>Welcome to the admin dashboard.</p>
            </div>

            <div id="manageStudents" class="content-section">
                <h1>Manage Students</h1>
                <p>Here you can manage student records and information.</p>
                <div class="container">
                    <div class="row mt-5">
                        <div class="col">
                            <div class="card mt-5">
                                <div class="card-header">
                                    <h2 class="display-6 text-center">Students</h2>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr class="bg-dark text-white">
                                            <td>Student ID</td>
                                            <td>First Name</td>
                                            <td>Middle Name</td>
                                            <td>Last Name</td>
                                            <td>Email</td>
                                            <td>Course</td>
                                            <td>Year</td>
                                            <td>Approve</td>
                                            <td>Deny</td>
                                        </tr>
                                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>  
                                            <tr id="studentRow_<?php echo $row['studID']; ?>">
                                                <td><?php echo $row['studID']; ?></td>
                                                <td><?php echo $row['firstName']; ?></td>
                                                <td><?php echo $row['mname']; ?></td>
                                                <td><?php echo $row['lname']; ?></td>
                                                <td><?php echo $row['email']; ?></td>
                                                <td><?php echo $row['course']; ?></td>
                                                <td><?php echo $row['year']; ?></td>
                                                <td>
                                                    <a href="#" class="btn btn-primary" onclick="approveStudent('<?php echo $row['studID']; ?>')">Approve</a>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-danger" onclick="denyStudent('<?php echo $row['studID']; ?>')">Deny</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="manageSection" class="content-section" onclick="refreshManageSection()">
                <h1>Manage Section</h1>
                <p>Here you can manage sections within the dashboard.</p>
                <p id="approvedStudentsList"></p> <!-- Display approved student IDs here -->
                <div class="container">
                    <div class="row mt-5">
                        <div class="col">
                            <div class="card mt-5">
                                <div class="card-header">
                                    <h2 class="display-6 text-center">Approved Students</h2>
                                </div>
                                <div class="card-body">
                                <table class="table table-bordered" id="approvedStudentsTable">
                                    <tr class="bg-dark text-white">
                                        <td>Student ID</td>
                                        <td>First Name</td>
                                        <td>Middle Name</td>
                                        <td>Last Name</td>
                                        <td>Email</td>
                                        <td>Course</td>
                                        <td>Year</td>
                                        <td>Approval Date</td>
                                        <td>Approved By</td>
                                    </tr>
                                    <?php while ($row = mysqli_fetch_assoc($results)) { ?>
                                        <tr>
                                            <td><?php echo $row['studID']; ?></td>
                                            <td><?php echo $row['firstName']; ?></td>
                                            <td><?php echo $row['mname']; ?></td>
                                            <td><?php echo $row['lname']; ?></td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td><?php echo $row['course']; ?></td>
                                            <td><?php echo $row['year']; ?></td>
                                            <td><?php echo $row['approval_date']; ?></td>
                                            <td><?php echo $row['approved_by']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to show sections
        function showSection(sectionId) {
            const sections = document.querySelectorAll('.content-section');

            // Hide all sections
            sections.forEach(section => {
                section.style.display = 'none';
            });

            // Show the selected section
            const section = document.getElementById(sectionId);
            if (section) {
                section.style.display = 'block';
            }
        }

        function approveStudent(studID) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "student_approval.php?studID=" + studID, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert(xhr.responseText);

                    // Remove the row from the table
                    const row = document.getElementById("studentRow_" + studID);
                    if (row) {
                        row.remove();
                    }
                } else {
                    alert("Error approving student.");
                }
            };
            xhr.send();
        }

        function approveStudent(studID) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "student_approval.php?studID=" + studID, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Check the response from PHP
                    if (xhr.responseText === "Student approved successfully.") {
                        alert(xhr.responseText);
                        
                        // Reload the page to reflect the changes
                        location.reload();
                    } else {
                        alert(xhr.responseText); // Error message from PHP
                    }
                } else {
                    alert("Error approving student.");
                }
            };
            xhr.send();
        }
        function denyStudent(studID) {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "student_deny.php?studID=" + studID, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert(xhr.responseText); // Success or error message from PHP

                    // Remove the row from the table
                    const row = document.getElementById("studentRow_" + studID);
                    if (row) {
                        row.remove();
                    }
                } else {
                    alert("Error denying student.");
                }
            };
            xhr.send();
        }



        // Default section to display
        showSection('home');
    </script>
</body>
</html>
