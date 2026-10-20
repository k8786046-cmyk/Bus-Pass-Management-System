<?php
require_once 'config.php';
$pageTitle = 'Log In';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT id, full_name, password FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Incorrect email or password.";
    }
    mysqli_stmt_close($stmt);
}

require 'includes/header.php';
?>

<section>
  <div class="container">
    <div class="form-shell">
      <h2>Welcome back</h2>
      <p class="subtext">Log in to check your application status or renew your pass.</p>

      <?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

      <form method="POST" action="login.php">
        <div class="field">
          <label for="email">Email address</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-ink btn-block">Log In</button>
      </form>
      <p class="form-foot">New here? <a href="register.php">Create an account</a></p>
    </div>
  </div>
</section>

<?php require 'includes/footer.php'; ?>
