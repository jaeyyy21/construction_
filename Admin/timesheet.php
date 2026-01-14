<?php
session_start();
include '../includes/db.php';
if ($_SESSION['role'] != 'Admin') { header("Location: ../auth/login.php"); exit(); }

if (isset($_POST['save_timesheet'])) {
    $first = $_POST['firstname'];
    $last = $_POST['lastname'];
    $role = $_POST['role'];
    $rate = $_POST['rate'];
    $hours = $_POST['hours'];
    $deduct = $_POST['deductions'];
    $total = $_POST['total'];

    $sql = "INSERT INTO timesheet (firstname, lastname, role, hourly_rate, hours_worked, deductions, total_salary)
            VALUES ('$first', '$last', '$role', '$rate', '$hours', '$deduct', '$total')";
    $conn->query($sql);
    $msg = "Timesheet Saved!";
}

// 1. Fetch user data for auto-fill
$username = $_SESSION['username'];
$user_query = "SELECT * FROM work_directory WHERE username = '$username'";
$user_result = $conn->query($user_query);
$user_data = $user_result->fetch_assoc();

$full_name = $user_data['full_name'] ?? "";
$name_parts = explode(" ", $full_name);
$first_name = $name_parts[0] ?? "";
$last_name = (count($name_parts) > 1) ? end($name_parts) : "";
$role = $user_data['role_designation'] ?? "";

// 2. Handle Time Out Update
if (isset($_POST['time_out_now'])) {
    $log_id = $_POST['log_id'];
    $current_time = date("H:i:s");
    $update_sql = "UPDATE logbook SET time_out = '$current_time' WHERE id = '$log_id'";
    $conn->query($update_sql);
    header("Location: ../admin/timesheet.php"); // Refresh to show changes
}

// 3. Handle New Log Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_log'])) {
    $fname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $role_input = mysqli_real_escape_string($conn, $_POST['role']);
    $consultant = mysqli_real_escape_string($conn, $_POST['consultant']);
    $date = $_POST['log_date'];
    $time_in = $_POST['time_in'];

    $sql = "INSERT INTO logbook (firstname, lastname, role_designation, consultant, log_date, time_in) 
            VALUES ('$fname', '$lname', '$role_input', '$consultant', '$date', '$time_in')";
    $conn->query($sql);
    header("Location: ../admin/timesheet.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Timesheet</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .sidebar { height: 100vh; width: 250px; position: fixed; right: 0; top: 0; background: white; border-left: 1px solid #ddd; padding-top: 20px; }
        .sidebar a { padding: 15px 25px; text-decoration: none; color: #333; display: block; border: 1px solid #eee; margin: 10px; text-align: center; border-radius: 5px;}
        .sidebar a:hover { background-color: #e3f2fd; }
        .main-content { margin-right: 250px; padding: 40px; }
        .form-box { background: white; padding: 30px; border-radius: 15px; border: 2px solid #333; max-width: 500px; margin: auto; }
		.dashboard-container { display: flex; gap: 20px; }
		.calendar-section { flex: 2; background: white; padding: 20px; border-radius: 10px; border: 1px solid #ccc; }
        .recent-logs-section { flex: 1.2; background: white; padding: 20px; border-radius: 10px; border: 1px solid #ccc; }
        .fc-day-past { background-color: #f0f0f0 !important; cursor: not-allowed !important; }
    </style>
    <script>
        function calculateSalary() {
            let rate = document.getElementById('rate').value || 0;
            let hours = document.getElementById('hours').value || 0;
            let deduct = document.getElementById('deduct').value || 0;
            let total = (rate * hours) - deduct;
            document.getElementById('total').value = total.toFixed(2);
        }
    </script>
</head>
<body style="background-color: #f4f6f9;">

<div class="sidebar">
    <h5 class="text-center mb-4">Admin Menu</h5>
    <a href="../admin/admin_dashboard.php" class="bg-light">Project</a>
    <a href="../admin/timesheet.php" class="bg-light fw-bold">Timesheet</a>
    <a href="../admin/procurement.php">Procurement</a>
    <!--<a href="projects_accomplishment.php">Projects Accomplishment</a>-->
    <!--<a href="documents.php">Documents</a>-->
    <a href="../admin/work_directory.php">Work Directory</a>
    <a href="../auth/logout.php" class="text-danger border-danger">Logout</a>
</div>

<div class="main-content">
    <h3>Logbook</h3>

    <div class="dashboard-container mt-4">
        <div class="calendar-section shadow-sm">
            <h5 class="mb-3 text-uppercase small fw-bold">Calendar View</h5>
            <div id="calendar"></div>
        </div>

        <div class="recent-logs-section shadow-sm">
            <h5 class="mb-3 text-uppercase small fw-bold">Recent Logs</h5>
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr class="x-small"><th>Date</th><th>In</th><th>Out</th><th>Action</th></tr>
                </thead>
                <tbody class="small">
                    <?php
                    $logs = $conn->query("SELECT * FROM logbook WHERE firstname = '$first_name' ORDER BY log_date DESC LIMIT 8");
                    while($row = $logs->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['log_date']; ?></td>
                            <td class="text-success fw-bold"><?php echo $row['time_in']; ?></td>
                            <td class="text-danger fw-bold"><?php echo $row['time_out'] ?? '--:--'; ?></td>
                            <td>
                                <?php if(empty($row['time_out'])): ?>
                                    <form method="POST">
                                        <input type="hidden" name="log_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="time_out_now" class="btn btn-sm btn-outline-danger py-0">Time-Out</button>
                                    </form>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Done</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="logModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Logbook Form</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="x-small fw-bold">Firstname:</label>
                            <input type="text" name="firstname" class="form-control bg-light" value="<?php echo $first_name; ?>" readonly>
                        </div>
                        <div class="col-6">
                            <label class="x-small fw-bold">Lastname:</label>
                            <input type="text" name="lastname" class="form-control bg-light" value="<?php echo $last_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="x-small fw-bold">Role/Designation:</label>
                        <input type="text" name="role" class="form-control bg-light" value="<?php echo $role; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="x-small fw-bold">Consultant:</label>
                        <input type="text" name="consultant" class="form-control">
                    </div>
                    <div class="p-3 bg-light border rounded">
                        <div class="mb-2">
                            <label class="small fw-bold">Date:</label>
                            <input type="date" name="log_date" id="modal_date" class="form-control border-0 bg-transparent fw-bold" readonly>
                        </div>
                        <div>
                            <label class="small fw-bold">Time In:</label>
                            <input type="time" name="time_in" id="modal_time_in" class="form-control border-0 bg-transparent fw-bold" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_log" class="btn btn-success w-100 fw-bold">SAVE LOG</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var today = new Date().toISOString().split('T')[0];

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 500,
        dateClick: function(info) {
            if (info.dateStr !== today) {
                alert("Attendance can only be logged for the current day.");
                return;
            }

            document.getElementById('modal_date').value = info.dateStr;
            const now = new Date();
            const currentTime = now.getHours().toString().padStart(2, '0') + ":" + 
                              now.getMinutes().toString().padStart(2, '0');
            document.getElementById('modal_time_in').value = currentTime;

            var myModal = new bootstrap.Modal(document.getElementById('logModal'));
            myModal.show();
        }
    });
    calendar.render();
});
</script>
</body>
</html>