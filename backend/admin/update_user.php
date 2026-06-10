<?php
session_start();
include '../../assets/inc/config.php';

if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['usertype'] != 1) {
    header("Location: index.php?error=Unauthorized Access");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: users.php?error=No user ID specified");
    exit();
}

$id = intval($_GET['id']);
$error_message = '';

$select_query = "SELECT * FROM users WHERE id = ?";
if ($stmt = $con->prepare($select_query)) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

if (empty($user)) {
    header("Location: users.php?error=User not found");
    exit();
}

// Sub-admin can only edit users in their own branch
if ($_SESSION['user_data']['branch_id'] != 1 && (int)$user['branch_id'] !== (int)$_SESSION['user_data']['branch_id']) {
    header("Location: users.php?error=You can only edit users in your sub metro");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $branch_id = isset($_POST['branch_id']) ? (int)$_POST['branch_id'] : 1;
    // Sub-admin cannot change the branch
    if ($_SESSION['user_data']['branch_id'] != 1) {
        $branch_id = (int)$_SESSION['user_data']['branch_id'];
    }
    $new_password = trim($_POST['password'] ?? '');
    $age = trim($_POST['age'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $staffID = trim($_POST['staffID'] ?? '');
    $usertype = isset($_POST['usertype']) ? (int)$_POST['usertype'] : 2;
    if ($usertype !== 1 && $usertype !== 2) {
        $usertype = $user['usertype'];
    }

    if ($name === '' || $email === '' || $branch_id <= 0) {
        $error_message = 'Name, email, and sub metro are required.';
    }

    if ($error_message === '') {
        if (!empty($staffID)) {
            $duplicateCheckQuery = "SELECT id FROM users WHERE (email = ? OR staffID = ?) AND id != ? LIMIT 1";
            if ($stmt = $con->prepare($duplicateCheckQuery)) {
                $stmt->bind_param("ssi", $email, $staffID, $id);
                $stmt->execute();
                $result = $stmt->get_result();
                if (mysqli_num_rows($result) > 0) {
                    $error_message = 'Duplicate Entry: Email or StaffID already exists';
                }
                $stmt->close();
            }
        } else {
            $duplicateCheckQuery = "SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1";
            if ($stmt = $con->prepare($duplicateCheckQuery)) {
                $stmt->bind_param("si", $email, $id);
                $stmt->execute();
                $result = $stmt->get_result();
                if (mysqli_num_rows($result) > 0) {
                    $error_message = 'Duplicate Entry: Email already exists';
                }
                $stmt->close();
            }
        }
    }

    $userimage = $user['userimage'];
    if ($error_message === '' && isset($_FILES['userimage']) && $_FILES['userimage']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../../assets/images/user_profiles/';
        $imageExtension = pathinfo($_FILES['userimage']['name'], PATHINFO_EXTENSION);
        $safeName = preg_replace('/[^A-Za-z0-9_-]/', '_', $name . '_' . $staffID);
        $uploadFile = $uploadDir . $safeName . '_' . time() . '.' . $imageExtension;

        if (move_uploaded_file($_FILES['userimage']['tmp_name'], $uploadFile)) {
            if (!empty($user['userimage']) && file_exists($user['userimage']) && $user['userimage'] !== $uploadFile) {
                unlink($user['userimage']);
            }
            $userimage = $uploadFile;
        } else {
            $error_message = 'Failed to upload profile picture.';
        }
    }

    if ($error_message === '') {
        if ($new_password !== '') {
            $hashed_password = md5($new_password);
            $update_query = "UPDATE users SET name = ?, email = ?, password = ?, userimage = ?, age = ?, gender = ?, phone_number = ?, address = ?, staffID = ?, branch_id = ?, usertype = ? WHERE id = ?";
            $stmt = $con->prepare($update_query);
            $stmt->bind_param("sssssssssiii", $name, $email, $hashed_password, $userimage, $age, $gender, $phone_number, $address, $staffID, $branch_id, $usertype, $id);
        } else {
            $update_query = "UPDATE users SET name = ?, email = ?, userimage = ?, age = ?, gender = ?, phone_number = ?, address = ?, staffID = ?, branch_id = ?, usertype = ? WHERE id = ?";
            $stmt = $con->prepare($update_query);
            $stmt->bind_param("sssssssssii", $name, $email, $userimage, $age, $gender, $phone_number, $address, $staffID, $branch_id, $usertype, $id);
        }

        if ($stmt && $stmt->execute()) {
            header("Location: users.php?success=User updated successfully");
            exit();
        }

        $error_message = 'Failed to update user';
        if ($stmt) {
            $stmt->close();
        }
    }

    $user = array_merge($user, [
        'name' => $name,
        'email' => $email,
        'branch_id' => $branch_id,
        'userimage' => $userimage,
        'age' => $age,
        'gender' => $gender,
        'phone_number' => $phone_number,
        'address' => $address,
        'staffID' => $staffID,
        'usertype' => $usertype
    ]);
}

include './assets/inc/navbar.php';
include './assets/inc/sidebar.php';
?>

<div class="container user-form-shell">
    <div class="user-form-header">
        <h2 class="page-title">Edit User</h2>
        <a href="users.php" class="btn btn-secondary">View Users</a>
    </div>

    <?php if ($error_message !== ''): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($error_message); ?>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
    <?php endif; ?>

    <form action="update_user.php?id=<?php echo (int)$id; ?>" method="post" enctype="multipart/form-data"
        class="professional-card" autocomplete="off">
        <div class="section-title">Account Details</div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Full Name</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="<?php echo htmlspecialchars($user['name']); ?>" required autocomplete="name">
            </div>
            <div class="form-group col-md-6">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?php echo htmlspecialchars($user['email']); ?>" required autocomplete="off">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <?php
                $is_main_admin = ($_SESSION['user_data']['branch_id'] == 1);
                $admin_branch_id = (int)$_SESSION['user_data']['branch_id'];
                ?>
                <label for="branch_id">Sub Metro</label>
                <?php if ($is_main_admin): ?>
                <select id="branch_id" name="branch_id" class="form-control" required autocomplete="off">
                    <option value="">Select sub metro</option>
                    <?php
                    $branches = $con->query("SELECT id, name, code FROM branches ORDER BY id");
                    while ($branch = $branches->fetch_assoc()) {
                        $selected = ((int)$branch['id'] === (int)$user['branch_id']) ? 'selected' : '';
                        echo '<option value="'.(int)$branch['id'].'" '.$selected.'>'.htmlspecialchars($branch['name']).' ('.htmlspecialchars($branch['code']).')</option>';
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
                <label for="password">New Password</label>
                <div class="password-wrap">
                    <input type="password" id="password" name="password" placeholder="Leave blank to keep current"
                        class="form-control" autocomplete="new-password" value="">
                    <button type="button" class="password-toggle" id="togglePassword">Show</button>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="usertype">Role</label>
                <select id="usertype" name="usertype" class="form-control" required>
                    <option value="2" <?php if ((int)$user['usertype'] === 2) echo 'selected'; ?>>User</option>
                    <option value="1" <?php if ((int)$user['usertype'] === 1) echo 'selected'; ?>>Admin</option>
                </select>
            </div>
            <div class="form-group col-md-6"></div>
        </div>

        <div class="section-title">Profile</div>
        <div class="form-group">
            <label>Profile Picture</label>
            <div class="upload-panel">
                <div class="profile-preview" id="profilePreview">
                    <?php if (!empty($user['userimage'])): ?>
                    <img src="<?php echo htmlspecialchars($user['userimage']); ?>" alt="Profile preview">
                    <?php else: ?>
                    <span>Photo</span>
                    <?php endif; ?>
                </div>
                <div class="upload-copy">
                    <p id="selectedFileName">
                        <?php echo !empty($user['userimage']) ? 'Current profile picture' : 'No profile picture selected'; ?>
                    </p>
                    <label for="userimage" class="btn btn-secondary mb-0">Upload Profile Picture</label>
                    <input type="file" id="userimage" name="userimage" class="file-input-hidden" accept="image/*">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="age">Age</label>
                <input type="number" class="form-control" id="age" name="age"
                    value="<?php echo htmlspecialchars($user['age']); ?>" autocomplete="off">
            </div>
            <div class="form-group col-md-4">
                <label for="gender">Gender</label>
                <select class="form-control" id="gender" name="gender" autocomplete="off">
                    <option value="">Select gender</option>
                    <option value="Male" <?php if ($user['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($user['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                    <option value="Other" <?php if ($user['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="staffID">Staff ID</label>
                <input type="text" class="form-control" id="staffID" name="staffID"
                    value="<?php echo htmlspecialchars($user['staffID']); ?>" autocomplete="off">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="phone_number">Phone Number</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number"
                    value="<?php echo htmlspecialchars($user['phone_number']); ?>" autocomplete="tel">
            </div>
            <div class="form-group col-md-6">
                <label for="address">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3"
                    autocomplete="street-address"><?php echo htmlspecialchars($user['address']); ?></textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
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
        selectedFileName.textContent = <?php echo json_encode(!empty($user['userimage']) ? 'Current profile picture' : 'No profile picture selected'); ?>;
        profilePreview.innerHTML = <?php echo json_encode(!empty($user['userimage']) ? '<img src="' . htmlspecialchars($user['userimage'], ENT_QUOTES) . '" alt="Profile preview">' : '<span>Photo</span>'); ?>;
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
