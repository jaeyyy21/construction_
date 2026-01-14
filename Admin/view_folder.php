<?php
session_start();
include '../includes/db.php';

$folder_id = $_GET['id'];
$folder_sql = "SELECT folder_name FROM document_folders WHERE id = $folder_id";
$folder_name = $conn->query($folder_sql)->fetch_assoc()['folder_name'];

// Handle File Upload
if (isset($_POST['upload'])) {
    $target_dir = "uploads/docs/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    
    $file_name = basename($_FILES["doc_file"]["name"]);
    $target_path = $target_dir . $file_name;
    
    if (move_uploaded_file($_FILES["doc_file"]["tmp_name"], $target_path)) {
        $sql = "INSERT INTO uploaded_files (folder_id, file_name, file_path) VALUES ('$folder_id', '$file_name', '$target_path')";
        $conn->query($sql);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $folder_name; ?> - Files</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .main-content { padding: 40px; }
        .file-list { background: white; border: 1px solid #ccc; min-height: 300px; border-radius: 10px; padding: 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; }
    </style>
</head>
<body>

<div class="container main-content text-center">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="../admin/documents.php" class="btn btn-outline-secondary">← Back</a>
        <h2><?php echo $folder_name; ?></h2>
        <div></div>
    </div>

    <div class="file-list">
        <h1 class="text-muted mb-4">Files Uploaded</h1>
        
        <table class="table table-hover text-start">
            <tbody>
                <?php
                $files = $conn->query("SELECT * FROM uploaded_files WHERE folder_id = $folder_id");
                while($f = $files->fetch_assoc()) {
                    echo "<tr><td>📄 {$f['file_name']}</td><td class='text-end'><a href='{$f['file_path']}' class='btn btn-sm btn-info' download>Download</a></td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="mt-auto pt-4 w-100 d-flex justify-content-center gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">Upload File</button>
            <button class="btn btn-success">EXPORT</button>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5>Upload Document</h5></div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="file" name="doc_file" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="upload" class="btn btn-primary">UPLOAD</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>