<?php
require_once "config.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

  //Ab hum check krenge ki username empty to nhi hai
  if (empty(trim($_POST["username"]))) {
    $username_err = "Username cannot be blank";
  } else {
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
      mysqli_stmt_bind_param($stmt, "s", $param_username);

      //Set the value of param username
      $param_username = trim($_POST['username']);

      //Try to execute this statement
      if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) == 1) {
          $username_err = "This username is already taken";
        } else {
          $username = trim($_POST['username']);
        }
      } else {
        echo "Something went wrong";
      }
    }
  }
  mysqli_stmt_close($stmt);


  //Check for password
  if (empty(trim($_POST['password']))) {
    $password_err = "Password cannot be blank";
  } elseif (strlen(trim($_Post['password'])) == 5) {
    $password_err = "Password cannot be less that 5 characters";
  } else {
    $password = trim($_POST['password']);
  }

  //Check for confirm password field
  if (trim($_POST['confirm_password']) != trim($_POST['password'])) {
    $password_err = "Passwords should match";
  }

  //if there were no errors, go and insert into database
  if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
    $sql = "INSERT INTO users (username, password) VALUES (?,?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
      mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $param_username,
        $param_password
      );

      //Set these parameters
      $param_username = $username;
      $param_password = password_hash($password, PASSWORD_DEFAULT);

      //Try to execute the query
      if (mysqli_stmt_execute($stmt)) {
        header("location: login.php");
      } else {
        echo "Something went wrong.....cannot redirect!";
      }
    }
    mysqli_stmt_close($stmt);
  }
  mysqli_close($conn);
}
?>




<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PHP Login System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">PHP Login System</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled">Contact Us</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container mt-4">
    <h3>Please Register Here!</h3>
    <hr>
    <form class="row g-3" action="" method="post">
      <div class="col-md-6">
        <label for="inputEmail4" class="form-label">Username</label>
        <input type="text" class="form-control" name = "username" id="inputEmail4" placeholder="Enter your username">
      </div>
      <div class="col-md-6">
        <label for="inputPassword4" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" id="inputPassword4" placeholder="Enter password here">
      </div>
      <div class="col-md-6">
        <label for="inputPassword4" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" name="confirm_password" id="inputPassword" placeholder="Re-enter your password">
      </div>
      <div class="col-12">
        <label for="inputAddress2" class="form-label">Address</label>
        <input type="text" class="form-control" id="inputAddress" placeholder="Apartment, studio, or floor">
      </div>
      <div class="col-md-6">
        <label for="inputCity" class="form-label">City</label>
        <input type="text" class="form-control" id="inputCity">
      </div>
      <div class="col-md-4">
        <label for="inputState" class="form-label">State</label>
        <select id="inputState" class="form-select">
          <option selected>Choose...</option>
          <option>...</option>
        </select>
      </div>
      <div class="col-md-2">
        <label for="inputZip" class="form-label">Zip</label>
        <input type="text" class="form-control" id="inputZip">
      </div>
      <div class="col-12">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="gridCheck">
          <label class="form-check-label" for="gridCheck">
            Check me out
          </label>
        </div>
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-primary">Sign in</button>
      </div>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>