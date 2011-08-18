<?php
# Copyright © 2010-2011, Ian McEwen and Eliza Gebow
# All rights reserved.
#
# See the file LICENSE for further conditions

include('turnip_utils.php');

$id = (int) $_GET['id'];

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

if (common_php_date($line['date']) > time())
{
    header('HTTP/1.0 403 Forbidden');
} else {
    $file = common_config('comic', 'directory') . '/' . $line['filename'];
    if (file_exists($file)) {
	// yes, this is janky, but unless weird filenames are being used it'll work
        header('Content-Type: image/' . array_pop(preg_split('/\./', $line['filename'])));
	// gets rid of any preceding directories
        header('Content-Disposition: filename=' . array_pop(preg_split('/\//', $line['filename'])));
        header('Content-Transfer-Encoding: binary');
	$hash = sha1_file($file);
	header('ETag: ' . $hash);
	// below: that's one month, in seconds
        header('Cache-Control: public, max-age=' . common_config('comic', 'cache time', '2592000'));
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
	// actually read out the file
        readfile($file);
	exit;
    }
}

mysql_free_result($result);

// go home, we don't want you here

?>
