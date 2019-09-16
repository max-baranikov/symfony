# Library app (Symfony 4.3)

Simple CRUD web-application for Book Library.
That is my first Symfony application.

## Features

* Uses **Symfony 4.3**
* Uses **Bootstrap 4** & **jQuery** for Front-end
* Have an authorization based on **FOSUserBundle**
* Have a **REST API** with *apiKey* authentication


## Requirements

* PHP 7.3 with pdo drivers
* PostgreSQL or MySQL
* Composer
* NPM or Yarn

## Install

To install this application first you need to clone this repository

``` bash
git clone https://github.com/max-baranikov/symfony.git

cd symfony
```

Then you need to run composer and npm to download other stuff:

``` bash
composer install
npm install
```

Congrats, now you have all the files downloaded! Next you need to configure app to your environment.

## Configure

To make things works you need to set database configuration to your **.env.local** file:

``` ini
# replace by your db_user, db_password and db_name
DATABASE_URL=pgsql://db_user:db_password@127.0.0.1:db_port/db_name

# set db_port depends on your database server
# for pgsql - 5432
# for mysql - 3306
```

Fine. Next you need to create the migration and run it in the way to get proper tables in your database:

``` shell
php bin/console make:migration

php bin/console doctrine:migrations:migrate
```

And press Y for all of questions

Also you need to create a new user to get access for the rest of web-site. You can do it either from the command line interface or from the web-site itself (use ```/register``` for that).

If you choose command line interface, then run the following command:

```bash
php bin/console fos:user:create
```

Then you need to promote this user either to ROLE_USER or ROLE_ADMIN

```bash
php bin/console fos:user:promote
```

## Usage

### Running the web-server

To run write the following command

``` shell
php bin/console server:run
```

Your application will be at *http://localhost:8000*

To stop it just press <kbd>Ctrl</kbd> + <kbd>C</kbd> in your terminal window

### Web-site

Homepage is ```/books/``` and it contain a list of books with it's info and cover. You can either preview or download book file (.pdf) if it was loaded earlier.

You can login or register by pressing the button in the right side of the header. Then you'll be allowed to create, delete and edit books.

![Homepage](files/readme/home.png 'Homepage')

You can edit all of fields of the book on the ```/books/{id}/edit``` page. As well from this page you can remove cover file, book file or the whole book record by clicking the Delete button

![Edit](files/readme/edit.png 'Edit page')

### API

There's a REST API, implemented four main methods to work with:

* ```GET api/v1/books ``` - is method to show all the books with it's info
* ```GET api/v1/books/{id}/info ``` - is method to show info for the particular book, specified by it's id
* ```POST api/v1/books/add ``` - is method to create a new book
* ```PUT api/v1/books/{id}/edit ``` - is method to edit the particular book, specified by it's id
* ```GET api/v1/books/token ``` - is method to get apiKey by username and password

Each method must be provided with **apiKey** GET/POST parameter, which can be gotted with ```api/v1/books/token``` method. ApiKey stored in the database's table *fos_user* and is unique for each user.

Response format is *json*.

For example, to get info of the book with id 1, you will do the following:

1. Get user's token


```http
GET api/v1/books/token?username=user&password=123
```
If everything is right, the response will be like this

```json
{"apiKey":"9a1221d0325eb3075b3e4db7a86918cc"}
```

2. Get book info

```http
GET api/v1/books/1/info?apiKey=9a1221d0325eb3075b3e4db7a86918cc
```

If the *apiKey* is forgotten, then you'll see

```json
{"message":"Authentication Required"}
```


If the *apiKey* is wrong, then you'll see

```json
{"message":"Username could not be found."}
```

And if everything is allright, the response should be

```json
{
    "id": 1,
    "name": "Harry Potter",
    "author": "J. Rowling",
    "last_read": "01.01.2014",
    "cover": "http://localhost:8000/uploads/0-10/1_cover.jpg",
    "file": "http://localhost:8000/books/download/1"
}
```
<!--
## Develop

### WEB Side

The main Controller for the web site is *BookController.php*, stored in the ```src/Controller/BookController.php```


### API Side

books

books/info

books/add

books/edit -->
