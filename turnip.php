<?php

include('turnip_utils.php');

// DISPLAY COMICS

class Comic
{
    public $id = 'current';
    public $current = null;
    public $newspost = null;
    public $alt_title = null;

    function __construct($id = 'current')
    {
        $this->id = $id;
        $this->current = (int) common_currentid();
        $this->resolve_id();
    }
    
    /*******************************************
     * If $this->id is still set to 'current', *
     * change it to the current comic ID.      *
     *******************************************/
    public function resolve_id()
    {
        if ($this->id=='current') {
            $this->id = (int) $this->current;
        } else {
            $this->id = (int) $this->id;
        }
    }

    // COMIC DISPLAY

    /***************************************
     * Print a URL to be used in an img    *
     * src attribute, to display the comic *
     ***************************************/
    public function display()
    {
        printf(common_config('comic', 'location'), $this->id);
    }

    // COMIC NAVIGATION

    /*************************************
     * Print URLs for a href attributes: *
     * first, previous, next, and        *
     * last/current comic links          *
     *************************************/
    public function nav_first()
    {
        printf(common_config('comic','previous'), 1);
    }

    public function nav_prev()
    {
        if ($this->id == 1) {
            echo '#';
        } else {
            printf(common_config('comic', 'previous'), $this->id-1);
        }
    }

    public function nav_next()
    {
        if($this->id == $this->current)
        {
            echo '#';
        } else {
            printf(common_config('comic','previous'), $this->id+1);
        }
    }

    public function nav_last()
    {
        if (common_config('comic', 'use index for current', true)) {
            echo common_config('comic','current');
        } else {
            printf(common_config('comic','previous'), $this->current);
        }
    }
    
    // NEWSPOSTS AND ALT/TITLE TEXT

    /***********************************
     * Print me my newspost, kind sir! *
     ***********************************/
    public function display_newspost()
    {
        if (is_null($this->newspost))
        {
            $this->fetch();
        }
        echo $this->newspost;
    }

    public function display_alt_title()
    {
        if (is_null($this->alt_title))
        {
            $this->fetch();
        }
        echo $this->alt_title;
    }

    /********************************
     * If you don't already got it, *
     * get it from the database!    *
     ********************************/
    public function fetch()
    {
        $link = mysql_connect(common_config('database','host'),
            common_config('database','user'),
            common_config('database','password'));

        // debug log?
        if (!$link) { exit; }
        if (!mysql_select_db(common_config('database','name'))) { exit; }

        $query = "SELECT newspost, alt_title FROM comic WHERE id = $this->id";
        $result = mysql_query($query);

        // debug log? 
        if(!$result) { exit; }

        if(mysql_num_rows($result) == 0)
        {
            $this->newspost = '';
            $this->alt_title = '';
        } else if ($this->id <= $this->current) {
            $line = mysql_fetch_assoc($result);
            $this->newspost = stripslashes($line['newspost']);
            $this->alt_title = stripslashes($line['alt_title']);
        } else {
            $this->newspost = '';
            $this->alt_title = '';
        }

        mysql_free_result($result);
    }
}

?>
