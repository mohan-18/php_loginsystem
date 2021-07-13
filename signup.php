<?php
$showAlert = false;
$showError = false;

function validate_password($data)
{
    $e = false;
    $f = false;
    for ($i = 0; $i < strlen($data); $i++) {
        if (($data[$i] >= 'a' && $data[$i] <= 'z')) {
            $f = true;
        }
    }
    if (!$f) {
        $e = "password should contain atleast one lowercase character";
        return $e;
    }
    $f = false;
    for ($i = 0; $i < strlen($data); $i++) {
        if (($data[$i] >= 'A' && $data[$i] <= 'Z')) {
            $f = true;
        }
    }
    if (!$f) {
        $e = "password should contain atleast one uppercase character";
        return $e;
    }
    $f = false;
    for ($i = 0; $i < strlen($data); $i++) {
        if (($data[$i] >= 'a' && $data[$i] <= 'z') || ($data[$i] >= 'A' && $data[$i] <= 'Z')) {
        } else {
            $f = true;
        }
    }
    if (!$f) {
        $e = "password should contain atleast one special character";
        return $e;
    }
    return $e;
}
function validate_username($data)
{
    $e = false;
    for ($i = 0; $i < strlen($data); $i++) {
        if (($data[$i] >= 'a' && $data[$i] <= 'z') || ($data[$i] >= 'A' && $data[$i] <= 'Z') || ($data[$i] >= '0' && $data[$i] <= '9') || ($data[$i] == '_')) {
        } else {
            $e = "invalid Username";
        }
    }
    return $e;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partials/_dbconnect.php';
    $username = $_POST["username"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];

    $e = validate_username($username);
    $e2 = validate_password($password);
    if ($e) {
        $showError = $e;
    } else if ($e2) {
        $showError = $e2;
    } else {
        $existSql = "SELECT * FROM `users` WHERE username = '$username'";
        $result = mysqli_query($conn, $existSql);
        $numExistRows = mysqli_num_rows($result);
        if ($numExistRows > 0) {
            $showError = "Username Already Exists";
        } else {
            if (($password == $cpassword)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO `users` ( `username`, `password`) VALUES ('$username', '$hash')";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $showAlert = true;
                } else {
                    $showError = "some error has occured please try after some time ";
                }
            } else {
                $showError = "Passwords do not match";
            }
        }
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>SignUp</title>
</head>

<body>
    <?php require 'partials/_nav.php' ?>
    <?php
    if ($showAlert) {
        echo ' <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Your account is now created and you can login
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div> ';
    }
    if ($showError) {
        echo ' <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> ' . $showError . '
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div> ';
    }
    ?>

    <div class="container my-4">
        <h1 class="text-center">Signup to our website</h1>
        <form action="/loginsystem/signup.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp">
                <small id="emailHelp" class="form-text text-muted">chars digits and underscore are allowed</small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password">
                <small id="emailHelp" class="form-text text-muted">password should contain atleast one lower/upper/special character</small>
            </div>
            <div class="form-group">
                <label for="cpassword">Confirm Password</label>
                <input type="password" class="form-control" id="cpassword" name="cpassword">
                <small id="emailHelp" class="form-text text-muted">Make sure to type the same password</small>
            </div>

            <button type="submit" class="btn btn-primary">SignUp</button>
        </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>