<?php require_once('../config.php') ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
<?php require_once('inc/header.php') ?>

<head>
  <link rel="stylesheet" type="text/css" href="./../form/css/style.css">
  <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a81368914c.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
  <script>
    start_loader()
  </script>
  <img class="wave" src="./../form/img/wave.png">
  <div class="container">
    <div class="img">
      <img src="./../form/img/bg_hr_portal.svg">
    </div>
    <div class="login-content">
      <form id="login-frm" action="" method="post">
        <img src="./../form/img/avatar.svg"><br><br>
        <h2 class="title">HR PORTAL</h2>
        <a href="http://192.168.1.28/hr-portal/"><i class="fa fa-home" aria-hidden="true"></i> Go to HR Portal</a>
        </br>
        <div class="input-div one">
          <div class="i">
            <i class="fas fa-user"></i>
          </div>
          <div class="div">
            <!-- <h5>Username</h5> -->
            <input type="text" class="form-control" autofocus name="username" placeholder="Username">
          </div>
        </div>
        <div class="input-div pass">
          <div class="i">
            <i class="fas fa-lock"></i>
          </div>
          <div class="div">
            <!-- <h5>Password</h5> -->
            <input type="password" class="form-control" name="password" placeholder="Password">
          </div>
        </div>
        <!-- <a href="#">Forgot Password?</a> -->
        <button type="submit" class="btn btn-success btn-block">LOGIN</button>
        <div class="text-center">
          <small>Copyright © Telford Svc. Phils. Inc. </small>
        </div>
      </form>
    </div>
  </div>
  <script type="text/javascript" src="./../form/js/main.js"></script>
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <script>
    $(document).ready(function() {
      end_loader();
    })
  </script>
</body>

</html>