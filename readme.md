## Simple crawler Laravel + Angularjs


What you have to do:

- create a database and reffern in your .env file
- run php artisan migrate;
- run php artisan db:seed (optional)
- run php artisan crawler:refresh
- run php serve
- access your domain to see the last 10 crawled e-mails.

## Changing the links crawled 
- change the array of first links in database/factories/ModelFactory.php:28 before db:seed or add the first ones in the pages table on db.


Joel Medeiros 09/2017 
