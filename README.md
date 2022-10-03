## About

Api backend for Movie application

## How to run in docker
- After clone the project copy `.env.example` to `.env`
- Update `OMDB_ENDPOINT` and `OMDB_API_KEY` key
- From root directory run `docker-compose up -d`
- Run `docker-compose exec app php artisan key:generate` to generate application key
- Run migration using command `docker-compose exec app php artisan migrate --seed`

Example database info in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=movie
DB_USERNAME=movie
DB_PASSWORD=123
```

## For test
- Run `docker-compose exec app php artisan test`


## Users
```
Basic customer with movie subscription:
Email: basic@customer.com
Password: password

Customer with premium plan:
Email: premium@customer.com
Password: password

Admin user:
Email: admin@app.com
Password: password
```
