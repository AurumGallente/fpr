-include .env
d=docker
dc=docker compose
watch_url='http://localhost:7900/?autoconnect=1&resize=scale&password=secret'

install:
	cp .env.example .env && \
	cp src/laravel/.env.example src/laravel/.env && \
	cp src/laravel/.env.dusk.local.example src/laravel/.env.dusk.local && \
	$(dc) -f docker-compose.yml up --build --detach && \
	cd src/laravel && \
	$(dc) run -it --rm composer install && \
	$(dc) run --rm npm run build && \
	cd ../../ && \
	$(dc) run -it --rm php /var/www/html/artisan migrate && \
	$(dc) run -it --rm php /var/www/html/artisan db:seed && \
	$(dc) run -it --rm php /var/www/html/artisan app:count-words && \
	$(dc) run -it --rm php /var/www/html/artisan app:count-readability &&\
	$(dc) run -it --rm php /var/www/html/artisan es:create-index &&\
	$(dc) run -it --rm --detach php /var/www/html/artisan queue:listen --timeout=3600 --queue=text_processing


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
	$(dc) run -it --rm php /var/www/html/artisan dusk

dusk_head:
	  @case "$$(uname)" in \
		Linux) command -v xdg-open >/dev/null && xdg-open $(watch_url) || echo "No browser available"; ;; \
		Darwin) command -v open >/dev/null && open $(watch_url) || echo "No browser available"; ;; \
		CYGWIN*|MINGW*) command -v start >/dev/null && start $(watch_url) || echo "No browser available"; ;; \
		*) echo "Unsupported OS: $$(uname)" ;; \
	  esac
	$(dc) run -it --rm php /var/www/html/artisan dusk

es_index_create:
	$(dc) run -it --rm php /var/www/html/artisan es:create-index
