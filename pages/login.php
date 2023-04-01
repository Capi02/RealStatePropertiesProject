<?php

require_once "../vendor/autoload.php";
require_once "../pages/auth.php";
require "../pages/includes/config/database.php"; /* DB connection */

$clientID =  "300621167353-pm1dmhq6rqv0rp4538rbop1fd99jitfo.apps.googleusercontent.com";
$secret = "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";

// Google API Client
$gclient = new Google_Client();

$gclient->setClientId($clientID);
$gclient->setClientSecret($secret);
$gclient->setRedirectUri("http://localhost/Properties-project/pages/login.php");


$gclient->addScope('email');
$gclient->addScope('profile');

if (isset($_GET['code'])) {
  // Get Token
  $token = $gclient->fetchAccessTokenWithAuthCode($_GET['code']);

  // Check if fetching token did not return any errors
  if (!isset($token['error'])) {
    // Setting Access token
    $gclient->setAccessToken($token['access_token']);

    // store access token
    $_SESSION['access_token'] = $token['access_token'];

    // Get Account Profile using Google Service
    $gservice = new Google_Service_Oauth2($gclient);

    // Get User Data
    $udata = $gservice->userinfo->get();
    // Get User Data
    $udata = $gservice->userinfo->get();
    foreach ($udata as $k => $v) {
      $_SESSION['login_' . $k] = $v;
    }
    $_SESSION['ucode'] = $_GET['code'];

    
    $name = $_SESSION["login_name"];
    $username = $_SESSION["login_given"];
    $lastname = $_SESSION["login_familyName"];
    $email = filter_var($_SESSION["login_email"], FILTER_VALIDATE_EMAIL);
   
    if ( $name && $lastname && $email){
      $query = "SELECT * FROM users WHERE email = '$email'";
      $pdo = $db->prepare($query);
      $pdo->execute();
      $rs = $pdo->fetch(PDO::FETCH_ASSOC);
  
      if( $pdo->rowCount() == 0){
        $query = "INSERT INTO users (name, lastname, email, password) VALUES ( '$name', '$lastname', '$email', '')";
        $pdo = $db->prepare($query);
        $pdo->execute();
  
      }else if( $pdo->rowCount() > 0){
  
        $_SESSION['user_id'] = $rs["id"];
        $_SESSION['username'] = $rs["username"];
        $_SESSION['email'] = $rs["email"];
        $_SESSION['login'] = true;
        $_SESSION["property_id"] = ""; // DECLARING THESE VARIABLES AS EMPTY, THEN ASSIGNING A VALUE OF THEM
        $_SESSION["property_price"] = "";
      }
    }

    header('location: index.php');
    exit;
  }
}

include "../pages/includes/head.php";
include "../pages/includes/mobileNavBar.php";
include "../pages/includes/navBar.php";



$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $username = $_POST["username"];
  $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
  $password = $_POST["password"];
  $confirm_pasword = $_POST["confirm_password"];

  if (!$email) {
    $errors[] = "The email is required, or it is not valid.";
  }
  if (!$password) {
    $errors[] = "The password is required";
  }
  if ($password != $confirm_pasword) {
    $errors[] = "Passwords are different, try again";
  }

  if (empty($errors)) {

    $query = "SELECT * FROM users WHERE email = '$email' "; //creamos el query
    $st = $db->prepare($query); // lo preparamos
    $st->execute();  // lo ejecutamos
    $rs = $st->fetch(PDO::FETCH_ASSOC); // obtenemos los resultados en un arreglo associativo

    echo '<pre>';
    var_dump($rs);
    echo '</pre>';

    if ($st->rowCount() > 0) {
      $auth = password_verify($password, $rs["password"]); // password ingresado / password base de datos

      if ($auth) {  // usuario autenticado

        $_SESSION['user_id'] = $rs["id"];
        $_SESSION['username'] = $rs["username"];
        $_SESSION['email'] = $rs["email"];
        $_SESSION['login'] = true;
        $_SESSION["property_id"] = ""; // DECLARING THESE VARIABLES AS EMPTY, THEN ASSIGNING A VALUE OF THEM
        $_SESSION["property_price"] = "";

        header('location: index.php');
      } else {
        $errors[] = "The password is incorrect";
      }
    } else {
      $errors[] = "The user doesn't exist";
    }
  }
}
?>

<!-- <div class="hero page-inner overlay" style="background-image: url('images/hero_bg_1.jpg')"> -->
<div class="container">

</div>
<!-- </div> -->

<div class="section">
  <div class="container">
    <div class="row justify-content-center align-items-center mt-5">
      <div class="col-lg-9 text-center mt-5">
        <h1 class="heading" data-aos="fade-up"> Log In</h1>

        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
          <ol class="breadcrumb text-center justify-content-center">
            <li class="breadcrumb-item"><a href="../pages/index.php" style="color:#000">Home</a></li>
            <li class="breadcrumb-item active text-black-50" aria-current="page">
              Log In
            </li>
          </ol>
        </nav>

      </div>
    </div>
    <div class="row">
      <div class="col-lg-8 " data-aos="fade-up" data-aos-delay="200" style="margin: 0 auto;">
        <form action="#" method="POST">
          <div class="row" style="display:flex; justify-content:center;">

            <?php
            foreach ($errors as $error) { ?>
              <div class="alert alert-danger col-7 text-center">
                <p><?php echo $error ?></p>
              </div>
            <?php } ?>

            <div class="col-7 mb-3">
              <input type="text" class="form-control" placeholder="Your Username" name="username" />
            </div>
            <div class="col-7 mb-3">
              <input type="email" class="form-control" placeholder="Your Email" name="email" />
            </div>
            <div class="col-7 mb-3">
              <input type="password" class="form-control" placeholder="Password" name="password" />
            </div>
            <div class="col-7 mb-3">
              <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" />
            </div>
            <div class="col-7">
              <input style=" width:100%; " type="submit" value="Log In" class="btn btn-primary" />
              <p class="text-center mt-4">Or</p>
              <a href="<?= $gclient->createAuthUrl() ?>" style="display: flex; justify-content:center"><img width="20px" height="20px" class="me-2" alt="Google sign-in" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png" />Log in with Google</a>

            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- /.untree_co-section -->

<?php include "../pages/includes/footer.php" ?>

<!-- Preloader -->
<div id="overlayer"></div>
<div class="loader">
  <div class="spinner-border" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>

<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/tiny-slider.js"></script>
<script src="../js/aos.js"></script>
<script src="../js/navbar.js"></script>
<script src="../js/counter.js"></script>
<script src="../js/custom.js"></script>
</body>

</html>