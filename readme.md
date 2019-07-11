# Helix Code Challenge

This is a sample Laravel API that allows users to manage products. 

For more information, reference these instructions: [https://gist.github.com/tonning/dba6327dbff52fa944d0dff0c130a44a](https://gist.github.com/tonning/dba6327dbff52fa944d0dff0c130a44a)

## Installation

Please find the basic Laravel installation instructions [here](https://laravel.com/docs/5.8#installation), along with server requirements. 

## Migrate & Seed

`php artisan migrate --seed`

## Authentication

This API uses token based authentication. All request have an `api_token` parameter. The token is required with each request and is associated with a single user. The token is stored in the api_token column of the users table and is NOT hashed, for the purposes of this application.

## Authorization

Authorization is handled with:

- The `ProductPolicy` which is registered in the `AuthServiceProvider`
- And the `$this->authorize()` controller helper in `ProductAPIController`

## Tests

run `phpunit` in the root folder

## **Security**

If you discover any security related issues, please email instead of using the issue tracker.