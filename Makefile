DC=docker compose
APP=php

.PHONY: up down restart logs sh install migrate assets tailwind cache-clear

up:
	$(DC) up

down:
	$(DC) down

restart:
	$(DC) down
	$(DC) up

logs:
	$(DC) logs -f

sh:
	$(DC) exec $(APP) bash

install:
	$(DC) exec $(APP) composer install
	$(DC) exec $(APP) mkdir -p var
	$(DC) exec $(APP) chmod -R 777 var
	$(DC) exec $(APP) php bin/console doctrine:migrations:migrate --no-interaction || true
	$(DC) exec $(APP) php bin/console cache:clear || true
	$(DC) exec $(APP) php bin/console asset-map:compile || true
	$(DC) exec $(APP) php bin/console tailwind:build || true

migrate:
	$(DC) exec $(APP) php bin/console doctrine:migrations:migrate --no-interaction

assets:
	$(DC) exec $(APP) php bin/console asset-map:compile

tailwind:
	$(DC) exec $(APP) php bin/console tailwind:build

cache-clear:
	$(DC) exec $(APP) php bin/console cache:clear
