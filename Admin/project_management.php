<?php
session_start();
include '../includes/db.php';

// Access Control
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../auth/login.php");
    exit();
}

$project_id = isset($_GET['id']) ? $_GET['id'] : exit("Project ID missing");
$msg = "";

// LOGIC: UPLOAD DOCUMENT
if (isset($_POST['upload_doc'])) {
    $target_dir = "uploads/docs/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    
    $file_name = basename($_FILES["project_file"]["name"]);
    $target_file = $target_dir . time() . "_" . $file_name;

    if (move_uploaded_file($_FILES["project_file"]["tmp_name"], $target_file)) {
        // Corrected table name based on your loop below: uploaded_files
        $sql = "INSERT INTO uploaded_files (folder_id, file_name, file_path, uploaded_at) VALUES ('$project_id', '$file_name', '$target_file', NOW())";
        $conn->query($sql);
        $msg = "File uploaded successfully!";
    }
}

// LOGIC: UPDATE GOOGLE SHEET SETTINGS
if (isset($_POST['update_sheet_settings'])) {
    $new_id = mysqli_real_escape_string($conn, $_POST['sheet_id']);
    $new_gid = mysqli_real_escape_string($conn, $_POST['tab_id']);
    
    $sql = "UPDATE projects SET google_sheet_id = '$new_id', google_sheet_tab_id = '$new_gid' WHERE id = '$project_id'";
    if($conn->query($sql)){
        $msg = "Project sheet settings updated!";
    }
}

// Fetch Project Data
$project_res = $conn->query("SELECT * FROM projects WHERE id = '$project_id'");
$project = $project_res->fetch_assoc();

$current_sheet_id = $project['google_sheet_id'];
$current_tab_id = $project['google_sheet_tab_id'] ?? '0';

$google_sheet_url = !empty($current_sheet_id) 
    ? "https://docs.google.com/spreadsheets/d/" . $current_sheet_id . "/edit?gid=" . $current_tab_id . "&rm=minimal&widget=false" 
    : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage | <?php echo $project['project_name']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, sans-serif; }

        /* Sidebar: Fixed Right */
        .sidebar { height: 100vh; width: 250px; position: fixed; right: 0; top: 0; background: white; border-left: 1px solid #ddd; padding-top: 20px; z-index: 1000; }
        .sidebar a { padding: 15px 25px; text-decoration: none; color: #333; display: block; border: 1px solid #eee; margin: 10px; text-align: center; border-radius: 8px; transition: 0.3s; }
        .sidebar a:hover { background-color: #e3f2fd; color: #0d6efd; border-color: #0d6efd; }
        .sidebar a.active-link { background-color: #f8f9fa; font-weight: bold; border-color: #0d6efd; color: #0d6efd; }

        /* Main Content shifted for Right Sidebar */
        .main-content { margin-right: 250px; margin-left: 0; padding: 30px; }

        /* Card Styling for split sections */
        .ui-card { background: #fff; border-radius: 12px; border: 1px solid #ccc; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; height: 750px; display: flex; flex-direction: column; }
        .ui-card-header { padding: 15px 20px; border-bottom: 1px solid #eee; background: #fff; display: flex; justify-content: space-between; align-items: center; }
        .ui-card-body { flex: 1; overflow-y: auto; padding: 0; }

        /* Excel View Specific */
        iframe { width: 100%; height: 100%; border: none; }
        
        .section-title { font-size: 0.85rem; font-weight: bold; text-transform: uppercase; color: #666; margin: 0; }
    </style>
</head>
<body>

<div class="sidebar shadow-sm">
    <h5 class="text-center mb-4">Admin Menu</h5>
    <a href="../admin/admin_dashboard.php" class="bg-light fw-bold">Project</a>
    <a href="../admin/timesheet.php">Timesheet</a>
    <a href="../admin/procurement.php">Procurement</a>
    <!--<a href="projects_accomplishment.php">Projects Accomplishment</a>-->
    <!--<a href="documents.php">Documents</a>-->
    <a href="../admin/work_directory.php">Work Directory</a>
    <a href="../admin/logout.php" class="text-danger border-danger">Logout</a>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="../admin/admin_dashboard.php" class="btn btn-light border me-3"><i class="bi bi-arrow-left"></i></a>
            <div>
                <h3 class="m-0 fw-bold"><?php echo strtoupper($project['project_name']); ?></h3>
                <span class="badge bg-success">Status: <?php echo $project['status']; ?></span>
            </div>
        </div>
        
        <div class="d-flex gap-2">
            <?php if ($current_sheet_id): ?>
                <a href="https://docs.google.com/spreadsheets/d/<?php echo $current_sheet_id; ?>/edit#gid=<?php echo $current_tab_id; ?>" 
                   target="_blank" class="btn btn-success btn-sm">
                    <i class="bi bi-pencil-square me-1"></i> Full Editor
                </a>
            <?php endif; ?>
            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#configModal">
                <i class="bi bi-gear"></i> Configure
            </button>
        </div>
    </div>

    <?php if($msg): ?>
        <div class="alert alert-success alert-dismissible fade show"><?php echo $msg; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="ui-card">
                <div class="ui-card-header">
                    <p class="section-title">Project Spreadsheet</p>
                </div>
                <div class="ui-card-body">
                    <?php if ($google_sheet_url): ?>
                        <iframe src="<?php echo $google_sheet_url; ?>"></iframe>
                    <?php else: ?>
                        <div class="text-center p-5">
                            <i class="bi bi-file-earmark-spreadsheet text-muted" style="font-size: 4rem;"></i>
                            <h4 class="mt-3">No Excel Sheet Linked</h4>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="ui-card">
                <div class="ui-card-header">
                    <p class="section-title">Project Documents</p>
                    <button class="btn btn-dark btn-sm py-0 px-2" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="bi bi-plus"></i> Upload
                    </button>
                </div>
                <div class="ui-card-body px-3 pt-3">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr style="font-size: 0.75rem;">
                                <th>Filename</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.85rem;">
                            <?php
                            $files = $conn->query("SELECT * FROM uploaded_files WHERE folder_id = '$project_id' ORDER BY uploaded_at DESC");
                            while($f = $files->fetch_assoc()): ?>
                            <tr>
                                <td class="text-truncate" style="max-width: 150px;"><?php echo $f['file_name']; ?></td>
                                <td class="text-end">
                                    <a href="<?php echo $f['file_path']; ?>" class="btn btn-sm btn-outline-primary p-1" download>
                                        <i class="bi bi-download"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if($files->num_rows == 0): ?>
                                <tr><td colspan="2" class="text-center text-muted py-4">No documents found</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="configModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header"><h5>Sheet Configuration</h5></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Google Sheet ID</label>
                    <input type="text" name="sheet_id" class="form-control" value="<?php echo $current_sheet_id; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Tab ID (gid)</label>
                    <input type="text" name="tab_id" class="form-control" value="<?php echo $current_tab_id; ?>" placeholder="e.g. 0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="update_sheet_settings" class="btn btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header"><h5>Upload File</h5></div>
            <div class="modal-body">
                <input type="file" name="project_file" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button type="submit" name="upload_doc" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>