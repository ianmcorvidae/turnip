turnip is a no-frills webcomic hosting toolkit, written in PHP and using a MySQL database backend. It aims to be a barebones, use-what-you-want set of functions to plop into a separate/existing site. It is licensed under the terms of the three-clause BSD license and is © 2010-2011 Ian McEwen (ianmcorvidae) and Eliza Gebow (lordbatsy). See the file LICENSE for details.

INSTALL
=======
Stick the directory of files somewhere web-accessible, and then copy turnip_config.php.example to turnip_config.php and edit as appropriate. See the section below on configuration options for details of what each option should contain.

Set up your MySQL (currently the only supported database, sorry D:), after creating a database, by running turnip_setup.sql or otherwise creating a table to the following specification:

CREATE TABLE comic (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255), newspost TEXT, alt_title TEXT, filename VARCHAR(255) NOT NULL, date CHAR(10) DEFAULT '0000-00-00');

CONFIGURATION OPTIONS
=====================
Each configuration option is of the form $config[section][property] = value;

Options are sorted below by section; each property in a section then has a description and some example values.

SECTION: database
-----------------

host: host where your MySQL database is sitting. Usually 'localhost'.

user: the user that should be used to connect to your MySQL database. If you can, you should make a new user for turnip that only has access to turnip's database, so that if there's security problems they can't screw up your *entire* database. Example: 'turnip_user'.

password: the password for the user configured by the above variable. Example: 'super_secure_password'.

name: the name of the database that you've set up for turnip. Example: 'turnip'.

SECTION: comic
--------------

directory: the place where all your actual comic files are going to sit, so that turnip_comic.php can find them (and restrict access to them, as it's designed to do). Don't put a final slash on the directory; it shouldn't matter, but better not to tempt fate! Example: '/home/blargh/comics'.

location: the place which should be linked to in the src attribute of an img tag -- that is, where the web server should get your comic from. This is a format string for PHP's sprintf and printf functions; it takes one argument, the ID number of the comic to display. Look up the PHP documentation on sprintf to learn how to use this string, but in general what you'll want is to just put '%s' into the value where you want the ID to get pasted in. Example: 'turnip/turnip_comic.php?id=%s'.

current: the web location of your "current comic" page. This one isn't a format string, just a plain old string. Example: '/index.php'.

previous: the web location of your archival comic-reading pages. Back to being a format string, this time! Example: '/comic.php?id=%s'.
