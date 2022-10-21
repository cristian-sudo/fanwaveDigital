PROJECT_ROOT=$(shell pwd)

# Sometimes non-interactive mode should be enabled (e.g. pre-push hooks)
ifeq (true, $(non-i))
  	PHP=docker-compose exec -T php
else
	PHP=docker-compose exec php
endif

php: prerequisites
	docker-compose exec php sh

##############################################################
# Docker compose                                             #
##############################################################
build:
	cp .env.dist .env
	docker-compose build
	cp -a tools/hooks/. ../.git/hooks

run:
	docker-compose up

stop:
	docker-compose stop

down:
	docker-compose down -v --rmi=all --remove-orphans

##############################################################
# Tests		                                     #
##############################################################
test:
	$(PHP) bin/phpunit

##############################################################
# Static Analysis		                                     #
##############################################################
#Variables
PHP_CS_FIXER=vendor/bin/php-cs-fixer fix
PHP_CS_FIXER_ARGS=--cache-file=.php-cs-fixer.cache --verbose --config=.php-cs-fixer.php --diff --allow-risky=yes

cs-check: prerequisites
	$(PHP) $(PHP_CS_FIXER) $(PHP_CS_FIXER_ARGS) --dry-run

cs-fix: prerequisites
	$(PHP) $(PHP_CS_FIXER) $(PHP_CS_FIXER_ARGS)

phpmnd: prerequisites
	$(PHP) vendor/povils/phpmnd/bin/phpmnd run src

phpcpd: prerequisites
	$(PHP) vendor/bin/phpcpd src

schema-validate: prerequisites
	$(PHP) bin/console doctrine:schema:validate

composer-validate: prerequisites
	$(PHP) composer validate

security-check: prerequisites
	$(PHP) local-php-security-checker

analyse: cs-check phpmnd phpcpd lint composer-validate schema-validate security-check

lint: prerequisites
	$(PHP) bin/console lint:yaml config --parse-tags
	$(PHP) bin/console lint:yaml src
	$(PHP) bin/console lint:container

##############################################################
# Xdebug				                                     #
##############################################################

xdebug-status:
	@cd docker/php/xdebug && bash xdebug status

xdebug-stop:
	@cd docker/php/xdebug && bash xdebug stop

prerequisites: vendor/autoload.php composer.lock

xdebug-start:
	@cd docker/php/xdebug && bash xdebug start

##############################################################
# Prerequisites Setup                                        #
##############################################################

db-reinstall: prerequisites
	$(PHP) bin/console doctrine:database:drop --force --if-exists
	$(PHP) bin/console doctrine:database:create
	$(PHP) bin/console doctrine:schema:update --force

db-test-install: prerequisites
	$(PHP) bin/console doctrine:database:drop --force --if-exists --env=test
	$(PHP) bin/console doctrine:database:create --env=test
	$(PHP) bin/console doctrine:schema:update --force --env=test

create-migration: prerequisites
	$(PHP) bin/console doctrine:migrations:diff

migrate: prerequisites
	$(PHP) bin/console --no-interaction doctrine:migrations:migrate
# We need both vendor/autoload.php and composer.lock being up to date
.PHONY: prerequisites
prerequisites: vendor/autoload.php composer.lock
