<?php

// Include config file

require_once 'config.php';

 

// Define variables and initialize with empty values

$username = $password = "";

$username_err = $password_err = "";

 

// Processing form data when form is submitted

if($_SERVER["REQUEST_METHOD"] == "POST"){

 

    // Check if username is empty

    if(empty(trim($_POST["username"]))){

        $username_err = 'Please enter username.';

    } else{

        $username = trim($_POST["username"]);

    }

    

    // Check if password is empty

    if(empty(trim($_POST['password']))){

        $password_err = 'Please enter your password.';

    } else{

        $password = trim($_POST['password']);

    }

    

    // Validate credentials

    if(empty($username_err) && empty($password_err)){

        // Prepare a select statement

        $sql = "SELECT username, password FROM user WHERE username = :username";

        

        if($stmt = $pdo->prepare($sql)){

            // Bind variables to the prepared statement as parameters

            $stmt->bindParam(':username', $param_username, PDO::PARAM_STR);

            

            // Set parameters

            $param_username = trim($_POST["username"]);

            

            // Attempt to execute the prepared statement

            if($stmt->execute()){

                // Check if username exists, if yes then verify password

                if($stmt->rowCount() == 1){

                    if($row = $stmt->fetch()){

                        $hashed_password = $row['password'];

                        if(password_verify($password, $hashed_password)){

                            /* Password is correct, so start a new session and

                            save the username to the session */

                            session_start();

                            $_SESSION['username'] = $username;      

                            header("location: welcome.php");

                        } else{

                            // Display an error message if password is not valid

                            $password_err = 'The password you entered was not valid.';

                        }

                    }

                } else{

                    // Display an error message if username doesn't exist

                    $username_err = 'No account found with that username.';

                }

            } else{

                echo "Oops! Something went wrong. Please try again later.";

            }

        }

        

        // Close statement

        unset($stmt);

    }

    

    // Close connection

    unset($pdo);

}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Contact-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Footer-Basic.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Dark.css">
    <link rel="stylesheet" href="assets/css/Navigation-with-Search1.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body style="margin:80px;background-color:rgb(33,74,128);">
    <div></div>
    <div class="row">
        <div class="col-md-12">
            <h1 style="background-position:center;color:rgb(30,40,51);font-size:60px;">Welcome</h1>
        </div>
    </div>
    <div class="login-dark" style="background-color:rgb(33,74,128);height:534px;">
        <form id="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2 class="sr-only">Login Form</h2>
            <div class="illustration"><i class="icon ion-ios-locked-outline"></i></div>
            <div class="form-group" <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <input class="form-control" type="Text" name="username" placeholder="User Name" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group" <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <input class="form-control" type="password" name="password" placeholder="Password">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-block" type="submit">Log In</button>
            </div>
            <p class="forgot">Don't have an account? <a href="register.php">Register now</a>.</p>
            <a href="#" class="forgot">Forgot your email or password?</a>
            </form>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>