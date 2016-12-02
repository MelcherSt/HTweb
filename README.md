# HTweb
FuelPHP based web application

## Quick start 
* download oil `$ sudo curl get.fuelphp.com/oil | sh`
* download dependencies using composer (from root) `$ sudo php composer.phar update`
* configure database and auth packages in fuel/app/config (see fuelphp documentation)
* migrate auth tables and others `$ oil r migrate --packages=auth` and `$ oil r migrate --all`
* run the application using `$ oil s`


--Melcher
