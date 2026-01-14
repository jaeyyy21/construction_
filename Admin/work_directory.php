<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../auth/login.php");
    exit();
}

$view = isset($_GET['view']) ? $_GET['view'] : 'selection';
$msg = "";

// --- LOGIC: SAVE NEW USER ---
if (isset($_POST['save_user'])) {
    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $work = mysqli_real_escape_string($conn, $_POST['work_type']);
    $rate = $_POST['daily_rate'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    // NEW CREDENTIAL FIELDS
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure hashing

    $sql = "INSERT INTO work_directory (full_name, role_designation, work_type, daily_rate, email, phone_no, username, password) 
            VALUES ('$name', '$role', '$work', '$rate', '$email', '$phone', '$username', '$password')";
    
    if ($conn->query($sql)) { 
        $msg = "User added to directory with login credentials."; 
    } else {
        $msg = "Error: " . $conn->error;
    }
}

// --- LOGIC: UPDATE USER ---
if (isset($_POST['update_user'])) {
    $id = $_POST['user_id'];
    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $work = mysqli_real_escape_string($conn, $_POST['work_type']);
    $rate = $_POST['daily_rate'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);

    $sql = "UPDATE work_directory SET 
            full_name='$name', 
            role_designation='$role', 
            work_type='$work', 
            daily_rate='$rate', 
            email='$email', 
            phone_no='$phone',
            username='$username' 
            WHERE id='$id'";
    
    $conn->query($sql);

    // Update password only if the field is not empty
    if (!empty($_POST['password'])) {
        $new_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $conn->query("UPDATE work_directory SET password='$new_pass' WHERE id='$id'");
    }

    $msg = "User information updated.";
}

// --- LOGIC: APPROVE PAYROLL ---
if (isset($_POST['approve_payroll'])) {
    $pay_id = $_POST['pay_id'];
    $admin_name = $_SESSION['username'];
    $today = date('Y-m-d');
    $sql = "UPDATE payroll_requests SET status='Approved', approved_by='$admin_name', date_approved='$today' WHERE id='$pay_id'";
    if ($conn->query($sql)) { $msg = "Payroll request approved successfully."; }
}

// --- LOGIC: MARK FEEDBACK AS READ ---
if (isset($_POST['mark_read'])) {
    $f_id = $_POST['feedback_id'];
    $conn->query("UPDATE feedback SET status='Read' WHERE id='$f_id'");
    $msg = "Feedback marked as read.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Work Directory - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { height: 100vh; width: 250px; position: fixed; right: 0; top: 0; background: white; border-left: 1px solid #ddd; padding-top: 20px; }
        .sidebar a { padding: 15px 25px; text-decoration: none; color: #333; display: block; border: 1px solid #eee; margin: 10px; text-align: center; border-radius: 5px;}
        .main-content { margin-right: 250px; padding: 40px; }
        .directory-card { background: white; border-radius: 10px; padding: 20px; border: 1px solid #ccc; }
        .folder-box { text-align: center; padding: 40px 20px; background: white; border: 1px solid #ddd; border-radius: 10px; text-decoration: none; color: #333; display: block; height: 100%; transition: 0.3s; }
        .folder-box:hover { background-color: #e3f2fd; transform: translateY(-5px); border-color: #0d6efd; }
        .folder-box i { font-size: 50px; color: #ffca28; }
        .section-header { font-size: 0.8rem; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #eee; margin-bottom: 15px; color: #666; margin-top:15px; }
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
    <a href="../admin/work_directory.php" class="bg-light fw-bold">Work Directory</a>
    <a href="../auth/logout.php" class="text-danger border-danger">Logout</a>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Work Directory <?php echo ($view != 'selection') ? " > " . ucfirst(str_replace('_', ' ', $view)) : ""; ?></h3>
        <div>
            <?php if($view == 'users'): ?>
                <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#userModal">+ ADD USER</button>
            <?php endif; ?>
            <?php if($view != 'selection'): ?>
                <a href="../admin/work_directory.php" class="btn btn-secondary px-4 ml-2">BACK</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if($msg): ?>
        <div class="alert alert-success alert-dismissible fade show"><?php echo $msg; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <?php if($view == 'selection'): ?>
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <a href="../admin/work_directory.php?view=users" class="folder-box shadow-sm">
                    <i class="bi bi-person-badge"></i><h5 class="mt-2">User Management</h5>
                </a>
            </div>
            <div class="col-md-3">
                <a href="../admin/work_directory.php?view=attendance_logs" class="folder-box shadow-sm">
                    <i class="bi bi-calendar-check" style="color: #6c757d;"></i><h5 class="mt-2">Attendance Logs</h5>
                </a>
            </div>
            <div class="col-md-3">
                <a href="../admin/work_directory.php?view=payroll" class="folder-box shadow-sm">
                    <i class="bi bi-cash-stack"></i><h5 class="mt-2">Payroll Handling</h5>
                </a>
            </div>
            <div class="col-md-3">
                <a href="../admin/work_directory.php?view=feedback" class="folder-box shadow-sm">
                    <i class="bi bi-chat-left-dots" style="color: #0d6efd;"></i><h5 class="mt-2">User Feedback</h5>
                </a>
            </div>
        </div>
		
	<?php elseif($view == 'users'): ?>
        <div class="directory-card shadow-sm">
            <table class="table table-hover align-middle">
                <thead>
                    <tr><th>Full Name</th><th>Username</th><th>Role/Work</th><th>Rate</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM work_directory");
                    while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['full_name']; ?></td>
                            <td><code class="text-primary"><?php echo $row['username']; ?></code></td>
                            <td><?php echo $row['role_designation']; ?><br><small class="text-muted"><?php echo $row['work_type']; ?></small></td>
                            <td>₱<?php echo number_format($row['daily_rate'], 2); ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary edit-btn" 
                                    data-id="<?php echo $row['id']; ?>" 
                                    data-name="<?php echo $row['full_name']; ?>" 
                                    data-role="<?php echo $row['role_designation']; ?>" 
                                    data-work="<?php echo $row['work_type']; ?>" 
                                    data-rate="<?php echo $row['daily_rate']; ?>" 
                                    data-email="<?php echo $row['email']; ?>" 
                                    data-phone="<?php echo $row['phone_no']; ?>"
                                    data-username="<?php echo $row['username']; ?>">
                                    <i class="bi bi-pencil"></i> EDIT
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
	
	<?php elseif($view == 'attendance_logs'): ?>
        <div class="directory-card shadow-sm">
            <h5>Logbook Summary (From User Side)</h5>
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Date</th>
                        <th>In</th>
                        <th>Out</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM logbook ORDER BY log_date DESC";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['firstname']." ".$row['lastname']; ?></td>
                            <td><?php echo $row['role_designation']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['log_date'])); ?></td>
                            <td class="text-success"><?php echo $row['time_in']; ?></td>
                            <td class="text-danger"><?php echo $row['time_out']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if($result->num_rows == 0) echo "<tr><td colspan='5' class='text-center py-4'>No logs found</td></tr>"; ?>
                </tbody>
            </table>
        </div>
		
	<?php elseif($view == 'payroll'): ?>
        <div class="directory-card shadow-sm">
            <table class="table table-hover align-middle">
                <thead>
                    <tr><th>Employee</th><th>Work Type</th><th>Total Salary</th><th>Status</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php
                    $pays = $conn->query("SELECT * FROM payroll_requests WHERE status='Pending' ORDER BY created_at DESC");
                    while($p = $pays->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $p['firstname']." ".$p['lastname']; ?></td>
                            <td><?php echo $p['work_type']; ?></td>
                            <td class="fw-bold text-primary">₱<?php echo number_format($p['total_salary'], 2); ?></td>
                            <td><span class="badge bg-warning text-dark"><?php echo $p['status']; ?></span></td>
                            <td>
                                <button class="btn btn-sm btn-info text-white review-btn" 
                                    data-id="<?php echo $p['id']; ?>"
                                    data-name="<?php echo $p['firstname'].' '.$p['lastname']; ?>"
                                    data-role="<?php echo $p['role']; ?>"
                                    data-work="<?php echo $p['work_type']; ?>"
                                    data-rate="<?php echo $p['daily_rate']; ?>"
                                    data-attendance="<?php echo $p['attendance_log']; ?>"
                                    data-allowance="<?php echo $p['allowance']; ?>"
                                    data-total="<?php echo $p['total_salary']; ?>">
                                    <i class="bi bi-eye"></i> REVIEW
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if($pays->num_rows == 0) echo "<tr><td colspan='5' class='text-center py-4'>No pending requests</td></tr>"; ?>
                </tbody>
            </table>
        </div>

	 <?php elseif($view == 'feedback'): ?>
			<div class="directory-card shadow-sm">
				<table class="table table-hover align-middle">
					<thead class="table-light">
						<tr><th>Message</th><th>Date</th><th>Action</th></tr>
					</thead>
					<tbody>
						<?php
						$feedbacks = $conn->query("SELECT * FROM feedback ORDER BY created_at DESC");
						while($f = $feedbacks->fetch_assoc()): ?>
							<tr class="<?php echo ($f['status'] == 'Unread') ? 'table-primary' : ''; ?>">
								
								<td><?php echo htmlspecialchars($f['message']); ?></td>
								<td><small><?php echo date('M d, Y', strtotime($f['created_at'])); ?></small></td>
								<td>
									<?php if($f['status'] == 'Unread'): ?>
										<form method="POST"><input type="hidden" name="feedback_id" value="<?php echo $f['id']; ?>">
										<button type="submit" name="mark_read" class="btn btn-sm btn-success">Mark Read</button></form>
									<?php else: ?>
										<span class="text-muted small">Read</span>
									<?php endif; ?>
								</td>
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</div>

<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5>Add New Staff</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3"><label>Full Name</label><input type="text" name="full_name" class="form-control" required></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>Role</label>
							<select name="role" class="form-select">
								<option value="Worker">Worker</option>
								<option value="Admin">Admin</option>
							</select>
						</div>
                        <div class="col-md-6 mb-3"><label>Position</label>
							<select name="work_type" class="form-select">
								<option>Construction</option>
                                <option>Engineer</option>
                                <option>Foreman</option>
                                <option>Site Manager</option>
                                <option>Timekeeper</option>
							</select>
						</div>
                    </div>
                    <div class="mb-3"><label>Daily Rate (₱)</label><input type="number" name="daily_rate" class="form-control" required></div>
                    <div class="mb-3"><label>Username</label><input type="text" name="username" class="form-control" required></div>
                    <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control"></div>
                    <div class="mb-3"><label>Phone</label><input type="text" name="phone" class="form-control"></div>
                </div>
                <div class="modal-footer"><button type="submit" name="save_user" class="btn btn-primary">SAVE</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5>Edit User Info</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST">
                <input type="hidden" name="user_id" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3"><label>Full Name</label><input type="text" name="full_name" id="edit_name" class="form-control"></div>
                    <div class="mb-3"><label>Username</label><input type="text" name="username" id="edit_username" class="form-control" required></div>
                    <div class="mb-3"><label>New Password (Leave blank to keep current)</label><input type="password" name="password" class="form-control"></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>Role</label>
						<select name="role" id="edit_role" class="form-select">
							<option>Admin</option>
							<option>Worker</option>
						</select>
					</div>
                        <div class="col-md-6 mb-3"><label>Work Type</label>
							<select name="work_type" id="edit_work" class="form-select">
								<option>Construction</option>
                                <option>Engineer</option>
                                <option>Foreman</option>
                                <option>Site Manager</option>
                                <option>Timekeeper</option>
							</select>
						</div>
                    </div>
                    <div class="mb-3"><label>Daily Rate</label><input type="number" name="daily_rate" id="edit_rate" class="form-control"></div>
                    <div class="mb-3"><label>Email</label><input type="email" name="email" id="edit_email" class="form-control"></div>
                    <div class="mb-3"><label>Phone</label><input type="text" name="phone" id="edit_phone" class="form-control"></div>
                </div>
                <div class="modal-footer"><button type="submit" name="update_user" class="btn btn-success">UPDATE</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="reviewPayrollModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Review Payroll Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="pay_id" id="review_pay_id">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 border-end">
                            <p class="section-header">Employee Info</p>
                            <div class="mb-2">
                                <label class="small text-muted">Full Name:</label>
                                <div id="view_name" class="fw-bold"></div>
                            </div>
                            <div class="mb-2">
                                <label class="small text-muted">Role:</label>
                                <div id="view_role"></div>
                            </div>
                            <div class="mb-2">
                                <label class="small text-muted">Work Type:</label>
                                <div id="view_work"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <p class="section-header">Payroll Breakdown</p>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Daily Rate:</span>
                                <span id="view_rate"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Days Logged:</span>
                                <span id="view_attendance"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Allowance:</span>
                                <span id="view_allowance"></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">Total Salary:</span>
                                <span id="view_total" class="fw-bold text-success fs-5"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                    <button type="submit" name="approve_payroll" class="btn btn-success px-4">APPROVE PAYROLL</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Logic for Edit Modal
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('edit_id').value = this.dataset.id;
        document.getElementById('edit_name').value = this.dataset.name;
        document.getElementById('edit_role').value = this.dataset.role;
        document.getElementById('edit_work').value = this.dataset.work;
        document.getElementById('edit_rate').value = this.dataset.rate;
        document.getElementById('edit_username').value = this.dataset.username;
        document.getElementById('edit_email').value = this.dataset.email || '';
        document.getElementById('edit_phone').value = this.dataset.phone || '';
        
        new bootstrap.Modal(document.getElementById('editModal')).show();
    });
});

// Logic for Payroll Review Modal
document.querySelectorAll('.review-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Fill Modal with Data
        document.getElementById('review_pay_id').value = this.dataset.id;
        document.getElementById('view_name').innerText = this.dataset.name;
        document.getElementById('view_role').innerText = this.dataset.role;
        document.getElementById('view_work').innerText = this.dataset.work;
        
        // Formatting Numbers
        const rate = parseFloat(this.dataset.rate).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' });
        const allowance = parseFloat(this.dataset.allowance).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' });
        const total = parseFloat(this.dataset.total).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' });

        document.getElementById('view_rate').innerText = rate;
        document.getElementById('view_attendance').innerText = this.dataset.attendance + " Days";
        document.getElementById('view_allowance').innerText = allowance;
        document.getElementById('view_total').innerText = total;

        // Show Modal
        new bootstrap.Modal(document.getElementById('reviewPayrollModal')).show();
    });
});
</script>


</body>
</html>