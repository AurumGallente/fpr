-include .env
d=docker
dc=docker compose

up_d:
	$(dc) -f docker-compose.yml up --detach

up_bd:
	$(dc) -f docker-compose.yml up --build --detach

up:
	$(dc) -f docker-compose.yml up

stop:
	$(dc) -f docker-compose.yml down

build:
	$(dc) -f docker-compose.yml build

migrate:
	$(dc) run -it --rm php /var/www/html/artisan migrate

listen:
	$(dc) run -it --rm php /var/www/html/artisan queue:listen

seed:
	$(dc) run -it --rm php /var/www/html/artisan db:seed

cache:
	$(dc) run -it --rm php /var/www/html/artisan cache:clear

npm_b:
	$(dc) run --rm npm run build
