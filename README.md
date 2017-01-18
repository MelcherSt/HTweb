# HTweb
FuelPHP based web application for dorm administration.

## Quick start 
* download oil `$ sudo curl get.fuelphp.com/oil | sh`
* download dependencies using composer (from root) `$ sudo php composer.phar update`
* configure database and auth packages in fuel/app/config (see fuelphp documentation)
* run all migrations. Please note there's a strict order in which to run migrations:
	1. auth tables and others `$ oil r migrate --packages=auth` 
	2. default tables `$ oil r migrate --default`
	3. session tables `$ oil r migrate --modules=sessions`
	4. all others `$ oil r migrate --all`
* run the application using `$ oil s`

## License
Explicitly no license is supplied. 
Melcher a.k.a AuroraWizard © 2016. All rights reserved.
