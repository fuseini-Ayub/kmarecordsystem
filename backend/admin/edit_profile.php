<?php
include './assets/inc/functions.php';
include '../../assets/inc/config.php';
check_login(1);

$user_data = $_SESSION['user_data'];
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $age = trim($_POST['age'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $staffID = trim($_POST['staffID'] ?? '');

    if ($name === '' || $email === '') {
        $error_message = 'Name and Email are required.';
    } else {
        $userimage = $user_data['userimage'] ?? null;

        // Handle profile picture upload
        if (isset($_FILES['userimage']) && $_FILES['userimage']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = '../../assets/images/user_profiles/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $imageExtension = pathinfo($_FILES['userimage']['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array(strtolower($imageExtension), $allowed)) {
                $error_message = 'Only JPG, PNG, GIF, and WebP images are allowed.';
            } else {
                $safeName = preg_replace('/[^A-Za-z0-9_-]/', '_', $name) . '_' . time() . '.' . $imageExtension;
                $uploadFile = $uploadDir . $safeName;

                if (move_uploaded_file($_FILES['userimage']['tmp_name'], $uploadFile)) {
                    // Delete old profile picture
                    if (!empty($user_data['userimage']) && file_exists($user_data['userimage'])) {
                        unlink($user_data['userimage']);
                    }
                    $userimage = $uploadFile;
                } else {
                    $error_message = 'Failed to upload profile picture.';
                }
            }
        }

        if ($error_message === '') {
            $stmt = $mysqli->prepare("UPDATE users SET name=?, email=?, age=?, gender=?, phone_number=?, address=?, staffID=?, userimage=? WHERE id=?");
            if ($stmt) {
                $stmt->bind_param('ssssssssi', $name, $email, $age, $gender, $phone_number, $address, $staffID, $userimage, $user_data['id']);
                if ($stmt->execute()) {
                    // Update session
                    $_SESSION['user_data']['name'] = $name;
                    $_SESSION['user_data']['email'] = $email;
                    $_SESSION['user_data']['age'] = $age;
                    $_SESSION['user_data']['gender'] = $gender;
                    $_SESSION['user_data']['phone_number'] = $phone_number;
                    $_SESSION['user_data']['address'] = $address;
                    $_SESSION['user_data']['staffID'] = $staffID;
                    $_SESSION['user_data']['userimage'] = $userimage;

                    // Refresh user data
                    $user_data = $_SESSION['user_data'];
                    $success_message = 'Profile updated successfully!';
                } else {
                    $error_message = 'Failed to update profile.';
                }
                $stmt->close();
            } else {
                $error_message = 'Database error: ' . $mysqli->error;
            }
        }
    }
}
?>
<?php include './assets/inc/navbar.php'; ?>
<?php include './assets/inc/sidebar.php'; ?>

<div class="container profile-container">
    <div class="page-header">
        <h2 class="page-title">Edit Profile</h2>
        <a href="view_profile.php" class="btn btn-secondary">Back to Profile</a>
    </div>

    <?php if ($success_message): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
    <?php endif; ?>
    <?php if ($error_message): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="profile-card">
                <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
                    <div class="text-center mb-4">
                        <?php if (!empty($user_data['userimage'])): ?>
                        <img src="<?php echo htmlspecialchars($user_data['userimage']); ?>" alt="Profile" class="profile-img" id="preview">
                        <?php else: ?>
                        <div class="profile-img d-flex align-items-center justify-content-center bg-primary text-white mx-auto" style="font-size:40px;font-weight:700;">
                            <?php echo strtoupper(substr($user_data['name'], 0, 1)); ?>
                        </div>
                        <?php endif; ?>
                        <div class="mt-3">
                            <label for="userimage" class="btn btn-sm btn-secondary" style="cursor:pointer;">
                                <i class="fa fa-camera"></i> Change Photo
                            </label>
                            <input type="file" id="userimage" name="userimage" accept="image/*" style="display:none;" onchange="previewImage(this)">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="age">Age</label>
                                <input type="number" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($user_data['age']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select class="form-control" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male" <?php echo ($user_data['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo ($user_data['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                    <option value="Other" <?php echo ($user_data['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user_data['phone_number']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="staffID">Staff ID</label>
                                <input type="text" class="form-control" id="staffID" name="staffID" value="<?php echo htmlspecialchars($user_data['staffID']); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($user_data['address']); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="view_profile.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('preview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                var img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Profile';
                img.className = 'profile-img';
                img.id = 'preview';
                preview.parentNode.replaceChild(img, preview);
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<script src="./assets/js/theme-toggle.js"></script>
<?php include './assets/inc/footer.php'; ?>
