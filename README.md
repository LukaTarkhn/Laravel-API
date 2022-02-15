## Install Docker
[First, install Docker](https://www.docker.com/get-started)

## Composer install

instead of running `composer install`, run the following command:

on windows:
`docker run -v %cd%:/app -it --rm composer install`

on linux: 
`docker run -v $(pwd):/app -it --rm composer install`

## Add an "sail" alias for docker

in console: `vim ~/.bashrc`

and then add this: `alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'`

## Copy .env and generate key

`cp .env.example .env`

`sail artisan key:generate`

## Run docker

in console: `sail up`

[localhost](http://localhost/)
