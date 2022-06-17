 <?php
/*
   TC: We will handle just 3 pseudo procedure here.
   1. Registration process
   2. Inserts user info into the database and 
   3. Sends account confirmation email message
 */
 
//TC: Set session variables to be used on profile.php page
$_SESSION['email'] = $_POST['email'];
$_SESSION['first_name'] = $_POST['firstname'];
$_SESSION['last_name'] = $_POST['lastname'];

//TC: Escape all $_POST variables to protect against SQL injections
$first_name = $_POST['firstname']; 
$last_name = $_POST['lastname'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$hash = md5( rand(0,1000) );

//TC: Check if user with that email already exists
$sql = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn,$sql);

//TC: We know user email exists if the rows returned are more than 0
if ( $result->num_rows > 0 ) {
    
    $_SESSION['message'] = 'User with this email already exists!';
    header("location: error.php");
    
}
else {//TC:  Email doesn't already exist in a database, proceed...

    //TC: active is 0 by DEFAULT (no need to include it here)
    $sql = "INSERT INTO users (first_name, last_name, email, password, hash) " 
            . "VALUES ('$first_name','$last_name','$email','$password', '$hash')";

    //TC: Add user to the database
    if ( mysqli_query($conn,$sql) ){

        $_SESSION['active'] = 0; //0 until user activates their account with verify.php
        $_SESSION['logged_in'] = true; // So we know the user has logged in
        $_SESSION['message'] =
                
                 "Great! you're almost done. A confirmation link has been sent to $email,
                  please verify your account by clicking on the link in the message!";

        //TC: Send registration confirmation link (verify.php)
        $to      = $email;
        $subject = 'Account Verification (www.TonyCletus.com)'; //Name of My own website(Edited)
        $message_body = '
        Hello '.$first_name.',

        Thank you for signing up!

        Kindly click this link to activate your account:

        http://localhost/CTC/Login_Register/verify.php?email='.$email.'&hash='.$hash;  

        mail( $to, $subject, $message_body );

        header("location: profile.php"); 

    }

    else {
        $_SESSION['message'] = 'Registration failed!';
        header("location: error.php"); //TC: This will  run only if there's an error
    }

}