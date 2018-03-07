#!/bin/bash
# Author: Melcher Stikkelorum

# Set to true always use default credentials
USE_DEFAULTS=true

# Declare database credentials
DB_NAME="htweb_dev"
DB_USER="root"
DB_PASS="123"

# Declare project root directory and FuelPHP profile
PROJECTDIR="htweb"
PROFILE="development"

# Don't touch these
CURDIR="${PWD##*/}"
MIG_FILE="$PWD/fuel/app/config/$PROFILE/migrations.php"

echo "htweb auto-migrate script"
echo "========================================================"
echo "This script will empty the current database and re-run all migrations"
echo "Please make sure any important data is backed-up as it will be overwritten!"
echo
read -p "Press [Enter] key to continue ..."
echo

# Make sure we're in the project root
if [ $PROJECTDIR != $CURDIR ]; then
	read -p "This script should be placed in the root of the project directory at /$PROJECTDIR!"
fi

# Get database credentials
if [ $USE_DEFAULTS = false ]; then
echo "Please provide your database information (or press enter to use default values):"
read -e -p "Database name: " -i "$DB_NAME" DB_NAME
read -e -p "User: " -i "$DB_USER" DB_USER
read -e -p "Password: " -i "$DB_PASS" DB_PASS
fi

echo
echo "STEP 1/3: Emptying database"
mysql --user="$DB_USER" --password="$DB_PASS" --database="$DB_NAME" --execute="DROP DATABASE $DB_NAME; CREATE DATABASE $DB_NAME;"

echo
echo "STEP 2/3: Removing migrations history"
if [ -f $MIG_FILE ]; then
	rm $MIG_FILE
fi

echo
echo "STEP 3/3: Rerun migrations"
if [ ! -f $PWD/oil ]; then
	read -p "FATAL: oil not found. Program will now end."
	exit 1
fi
php oil r migrate --packages=auth
php oil r migrate --modules=sessions
php oil r migrate --all

echo
echo "bye!"
exit 1



