<?php
session_start();
//if (!empty($_SESSION['email'] && $_SESSION['password'])){
//    header("Location: index.php");
//}

include "../adFunctions/DB_connector.php";
if (isset($_POST['admin_login'])) {
    global $DB_Connector;
    $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
    $password = htmlspecialchars(stripslashes(trim($_POST['password'])));
    $read = "SELECT Email,Password FROM admins";
    $admin_check = mysqli_query($DB_Connector, $read);
    $found = false;
    while ($admins=mysqli_fetch_assoc($admin_check)) {
        if ($email===$admins['Email']) {
            $found = true;
            if ($password !== $admins['Password']) {
                $InPassword = "<h2 style='color: red' align='center'> Incorrect password</h2>";
            } else {
                $_SESSION['admin_email'] = $admins['Email'];
                $_SESSION['admin_password'] = $admins['Password']; //Creating the session containing login Details
                $_SESSION['loggedIn']=true;
                header("Location: ../index.php");
                exit; // add this to stop execution after redirection
            }
        }
    }

    if (!$found) {
        $notAdmin =  "<h2 style='color: red' align='center'>You are not admin</h2>";
    }

}
?>

<!doctype html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/AdminLTE.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <title>Admin Login - GM</title>
</head>
<body class="bg-dark">


<form class="form-horizontal" action="adminlogin.php" method="post">
    <div class="box-body">
        <div class="form-group">

            <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="inputEmail3" placeholder="Email" name="email">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">Password</label>

            <div class="col-sm-10">
                <input type="password" class="form-control" id="inputPassword3" placeholder="Password" name="password">
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <button type="submit" class="btn btn-info pull-right" name="admin_login">Sign in</button>
    </div>
    <?php if(isset($InPassword)){ echo $InPassword;}
    else if (isset($notAdmin)){ echo $notAdmin;}
    ?>
    <!-- /.box-footer -->
</form>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>