Currency Manager
================
Spider Web Development Task 2
Overview
--------
This project is a service that obtains information from an API obtained from [Currency Layer](https://currencylayer.com) which is refreshed on an hourly basis. The API itself provides new data every hour. The application runs on PHP and Apache and uses MySQL as a database (WAMP Stack). The application is powered by Laravel.
###Info   
Displays information about the project.   
###Current Rates   
Displays the present exchange rates of currencies with respect to the US Dollar.    
###Currency Converter   
An application that accepts user input and converts it to the required currency.   
###Graph
An bar graph that displays the relative values of the currency. The scale of the graph resizes based on the currencies that are chosen and is useful for comparision purposes.
Build Instructions
------------------
1. Download PHP, Apache and MySQL for your system.
    1. Here's a link for downloading WAMP for Windows. [WAMP](http://www.wampserver.com/en/)
    2. LAMP for linux. [LAMP](http://lamphowto.com/)
    3. You can also install them seperately.
2. Install Laravel for your project directory.
    1. Clone the repository into your project directory from github.   
    ``git clone git@github.com:laravel/laravel.git directory_name``  
    2. Install composer. [COMPOSER](https://getcomposer.org/)
    From the command line, run the following command in the project folder  
    ``composer install``
    3. Rename .env.example to .env
3. Download all the files and put them in the project directory as follows
    1. Replace /apps/Http/routes.php with the downloaded routes.php
    2. Put the following files in the public directory
        1. background.jpg
        2. php.jpg
        3. dollar.jpg
        4. update.jpg
        5. world.jpg
        6. code.jpg
        7. converter.jpg
        8. select.txt
        9. Charts.js
    3. Put the following files in /resources/views/
        1. checkcurrentrate.php
        2. converter.php
        3. convertvalue.php
        4. current_rates.php
        5. graph.php
        6. home.php
        7. info.php
        8. table.php
        9. drawgraph.php
4. To run the project on localhost on your desired port,
    1. Generate a new key  
    ``php artisan key:generate`` (one time requirement)
    2. Run the following command in your project folder.   
    ``php artisan serve --port=<desired port>``

Database Requirements
---------------------
1. The latest version of MySQL must be installed.
2. The password for the user 'root' must be left blank.
3. If MySQL is already installed and you wish to use your own login, replace the code with your credentials.
4. Ideally, no database named spider should exist.
5. Two tables are created, apidata and timestore.
    1. apidata stores the values obtained from the API.
    2. timestore stores the time at which the data is updated.

List of server routes
---------------------
**/** - Homepage  
**/info** - Info page  
**/current_rates** - Page for viewing the current rates  
**/converter** - Page for the converter application  
**/graph** - Page for viewing the graph  
**/table** - Pages for viewing the list of currency codes and their names  
**/checkcurrentrate/{code}** - This is for processing input in the current_rates page. Called through an ajax request  
**/convertvalue/{cur1}/{cur2}/{val}** - This is for processing input in the converter application. Called through an ajax request.  
**/drawgraph/{curr}/{bardata}** - This is for adding values to the graph. Called through an ajax request.

Libraries and Extensions used
-----------------------------
###Backend
***mysqli*** - Used for setting up a database connection from PHP to MySQL. [mysqli](http://php.net/manual/en/book.mysqli.php)
###Frontend
***Bootstrap*** - For frontend layouts. [Bootstrap](http://getbootstrap.com/getting-started/)   
***Charts.js*** - For the bar graph. 
