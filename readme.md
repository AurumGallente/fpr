### to install run *git clone https://https://github.com/AurumGallente/fpr*
### run *cd fpr/*
### copy and rename *.env.example* into *.env* and configure variables (for docker)
### run *src/laravel/*
### copy and rename *.env.example* into *.env* and configure variables (for project)
### run *cd ../../*
### run *docker compose run -it --rm composer install*
### run *make build*
### run *make npm_b*
### run *make migrate*
### run *make up_d*
### run *make seed*
### run *docker compose run -it --rm php /var/www/html/artisan app:count-words*
### run *docker compose run -it --rm php /var/www/html/artisan app:count-readability*
### run *make listen*
