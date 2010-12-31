<?php

// TURNIPDIR is where we keep ALLLLLL the files
define('TURNIPDIR', dirname(__FILE__));

include(TURNIPDIR . '/turnip_config.php');

function common_config($main,$sub)
{
global $config;
return (array_key_exists($main,$config) && 
        array_key_exists($sub,$config[$main]) ? $config[$main][$sub] : false;
}

function common_sql_date($datetime)
{
return strftime('%Y-%m-%d',$datetime);
}

function common_php_date($datetime)
{
return strtotime($datetime);
}

?>
