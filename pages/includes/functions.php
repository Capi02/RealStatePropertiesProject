<?php
function authenticated()
{
  $auth = $_SESSION["login"];
  if (!$auth) {
    header("location: login.php");
  }
}

function price_formater($var)
{ // format a price 
  printf(number_format($var));
}

function finalPrice($porcent, $price)
{
  $price = ($porcent / 100) * $price;
  return $price;
}
