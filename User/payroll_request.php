<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit();
}

$username = $_SESSION['username'];

// 1. Fetch worker details (Work Type & Rate)
$user_query = "SELECT * FROM work_directory WHERE username = '$username'";
$user_result = $conn->query($user_query);
$user_data = $user_result->fetch_assoc();

$full_name = $user_data['full_name'] ?? "";
$name_parts = explode(" ", $full_name);
$first_name = $name_parts[0] ?? "";
$last_name = (count($name_parts) > 1) ? end($name_parts) : "";
$role = $user_data['role_designation'] ?? "";
$work_type = $user_data['work_type'] ?? "Not Set";
$daily_rate = $user_data['daily_rate'] ?? 0;

// 2. FIXED QUERY: Count logs from the 'logbook' table instead of 'attendance'
// We match based on the 'firstname' column as seen in your SQL dump
$attendance_query = "SELECT COUNT(*) as total_days FROM logbook WHERE firstname = '$first_name'";
$attendance_result = $conn->query($attendance_query);
$attendance_data = $attendance_result->fetch_assoc();
$total_attendance = $attendance_data['total_days'] ?? 0;

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_request'])) {
    $allowance = $_POST['allowance'] ?? 0;
    $total = ($daily_rate * $total_attendance) + $allowance;

    $sql = "INSERT INTO payroll_requests (username, firstname, lastname, role, work_type, daily_rate, attendance_log, allowance, total_salary, status) 
            VALUES ('$username', '$first_name', '$last_name', '$role', '$work_type', '$daily_rate', '$total_attendance', '$allowance', '$total', 'Pending')";
    
    if ($conn->query($sql) === TRUE) {
        $notify = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payroll Request</title>
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
    </style>
</head>
<body>

<div class="sidebar">
	<div class="text-center mb-4"><i class="bi bi-person-circle fs-2"></i><br><?php echo $username; ?></div>
    <a href="#">Home</a>
    <a href="../user/user_dashboard.php">Logbook</a>
    <a href="../user/payroll_request.php" class="active">Payroll Request</a>
    <a href="../user/feedback.php">Feedback</a>
    <a href="../auth/logout.php" class="text-danger mt-5">Logout</a>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Payroll Request</h3>
    </div>

    <div class="request-card shadow-sm">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Date Requested</th>
                    <th>Days Worked</th>
                    <th>Total Salary</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $requests = $conn->query("SELECT * FROM payroll_requests WHERE username = '$username' ORDER BY id DESC");
                while($row = $requests->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                        <td><?php echo $row['attendance_log']; ?> days</td>
                        <td class="fw-bold">₱<?php echo number_format($row['total_salary'], 2); ?></td>
                        <td>
                            <span class="badge <?php echo ($row['status'] == 'Approved') ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="position-absolute bottom-0 end-0 m-4">
            <button class="btn btn-primary px-5 shadow" data-bs-toggle="modal" data-bs-target="#requestModal">
                <i class="bi bi-plus-lg"></i> CREATE REQUEST
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="requestModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Payroll Request Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 border-end">
                            <p class="section-header">Employment Info (Fixed)</p>
                            <div class="mb-2">
                                <label class="small text-muted">Name:</label>
                                <input type="text" class="form-control bg-light" value="<?php echo $full_name; ?>" readonly>
                            </div>
                            <div class="mb-2">
                                <label class="small text-muted">Work Type:</label>
                                <input type="text" class="form-control bg-light" value="<?php echo $work_type; ?>" readonly>
                            </div>
                            <div class="mb-2">
                                <label class="small text-muted">Daily Rate (₱):</label>
                                <input type="text" id="rate" class="form-control bg-light" value="<?php echo $daily_rate; ?>" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <p class="section-header">Calculation</p>
                            <div class="mb-2">
                                <label class="small text-muted">Attendance Log (Total Days):</label>
                                <input type="text" id="days" class="form-control bg-light fw-bold" value="<?php echo $total_attendance; ?>" readonly>
                            </div>
                            <div class="mb-2">
                                <label class="small text-muted">Allowance Total (If any):</label>
                                <input type="number" name="allowance" id="allowance" class="form-control" value="0" step="0.01" oninput="calculateTotal()">
                            </div>
                            <hr>
                            <div class="mb-2">
                                <label class="fw-bold">Total Estimated Salary:</label>
                                <input type="text" id="total_display" class="form-control bg-light fw-bold text-success fs-5" readonly value="₱ <?php echo number_format($daily_rate * $total_attendance, 2); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" name="send_request" class="btn btn-success px-4">SEND REQUEST</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function calculateTotal() {
    const rate = <?php echo $daily_rate; ?>;
    const days = <?php echo $total_attendance; ?>;
    const allowance = parseFloat(document.getElementById('allowance').value) || 0;
    const total = (rate * days) + allowance;
    document.getElementById('total_display').value = "₱ " + total.toLocaleString(undefined, {minimumFractionDigits: 2});
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>