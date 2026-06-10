<?php
session_start();
if (isset($_SESSION['user_data'])) {
    if ($_SESSION['user_data']['usertype'] == 1) {
        header("Location: backend/admin/index.php");
    } else {
        header("Location: backend/records/index.php");
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login - KMA Records Management</title>
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="./assets/css/styles.css">
    <link rel="icon" href="./assets/images/logo.ico" type="image/ico">

    <style>
    @font-face {
      font-family: Inter;
      src: url(./assets/fonts/Inter_Regular.ttf);
      font-weight: 400;
    }
    @font-face {
      font-family: Inter;
      src: url(./assets/fonts/Inter_Bold.ttf);
      font-weight: 700;
    }

    * {
      box-sizing: border-box;
    }

    body {
      background-image: url('./assets/images/bg.jpg');
      background-size: cover;
      background-position: center;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      font-family: Inter, system-ui, -apple-system, sans-serif;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.55);
      z-index: 0;
    }

    .login-container {
      position: relative;
      z-index: 1;
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
      padding: 40px 36px;
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .login-container img {
      display: block;
      margin: 0 auto 24px;
      width: 72px;
    }

    .login-container h2 {
      margin-bottom: 28px;
      font-size: 22px;
      font-weight: 700;
      color: #0f172a;
      letter-spacing: -0.01em;
    }

    .form-group {
      margin-bottom: 18px;
      text-align: left;
    }

    .form-group label {
      display: block;
      font-size: 12px;
      font-weight: 600;
      color: #475569;
      margin-bottom: 4px;
    }

    .form-control {
      width: 100%;
      background: #ffffff;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      color: #1e293b;
      font-family: Inter, system-ui, -apple-system, sans-serif;
      font-size: 14px;
      min-height: 44px;
      padding: 10px 14px;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .form-control:focus {
      border-color: #1a56db;
      box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.1);
      outline: none;
    }

    .form-control::placeholder {
      color: #94a3b8;
    }

    .btn {
      width: 100%;
      background: #1a56db;
      border: none;
      border-radius: 8px;
      color: #ffffff;
      cursor: pointer;
      font-family: Inter, system-ui, -apple-system, sans-serif;
      font-size: 14px;
      font-weight: 600;
      min-height: 44px;
      padding: 10px 20px;
      transition: background 0.15s ease;
    }

    .btn:hover {
      background: #1648c0;
    }

    .alert {
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      padding: 10px 14px;
      margin-bottom: 20px;
    }

    .alert-danger {
      background: #fef2f2;
      border: 1px solid #fecaca;
      color: #dc2626;
    }

    .text-center {
      text-align: center;
    }

    .text-font {
      font-family: Inter, system-ui, -apple-system, sans-serif;
    }

    .login-footer {
      margin-top: 20px;
      font-size: 13px;
      color: #64748b;
    }

    .login-footer a {
      color: #1a56db;
      font-weight: 600;
      text-decoration: none;
    }

    .login-footer a:hover {
      color: #1648c0;
      text-decoration: underline;
    }

    .sr-only {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      border: 0;
    }
    </style>
</head>

<body>
    <div class="login-container">
        <img src="./assets/images/logo.png" alt="KMA Logo">
        <?php if (isset($_REQUEST['error'])) { ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($_REQUEST['error']); ?>
        </div>
        <?php } ?>
        <form action="login.php" method="post">
            <h2>Sign in to your account</h2>
            <div class="form-group">
                <label for="inputEmail">Email or Access Key</label>
                <input type="text" id="inputEmail" name="email" class="form-control" placeholder="Enter your email or access key" required autofocus>
            </div>
            <div class="form-group">
                <label for="inputPassword">Password</label>
                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Enter your password" required>
            </div>
            <button class="btn" type="submit">Sign In</button>
        </form>
        <p class="login-footer">Don't have an account?<br><a href="help.php">Contact the administrator</a></p>
    </div>
</body>

</html>