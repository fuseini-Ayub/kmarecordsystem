<?php
// Use include_once to ensure the file is included only once
include './assets/inc/functions.php';

// Use the function
check_login(1);

// Include other necessary files
include_once '../../assets/inc/config.php';

// Check if form data is submitted
function generate_access_key($branch_id, $name, $staff_id) {
    global $con;
    $branch = $con->query("SELECT code, name FROM branches WHERE id = $branch_id")->fetch_assoc();
    $branch_code = strtoupper(preg_replace('/[^A-Z0-9]/', '', $branch['code'] ?? 'KMA'));
    $name_code = strtoupper(substr(preg_replace('/[^A-Z0-9]/', '', $name ?? ''), 0, 3));
    $staff_code = strtoupper(substr(preg_replace('/[^A-Z0-9]/', '', $staff_id ?? ''), 0, 3));
    $random_code = strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
    return $branch_code . '-' . ($name_code ?: 'USR') . '-' . ($staff_code ?: 'KEY') . '-' . $random_code;
}

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
    if ($_SESSION['user_data']['usertype'] != 1) {
        header("Location: backend/records/index.php");
        exit();
    }

    // Retrieve form data and sanitize it
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $branch_id = isset($_POST['branch_id']) ? (int)$_POST['branch_id'] : 1;
    // Sub-admin can only add users to their own branch
    if ($_SESSION['user_data']['branch_id'] != 1) {
        $branch_id = (int)$_SESSION['user_data']['branch_id'];
    }
    $age = isset($_POST['age']) ? mysqli_real_escape_string($con, $_POST['age']) : null;
    $gender = isset($_POST['gender']) ? mysqli_real_escape_string($con, $_POST['gender']) : null;
    $phone_number = isset($_POST['phone_number']) ? mysqli_real_escape_string($con, $_POST['phone_number']) : null;
    $address = isset($_POST['address']) ? mysqli_real_escape_string($con, $_POST['address']) : null;
    $raw_staffID = isset($_POST['staffID']) ? trim($_POST['staffID']) : '';
    $staffID = !empty($raw_staffID) ? "'" . mysqli_real_escape_string($con, $raw_staffID) . "'" : "NULL";
    $usertype = isset($_POST['usertype']) ? (int)$_POST['usertype'] : 2;
    if ($usertype !== 1 && $usertype !== 2) {
        $usertype = 2;
    }
    // Only super admin (branch_id == 1) can create admin users
    if ($usertype === 1 && $_SESSION['user_data']['branch_id'] != 1) {
        $usertype = 2;
    }

    // Check for duplicates in the users table
    $email_check = mysqli_real_escape_string($con, $email);
    $duplicateCheckQuery = "SELECT * FROM users WHERE email = '$email_check'";
    if (!empty($raw_staffID)) {
        $staffID_check = mysqli_real_escape_string($con, $raw_staffID);
        $duplicateCheckQuery .= " OR staffID = '$staffID_check'";
    }
    $duplicateCheckResult = mysqli_query($con, $duplicateCheckQuery);

    if (mysqli_num_rows($duplicateCheckResult) > 0) {
        header("Location: add_user.php?error=Duplicate Entry: Email or StaffID already exists");
        exit();
    }

    // Handle file upload for profile image
    $userimage = null;
    if (isset($_FILES['userimage']) && $_FILES['userimage']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../../assets/images/user_profiles/';
        $imageExtension = pathinfo($_FILES['userimage']['name'], PATHINFO_EXTENSION);
        $imageBaseName = str_replace(' ', '_', $name . '_' . $staffID);
        $uploadFile = $uploadDir . $imageBaseName . '.' . $imageExtension;
        if (move_uploaded_file($_FILES['userimage']['tmp_name'], $uploadFile)) {
            $userimage = mysqli_real_escape_string($con, $uploadFile);
        }
    }

    $access_key = generate_access_key($branch_id, $name, $raw_staffID);

    // Insert query
    $query = "INSERT INTO users (name, email, password, userimage, age, gender, phone_number, address, staffID, branch_id, access_key, usertype, created_at) 
              VALUES ('$name', '$email', '".md5($password)."', '$userimage', '$age', '$gender', '$phone_number', '$address', $staffID, '$branch_id', '$access_key', '$usertype', '".date('Y-m-d H:i:s')."')";

    $qr = mysqli_query($con, $query);

    if ($qr) {
        header("Location: users.php?success=Added Successfully");
    } else {
        header("Location: add_user.php?error=Failed to Add User");
    }
} else {
    header("Location: index.php?error=UnAuthorized Access");
}
?>