<?php
# Copyright © 2010-2011, Ian McEwen and Eliza Gebow
# All rights reserved.
#
# See the file LICENSE for further conditions

// TURNIPDIR is where we keep ALLLLLL the files
define('TURNIPDIR', dirname(__FILE__));

include(TURNIPDIR . '/turnip_config.php');

// TODO: initialize plugins, add hook functions

function common_initialize_plugins()
{
   $plugins = common_config('plugins', 'files', array());
   foreach ($plugins as $value) {
       include(TURNIPDIR . '/plugins/' . $value);
   }
}

function common_set_hook($hook_main, $hook_sub, $handler)
{
   global $hooks;
   $hooks[$hook_main][$hook_sub][] = $handler;
}

function common_run_hooks($hook_main, $hook_sub,  $args=array())
{
    global $hooks;
    $result = null;

    if (!$hooks) { return true; }
    if (array_key_exists($hook_main, $hooks) && 
        array_key_exists($hook_sub, $hooks[$hook_main])) 
    {
        foreach ($hooks[$hook_main][$hook_sub] as $handler) {
            $result = call_user_func_array($handler, $args);
            if ($result === false) {
                break;
            }
        }
    }

    return ($result !== false);
}

/***********************************
 * Return a config value from the  *
 * category and property it's      *
 * stored under.                   *
 *                                 *
 * Optional third argument is a    *
 * default to return if a property *
 * is not found.                   *
 ***********************************
 */
function common_config($main,$sub,$default = false)
{
    global $config;
    return (array_key_exists($main,$config) && 
            array_key_exists($sub,$config[$main])) ? 
                $config[$main][$sub] : $default;
}

/*******************************
 * Returns an SQL time string  *
 * from a PHP time object      *
 *******************************
 */
function common_sql_date($datetime)
{
    return strftime('%Y-%m-%d', $datetime);
}

/*******************************
 * Returns a PHP time object   *
 * from the format used in SQL *
 *******************************
 */
function common_php_date($timestr)
{
    return strtotime($timestr);
}

/******************************
 * Returns the ID number of   *
 * the current comic.         *
 * (that is, the one with the *
 * highest ID whose release   *
 * date has passed)           *
 ******************************
 */
function common_currentid()
{
    $link = mysql_connect(common_config('database','host'),
        common_config('database','user'),
        common_config('database','password'));

    if (!$link || !mysql_select_db(common_config('database', 'name')))
    {
        return 0;
    }

    $query = "SELECT id FROM comic WHERE date <= '" . 
             common_sql_date(time()) . 
             "' ORDER BY id DESC LIMIT 1;";
    $result = mysql_query($query);

    if(!$result || mysql_num_rows($result) == 0)
    {
        return 0;
    }

    $line = mysql_fetch_assoc($result);
    mysql_free_result($result);
    return $line['id'];
}

?>
