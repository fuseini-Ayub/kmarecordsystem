<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<button class="toggle-btn" type="button" onclick="toggleSidebar()" aria-label="Toggle sidebar">
    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24">
        <path fill="currentColor" d="M4 6.5A1.5 1.5 0 0 1 5.5 5h13A1.5 1.5 0 0 1 20 6.5v11a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 17.5v-11Zm4 10.5V7H6v10h2Zm2 0h8V7h-8v10Z" />
    </svg>
</button>

<div class="sidebar" id="mainSidebar">
    <div class="sidebar-section">Overview</div>
    <a href="index.php">Dashboard</a>
    <a href="incoming.php">Incoming</a>
    <a href="outgoing.php">Outgoing</a>

    <div class="sidebar-section">Management</div>
    <a href="users.php">Users</a>
    <a href="add_user.php">Add User</a>
    <?php if ($_SESSION['user_data']['branch_id'] == 1): ?>
    <a href="add_submetro.php">Add Sub Metro</a>
    <?php endif; ?>
    <a href="departments.php">Departments</a>
    <a href="transactions.php">Transactions</a>
</div>

<script>
function toggleSidebar() {
    var sidebar = document.getElementById('mainSidebar');
    var content = document.querySelector('.content');
    var toggleBtn = document.querySelector('.toggle-btn');
    var overlay = document.getElementById('sidebarOverlay');
    var isOpening = !sidebar.classList.contains('open');
    sidebar.classList.toggle('open');
    if (content) content.classList.toggle('shift');
    if (toggleBtn) toggleBtn.classList.toggle('shift');
    if (window.innerWidth <= 991) {
        overlay.classList.toggle('active', isOpening);
    }
}

function closeSidebar() {
    var sidebar = document.getElementById('mainSidebar');
    var content = document.querySelector('.content');
    var toggleBtn = document.querySelector('.toggle-btn');
    var overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.remove('open');
    if (content) content.classList.remove('shift');
    if (toggleBtn) toggleBtn.classList.remove('shift');
    overlay.classList.remove('active');
}
</script>

<main class="content">
