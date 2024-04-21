
# Setting up the app
1. Authenticate the app with your Auth0 account
`./auth0 login`

2. On the Auth0 console: Allow the "Password" Auth0 grant type to the application:
Applications -> <Your application name> -> Advanced -> Grant Types -> Password -> Save
<img width="965" alt="Screenshot 2024-04-21 at 8 14 12 PM" src="https://github.com/ryoskeii/heya-book/assets/57847059/2bf8141c-fa10-439f-a1e5-664bf44c202c">


4. On the Auth0 console: Set `Username-Password-Authentication` on API Authorization Settings -> Default Directory
You can do this here: https://manage.auth0.com/dashboard/us/dev-orlrs87qrliauoeq/tenant/general
<img width="1407" alt="Screenshot 2024-04-21 at 8 14 42 PM" src="https://github.com/ryoskeii/heya-book/assets/57847059/bf3b3667-bbff-4594-b171-b0aca13227b7">

5. Please set the following ENV variables in the generated `./.env` file
- The URL of our Auth0 Tenant Domain.
`AUTH0_DOMAIN='https://dev-****.us.auth0.com'`

- Our Auth0 application's Client ID
`AUTH0_CLIENT_ID=''`

- Our Auth0 application's Client Secret
`AUTH0_CLIENT_SECRET=''`

- Auth0 Management API key.
`AUTH0_MANAGEMENT_API_KEY=''`
<img width="1428" alt="Screenshot 2024-04-21 at 8 54 45 PM" src="https://github.com/ryoskeii/heya-book/assets/57847059/35eca3bf-7106-4b75-9097-90ce8b998fa1">

7. Use sail to create the docker runtime.
`./vendor/bin/sail up`

8. Run DB migrations once the containers are all running (can take around 15 minutes for the images to build)
`./vendor/bin/sail artisan migrate`

# Testing:
In order to create an authorized user, you must first sign up via the Auth0 web UI.
You can hit this by calling the host on the browser: 'http://localhost:80'

Next, call: `POST http://localhost:80/api/auth/token` with your username and password credentials to generate an access_token

Now, you can call any API by passing this Bearer token in.

## Examples:

### POST - Create a temporary bearer token
`curl --location 'http://localhost:80/api/auth/token' \
--header 'Content-Type: application/json' \
--data-raw '{
    "username": "test@user.com",
    "password": "devTest1123"
}'`

### POST - Create a facility
`curl --location 'http://localhost:80/api/facility' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer <your token here>' \
--data '{
    "name": "test",
    "address": "Test",
    "utc_offset_seconds": "32400",
    "open_time_local": "09:00:00",
    "close_time_local": "20:00:00"
}'`

### POST - Create a resource (such as rooms)
`curl --location 'http://localhost:80/api/resource' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer <your token here>
--data '{
    "name": "Reservable Booth 1",
    "facility_id": "1"
}'`

### GET - Resources (such as rooms) for a facility and their current bookings
`curl --location 'http://localhost:80/api/resource' \
--header 'Authorization: Bearer <your token here>`

### POST - Create a booking
`curl --location 'http://localhost:80/api/booking' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer <your token here>
--data '{
    "user_id": "1",
    "resource_id": "1",
    "from_datetime": "2024-10-23 11:30:00",
    "to_datetime": "2024-10-25 12:30:00"
}'`

### GET A user's bookings
`curl --location 'http://localhost:80/api/booking' \
--header 'Authorization: Bearer <your token here>`


<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

