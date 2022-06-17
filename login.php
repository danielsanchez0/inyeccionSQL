<?php
/* proceso de login, chequea si un usuario existe y el proceso es correcto */

$email = $_POST['email'];
$pass = $_POST['password'];

$sql = "SELECT * FROM users WHERE email= '$email' AND password = '$pass'";
$result = mysqli_query($conn,$sql);


if ( $result->num_rows == 0 ){ // User doesn't exist
    $_SESSION['message'] = "No existe un usuario con este correo electronico!";
    header("location: error.php");
}
else {
    $user = $result->fetch_assoc();
      
    $_SESSION['email'] = $user['email'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    $_SESSION['active'] = $user['active'];
        
    // This is how we'll know the user is logged in
    $_SESSION['logged_in'] = true;

    header("location: profile.php");
}
