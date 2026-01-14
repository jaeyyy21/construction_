<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Handle Add Folder
if (isset($_POST['add_folder'])) {
    $folder_name = $_POST['folder_name'];
    $sql = "INSERT INTO document_folders (folder_name) VALUES ('$folder_name')";
    $conn->query($sql);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Documents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { height: 100vh; width: 250px; position: fixed; right: 0; top: 0; background: white; border-left: 1px solid #ddd; padding-top: 20px; }
        .sidebar a { padding: 15px 25px; text-decoration: none; color: #333; display: block; border: 1px solid #eee; margin: 10px; text-align: center; border-radius: 5px;}
        .main-content { margin-right: 250px; padding: 40px; }
        .folder-card { background-color: #bbdefb; color: #0d47a1; padding: 40px; text-align: center; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.3s; border: 1px solid #90caf9; }
        .folder-card:hover { background-color: #90caf9; transform: scale(1.02); }
    </style>
</head>
<body>

<div class="sidebar">
    <h5 class="text-center mb-4">Admin Menu</h5>
    <a href="../admin/admin_dashboard.php">Project</a>
    <a href="../admin/timesheet.php">Timesheet</a>
    <a href="../admin/procurement.php">Procurement</a>
    <!--<a href="projects_accomplishment.php">Projects Accomplishment</a>-->
    <!--<a href="documents.php">Documents</a>-->
	<a href="../admin/work_directory.php">Work Directory</a>
    <a href="../auth/logout.php" class="text-danger border-danger">Logout</a>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Documents</h3>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#folderModal">ADD</button>
            <button class="btn btn-success">EXPORT</button>
        </div>
    </div>

    <div class="row g-4">
        <?php
        $sql = "SELECT * FROM document_folders";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            echo '<div class="col-md-3">
					<a href="../admin/project_management.php?id='.$row['id'].'" style="text-decoration:none;">
						<div class="project-folder">
							' . $row['project_name'] . '
						</div>
					</a>
				  </div>';
					}
        ?>
    </div>
</div>

<div class="modal fade" id="folderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5>Create New Folder</h5></div>
            <form method="POST">
                <div class="modal-body">
                    <input type="text" name="folder_name" class="form-control" placeholder="Folder Name (e.g., FOLDER 1)" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_folder" class="btn btn-success">SAVE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
</html>