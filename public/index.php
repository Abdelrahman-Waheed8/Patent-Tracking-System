<?php
session_start();
include "../src/view/signupView.php";
include "../src/view/loginView.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Auth UI</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="container <?php echo (isset($_GET['signup']) && $_GET['signup'] == 'failed') || isset($_SESSION['errorsignup']) ? 'active' : ''; ?>" id="container">

  <!-- REGISTER -->
  <div class="form-container sign-up">
    <form action="../src/signup.php" method = "POST">
      <h1>Create Account</h1>
      <?php
            $view = new signupView();
            $view->displayErrorsignup();
        ?>
      <input type="email" placeholder="Email" name = "email">
      <input type="password" placeholder="Password" name = "password">
      <input type="password" placeholder="Repeat Password" name = "repeatedpassword">
      <button name ="submit">Sign Up</button>
    </form>
  </div>

  <!-- LOGIN -->
  <div class="form-container sign-in">
    <form action="../src/control/loginControl.php">
      <h1>Login</h1>
      <?php
            $view = new loginView();
            $view->displayErrorslogin();
        ?>
      <input type="email" placeholder="Email" name = "email">
      <input type="password" placeholder="Password" name = "password">
      <button name ="submit">Login</button>
    </form>
  </div>

  <!-- PANEL -->
  <div class="overlay-container">
    <div class="overlay">

      <div class="overlay-panel left">
        <h1>Welcome Back!</h1>
        <p>Already have an account?</p>
        <button id="loginBtn">Login</button>
      </div>

      <div class="overlay-panel right">
        <h1>Hello, Friend!</h1>
        <p>Don't have an account?</p>
        <button id="registerBtn">Register</button>
      </div>

    </div>
  </div>

</div>

<script src="script.js"></script>
</body>
</html>