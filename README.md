# HTweb
FuelPHP based web application for dorm administration. Developed and maintained by Melcher.

## Features
* Complete point based diner tracking module including enrollments of guests, cooks and dishwashers. 
* Product module for easy cost logging
* Receipt module including cost distribution tools
* Bilingual user interface (Dutch and English)

## Dependencies
### PHP 7.1
Unfortunately there is no php7.1 package in the official ubuntu xenial archive.
To *upgrade* your existing php installation, take the steps below: 
1. `$ add-apt-repository ppa:ondrej/php`
2. `$ apt update`
3. `$ apt purge php7.0 php7.0-common`
4. `$ apt install php7.1` 

### FuelPHP
(Optional) Install oil standalone `$ curl get.fuelphp.com/oil | sh`.
Next, download and install FuelPHP itself using composer `$ php composer.phar update`.

## Configuration
Configuration files reside in the `fuel\app\config` directory.
Be sure to enter your database credentials in the `db.php` file.
For production environments please be sure to configure both `auth.php` and `ormauth.php` as well.

## Migrations
Run all migrations. Please note there's a strict order in which to run migrations:
1. auth tables and others `$ oil r migrate --packages=auth` 
3. session tables `$ oil r migrate --modules=sessions` (some other modules depend on sessions' functionality)
4. all others `$ oil r migrate --all`

## Run it
Oil has a built-in web server. Run the server using `$ oil s`
