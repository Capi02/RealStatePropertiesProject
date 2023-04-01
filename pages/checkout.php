<?php
include "../pages/includes/head.php";
require "../pages/includes/navBar.php";

authenticated();

include "../pages/includes/mobileNavBar.php";
require "../pages/includes/config/database.php"; /* DB connection */

extract($_POST);
$_SESSION["property_id"] = $rs["property_id"] ?? $btnBuy; // assigning the id of the property

try {
  if (isset($_POST["btnBuy"])) {
    $query = "call getPropertyById($btnBuy)";
    $st = $db->prepare($query);
    $st->execute();
    $rs = $st->fetch(PDO::FETCH_ASSOC);   
  }
} catch (PDOException $error) {
  echo $error;
} ?>

<div class="section">

  <div class="container checkout-container" style="margin-top: 4rem;">
    <div class="row">
      <div class="col">
        <form action="#" method="POST" id="check_out_form">
          <div class="row form-container h-100">
            <h2 class="text-center mb-4"> Checkout</h2>
            <div class="col-6 mb-3">
              <input type="hidden" name="btnBuy" value="<?php echo $btnBuy ?>">
              <input type="text" class="form-control" placeholder="Your username" name="name" id="username" />
            </div>
            <div class="col-6 mb-3">
              <input type="tel" class="form-control" placeholder="Your cellphone" name="cellphone" id="cellphone" />
            </div>
            <div class="col-12 mb-3">
              <input type="email" class="form-control" placeholder="Your email" name="email" id="email" />
            </div>

            <!-- <script src="https://www.paypal.com/sdk/js?client-id=AXP0Y6izx1thUds7OHlSZDIWLQD5r4sNZg2S9vU_cQagh9_j6jtI4q9PcohsgmICkq17YlQsdVcXMmLf&currency=MXN"></script> -->

            <!-- sanbox account -->
            <!-- email: sb-x3t43j25061878@personal.example.com -->
            <!-- password: ces$6N#W -->

            <div class="col-12">
              <div id="smart-button-container">
                <div style="text-align: center;">
                  <div id="paypal-button-container">
                    <!-- Set up a container element for the button -->


                    <!-- Include the PayPal JavaScript SDK -->
                    <script src="https://www.paypal.com/sdk/js?client-id=AXP0Y6izx1thUds7OHlSZDIWLQD5r4sNZg2S9vU_cQagh9_j6jtI4q9PcohsgmICkq17YlQsdVcXMmLf&currency=MXN"></script>

                    <script>
                      function initPayPalButton() {
                        paypal.Buttons({
                          style: {
                            shape: 'pill',
                            color: 'blue',
                            layout: 'horizontal',
                            label: 'pay',

                          },
                          createOrder: function(data, actions) {
                            return actions.order.create({
                              purchase_units: [{
                                "amount": {
                                  "currency_code": "MXN",
                                  "value": 100
                                }
                              }]
                            });
                          },
                          onApprove: function(data, actions) {
                            return actions.order.capture().then(function(orderData) {
                              const username = document.querySelector("#username").value = "";
                              const cellphone = document.querySelector("#cellphone").value = "";
                              const email = document.querySelector("#email").value = "";

                              fetch("../data/checkOutConfirmation.php", {
                                  method: "POST",
                                  headers: {
                                    "Content-Type": "application/json",
                                  },
                                  body: JSON.stringify({
                                    btn_order: "btn_order",
                                    username: username,
                                    cellphone: cellphone,
                                    email: email,
                                    user_id: "<?php echo $_SESSION["user_id"]?>",
                                    property_id: "<?php echo $_SESSION["property_id"]?>",
                                    property_price: "<?php echo $_SESSION["property_price"]?>",
                                  }),
                                })
                              
                              // Full available details
                              console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                              // Show a success message within this page, e.g.
                              const element = document.getElementById('paypal-button-container');
                              element.innerHTML = '';
                              element.innerHTML = '<h3>Thank you for your payment!</h3>';
                              // Or go to another URL:  actions.redirect('thank_you.html');

                            });
                          },
                          onError: function(err) {
                            console.log(err);
                          }
                        }).render('#paypal-button-container');
                      }
                      initPayPalButton();
                    </script>
                  </div>
                </div>
              </div>
              <!-- <button type="submit" name="btn_order" class="btn btn-primary w-100">ENVIAR</button> -->
            </div>
          </div>
        </form>
      </div>
      <div class="col">
        <table class=" product_table table h-100 text-center">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Property</th>
              <th scope="col">Country</th>
              <th scope="col">City</th>
              <th scope="col">Price</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <?php $price = $rs["price"] ?>
              <th style="display:flex; align-items:center; height:100%; justify-content:center;" scope="row">1</th>
              <td style="vertical-align: baseline;"><img src="<?php echo $rs["path_1"] ?>" alt="property_image" height="120" width="85%"></td>
              <td style="display:flex; align-items:center; height:100%; justify-content:center;"><?php echo $rs["country"] ?></td>
              <td style="vertical-align:baseline;"><?php echo $rs["city"] ?></td>
              <td style="display:flex; align-items:center; height:100%; justify-content:center;"><?php $total = price_formater($price) ?></td>
            </tr>
          </tbody>
          <tfoot style="text-align: right;">
            <?php $final_price = finalPrice(2, $price) ?>
            <td colspan="5">The amount to separate the house is only 2%.<span style="font-weight: 600; margin-left: 20px;">Total: </span>$<?php echo $final_price; ?></td>
            <?php $_SESSION["property_price"] = (int)$final_price; ?>
          </tfoot>

        </table>
      </div>
    </div> <!-- ROW -->
  </div> <!-- CONTAINER -->
</div> <!-- SECTION -->

</div>
</section>

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
<script src="../js/APIs/insertingData.js"></script>
</body>

</html>