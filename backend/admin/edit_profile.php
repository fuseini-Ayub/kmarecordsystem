 <?php
include './assets/inc/functions.php';
include '../../assets/inc/config.php';
check_login(1);

// Fetch user data from session
$user_data = $_SESSION['user_data'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $age = htmlspecialchars($_POST['age']);
    $gender = htmlspecialchars($_POST['gender']);
    $phone_number = htmlspecialchars($_POST['phone_number']);
    $address = htmlspecialchars($_POST['address']);
    $staffID = htmlspecialchars($_POST['staffID']);
    $userimage = htmlspecialchars($_POST['userimage']); // Assuming you want to change the image URL as well

    // Update user data in the session (and also in the database if applicable)
    $_SESSION['user_data']['name'] = $name;
    $_SESSION['user_data']['email'] = $email;
    $_SESSION['user_data']['age'] = $age;
    $_SESSION['user_data']['gender'] = $gender;
    $_SESSION['user_data']['phone_number'] = $phone_number;
    $_SESSION['user_data']['address'] = $address;
    $_SESSION['user_data']['staffID'] = $staffID;
    $_SESSION['user_data']['userimage'] = $userimage;

    // Redirect to profile page after update
    header('Location: view_profile.php');
    exit;
}

?>
     <?php include './assets/inc/navbar.php'; ?>
     <?php include './assets/inc/sidebar.php'; ?>

     <div class="container profile-container">
         <div class="page-header">
             <h2 class="page-title">Edit Profile</h2>
         </div>
         <div class="row justify-content-center">
             <div class="col-md-6">
                 <div class="profile-card text-center">
                     <form action="edit_profile.php" method="POST">
                         <div class="d-flex justify-content-center">
                             <img src="<?php echo htmlspecialchars($user_data['userimage']); ?>" alt="User Image"
                                 class="profile-img">
                         </div>
                         <div class="profile-info">
                             <h3>Edit Profile</h3>
                             <div class="form-group">
                                 <label for="name">Name:</label>
                                 <input type="text" class="form-control" id="name" name="name"
                                     value="<?php echo htmlspecialchars($user_data['name']); ?>">
                             </div>
                             <div class="form-group">
                                 <label for="email">Email:</label>
                                 <input type="email" class="form-control" id="email" name="email"
                                     value="<?php echo htmlspecialchars($user_data['email']); ?>">
                             </div>
                             <div class="form-group">
                                 <label for="age">Age:</label>
                                 <input type="text" class="form-control" id="age" name="age"
                                     value="<?php echo htmlspecialchars($user_data['age']); ?>">
                             </div>
                             <div class="form-group">
                                 <label for="gender">Gender:</label>
                                 <input type="text" class="form-control" id="gender" name="gender"
                                     value="<?php echo htmlspecialchars($user_data['gender']); ?>">
                             </div>
                             <div class="form-group">
                                 <label for="phone_number">Phone Number:</label>
                                 <input type="text" class="form-control" id="phone_number" name="phone_number"
                                     value="<?php echo htmlspecialchars($user_data['phone_number']); ?>">
                             </div>
                             <div class="form-group">
                                 <label for="address">Address:</label>
                                 <input type="text" class="form-control" id="address" name="address"
                                     value="<?php echo htmlspecialchars($user_data['address']); ?>">
                             </div>
                             <div class="form-group">
                                 <label for="staffID">Staff ID:</label>
                                 <input type="text" class="form-control" id="staffID" name="staffID"
                                     value="<?php echo htmlspecialchars($user_data['staffID']); ?>">
                             </div>
                             <div class="form-group">
                                 <label for="userimage">Image URL:</label>
                                 <input type="text" class="form-control" id="userimage" name="userimage"
                                     value="<?php echo htmlspecialchars($user_data['userimage']); ?>">
                             </div>
                             <button type="submit" class="btn btn-primary mt-3">Update Profile</button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
<script src="./assets/js/theme-toggle.js"></script>
<?php include './assets/inc/footer.php'; ?>
