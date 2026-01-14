<?php
session_start();
include '../includes/db.php';

// Access Control
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Handle Adding a New Project
if (isset($_POST['add_project'])) {
    $name = $_POST['project_name'];
    $loc = $_POST['location'];
    $manager = $_POST['site_manager'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $budget = $_POST['budget'];
    $status = $_POST['status'];

    $sql = "INSERT INTO projects (project_name, location, site_manager, start_date, end_date, budget, status) 
            VALUES ('$name', '$loc', '$manager', '$start', '$end', '$budget', '$status')";
    
    if ($conn->query($sql) === TRUE) {
        $msg = "Project Added Successfully!";
    } else {
        $msg = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Projects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { height: 100vh; width: 250px; position: fixed; right: 0; top: 0; background: white; border-left: 1px solid #ddd; padding-top: 20px; }
        .sidebar a { padding: 15px 25px; text-decoration: none; color: #333; display: block; border: 1px solid #eee; margin: 10px; text-align: center; border-radius: 5px;}
        .sidebar a:hover { background-color: #e3f2fd; }
        .main-content { margin-right: 250px; padding: 40px; }
        .project-folder { background-color: #bbdefb; color: #0d47a1; padding: 30px; text-align: center; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.3s; text-transform: uppercase; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .project-folder:hover { background-color: #90caf9; transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="sidebar">
    <h5 class="text-center mb-4">Admin Menu</h5>
    <a href="../admin/admin_dashboard.php" class="bg-light fw-bold">Project</a>
    <a href="../admin/timesheet.php">Timesheet</a>
    <a href="../admin/procurement.php">Procurement</a>
    <!--<a href="projects_accomplishment.php">Projects Accomplishment</a>-->
    <!--<a href="documents.php">Documents</a>-->
    <a href="../admin/work_directory.php">Work Directory</a>
    <a href="../auth/logout.php" class="text-danger border-danger">Logout</a>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Projects</h3>
        <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#addProjectModal">+ ADD</button>
    </div>

    <?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

    <div class="row g-4">
        <?php
        $sql = "SELECT * FROM projects ORDER BY id DESC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="col-md-3">
					<a href="../admin/project_management.php?id='.$row['id'].'" style="text-decoration:none;">
						<div class="project-folder">
							' . $row['project_name'] . '
						</div>
					</a>
				  </div>';
            }
        }
        ?>
    </div>
</div>

<div class="modal fade" id="addProjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Project Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="mb-3"><label>Project Name:</label><input type="text" name="project_name" class="form-control" required></div>
                    <div class="mb-3"><label>Location:</label><input type="text" name="location" class="form-control" required></div>
                    <div class="mb-3"><label>Site Manager:</label><input type="text" name="site_manager" class="form-control" required></div>
                    <div class="row mb-3">
                        <div class="col"><label>Start Date:</label><input type="date" name="start_date" class="form-control"></div>
                        <div class="col"><label>End Date:</label><input type="date" name="end_date" class="form-control"></div>
                    </div>
                    <div class="mb-3"><label>Budget:</label><input type="number" name="budget" class="form-control"></div>
                    <div class="mb-3">
                        <label>Status:</label>
                        <select name="status" class="form-select">
                            <option>Ongoing</option>
                            <option>Completed</option>
                            <option>Pending</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" name="add_project" class="btn btn-success">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>