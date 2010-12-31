<?php

include(TURNIPDIR . '/turnip_utils.php');

// DISPLAY COMICS

function displaycomic($id)
{
$link = mysql_connect(common_config('database','host'),
                      common_config('database','user'),
                      common_config('database','password')
);
mysql_select_db(common_config('database','name'));
$query = "SELECT filename, date FROM comic WHERE id = $id";
$result = mysql_query($query);

$line = mysql_fetch_assoc($result);

if (common_php_date($line['date']) > now())
{
header('HTTP/1.0 403 Forbidden');
} else
{
$file = $line['filename'];
if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;}
}


}

// COMIC NAVIGATION
function comicnav ()
{}

// NEWSPOSTS
function newspost()
{}

?>
