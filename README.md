## Configure new taxi project

We are using composer to manage all laravel dependancies. This project is build based on php 8.2 and laravel version 12.

### 1. Create .env from .env.example file

Just copy .env.example file and pest as .env
you can do it by command too by running<br>
`cp .env.example .env`

### 2. Install necessary packages

Run this commands for node inside the root directory of the project.
`npm install`
`npm run build`

### 3. Install packages using

Run composer inside the root directory of the project.
`composer install`

### 4.Generate your application encryption key

`php artisan key:generate`

### 5. Give proper access to some folders

In linux server you may need to provide some folder access to<br>
bootstrap/cache -to generate caches <br>
storage - to store log files

`sudo chown -R www-data:www-data storage`
`sudo chmod -R 775 storage`
`sudo chmod -R 755 public`
`sudo chown -R www-data:www-data public`

### 6. Link storage files

`php artisan storage:link`

### 7. Run migration using

`php artisan migrate --seed`

### 8. Other commands may require to run project

`php artisan db:seed --class=PermissionsSeeder`
`php artisan db:seed --class=SidebarSeeder`
`php artisan db:seed --class=ConfigurationSeeder`



