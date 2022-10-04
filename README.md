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
- Firstly we need to have login as admin `http://localhost:3000/admin-login`
- Then need to go to admin movie serach from OMDB api `http://localhost:3000/admin-movies` by clicking Admin movies from menu
- After search with movie title click on import movie, we can put necessary data and submit the form.
- After import the movie will available in customers homepage.
- Then logout admin
- To view movie details we need to log in as customer `http://localhost:3000/customer-login`
- Go to details of a premium movie click subscribe button and fill the form for rent the movie.
- No need to subscribe premium movie for premium customer to watch the movie.
