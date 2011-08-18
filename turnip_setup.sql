CREATE TABLE comic (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255), 
                    newspost TEXT, alt_title TEXT, 
                    filename VARCHAR(255) NOT NULL, 
                    date CHAR(10) DEFAULT '0000-00-00');
