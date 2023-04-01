<?php if (isset($_POST["btnBuy"])) {
  header("Location: checkout.php");
} 

  include "../pages/includes/head.php";
  include "../pages/includes/mobileNavBar.php";
  include "../pages/includes/navBar.php";
  require "../pages/includes/config/database.php"; /* DB connection */
?>

<?php

extract($_GET); // EXTRAYENDO LOS VALORES DEL METHODO POST
/* var_dump($id); */ // REVISANDO EL VALOR QUE TIENE LA VARIABLE $ID QUE SE ENVIO EN EL POST

try {
  if (isset($id)) {

    $query = "SELECT * FROM properties INNER JOIN images_path AS img ON img.property_id = {$id} AND properties.id = {$id} INNER JOIN cities ON properties.city_id = cities.id";
    $st = $db->prepare($query);
    $st->execute();

  }

  $rs = $st->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
  echo $e;
} ?>

<div class="hero page-inner overlay" style="background-image: url(<?php echo $rs["path_3"] ?>)">
  <div class="container">
    <div class="row justify-content-center align-items-center">
      <div class="col-lg-9 text-center mt-5">
        <h1 class="heading" data-aos="fade-up">
          <?php echo $rs["address"] ?>
        </h1>

        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
          <ol class="breadcrumb text-center justify-content-center">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item">
              <a href="properties.php">Properties</a>
            </li>
            <li class="breadcrumb-item active text-white-50" aria-current="page">
              <?php $rs['address'] ?>
            </li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
</div>

<div class="section">
  <div class="container">
    <div class="row justify-content-between" id="infoContainer">
      <div class="col-lg-7">
        <div class="img-property-slide-wrap">
          <div class="img-property-slide">
            <?php for ($i = 1; $i <= 5; $i++) { ?>
              <img src=<?php echo $rs["path_" . $i] ?> alt="Property Image" class="img-fluid" loading="lazy"/>
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <p class="meta" style="font-weight: 600;"> <?php echo ucfirst($rs["city"] . ", " . strtoupper($rs["country"])) ?></p>

        <h3 class="heading text-primary mb-3"> $<?php price_formater($rs["price"])?> </h3> <!-- That function takes a value that will format -->

        <p class="text-black-50 text-justify"><?php echo $rs["description"] ?></p>

        <div class="d-block agent-box p-5">
          <div class="img mb-4">
            <img src="../images/person_2-min.jpg" alt="Image" class="img-fluid" />
          </div>
          <div class="text">
            <h3 class="mb-0">Alicia Huston</h3>
            <div class="meta mb-3">Real Estate</div>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Ratione laborum quo quos omnis sed magnam id ducimus saepe
            </p>
            <ul class="list-unstyled social dark-hover d-flex">
              <li class="me-1">
                <a href="#"><span class="icon-instagram"></span></a>
              </li>
              <li class="me-1">
                <a href="#"><span class="icon-twitter"></span></a>
              </li>
              <li class="me-1">
                <a href="#"><span class="icon-facebook"></span></a>
              </li>
              <li class="me-1">
                <a href="#"><span class="icon-linkedin"></span></a>
              </li>
            </ul>
            <div>

            <form action="checkout.php" method="POST">
              <input type="hidden" name="btnBuy" value="<?php echo $id ?>">
              <button type ="submit" class="btn btn-primary w-100">Apart Now</button>
            </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container mt-5" id="map_container">
 
    <div id="map" style="height: 400px; margin:0; margin-bottom: 5rem"></div>
   

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAq6YpHlNS9VE1aexwb4g9Fid4Skd2fXh0"></script>
    <script>

      let api = "AIzaSyBYGwd76vzdChIFKHDarA2hNlytNGjhrT0" // api key

      document.addEventListener("DOMContentLoaded", initMap)

      function initMap() {
        // Request permission from the user
        navigator.geolocation.getCurrentPosition(function(position) {
          // Get the user's location

          var lat = <?php echo $rs["lat"]?>  
          var lng = <?php echo $rs["lng"]?>/*  */

          distance = calculateDistance(position.coords.latitude, position.coords.longitude, lat, lng)
          distance = distance.toFixed(2)

          mapContainer = document.querySelector("#map_container");
          const distance_text = document.createElement("h4");
          distance_text.textContent = `The distance between you and the house is: ${distance} Km`;
          mapContainer.appendChild(distance_text);
        
          // Create a map instance
          var map = new google.maps.Map(document.getElementById('map'), {
            center: {
              lat: lat,
              lng: lng
            },
            zoom: 12
          });

          // Mark the user's location on the map
          var marker = new google.maps.Marker({
            position: {
              lat: lat,
              lng: lng
            },
            map: map,
            title: 'Your Location'
          });
        });   
      }
    </script>

  </div>
</div>

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
<script src="../js/functions"></script>

</body>

</html>