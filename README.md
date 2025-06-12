# Astronacci API
A Laravel Starter Application with Sanctum Authentication.

## Installation

Use the package manager composer for installing

1. Do the following commands for installing
```bash
git clone https://github.com/ericwidhiantara/astronacci-api.git
cd astronacci_api
composer install
copy .env.example .env
php artisan key:generate
```

2. Create a database
3. Setting database name, username, and password in your .env file
4. Do the following instructions if you're done setting database in .env
```bash
php artisan migrate —seed
```

## To run the application
```bash
php artisan serve
```

## Import Postman Collection
1. You can import postman collection from this repository into your postman workspace

## Mobile App Link
[https://github.com/ericwidhiantara/astronacci_app.git](https://github.com/ericwidhiantara/astronacci_app.git)

## License
[MIT](https://choosealicense.com/licenses/mit/)
