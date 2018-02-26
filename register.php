<?php

// Include config file

require_once 'config.php';

 

// Define variables and initialize with empty values

$username = $password = $confirm_password = "";

$username_err = $password_err = $confirm_password_err = "";

 

// Processing form data when form is submitted

if($_SERVER["REQUEST_METHOD"] == "POST"){

 

    // Validate username

    if(empty(trim($_POST["username"]))){

        $username_err = "Please enter a username.";

    } else{

        // Prepare a select statement

        $sql = "SELECT iduser FROM users WHERE username = ?";

        

        if($stmt = mysqli_prepare($link, $sql)){

            // Bind variables to the prepared statement as parameters

            mysqli_stmt_bind_param($stmt, "s", $param_username);

            

            // Set parameters

            $param_username = trim($_POST["username"]);

            

            // Attempt to execute the prepared statement

            if(mysqli_stmt_execute($stmt)){

                /* store result */

                mysqli_stmt_store_result($stmt);

                

                if(mysqli_stmt_num_rows($stmt) == 1){

                    $username_err = "This username is already taken.";

                } else{

                    $username = trim($_POST["username"]);

                }

            } else{

                echo "Oops! Something went wrong. Please try again later.";

            }

        }

         

        // Close statement

        mysqli_stmt_close($stmt);

    }

    

    // Validate password

    if(empty(trim($_POST['password']))){

        $password_err = "Please enter a password.";     

    } elseif(strlen(trim($_POST['password'])) < 6){

        $password_err = "Password must have atleast 6 characters.";

    } else{

        $password = trim($_POST['password']);

    }

    

    // Validate confirm password

    if(empty(trim($_POST["confirm_password"]))){

        $confirm_password_err = 'Please confirm password.';     

    } else{

        $confirm_password = trim($_POST['confirm_password']);

        if($password != $confirm_password){

            $confirm_password_err = 'Password did not match.';

        }

    }

    

    // Check input errors before inserting in database

    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        

        // Prepare an insert statement

        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

         

        if($stmt = mysqli_prepare($link, $sql)){

            // Bind variables to the prepared statement as parameters

            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            

            // Set parameters

            $param_username = $username;

            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            

            // Attempt to execute the prepared statement

            if(mysqli_stmt_execute($stmt)){

                // Redirect to login page

                header("location: login.php");

            } else{

                echo "Something went wrong. Please try again later.";

            }

        }

         

        // Close statement

        mysqli_stmt_close($stmt);

    }

    

    // Close connection

    mysqli_close($link);

}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>demo</title>
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
            <h1 style="background-position:center;color:rgb(30,40,51);font-size:60px;">Welcome </h1>
        </div>
    </div>
    <div class="login-dark" style="background-color:rgb(33,74,128);height:534px;">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="padding:24px;width:363px;">
            <h2 class="sr-only">Registation Form</h2>
            <div class="illustration"><i class="icon ion-ios-locked-outline"></i></div>
            <div class="form-group" <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <input class="form-control" type="Text" name="username" placeholder="User Name" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group" <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <input class="form-control" type="password" name="password" placeholder="Password" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group" <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <input class="form-control" type="password" name="confirm_password" placeholder="Confirm Password" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-block" type="submit">Register</button>
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-block" type="Reset" value="Reset">Reset</button>
            </div>
            <div><p>Already have an account? <a href="login.php">Login here.</a></p> </div>
            </form>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>