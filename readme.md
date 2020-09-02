The use (Instruction are for a mac):
1) Install Homebrew
2) In terminal "homebrew install php composer mysql"
3) In terminal "Git pull https://github.com/akatz6/BidOps.git"
4) In terminal "sudo chmod 755 -R BidOps"
5) In terminal "chmod -R o+w laravel/BidOps"
6) In terminal "composer install"
7) In terminal "cp .env.example .env"
8) In terminal "php artisan key:generate"
9) Open .env file
10) change DB_CONNECTION
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
11) In mysql create Database Laravel and sellect as database.
12) In terminal "php artisan migrate"
13) In terminal "php artisan serve"
14) Open to tab in terminal and run "vendor/bin/phpunit" to run unit tests
15) In browser copy url for tab where you ran "php artisan serve" and past into browser.
