# Subscription Plan Calculator
## A simple web API that manages users' subscription plans

### To install
You need:
* PHP 7.2+ with:
    * php-mbstring
	* php-xml
	* composer

* MySql (Maria DB)

### To setup
* import the file `subscription_plan_calculator.sql` into your database
* `cp .env.example .env`
* Edit `./.env` to set `DATABASE_URL`
* `./composer install`

### To test
* `./bin/phpunit`

### To run
#### Firstly in a terminal run: `php -S 0.0.0.0:8000 -t public`

#### To see the calculator in action run:
`curl -X POST http://localhost:45301/calculator -d '[{"code": "jp", "isYearCost": false}, {"code": "us", "isYearCost": true}, {"code": "de", "isYearCost": false}]'`

#### To see save a plan for user `person1` run:
`curl -X POST http://localhost:8000/user/person1/plan -d '[{"code": "gb", "isYearCost": true}, {"code": "us", "isYearCost": true}, {"code": "gb", "isYearCost": true}]'`

then check the `user_plan` table