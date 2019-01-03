.PHONY: test

-include .env

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
