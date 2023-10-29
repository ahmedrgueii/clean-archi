include .env

ifneq (,$(wildcard ./.env.local))
    include .env.local
    export
endif

DOCKER          = docker
DOCKER_COMPOSE  = docker-compose --env-file .env --env-file .env.local
EXEC_PHP        = $(DOCKER_COMPOSE) exec php
YARN            = $(EXEC_PHP) yarn
COMPOSER        = $(EXEC_PHP) composer
CONSOLE         = $(EXEC_PHP) php bin/console
PHPUNIT         = $(EXEC_PHP) php ./vendor/bin/phpunit
PATH_TO_FILE    = .env.local
COLOUR_SUCCESS  = \033[0;32m
COLOUR_DANGER   = \033[0;31m
COLOUR_END      = \033[0m

##
## Project setup
##---------------------------------------------------------------------------

init: build up vendor node_modules db migrate here-we-go ## Construit et monte les containers, installe les vendors et crée la base de données

build: generate-env-local-file add-local-hostname generate-certificates override-docker-compose ## Ajout des certificats, override docker & constriut les containers Docker
	$(DOCKER_COMPOSE) build --pull

up: ## Monte les containers Docker
	$(DOCKER_COMPOSE) up -d --remove-orphans

down: ## Démonte les containers Docker
	$(DOCKER_COMPOSE) down

start: ## Installe toutes les dépendances
	$(COMPOSER) install -n
	$(YARN) install --non-interactive

stop: ## Stop tous containers
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) rm -v --force

here-we-go: ## Ouvre la page d'accueil
	open http://localhost:$(DOCKER_NGINX_PORT);
	open http://$(DOCKER_LOCAL_URL):$(DOCKER_NGINX_PORT);

add-local-hostname: ## Ajout de la base uri pour le développement local
	sudo -- sh -c -e "echo '127.0.0.1 $(DOCKER_LOCAL_URL)' >> /etc/hosts";

.PHONY: init build up down start stop here-we-go add-host-name

##
## Certificates and docker
##---------------------------------------------------------------------------

generate-certificates: ## Génére les certificats pour les utilisateurs Mac
	@if [[ $(shell uname) = "Darwin" && ! -f .docker/php/certs/certs.crt ]]; then \
		security export -t certs -f pemseq -k /Library/Keychains/System.keychain -o .docker/php/certs/certs.crt; \
		cp .docker/php/certs/certs.crt .docker/php/certs/certs.pem; \
		cp .docker/php/certs/certs.crt .docker/php/certs/cert.crt; \
		cp .docker/php/certs/certs.crt .docker/php/certs/cert.pem; \
		echo "$(COLOUR_SUCCESS)The needed certificates are generated locally.$(COLOUR_END)"; \
	else \
		echo "$(COLOUR_DANGER)The needed certificates exists or not mandatory.$(COLOUR_END)"; \
	fi;

override-docker-compose: ## Override le fichier docker-compose file pour les utilisateurs Mac
	@if [[ $(shell uname) = "Darwin" && ! -f docker-compose.override.yaml ]]; then \
		cp docker-compose.override.yaml.dist docker-compose.override.yaml; \
		echo "$(COLOUR_SUCCESS)docker-compose.override.yaml file was created.$(COLOUR_END)"; \
	else \
		echo "$(COLOUR_DANGER)docker-compose.override.yaml exists or not mandatory to override.$(COLOUR_END)"; \
	fi;

generate-env-local-file:
	if [ ! -f .env.local ]; then \
		cp .env.local.dist .env.local; \
		echo "$(COLOUR_SUCCESS).env.local file was created.$(COLOUR_END)"; \
	else \
		echo "$(COLOUR_DANGER).env.local file exists.$(COLOUR_END)"; \
	fi;
.PHONY: generate-certificates override-docker-compose generate-env-local-file

##
## Database
##---------------------------------------------------------------------------

db: vendor-no-scripts ## Crée la base de données si elle n'existe pas
	$(CONSOLE) doctrine:database:create --if-not-exists

db-diff: vendor-no-scripts ## Génère un fichier de migration
	$(CONSOLE) doctrine:migration:diff --formatted

migrate: vendor-no-scripts ## Lance les migrations en attente
	$(CONSOLE) doctrine:migrations:migrate --no-interaction --allow-no-migration

fixtures: ## Charge les fixtures
	$(CONSOLE) hautelook:fixtures:load --no-interaction --purge-with-truncate

db-validate: vendor-no-scripts  ## Valide le schéma de la base de données
	$(CONSOLE) doctrine:schema:validate

db-load-fixtures-test: vendor-no-scripts
	$(CONSOLE) hautelook:fixtures:load --env=test -n --purge-with-truncate

.PHONY: db db-diff migrate fixtures db-validate db-test db-load-fixtures-test

##
## Assets
##---------------------------------------------------------------------------

encore: node_modules  ## Force la commande yarn run encore dev et regénère le js-routing
	$(CONSOLE) fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json
	$(YARN) encore dev

watch: node_modules ## Lance la commande Yarn en mode Watch
	$(YARN) watch

.PHONY: encore watch

##
## Tests
##---------------------------------------------------------------------------

phpunit: ## Lance les tests unitaires
	$(PHPUNIT)

phpunit-coverage: ## Lance les tests unitaires avec un rapport couverture du code (format HTML)
	$(PHPUNIT) --coverage-html coverage

##
## Miscellaneous
##---------------------------------------------------------------------------

vendor: composer.lock
	$(COMPOSER) install -n

vendor-no-scripts: composer.lock
	$(COMPOSER) install -n --no-scripts

node_modules: yarn.lock
	$(YARN) install

public/assets: node_modules
	$(YARN) run encore dev

warm: ## Préchauffe les caches Symfony
	$(CONSOLE) cache:warmup

console:
	$(CONSOLE) $(args)

.PHONY: vendor vendor-no-scripts yarn.lock node_modules warm console
