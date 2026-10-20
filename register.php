<?php
require_once 'config.php';
$pageTitle = 'Register';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm_password'];

    if ($full_name === '' || $email === '' || $phone === '' || $password === '') {
        $error = "Please fill in every field.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password should be at least 6 characters long.";
    } else {
        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $error = "An account with this email already exists. Please log in instead.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssss", $full_name, $email, $phone, $hashed);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['user_id']   = mysqli_insert_id($conn);
                $_SESSION['user_name'] = $full_name;
                header("Location: apply.php");
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($check);
    }
}

require 'includes/header.php';
?>

<section>
  <div class="container">
    <div class="form-shell">
      <h2>Create your commuter account</h2>
      <p class="subtext">Register once to apply for a pass, track approvals, and renew every month.</p>

      <?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

      <form method="POST" action="register.php">
        <div class="field">
          <label for="full_name">Full name</label>
          <input type="text" id="full_name" name="full_name" value="<?php echo isset($full_name) ? htmlspecialchars($full_name) : ''; ?>" required>
        </div>
        <div class="field">
          <label for="email">Email address</label>
          <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
        </div>
        <div class="field">
          <label for="phone">Phone number</label>
          <input type="tel" id="phone" name="phone" value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>" required>
        </div>
        <div class="two-col">
          <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
          </div>
          <div class="field">
            <label for="confirm_password">Confirm password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
          </div>
        </div>
        <button type="submit" class="btn btn-ink btn-block">Create Account</button>
      </form>
      <p class="form-foot">Already have an account? <a href="login.php">Log in</a></p>
    </div>
  </div>
</section>

<?php require 'includes/footer.php'; ?>
