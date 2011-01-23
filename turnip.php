<?php

include('turnip_utils.php');

// DISPLAY COMICS

function displaycomic($id = 'current')
{

	if ($id=='current'){
            $current = common_currentid();
        } else {
           $current = $id;
        }
        printf(common_config('comic','location'), $current);
}

// COMIC NAVIGATION

//first
function nav_first($id = 'current')
{
    printf(common_config('comic','previous'), 1);
}

//previous
function nav_prev($id = 'current')
{
    if ($id=='current'){
        $current = common_currentid();
    } else {
        $current = $id;
    }
    if ($current==1){
        echo '#';
    } else {
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

    if($id==common_currentid())
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
	if ($id=='current'){
            $current = common_currentid();
        } else {
           $current = $id;
        }

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
$query = "SELECT newspost FROM newspost WHERE id = $current";
$result = mysql_query($query);

if(!$result)
{
    header('HTTP/1.0 500 Internal Server Error');
    exit;
}

$line = mysql_fetch_assoc($result);
echo stripslashes($line['newspost']);
mysql_free_result($result);
}

?>
