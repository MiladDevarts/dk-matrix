<?php
function fa_numbers($input)
{
    $fa_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    $en_num = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    return str_replace($en_num, $fa_num, (string)$input);
}

function fa_price($input)
{
    return fa_numbers(number_format(intval($input)));
}

function get_plp_response($url) {
    $options = array(
        CURLOPT_RETURNTRANSFER => true,   // return web page
        CURLOPT_HEADER         => false,  // don't return headers
        CURLOPT_FOLLOWLOCATION => true,   // follow redirects
        CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
        CURLOPT_ENCODING       => "",     // handle compressed
        CURLOPT_USERAGENT      => "dk", // name of client
        CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
        CURLOPT_TIMEOUT        => 120,    // time-out on response
    ); 

    $ch = curl_init($url);
    curl_setopt_array($ch, $options);

    $content  = curl_exec($ch);

    curl_close($ch);
    return json_decode($content);
}

function get_products($plp_url) {
    $response = get_plp_response($plp_url);
    $products = $response->data->click_impression;
    foreach ($products as $p){
        $p->fa_discounted_price_str = fa_price($p->price ?? 0);
        $p->fa_discount_str = '٪'.fa_numbers($p->price_detail->discount_percent ?? 0);
        if($p->price_detail->discount_percent){
            $price_val = (100*$p->price_detail->selling_price)/(100-$p->price_detail->discount_percent);
            $p->fa_main_price_str = fa_price(round($price_val,-4));
        }else{
            $p->fa_main_price_str = fa_price($p->price_detail->selling_price);
        }
    }
    return $products ?? [];
}



function nice_dump($var)
{
    echo "<style>
        pre * {
            unicode-bidi: embed;
        }
        </style>
        <pre style='max-width: 80%;display: block; text-align: left; direction: ltr; background-color: #fff; border: 1px solid #b75520; border-left-width: 7px; border-radius: 5px; margin: 10px; padding: 10px 10px 0 10px !important; font-size: 17px !important;'>";
    var_dump($var);
    echo "</pre>";
}
function dd($var)
{
    nice_dump($var);
    die();
}