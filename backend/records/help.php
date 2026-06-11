<?php
session_start();
include '../../assets/inc/config.php';

if (!isset($_SESSION['user_data'])) {
    header("Location: index.php?error=Unauthorized Access");
    exit();
}

include './assets/inc/navbar.php';
include './assets/inc/sidebar.php';
?>

<div class="container">
    <h2 class="page-title">Contact Support</h2>
    <div class="admin-card">
        <p style="margin-bottom: 20px; font-size: 14px;">If you need help or have any questions about the system, please reach out to our developer:</p>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="padding: 10px 0; border-bottom: 1px solid var(--table-row-border);">
                <strong style="min-width: 100px; display: inline-block;">Phone:</strong>
                <a href="tel:0531114854">0531114854</a>
            </li>
            <li style="padding: 10px 0; border-bottom: 1px solid var(--table-row-border);">
                <strong style="min-width: 100px; display: inline-block;">Email:</strong>
                <a href="mailto:sa.devwin@gmail.com">sa.devwin@gmail.com</a>
            </li>
            <li style="padding: 10px 0;">
                <strong style="min-width: 100px; display: inline-block;">WhatsApp:</strong>
                <a href="https://wa.me/+233531114854">0531114854</a>
            </li>
        </ul>
    </div>
</div>

<?php include './assets/inc/footer.php'; ?>