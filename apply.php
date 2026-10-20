<?php
require_once 'config.php';
$pageTitle = 'Apply for a Pass';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass_type = $_POST['pass_type'];
    $route     = trim($_POST['route']);
    $id_proof  = trim($_POST['id_proof_number']);

    $allowed_types = ['Student', 'General', 'Senior Citizen'];

    // ---- Photo validation ----
    $photo_name = null;
    $photo_ok   = false;
    if (!isset($_FILES['photo']) || $_FILES['photo']['error'] === UPLOAD_ERR_NO_FILE) {
        $error = "Please upload your face photo.";
    } elseif ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        $error = "Photo upload failed. Please try a smaller image (max 2 MB).";
    } elseif ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
        $error = "Photo is too large. Maximum size is 2 MB.";
    } else {
        $info = @getimagesize($_FILES['photo']['tmp_name']);
        $mime_map = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
        if ($info === false || !isset($mime_map[$info['mime']])) {
            $error = "Only JPG or PNG images are allowed.";
        } else {
            $photo_name = 'user' . $user_id . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $mime_map[$info['mime']];
            $photo_ok = true;
        }
    }

    if ($error === '') {
        if (!in_array($pass_type, $allowed_types) || $route === '' || $id_proof === '') {
            $error = "Please fill in every field with a valid pass type.";
        } else {
            $upload_dir = __DIR__ . '/uploads/photos/';
            if (!is_dir($upload_dir)) { mkdir($upload_dir, 0755, true); }

            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $photo_name)) {
                $error = "Could not save your photo. Please try again.";
            } else {
                $stmt = mysqli_prepare($conn, "INSERT INTO pass_applications (user_id, pass_type, route, id_proof_number, photo) VALUES (?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "issss", $user_id, $pass_type, $route, $id_proof, $photo_name);
                if (mysqli_stmt_execute($stmt)) {
                    header("Location: dashboard.php?applied=1");
                    exit;
                } else {
                    @unlink($upload_dir . $photo_name);
                    $error = "Could not submit your application. Please try again.";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
}

require 'includes/header.php';
?>

<section>
  <div class="container">
    <div class="form-shell">
      <h2>Apply for a bus pass</h2>
      <p class="subtext">Pick your pass type and route. Your application will be reviewed by the transit authority.</p>

      <?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

      <form method="POST" action="apply.php" enctype="multipart/form-data">
        <div class="field">
          <label for="pass_type">Pass type</label>
          <select id="pass_type" name="pass_type" required>
            <option value="">Select a pass type</option>
            <option value="Student">Student Pass — ₹250/month</option>
            <option value="General">General Pass — ₹600/month</option>
            <option value="Senior Citizen">Senior Citizen Pass — ₹150/month</option>
          </select>
        </div>
        <div class="field">
          <label for="route">Route</label>
          <input type="text" id="route" name="route" placeholder="e.g. Route 24 — Depot to City Centre" required>
        </div>
        <div class="field">
          <label for="id_proof_number">ID proof number</label>
          <input type="text" id="id_proof_number" name="id_proof_number" placeholder="Aadhaar / College ID / Voter ID number" required>
        </div>
        <div class="field">
          <label for="photo">Face photo (for your pass)</label>
          <div class="photo-upload">
            <img id="photoPreview" class="photo-preview" alt="Photo preview" style="display:none;">
            <div class="photo-placeholder" id="photoPlaceholder">No photo<br>selected</div>
            <div class="photo-input">
              <input type="file" id="photo" name="photo" accept="image/jpeg,image/png" required>
              <small>Clear, front-facing passport-size photo. JPG or PNG, max 2 MB.</small>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-ink btn-block">Submit Application</button>
      </form>
    </div>
  </div>
</section>

<script>
document.getElementById('photo').addEventListener('change', function () {
  var f = this.files[0];
  var img = document.getElementById('photoPreview');
  var ph  = document.getElementById('photoPlaceholder');
  if (!f) { img.style.display = 'none'; ph.style.display = 'flex'; return; }
  if (f.size > 2 * 1024 * 1024) { alert('Photo must be under 2 MB.'); this.value = ''; return; }
  img.src = URL.createObjectURL(f);
  img.style.display = 'block';
  ph.style.display = 'none';
});
</script>

<?php require 'includes/footer.php'; ?>
