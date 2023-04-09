# JITERA - PHP Coding Assignment - Elka Aprico

## Prerequisite
- PHP minimum version 7.2
- MySQL minimum version 5.7
- Postman
- Git installed
- Composer installed

## How to Deploy
1. Clone this github repository.
```console
git clone  https://github.com/el-aprico/jitera-elka.git
cd jitera-elka
```
2. Create a new MySQL database schema.
3. Setup your database setting:
   - Copy `.env.example` to `.env`.
```console
cp .env.example .env
```
   - Setup your database setting in `.env` (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD).
4. Run command:
```console
composer install // to install application package dependency
php artisan key:generate // to generate encryption key 
php artisan migrate // to migrate the database.
php artisan db:seed // to insert initial data.
php artisan serve // to run your application instantly with port 8000.
```

## Test the Application
1. On Postman, on your workspace, click "Import" and drop files `Jitera.postman_collection.json` and `Jitera.postman_environment.json`.
2. On Collection's tab, set the "Environment" to Jitera.
3. Happy testing :)

## Test the Application on My Server
1. Open Postman and go to "Environment" tab.
2. Select Jitera environment.
3. Change the value of api_endpoint to `https://jitera.apri.co/api`.
