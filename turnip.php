<?php

include('turnip_utils.php');

// DISPLAY COMICS

function displaycomic($id)
{
}

// COMIC NAVIGATION

//first
function nav_first($id)
{
printf(common_config('comic','current'), 1);
}

//previous
function nav_prev($id)
{
if($id==1){echo '#';} 

else
{printf(common_config('comic','current'), $id-1);}
}

//next
function nav_next($id)
{
$link = mysql_connect(common_config('database','host'),
    common_config('database','user'),
    common_config('database','password'));

if (!$link)
{
    header('HTTP/1.0 500 Internal Server Error');
    exit;
}

if (!mysql_select_db(common_config('database','name')))
{
    header('HTTP/1.0 500 Internal Server Error');
    exit;
}
$query = "SELECT id FROM comic WHERE date <= '" . common_sql_date(time()) . "' LIMIT 1 ORDER BY id DESC;";
$result = mysql_query($query);

if(!$result)
{
    header('HTTP/1.0 500 Internal Server Error');
    exit;
}

if (mysql_num_rows($result) == 0)
{
    header('HTTP/1.0 404 Not Found');
    exit;
}

$line = mysql_fetch_assoc($result);

if($id==$line['id']){echo '#';} 

else
{printf(common_config('comic','current'), $id+1);}
}

//current
function nav_last($id)
{
echo common_config('comic','current');
}



// NEWSPOSTS
function newspost($id)
{
}

?>
