**turnip** is a no-frills webcomic hosting toolkit, written in PHP and using a MySQL database backend. It aims to be a barebones, use-what-you-want set of functions to plop into a separate/existing site. It is licensed under the terms of the three-clause BSD license and is © 2010-2011 Ian McEwen (ianmcorvidae) and Eliza Gebow (lordbatsy). See the file `LICENSE` for details.

INSTALL
=======
Stick the directory of files somewhere web-accessible, and then copy turnip_config.php.example to turnip_config.php and edit as appropriate. See the section below on configuration options for details of what each option should contain.

Set up your MySQL (currently the only supported database, sorry D:), after creating a database, by running turnip_setup.sql or otherwise creating a table to the following specification:

```sql
CREATE TABLE comic (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255), newspost TEXT, alt_title TEXT, filename VARCHAR(255) NOT NULL, date CHAR(10) DEFAULT '0000-00-00');
```

USAGE
=====
`comic.php.example` and `index.php.example` provide examples of how to use turnip to include comics/newsposts/etc. into your page. turnip doesn't provide this for you - you provide your own web design! In short, you should include `turnip.php`, wherever you put it, and then initialize a new comic by something like `$comic = new Comic($id);`, presuming you have the variable `$id` already set for you. If you don't include `$id` or something in its place, it'll show the most recent comic - this is probably what you want your index page to do. If this sounds daunting, don't fear: `comic.php.example` shows probably the easiest thing to do, which is to grab the ID from the URL of the page by way of `$_GET['id']`. The configuration options in turnip_config.php.example assume you're doing something like this. 

When you'd like to upload a comic:

1. Upload the image file to your comic directory
2. Insert a line into the database table with everything you want, using whichever tool you like. Using SQL, something like: 

    ```sql
    INSERT INTO comic (name, newspost, alt_title, filename, date) VALUES ('some awesome name', 'hey look, a newspost', 'here\'s what\'ll go in the title tag', 'comic0567.png', '2099-07-18');
    ```
3. Profit!

CONFIGURATION OPTIONS
=====================
Each configuration option is of the form `$config[section][property] = value;`

Options are sorted below by section; each property in a section then has a description and some example values.

SECTION: database
-----------------

host: host where your MySQL database is sitting. Usually 'localhost'. *REQUIRED*

user: the user that should be used to connect to your MySQL database. If you can, you should make a new user for turnip that only has access to turnip's database, so that if there's security problems they can't screw up your *entire* database. Example: 'turnip_user'. *REQUIRED*

password: the password for the user configured by the above variable. Example: 'super_secure_password'. *REQUIRED*

name: the name of the database that you've set up for turnip. Example: 'turnip'. *REQUIRED*

SECTION: comic
--------------

directory: the place where all your actual comic files are going to sit, so that turnip_comic.php can find them (and restrict access to them, as it's designed to do). Don't put a final slash on the directory; it shouldn't matter, but better not to tempt fate! Example: '/home/blargh/comics'. *REQUIRED*

location: the place which should be linked to in the src attribute of an img tag -- that is, where the web server should get your comic from. This is a format string for PHP's sprintf and printf functions; it takes one argument, the ID number of the comic to display. Look up the PHP documentation on sprintf to learn how to use this string, but in general what you'll want is to just put '%s' into the value where you want the ID to get pasted in. Example: 'turnip/turnip_comic.php?id=%s'. *REQUIRED*

previous: the web location of your archival comic-reading pages. Despite the name, this is used by `nav_first` and `nav_next` as well. Example: '/comic.php?id=%s'. *REQUIRED*

current: the web location of your "current comic" page. This one isn't a format string, just a plain old string. Example: '/index.php'. *Not required if `comic/use index for current` is set to false.*

use index for current: Use the `comic/current` setting for `nav_last`. *Defaults to true.*

cache time: amount of time to set in the Cache-Control HTTP header, as a string. *Defaults to one month ('2592000').*
