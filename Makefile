.PHONY: test dev-server create-database

-include .env

DB_HOST?=localhost
DB_PORT?=3306
DB_USER?=root
DB_PASSWORD?=root
DB_NAME?=jeedom

PHP?=php
PHPUNIT?=vendor/bin/phpunit
SERVER_HOST?=localhost
SERVER_PORT?=80
PUBLIC_PATH=./

phpunit.xml: phpunit.xml.dist
	cp $< $@

test: phpunit.xml
	$(PHPUNIT) --configuration $<

dev-server: $(PUBLIC_PATH)/index.php
	$(PHP) -S $(SERVER_HOST):$(SERVER_PORT) -t $(PUBLIC_PATH)

create-database:
	mysql \
	--host=$(DB_HOST):$(DB_PORT)
	--user=$(DB_USER) \
	--password=$(DB_PASSWORD) \
	--execute=`CREATE DATABASE $(DB_NAME);`
