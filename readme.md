# Avalon
A repository for developing a private "Slimmer meter" and bank transaction application.
Currently still in development.

### Purpose
Why use other software whilst its more fun to create your own platform that support the needs?
The project is meant as a personal solution to support my personal need,
yet it's open source with the aim so other might learn and also benefit from the developed platform.

## Getting started
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.


### Prerequisites
What things you need to install the software and how to install them:

* PHP 7.2+
* MySQL, sqlLite or ProgressSQL

### Installing
Since there's no stable version, 
detailed installation instructions to guide non-expert users are not there yet.

For people who require no support, feel free to run your own instance via:

`composer install`

#### Initialize the database
An important step, please use the following sequence:

`php artisan migrate:refresh --seed`

`php artisan passport:install`

`php artisan jwt:secret`
