## Setup

### Clone the project
In order to get the project up and running you need to first clone this project

```shell
git clone <repo_url>
```

### Composer
Next we need to install our composer dependencies in order to use sail

```shell
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```
NOTE: If you already have a vendor folder and can access sail you can just do 

```shell 
sail composer install
```

### Environment

You will need to set up an environment for your project. By default, the .env.example will have everything you need to get a local environment kick-started.

To create an environment based on .env.example, run the following: 
```shell
cp .env.example .env
```

### Sail
We need to run sail up to start all our containers

```shell
sail up -d
```

If this command gives you an error that says sail does not exist please see the laravel documentation on how to create an alias for sail

### NPM

We need to install all our node dependencies

```shell
sail npm install
```

We need to build our assets before we can continue

```shell
sail npm run build
```

If you are developing on the frontend, run the following to sync your changes in real time.

```shell
sail npm run dev
```

### Laravel Key
Generate a Laravel key with the following command: 
```shell
sail artisan key:generate
```

### Migrations & Seeders
To run migrations and seeders: 
```shell
sail artisan migrate:fresh --seed
```
Use `localhost/api/{{route}}` to make API calls on postman for local development
