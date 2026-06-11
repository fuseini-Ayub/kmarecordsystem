<?php
session_start();
include '../../assets/inc/config.php';

if (!isset($_SESSION['user_data'])) {
    header("Location:index.php?error=UnAuthorized Access");
    exit();
}

if ($_SESSION['user_data']['usertype'] != 1) {
    header("Location:backend/records/index.php");
    exit();
}

include './assets/inc/navbar.php';
include './assets/inc/sidebar.php';
?>

<div class="container user-form-shell">
    <div class="user-form-header">
        <h2 class="page-title">Add User</h2>
        <a href="users.php" class="btn btn-secondary">View Users</a>
    </div>

    <?php if (isset($_REQUEST['error'])): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($_REQUEST['error']); ?>
    </div>
    <?php endif; ?>

    <?php if (isset($_REQUEST['success'])): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($_REQUEST['success']); ?>
    </div>
    <?php endif; ?>

    <form action="add_user_post.php" method="post" enctype="multipart/form-data" class="professional-card"
        autocomplete="off">
        <div class="section-title">Account Details</div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter full name" required class="form-control"
                    autocomplete="name">
            </div>
            <div class="form-group col-md-6">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="name@example.com" required
                    class="form-control" autocomplete="off" value="">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="branch_id">Sub Metro</label>
                <?php
                $is_main_admin = ($_SESSION['user_data']['branch_id'] == 1);
                $admin_branch_id = (int)$_SESSION['user_data']['branch_id'];
                ?>
                <?php if ($is_main_admin): ?>
                <select id="branch_id" name="branch_id" class="form-control" required autocomplete="off">
                    <option value="">Select sub metro</option>
                    <?php
                    $branches = $con->query("SELECT id, name, code FROM branches ORDER BY id");
                    while ($branch = $branches->fetch_assoc()) {
                        echo '<option value="'.(int)$branch['id'].'">'.htmlspecialchars($branch['name']).' ('.htmlspecialchars($branch['code']).')</option>';
                    }
                    ?>
                </select>
                <?php else: ?>
                <input type="hidden" name="branch_id" value="<?php echo $admin_branch_id; ?>">
                <select class="form-control" disabled>
                    <?php
                    $branch = $con->query("SELECT id, name, code FROM branches WHERE id = $admin_branch_id")->fetch_assoc();
                    echo '<option selected>'.htmlspecialchars($branch['name']).' ('.htmlspecialchars($branch['code']).')</option>';
                    ?>
                </select>
                <?php endif; ?>
            </div>
            <div class="form-group col-md-6">
                <label for="password">Password</label>
                <div class="password-wrap">
                    <input type="password" id="password" name="password" placeholder="Create password" required
                        class="form-control" autocomplete="new-password" value="">
                    <button type="button" class="password-toggle" id="togglePassword">Show</button>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="usertype">Role</label>
                <?php $is_super_admin = ($_SESSION['user_data']['branch_id'] == 1); ?>
                <?php if ($is_super_admin): ?>
                <select id="usertype" name="usertype" class="form-control" required>
                    <option value="2">User</option>
                    <option value="1">Admin</option>
                </select>
                <?php else: ?>
                <input type="hidden" name="usertype" value="2">
                <select class="form-control" disabled>
                    <option value="2" selected>User</option>
                </select>
                <?php endif; ?>
            </div>
            <div class="form-group col-md-6"></div>
        </div>

        <div class="section-title">Profile</div>
        <div class="form-group">
            <label>Profile Picture</label>
            <div class="upload-panel">
                <div class="profile-preview" id="profilePreview">
                    <span>Photo</span>
                </div>
                <div class="upload-copy">
                    <p id="selectedFileName">No profile picture selected</p>
                    <label for="userimage" class="btn btn-secondary mb-0">Upload Profile Picture</label>
                    <input type="file" id="userimage" name="userimage" class="file-input-hidden" accept="image/*">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" placeholder="Age" class="form-control" autocomplete="off">
            </div>
            <div class="form-group col-md-4">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" class="form-control" autocomplete="off">
                    <option value="">Select gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="staffID">Staff ID</label>
                <input type="text" id="staffID" name="staffID" placeholder="Staff ID" class="form-control"
                    autocomplete="off">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" placeholder="Phone number"
                    class="form-control" autocomplete="tel">
            </div>
            <div class="form-group col-md-6">
                <label for="address">Address</label>
                <textarea id="address" name="address" placeholder="Address" class="form-control" rows="3"
                    autocomplete="street-address"></textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add User</button>
            <a href="users.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
const passwordInput = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');
const userImage = document.getElementById('userimage');
const profilePreview = document.getElementById('profilePreview');
const selectedFileName = document.getElementById('selectedFileName');

togglePassword.addEventListener('click', function() {
    const isHidden = passwordInput.type === 'password';
    passwordInput.type = isHidden ? 'text' : 'password';
    togglePassword.textContent = isHidden ? 'Hide' : 'Show';
});

userImage.addEventListener('change', function() {
    const file = userImage.files && userImage.files[0];
    if (!file) {
        selectedFileName.textContent = 'No profile picture selected';
        profilePreview.innerHTML = '<span>Photo</span>';
        return;
    }

    selectedFileName.textContent = file.name;
    const reader = new FileReader();
    reader.onload = function(event) {
        profilePreview.innerHTML = '<img src="' + event.target.result + '" alt="Profile preview">';
    };
    reader.readAsDataURL(file);
});
</script>
<script src="./assets/js/theme-toggle.js"></script>

<?php include './assets/inc/footer.php'; ?>
