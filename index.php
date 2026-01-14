<?php
session_start();
include 'includes/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_submit'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    $sql = "SELECT * FROM work_directory WHERE username = '$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role_designation'];
            
            if ($row['role_designation'] == 'Admin') {
                header("Location: /construction/admin/admin_dashboard.php");
            } else {
                header("Location: /construction/user/user_dashboard.php");
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
    <title>Construction System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background: white; border-bottom: 1px solid #ddd; padding: 1rem 2rem; }
        .hero-section { height: 80vh; display: flex; align-items: center; justify-content: center; }
        .about-box { background: white; padding: 100px; border: 1px solid #ccc; width: 80%; text-align: center; }
        /* Modal Styling to match your wireframe */
        .modal-content { border-radius: 20px; border: 2px solid #444; padding: 20px; }
    </style>
</head>
<body>

<nav class="navbar d-flex justify-content-between">
    <div class="fw-bold">CONSTRUCTION SITE MANAGEMENT SYSTEM</div>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
</nav>

<div class="container mt-4">
    <h6>About Page</h6>
    <div class="hero-section">
        <div class="about-box">
            <h3>CONSTRUCTION SITE MANAGEMENT SYSTEM</h3>
            <p class="text-muted">Image with about system descriptions</p>
        </div>
    </div>
</div>

<div class="modal fade" id="loginModal" tabindex="-1" <?php if($error) echo 'data-bs-show="true"'; ?>>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="text-end"><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="text-center mb-4">
                <h2 class="fw-bold">LogIn</h2>
            </div>

            <?php if($error): ?>
                <div class="alert alert-danger py-2 small text-center"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label>Username:</label>
                    <input type="text" name="username" class="form-control border-dark" required>
                </div>
                <div class="mb-3">
                    <label>Password:</label>
                    <input type="password" name="password" class="form-control border-dark" required>
                </div>
                <div class="d-flex justify-content-between mb-4 small">
                    <label><input type="checkbox"> Remember Me</label>
                    <a href="#" class="text-decoration-none">Forgot Password?</a>
                </div>
                <div class="text-center">
                    <button type="submit" name="login_submit" class="btn btn-primary px-5">LOGIN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto-open modal if there is a login error
    <?php if($error): ?>
    var myModal = new bootstrap.Modal(document.getElementById('loginModal'));
    myModal.show();
    <?php endif; ?>
</script>
</body>
</html>