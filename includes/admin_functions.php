<?php
// Admin user variables
$admin_id = 0;
$isEditingUser = false;
$username = "";
$email = "";
// Topics variables
$topic_id = 0;
$isEditingTopic = false;
$topic_name = "";
// general variables
$errors = array();
/* - - - - - - - - - -
- Admin users actions
- - - - - - - - - - -*/
if (isset($_POST['update_admin'])) {
    updateAdmin($_POST);
}
// if user clicks the create admin button
if (isset($_POST['create_admin'])) {
    createAdmin($_POST);
}

if (isset($_GET['edit-admin'])) {
    editAdmin($_GET['edit-admin']);
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* - Returns all admin users and their corresponding roles
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

function getAdminUsers(){
    global $conn;
    $sql = "SELECT * FROM users WHERE role='Admin' OR role='Author'";
    $result = mysqli_query($conn, $sql);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $users;
}

/* * * * * * * * * * * * * *
* - Returns all admin roles
* * * * * * * * * * * * * */
function getAdminRoles(){
    global $conn;
    $sql = "SELECT * FROM roles WHERE name='Admin' OR name='Author'";
    $result = mysqli_query($conn, $sql);
    $roles = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $roles;
}
    /* * * * * * * * * * * * * * * * * * * * * * *
    * - Receives new admin data from form
    * - Create new admin user
    * - Returns all admin users with their roles
    * * * * * * * * * * * * * * * * * * * * * * */

function createAdmin($request_values){
    global $conn, $errors;

    // Get values from the form
    $username = $request_values['username'];
    $email = $request_values['email'];
    $password = $request_values['password'];
    $passwordConfirm = $request_values['passwordConfirmation'];
    $role = $request_values['role_id'];



    // Validate form inputs
    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($password)) { array_push($errors, "Password is required"); }
    if (empty($role)) { array_push($errors, "Role is required"); }
    if ($password != $passwordConfirm) { array_push($errors, "Passwords do not match"); }

    // Check if username and email already exists 
    $sql = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user) { // If user exists
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
        }
        if ($user['email'] === $email) {
            array_push($errors, "Email already exists");
        }
    }

    if (empty($errors)){
        // Create SQL query to fetch role name
        $sql = "SELECT name FROM roles WHERE id = $role";

        // Execute the query and fetch the result
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        
        // Get the role name from the result
        $role_name = $row['name'];

        $password = md5($password);
        // Create SQL query to insert new admin user
        $sql = "INSERT INTO users (username, email, password, role, created_at) VALUES ('$username', '$email', '$password', '$role_name', NOW())";

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Admin user created successfully";
            header('location: users.php');
            exit(0);
        } else {
            array_push($errors, "Failed to create admin user");
        }
    }
}

/* * * * * * * * * * * * * * * * * * * * *
* - Takes admin id as parameter
* - Fetches the admin from database
* - sets admin fields on form for editing
* * * * * * * * * * * * * * * * * * * * * */
function editAdmin($adminId){
    // Delete the variable $admin_id in global
    global $conn, $username, $isEditingUser, $email, $admin_id;
    $admin_id = $adminId;
    $sql = "SELECT * FROM users WHERE id=$admin_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $admin = mysqli_fetch_assoc($result);


    // set form values on the form to be updated

    $username = $admin['username'];
    $email = $admin['email'];

    $isEditingUser = true;
}
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
    * - Receives admin request from form and updates in database
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function updateAdmin($request_values){
    global $conn, $errors;

    // Get values from the form
    $username = $request_values['username'];
    $email = $request_values['email'];
    $password = $request_values['password'];
    $passwordConfirm = $request_values['passwordConfirmation'];
    $role = $request_values['role_id'];
    $admin_id = $request_values['admin_id'];


    // Validate form inputs
    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($password)) { array_push($errors, "Password is required"); }
    if (empty($role)) { array_push($errors, "Role is required"); }
    if ($password != $passwordConfirm) { array_push($errors, "Passwords do not match"); }


    if (empty($errors)){
        // Create SQL query to fetch role name
        $sql = "SELECT name FROM roles WHERE id = $role";

        // Execute the query and fetch the result
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
            
        // Get the role name from the result
        $role_name = $row['name'];
        $password = md5($password);

        // create SQL query
        $query = "UPDATE users SET password='$password', role='$role_name' WHERE id=$admin_id";
        
        // echo $query;
        // execute query
        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Admin user updated successfully";
            header('location: users.php');
            exit(0);
        } else {
            array_push($errors, "Failed to update admin user");
        }
    }
}
