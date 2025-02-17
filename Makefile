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
	$(dc) run -it --rm php /var/www/html/artisan queue:listen --timeout=3600

listen_text:
	$(dc) run -it --rm php /var/www/html/artisan queue:listen --timeout=3600 --queue=text_processing

seed:
	$(dc) run -it --rm php /var/www/html/artisan db:seed

cache:
	$(dc) run -it --rm php /var/www/html/artisan cache:clear

docs:
	$(dc) run -it --rm php /var/www/html/artisan scribe:generate

npm_b:
	$(dc) run --rm npm run build
