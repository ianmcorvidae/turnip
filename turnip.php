<?php
# Copyright © 2010-2011, Ian McEwen and Eliza Gebow
# All rights reserved. Licensed under the terms of the 3-clause BSD License.
#
# See the file LICENSE for further conditions

include('turnip_utils.php');

// DISPLAY COMICS

class Comic
{
    private $id = 'current';
    private $name = null;
    private $newspost = null;
    private $alt_title = null;
    private $filename = null;
    private $date = null;

    private $current = null;

    function __construct($id = 'current')
    {
	common_initialize_plugins();
        $this->id = $id;
        $this->current = (int) common_currentid();
        $this->resolve_id();
	common_run_hooks('main', 'construct', array(&$this, $id));
    }
    
    /*******************************************
     * If $this->id is still set to 'current', *
     * change it to the current comic ID.      *
     *******************************************/
    private function resolve_id()
    {
        if ($this->id=='current') {
            $this->id = (int) $this->current;
        } else {
            $this->id = (int) $this->id;
        }
    }

    // COMIC DISPLAY

    /*****************************************
     * Display whatever you need to display. *
     * With no argument, defaults to 'comic' *
     *****************************************/
    public function display($something = 'comic')
    {
        echo $this->ret($something);
    }

    /********************************************
     * Return something to be used or displayed *
     * With no argument, defaults to 'comic'    *
     ********************************************/
    // TODO: refactor to use some sort of hook rather than hardcoded cases
    // TODO: add hooks to existing options so they can be customized
    public function ret($something = 'comic')
    {
        if ($something == 'comic') {
            return sprintf(common_config('comic', 'location'), $this->id);
        } else if ($something == 'nav_prev') {
            if ($this->id == 1) 
            { 
                return '#'; 
            } else {
                return sprintf(common_config('comic', 'previous'), $this->id-1);
            }
        } else if ($something == 'nav_next') {
            if ($this->id >= $this->current) 
            { 
                return '#'; 
            } else {
                return sprintf(common_config('comic', 'previous'), $this->id+1);
            }
        } else if ($something == 'nav_first') {
            return sprintf(common_config('comic','previous'), 1);
        } else if ($something == 'nav_last') {
            if (common_config('comic', 'use index for current', true)) {
                return common_config('comic','current');
            } else {
                return sprintf(common_config('comic','previous'), 
                               $this->current);
            }
        } else if (common_run_hooks('main', 'ret', array(&$this, $something))) {
            if (is_null($this->$something))
            {
                $this->fetch();
            }
            return $this->$something;
        }
    }

    /**********************************************************
     * If you don't already got it, get it from the database! *
     **********************************************************/
    // TODO: add hooks for changing query and for using the query result
    private function fetch()
    {
        $link = mysql_connect(common_config('database','host'),
            common_config('database','user'),
            common_config('database','password'));

        // debug log?
        if (!$link) { exit; }
        if (!mysql_select_db(common_config('database','name'))) { exit; }

        $columns = array('name', 'newspost', 'alt_title', 'filename', 'date');
        $tables = array('comic');
	$wheres = array('id = ' . $this->id);
	common_run_hooks('main','fetch_query', array(&$this, &$columns, &$tables, &$wheres));

        $query = "SELECT " . implode(' ,', $columns) .
		 " FROM " . implode(' ', $tables) . 
		 " WHERE " . implode(' ', $wheres) . ';';
        $result = mysql_query($query);

        // debug log? 
        if(!$result) { exit; }

        if(mysql_num_rows($result) == 0 || $this->id > $this->current)
        {
            $this->name = '';
            $this->newspost = '';
            $this->alt_title = '';
            $this->filename = '';
            $this->date = '';
	    common_run_hooks('main', 'fetch_result', array(&$this, null));
        } else {
            $line = mysql_fetch_assoc($result);

            $this->name = stripslashes($line['name']);
            $this->newspost = stripslashes($line['newspost']);
            $this->alt_title = stripslashes($line['alt_title']);
            $this->filename = stripslashes($line['alt_title']);
            $this->date = $line['date'];
	    common_run_hooks('main', 'fetch_result', array(&$this, $line));
        }

        mysql_free_result($result);
    }
}

?>
