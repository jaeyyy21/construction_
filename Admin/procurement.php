<?php
session_start();
include '../includes/db.php';

if ($_SESSION['role'] != 'Admin') { header("Location: ../auth/login.php"); exit(); }

// Handle Form Submission
if (isset($_POST['save_procurement'])) {
    $project = $_POST['project'];
    $location = $_POST['location'];
    $item = $_POST['item'];
    $unit = $_POST['unit'];
    $status = $_POST['status'];
    $req_by = $_POST['req_by'];
    $app_by = $_POST['app_by'];
    $rec_by = $_POST['rec_by'];
    $del_by = $_POST['del_by'];

    // Image Upload Logic
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir);
    $target_file = $target_dir . basename($_FILES["proof_image"]["name"]);
    move_uploaded_file($_FILES["proof_image"]["tmp_name"], $target_file);

    $sql = "INSERT INTO procurement (project_name, location, item_name, unit, status, requested_by, approved_by, received_by, delivered_by, proof_image)
            VALUES ('$project', '$location', '$item', '$unit', '$status', '$req_by', '$app_by', '$rec_by', '$del_by', '$target_file')";
    
    $conn->query($sql);
    $msg = "Procurement Saved!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Procurement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
			height: 100vh;
			width: 250px;
			position: fixed;
			right: 0;
			top: 0;
			background: white;
			border-left: 1px solid #ddd;
			padding-top: 20px; 
		}
        .sidebar a {
			padding: 15px 25px;
			text-decoration: none;
			color: #333;
			display: block;
			border: 1px solid #eee;
			margin: 10px;
			text-align: center;
			border-radius: 5px;
		}
        .sidebar a:hover {
			background-color: #e3f2fd;
		}
        .main-content {
			margin-right: 250px;
			padding: 40px; 
		}
        .form-box {
			background: white;
			padding: 30px;
			border-radius: 15px;
			border: 1px solid #ccc;
			max-width: 800px;
		}
    </style>
</head>
<body style="background-color: #f4f6f9;">

<div class="sidebar">
    <h5 class="text-center mb-4">Admin Menu</h5>
    <a href="../admin/admin_dashboard.php" class="bg-light">Project</a>
    <a href="../admin/timesheet.php">Timesheet</a>
    <a href="../admin/procurement.php" class="bg-light fw-bold">Procurement</a>
    <!--<a href="projects_accomplishment.php">Projects Accomplishment</a>-->
    <!--<a href="documents.php">Documents</a>-->
    <a href="../admin/work_directory.php">Work Directory</a>
    <a href="../auth/logout.php" class="text-danger border-danger">Logout</a>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between">
        <h3>Procurement Request and Status</h3>
        <button class="btn btn-success" onclick="document.getElementById('procForm').style.display='block'">+ ADD</button>
    </div>

    <div id="procForm" class="mt-4 form-box" style="display: <?php echo isset($msg) ? 'none' : 'block'; ?>;">
        <h4 class="text-center mb-4">Procurement Form</h4>
        <?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Project Name:</label>
                    <select name="project" class="form-select">
                        <option>Project 1</option>
                        <option>Project 2</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Requested By:</label>
                    <input type="text" name="req_by" class="form-control">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label>Location:</label>
                    <input type="text" name="location" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Approved By:</label>
                    <input type="text" name="app_by" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Item:</label>
                    <input type="text" name="item" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Received By:</label>
                    <input type="text" name="rec_by" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Unit (kg, pcs):</label>
                    <input type="text" name="unit" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Delivered By:</label>
                    <input type="text" name="del_by" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Status:</label>
                    <select name="status" class="form-select">
                        <option>Pending</option>
                        <option>Delivered</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Proof of Delivery:</label>
                    <input type="file" name="proof_image" class="form-control">
                </div>
            </div>
            <div class="text-end">
                <button type="button" class="btn btn-secondary">CANCEL</button>
                <button type="submit" name="save_procurement" class="btn btn-success">SAVE</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>