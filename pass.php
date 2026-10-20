<?php
require_once 'config.php';
$pageTitle = 'My Bus Pass';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id      = intval($_GET['id'] ?? 0);
$user_id = $_SESSION['user_id'];

// Only the owner can view their own approved pass
$stmt = mysqli_prepare($conn, "
  SELECT pa.*, u.full_name, u.phone
  FROM pass_applications pa
  JOIN users u ON u.id = pa.user_id
  WHERE pa.id = ? AND pa.user_id = ? AND pa.status = 'Approved'
");
mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
mysqli_stmt_execute($stmt);
$pass = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$pass) {
    header("Location: dashboard.php");
    exit;
}

require 'includes/header.php';
?>

<section>
  <div class="container">
    <div class="pass-wrap">
      <div class="pass-card" id="passCard">
        <div class="pass-top">
          <span class="brand-badge">24</span> CityLine Bus Pass
          <span class="pass-type"><?php echo htmlspecialchars($pass['pass_type']); ?></span>
        </div>
        <div class="pass-body">
          <?php if (!empty($pass['photo'])): ?>
            <img src="uploads/photos/<?php echo htmlspecialchars($pass['photo']); ?>" class="pass-photo" alt="Pass holder photo">
          <?php else: ?>
            <div class="pass-photo pass-photo-empty">No photo</div>
          <?php endif; ?>
          <div class="pass-info">
            <div><label>Name</label><b><?php echo htmlspecialchars($pass['full_name']); ?></b></div>
            <div><label>Route</label><b><?php echo htmlspecialchars($pass['route']); ?></b></div>
            <div><label>Pass No.</label><b>CL-<?php echo str_pad($pass['id'], 6, '0', STR_PAD_LEFT); ?></b></div>
            <div><label>Valid until</label><b><?php echo $pass['valid_until'] ? date('d M Y', strtotime($pass['valid_until'])) : '—'; ?></b></div>
          </div>
        </div>
      </div>
      <div class="pass-actions">
        <button onclick="window.print()" class="btn btn-ink">Print Pass</button>
        <a href="dashboard.php" class="btn btn-amber">Back</a>
      </div>
    </div>
  </div>
</section>

<?php require 'includes/footer.php'; ?>
