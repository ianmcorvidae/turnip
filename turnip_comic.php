<?php

include('turnip_utils.php');

$id = $_GET['id'];
if (!is_int($id)) {
    // go to hell >:(
    header('HTTP/1.0 403 Forbidden');
    exit;
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
$query = "SELECT filename, date FROM comic WHERE id = $id";
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

if (common_php_date($line['date']) > now())
{
    header('HTTP/1.0 403 Forbidden');
} else {
    $file = common_config('comic', 'directory') . '/' . $line['filename'];
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

mysql_free_result($result);

// go home, we don't want you here

?>
