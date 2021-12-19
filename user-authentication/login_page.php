<!doctype html>
<html lang="en">
<?php
//require ssl
/*
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
*/
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PHP User Registration & Login System Demo</title>
    <!-- jQuery + Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>

<body>
    <?php include('navbar/Navbar.php'); ?>
    <!-- Header -->
    <?php include('header.php'); ?>

    <!-- Login script -->
    <?php include('user-authentication/controllers/login.php'); ?>
    <br><br>
    <!-- Login form -->
    <div class="login">
        <div class="vertical-center">
            <div class="inner-block">

                <form action="" method="post">
                    <text class="title">Login</text>
                    <text class="subtitle">
                    <?php echo $accountNotExistErr; ?>
                    <?php echo $emailPwdErr; ?>
                    <?php echo $verificationRequiredErr; ?>
                    <?php echo $email_empty_err; ?>
                    <?php echo $pass_empty_err; ?>
                    </text>
                    <div class="input-container ic1">
                        <input type="email" name="email_signin" id="email_signin" class="input" required placeholder=""/>
                        <div class="cut"></div>
                        <label class="placeholder">Email</label>
                    </div>

                    <div class="input-container ic1">
                        <input type="password" name="password_signin" id="password_signin" class="input" required placeholder=""/>
                        <div class="cut"></div>
                        <label class="placeholder">Password</label>
                    </div>

                    <button type="submit" name="login" id="sign_in" class="submit">Sign
                        in</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>