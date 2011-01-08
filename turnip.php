<?php

include('turnip_utils.php');

// DISPLAY COMICS

function displaycomic($id = 'current')
{

	if ($id=='current'){
            $current = nav_currentid();
        } else {
           $current = $id;
        }
        printf(common_config('comic','location'), $current);
}

// COMIC NAVIGATION

function nav_currentid()
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
return $line['id'];
}

//first
function nav_first($id = 'current')
{
    printf(common_config('comic','previous'), 1);
}

//previous
function nav_prev($id = 'current')
{
    if ($id==1) 
    {
        echo '#';
    } else {
	if ($id=='current'){
            $current = nav_currentid();
        } else {
           $current = $id;
        }
        printf(common_config('comic','previous'), $current-1);
    }
}

//next
function nav_next($id = 'current')
{
    if ($id == 'current')
    {
        echo '#';
        return;
    }

    if($id==nav_currentid())
    {
        echo '#';
    } else {
        printf(common_config('comic','previous'), $id+1);
    }
}

//current
function nav_last($id = 'current')
{
    echo common_config('comic','current');
}



// NEWSPOSTS
function newspost($id = 'current')
{
}

?>
