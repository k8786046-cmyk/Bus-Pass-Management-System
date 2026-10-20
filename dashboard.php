<?php
require_once 'config.php';
$pageTitle = 'My Dashboard';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM pass_applications WHERE user_id = ? ORDER BY applied_date DESC");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$applications = mysqli_stmt_get_result($stmt);

require 'includes/header.php';
?>

<div class="dash-shell">
  <div class="container">
    <div class="dash-head">
      <div>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <p>Track the status of every pass you've applied for.</p>
      </div>
      <a href="apply.php" class="btn btn-amber">Apply for Another Pass</a>
    </div>

    <?php if (isset($_GET['applied'])): ?>
      <div class="alert alert-success">Your application has been submitted and is pending review.</div>
    <?php endif; ?>

    <div class="card">
      <h3>My Applications</h3>
      <?php if (mysqli_num_rows($applications) === 0): ?>
        <div class="empty-state">
          <p>You haven't applied for a bus pass yet.</p>
          <a href="apply.php" class="btn btn-ink">Apply Now</a>
        </div>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>Photo</th>
              <th>Pass Type</th>
              <th>Route</th>
              <th>Applied On</th>
              <th>Valid Until</th>
              <th>Status</th>
              <th>Pass</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($applications)): ?>
              <tr>
                <td>
                  <?php if (!empty($row['photo'])): ?>
                    <img src="uploads/photos/<?php echo htmlspecialchars($row['photo']); ?>" class="thumb" alt="Photo">
                  <?php else: ?>—<?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row['pass_type']); ?></td>
                <td><?php echo htmlspecialchars($row['route']); ?></td>
                <td><?php echo date('d M Y', strtotime($row['applied_date'])); ?></td>
                <td><?php echo $row['valid_until'] ? date('d M Y', strtotime($row['valid_until'])) : '—'; ?></td>
                <td><span class="badge <?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
                <td>
                  <?php if ($row['status'] === 'Approved'): ?>
                    <a href="pass.php?id=<?php echo $row['id']; ?>" class="mini-btn mini-approve">View Pass</a>
                  <?php else: ?>—<?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require 'includes/footer.php'; ?>
