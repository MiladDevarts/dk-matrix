<?php
include_once 'api/functions.php';


$allInOne_products = get_products("https://www.digikala.com/ajax/product-list/plp_10579405/");
$hard_products = get_products("https://www.digikala.com/ajax/product-list/plp_10366314/");
$laptop_products = get_products("https://www.digikala.com/ajax/product-list/plp_10578881/");
