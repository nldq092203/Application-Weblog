<?php
// variable declaration
$username = "";
$email = "";
$errors = array();

//REGISTER
if (isset($_POST['register_btn'])) {
    $username = esc($_POST['username']);
    $email = esc($_POST['email']);
    $password = esc($_POST['password']);
    $passwordConfirm = esc($_POST['password_confirm']);
    $role = "Author";

    // Validate form inputs
    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($password)) { array_push($errors, "Password is required"); }
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

    // If no errors, proceed with registration
    if (empty($errors)) {
        $password = md5($password); // encrypt password
        $date = date('Y-m-d H:i:s');
        $sql = "INSERT INTO users (username, email, password, role, updated_at) VALUES ('$username', '$email', '$password', '$role', '$date')";
        mysqli_query($conn, $sql);
        $reg_user_id = mysqli_insert_id($conn); // get id of created user
        $_SESSION['user'] = getUserById($reg_user_id); // put logged in user into session array

        // Redirect user based on role
        if ($_SESSION['user']['role'] == "Admin") {
            $_SESSION['message'] = "You are now logged in";
            header('location: ' . BASE_URL . '/admin/dashboard.php');
            exit(0);
        } else {
            $_SESSION['message'] = "You are now logged in";
            header('location: index.php');
            exit(0);
        }
    }
}

// LOG USER IN
if (isset($_POST['login_btn'])) {
$username = esc($_POST['username']);
$password = esc($_POST['password']);
if (empty($username)) {
array_push($errors, "Username required");
}
if (empty($password)) {
array_push($errors, "Password required");
}
if (empty($errors)) {
$password = md5($password); // encrypt password
$sql = "SELECT * FROM users WHERE username='$username' and password='$password' LIMIT 1";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
// get id of created user
$reg_user_id = mysqli_fetch_assoc($result)['id'];
//var_dump(getUserById($reg_user_id)); die();
// put logged in user into session array
$_SESSION['user'] = getUserById($reg_user_id);
// if user is admin, redirect to admin area
if ($_SESSION['user']['role'] == "Admin") {
$_SESSION['message'] = "You are now logged in";
// redirect to admin area
header('location: ' . BASE_URL . '/admin/dashboard.php');
exit(0);
} else {
$_SESSION['message'] = "You are now logged in";
// redirect to public area
header('location: index.php');
exit(0);
}
} else {
array_push($errors, 'Wrong credentials');
}
}
}
// Get user info from user id
function getUserById($id)
{
    global $conn; //rendre disponible, à cette fonction, la variable de connexion $conn
    $sql = "SELECT * FROM users WHERE id='$id' LIMIT 1"; //requête qui récupère le user et son rôle
    $result = mysqli_query($conn, $sql) ;//la fonction php-mysql
    $user = mysqli_fetch_assoc($result) ;//je met $result au format associatif
    return $user;
}
// escape value from form
function esc(String $value)
{
// bring the global db connect object into function
global $conn;
$val = trim($value); // remove empty space sorrounding string
$val = mysqli_real_escape_string($conn, $value);
return $val;
}