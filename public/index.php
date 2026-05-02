<?php
include "../src/config/config_session.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Auth UI</title>
  <script src="https://code.jquery.com/jquery-4.0.0.min.js" 
  integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=" 
  crossorigin="anonymous"></script>
  <link rel="stylesheet" href="style.css">
  <script src="ajax.js"></script>
</head>
<body>

<div class="container" id="container">

  <!-- REGISTER -->
  <div class="form-container sign-up">
    <form id="signup-form" class="signup" action="../src/signup.php" method = "POST">
      <h1>Create Account</h1>
      <p class="error-message"></p>
      <input id="signup-email" type="email" placeholder="Email" name = "email">
      <input id="signup-fname" type="text" placeholder="First Name" name = "fname">
      <input id="signup-lname" type="text" placeholder="Last Name" name = "lname">
      <input id="signup-pwd" type="password" placeholder="Password" name = "password">
      <input id="signup-repeatpwd" type="password" placeholder="Repeat Password" name = "repeatedpassword">
      <button id="signup-submit" name ="submit">Sign Up</button>
    </form>
  </div>

  <!-- LOGIN -->
  <div class="form-container sign-in">
    <form id="login-form" class="login" action="../src/login.php" method="POST">
      <h1>Login</h1>
      <p class="error-message"></p>
      <input id="login-email" type="email" placeholder="Email" name = "email">
      <input id="login-pwd" type="password" placeholder="Password" name = "password">
      <button id="login-submit" name ="submit">Login</button>
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