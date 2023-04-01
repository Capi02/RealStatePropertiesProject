
<nav class="site-nav">
  <div class="container">
    <div class="menu-bg-wrap">
      <div class="site-navigation">
        <a href="../pages/index.php" class="logo m-0 float-start">Capi Real State.com</a>

        <ul class="js-clone-nav d-none d-lg-inline-block text-start site-menu float-end">
          <li class="active"><a href="../pages/index.php">Home</a></li>
          <li>
            <a href="../pages/properties.php">Properties</a>
          </li>
          <li><a href="../pages/about.php">About</a></li>
          <li><a href="../pages/contact.php">Contact Us</a></li>
          <?php
          if (!isset($_SESSION["login"]) && !isset($_SESSION["login_verifiedEmail"]))  { ?>
            <li><a href="../pages/register.php">Register</a></li>
            <li><a href="../pages/login.php">Login</a></li>
          <?php } else { ?>
            <li><a href="../pages/register.php">Register</a></li>
            <li class="has-children">
            <a href="#">Profile</a>
            <ul class="dropdown">
              <li><a href="../pages/purchases.php">Purchases</a></li>
              <li><a href="../pages/signOut.php">Sign Out</a></li>
            </ul>
          </li>
  
         <?php } ?>
        </ul>

        <a href="#" class="burger light me-auto float-end mt-1 site-menu-toggle js-menu-toggle d-inline-block d-lg-none" data-toggle="collapse" data-target="#main-navbar">
          <span></span>
        </a>
      </div>
    </div>
  </div>
</nav>