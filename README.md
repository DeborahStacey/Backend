# README

## Setup Instructions

1.In the root directory run `composer install`

2.Make sure the directory is running somewhere with apache 2.0 and PHP 7


## Don't have composer?

Run the following command to setup a instance of composer. Please be sure you are inside of the backendTOE directory before doing this.

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

php -r "if (hash_file('SHA384', 'composer-setup.php') === 'e115a8dc7871f15d853148a7fbac7da27d6c0030b848d9b3dc09e2a0388afed865e6a3d6b3c0fad45c48e2b5fc1196ae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

php composer-setup.php

Once you have run all 3 of these commands you will now have a file called composer.phar so as stated above you need to run composer install in this case you will run the following:

`./composer.phar install`

This is the same as running `composer install`.
