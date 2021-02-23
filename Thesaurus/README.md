# Thesaurus

## Requirements
- Docker

## Installation

### Docker

Use docker to run the project locally.

Run the following inside the ``docker`` folder:
    
    $ docker-compose up -d 
    
To enter the docker container:

    $ docker exec -it thesaurus /bin/bash
    
### Setup Composer

The composer tool is installed in the docker container. To install composer packages, run:

    $ composer install
    
### Setup DB

Enter the docker container. In ``/var/www/html/migrations``, run migrations:

    $ php initDb.php
    
### Optional
    
Import ``migrations/sql/test/testdata.sql`` to populate the DB.

## Tests

Enter the docker container. In ``/var/www/html``, run the following to execute tests:

    $ php ./vendor/bin/phpunit tests
    
## Usage

### GET words

    localhost:80/words

### POST synonym

POST request with payload, feks:

    localhost:80/synonym

payload:

    { words: ["hi", "hello"] }
    
### GET synonym

GET request with query param

    localhost:80/synonym?word=searchWord
    

