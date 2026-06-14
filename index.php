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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KMA Records Management</title>
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
    @font-face {
      font-family: Inter;
      src: url(./assets/fonts/Inter_Black.ttf);
      font-weight: 900;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: Inter, system-ui, -apple-system, sans-serif;
      background: #0b1120;
      overflow: hidden;
    }

    .bg {
      position: fixed;
      inset: 0;
      z-index: 0;
      background: url('./assets/images/pic.jpeg') center/cover no-repeat;
    }

    .bg::after {
      content: '';
      position: absolute;
      inset: 0;
      background:
        linear-gradient(135deg, rgba(11, 17, 32, 0.85) 0%, rgba(11, 17, 32, 0.5) 50%, rgba(11, 17, 32, 0.85) 100%);
    }

    .bg-accent {
      position: fixed;
      inset: 0;
      z-index: 0;
      pointer-events: none;
    }

    .bg-accent::before {
      content: '';
      position: absolute;
      top: -30%;
      right: -20%;
      width: 600px;
      height: 600px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(26, 86, 219, 0.12), transparent 70%);
      animation: accentDrift 18s ease-in-out infinite;
    }

    .bg-accent::after {
      content: '';
      position: absolute;
      bottom: -30%;
      left: -20%;
      width: 500px;
      height: 500px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(99, 102, 241, 0.1), transparent 70%);
      animation: accentDrift 18s ease-in-out infinite reverse;
    }

    @keyframes accentDrift {
      0%, 100% { transform: translate(0, 0) scale(1); }
      33% { transform: translate(40px, -40px) scale(1.05); }
      66% { transform: translate(-30px, 30px) scale(0.95); }
    }

    .login-wrapper {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 420px;
      padding: 20px;
      animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      opacity: 0;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .login-card {
      background: rgba(255, 255, 255, 0.04);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border: 1px solid rgba(255, 255, 255, 0.06);
      border-radius: 20px;
      padding: 44px 36px 36px;
      box-shadow:
        0 0 0 1px rgba(255, 255, 255, 0.03),
        0 24px 80px rgba(0, 0, 0, 0.4);
    }

    .login-header {
      text-align: center;
      margin-bottom: 32px;
    }

    .login-header .logo {
      width: 64px;
      height: 64px;
      border-radius: 16px;
      margin-bottom: 20px;
      animation: logoPop 1s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards;
      opacity: 0;
      transform: scale(0.8);
    }

    @keyframes logoPop {
      from { opacity: 0; transform: scale(0.8); }
      to { opacity: 1; transform: scale(1); }
    }

    .login-header h1 {
      font-size: 22px;
      font-weight: 700;
      color: #f1f5f9;
      letter-spacing: -0.02em;
      line-height: 1.3;
      animation: fadeIn 0.6s ease 0.3s forwards;
      opacity: 0;
    }

    .login-header p {
      font-size: 14px;
      color: #94a3b8;
      margin-top: 6px;
      animation: fadeIn 0.6s ease 0.4s forwards;
      opacity: 0;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .alert {
      border-radius: 12px;
      font-size: 13px;
      font-weight: 500;
      padding: 12px 16px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      gap: 10px;
      animation: shake 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97);
    }

    .alert-danger {
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.2);
      color: #fca5a5;
    }

    .alert svg {
      flex-shrink: 0;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      10% { transform: translateX(-8px); }
      20% { transform: translateX(8px); }
      30% { transform: translateX(-6px); }
      40% { transform: translateX(6px); }
      50% { transform: translateX(-4px); }
      60% { transform: translateX(4px); }
      70% { transform: translateX(-2px); }
      80% { transform: translateX(2px); }
    }

    .form-group {
      margin-bottom: 20px;
      animation: fadeIn 0.6s ease 0.5s forwards;
      opacity: 0;
    }

    .form-group:nth-child(2) {
      animation-delay: 0.6s;
    }

    .form-group label {
      display: block;
      font-size: 12px;
      font-weight: 600;
      color: #94a3b8;
      margin-bottom: 6px;
      letter-spacing: 0.02em;
      text-transform: uppercase;
    }

    .input-wrap {
      position: relative;
    }

    .input-wrap .icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #64748b;
      pointer-events: none;
      transition: color 0.3s ease;
    }

    .input-wrap:focus-within .icon {
      color: #60a5fa;
    }

    .form-control {
      width: 100%;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      color: #f1f5f9;
      font-family: Inter, system-ui, -apple-system, sans-serif;
      font-size: 14px;
      padding: 12px 14px 12px 42px;
      min-height: 48px;
      transition: border-color 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
      outline: none;
    }

    .form-control:hover {
      background: rgba(255, 255, 255, 0.07);
    }

    .form-control:focus {
      border-color: rgba(96, 165, 250, 0.4);
      box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
      background: rgba(255, 255, 255, 0.07);
    }

    .form-control::placeholder {
      color: #475569;
    }

    .form-control:-webkit-autofill,
    .form-control:-webkit-autofill:hover,
    .form-control:-webkit-autofill:focus {
      -webkit-text-fill-color: #f1f5f9;
      -webkit-box-shadow: 0 0 0px 1000px rgba(15, 23, 42, 0.95) inset;
      transition: background-color 5000s ease-in-out 0s;
    }

    .password-toggle {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #64748b;
      cursor: pointer;
      padding: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: color 0.2s ease;
    }

    .password-toggle:hover {
      color: #94a3b8;
    }

    .btn {
      width: 100%;
      background: linear-gradient(135deg, #1a56db, #6366f1);
      border: none;
      border-radius: 12px;
      color: #ffffff;
      cursor: pointer;
      font-family: Inter, system-ui, -apple-system, sans-serif;
      font-size: 14px;
      font-weight: 600;
      min-height: 48px;
      padding: 12px 24px;
      position: relative;
      overflow: hidden;
      transition: transform 0.2s ease, box-shadow 0.3s ease;
      animation: fadeIn 0.6s ease 0.7s forwards;
      opacity: 0;
    }

    .btn::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 30px rgba(26, 86, 219, 0.3);
    }

    .btn:hover::before {
      opacity: 1;
    }

    .btn:active {
      transform: translateY(0);
    }

    .btn.loading {
      pointer-events: none;
    }

    .btn .btn-text {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      position: relative;
      z-index: 1;
    }

    .btn .spinner {
      display: none;
      width: 18px;
      height: 18px;
      border: 2px solid rgba(255,255,255,0.3);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin 0.6s linear infinite;
    }

    .btn.loading .btn-text {
      opacity: 0;
    }

    .btn.loading .spinner {
      display: block;
      position: absolute;
      top: 50%;
      left: 50%;
      margin: -9px 0 0 -9px;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .login-footer {
      text-align: center;
      margin-top: 24px;
      font-size: 13px;
      color: #64748b;
      animation: fadeIn 0.6s ease 0.8s forwards;
      opacity: 0;
    }

    .login-footer a {
      color: #60a5fa;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.2s ease;
    }

    .login-footer a:hover {
      color: #93bbfd;
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

    @media (max-width: 480px) {
      .login-card {
        padding: 28px 20px 24px;
        border-radius: 16px;
      }
      .login-wrapper {
        padding: 12px;
      }
    }
    </style>
</head>
<body>

    <div class="bg"></div>
    <div class="bg-accent"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <img class="logo" src="./assets/images/logo.png" alt="KMA Logo">
                <h1>Welcome back</h1>
                <p>Sign in to KMA Records Management</p>
            </div>

            <?php if (isset($_REQUEST['error'])) { ?>
            <div class="alert alert-danger" role="alert">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                <span><?php echo htmlspecialchars($_REQUEST['error']); ?></span>
            </div>
            <?php } ?>

            <form action="login.php" method="post" autocomplete="off" id="loginForm">
                <div class="form-group">
                    <label for="inputEmail">Email or Access Key</label>
                    <div class="input-wrap">
                        <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
                        <input type="text" id="inputEmail" name="email" class="form-control" placeholder="Enter your email or access key" required autofocus autocomplete="off">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword">Password</label>
                    <div class="input-wrap">
                        <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Enter your password" required autocomplete="new-password">
                        <button type="button" class="password-toggle" id="togglePassword" aria-label="Toggle password visibility">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <button class="btn" type="submit" id="loginBtn">
                    <span class="btn-text">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Sign In
                    </span>
                    <span class="spinner"></span>
                </button>
            </form>

            <p class="login-footer">Don't have an account?<br><a href="help.php">Contact the administrator</a></p>
        </div>
    </div>

    <script>
    const togglePass = document.getElementById('togglePassword');
    const passInput = document.getElementById('inputPassword');
    togglePass.addEventListener('click', () => {
        const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passInput.setAttribute('type', type);
        togglePass.innerHTML = type === 'password'
            ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>'
            : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
    });

    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    loginForm.addEventListener('submit', (e) => {
        const email = document.getElementById('inputEmail').value.trim();
        const password = document.getElementById('inputPassword').value.trim();
        if (!email || !password) return;
        loginBtn.classList.add('loading');
    });
    </script>

</body>
</html>
