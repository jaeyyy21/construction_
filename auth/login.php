<?php
session_start();
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    // Search for the user in the work_directory table
    $sql = "SELECT * FROM work_directory WHERE username = '$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify the hashed password
        if (password_verify($pass, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['role'] = $row['role_designation'];

            // Route based on Role
			if ($row['role_designation'] == 'Admin') {
				// ../ means "go back to the construction folder", then enter Admin
				header("Location: ../admin/admin_dashboard.php");
			} else {
				// ../ means "go back to the construction folder", then enter User
				header("Location: ../user/user_dashboard.php");
			}
			exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Username not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Construction System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 380px; }
        .btn-login { background-color: #343a40; color: white; border: none; padding: 10px; }
        .btn-login:hover { background-color: #23272b; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <h4>Construction System</h4>
        <p class="text-muted">Sign in to your account</p>
    </div>
    
    <?php if(isset($error)): ?>
        <div class="alert alert-danger py-2 text-center"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required placeholder="Enter username">
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required placeholder="Enter password">
        </div>
        <button type="submit" class="btn btn-login w-100">Login</button>
    </form>
</div>

</body>
</html>