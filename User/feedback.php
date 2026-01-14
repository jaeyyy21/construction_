<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit();
}

$username = $_SESSION['username'];
$notify = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_feedback'])) {
    $message = mysqli_real_escape_string($conn, $_POST['feedback_text']);
    if (!empty($message)) {
        $sql = "INSERT INTO feedback (username, message, status) VALUES ('$username', '$message', 'Unread')";
        if ($conn->query($sql)) {
            $notify = true;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; }
        /* Sidebar matches your wireframe (Right Side) */
        .sidebar { height: 100vh; width: 250px; position: fixed; background: white; border-right: 1px solid #ddd; }
        .sidebar a { padding: 15px 25px; text-decoration: none; color: #333; display: block; }
        .sidebar a.active { background-color: #e3f2fd; font-weight: bold; border-right: 4px solid #0d6efd; }
        .main-content { margin-right: 20px; margin-left: 250px; padding: 40px; }
        .request-card { background: white; border-radius: 10px; min-height: 500px; padding: 20px; border: 1px solid #ccc; position: relative; }
        .section-header { font-size: 0.8rem; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #eee; margin-bottom: 15px; color: #666; }
        
        textarea { 
            width: 100%; 
            border: 1px solid #dee2e6; 
            border-radius: 5px; 
            padding: 15px; 
            min-height: 300px; 
            outline: none; 
            resize: none; 
        }

        .notify-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.3); display: flex; justify-content: center; align-items: center; z-index: 2000; }
        .notify-box { background: white; border-radius: 15px; padding: 40px 80px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 1px solid #333; }
        .ok-btn { background: #e3f2fd; border: 1px solid #333; padding: 5px 20px; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="text-center mb-4">
        <i class="bi bi-person-circle fs-2"></i><br>
        <?php echo $username; ?>
    </div>
    <a href="#">Home</a>
    <a href="../user/user_dashboard.php">Logbook</a>
    <a href="../user/payroll_request.php">Payroll Request</a>
    <a href="../user/feedback.php" class="active">Feedback</a>
    <a href="../auth/logout.php" class="text-danger mt-5">Logout</a>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Feedback</h3>
    </div>

    <div class="request-card shadow-sm">
        <p class="section-header">SEND FEEDBACK TO ADMIN</p>
        <form method="POST">
            <div class="mb-3">
                <label class="text-muted small mb-2">How can we improve the system? Write your message below:</label>
                <textarea name="feedback_text" placeholder="Type your concerns, suggestions, or feedback here..." required></textarea>
            </div>
            
            <div class="position-absolute bottom-0 end-0 m-4">
                <button type="submit" name="submit_feedback" class="btn btn-primary px-5 shadow">
                    SUBMIT
                </button>
            </div>
        </form>
    </div>
</div>

<?php if($notify): ?>
<div class="notify-overlay" id="notifyOverlay">
    <div class="notify-box">
        <h4 class="mb-0">Request Sent!</h4>
        <button class="ok-btn" onclick="document.getElementById('notifyOverlay').style.display='none'">OK</button>
    </div>
</div>
<?php endif; ?>

</body>
</html>