<?php
session_start();
include '../includes/db.php';

// Access Control
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Projects List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { height: 100vh; width: 250px; position: fixed; right: 0; top: 0; background: white; border-left: 1px solid #ddd; padding-top: 20px; }
        .sidebar a { padding: 15px 25px; text-decoration: none; color: #333; display: block; border: 1px solid #eee; margin: 6px; text-align: center; border-radius: 5px;}
        .sidebar a:hover { background-color: #e3f2fd; }
        .main-content { margin-right: 250px; padding: 40px; }
        .project-card { background: #bbdefb; padding: 30px; text-align: center; border-radius: 5px; font-weight: bold; margin-bottom: 20px; cursor: pointer; transition: 0.3s; }
        .project-card:hover { background: #90caf9; }
    </style>
</head>
<body>

<div class="sidebar">
    <h5 class="text-center mb-4">Admin Menu</h5>
    <a href="../admin/admin_dashboard.php">Project</a>
    <a href="../admin/timesheet.php">Timesheet</a>
    <a href="../admin/procurement.php">Procurement</a>
    <a href="../admin/projects_accomplishment.php" class="bg-light fw-bold">Projects Accomplishment</a>
    <a href="../admin/documents.php">Documents</a>
    <a href="../admin/work_directory.php">Work Directory</a>
    <a href="../auth/logout.php" class="text-danger border-danger">Logout</a>
</div>

<div class="main-content">
    <h3>Projects</h3>
    <hr>
    
    <div class="row">
        <div class="col-md-3"><div class="project-card">PROJECT 1</div></div>
        <div class="col-md-3"><div class="project-card">PROJECT 2</div></div>
        <div class="col-md-3"><div class="project-card">PROJECT 3</div></div>
    </div>

</body>
</html>