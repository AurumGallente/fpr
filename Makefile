-include .env
d=docker
dc=docker compose
watch_url='http://localhost:7900/?autoconnect=1&resize=scale&password=secret'

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

dusk:
	  @case "$$(uname)" in \
		Linux) xdg-open $(watch_url) ;; \
		Darwin) open $(watch_url) ;; \
		CYGWIN*|MINGW*) start $(watch_url) ;; \
		*) echo "Unsupported OS: $$(uname)" ;; \
	  esac
	$(dc) run -it --rm php /var/www/html/artisan dusk
