.PHONY: help dockerize shell fix_permissions install_linters tests

help:
	@echo 'Available targets:'
	@echo '  make dockerize'
	@echo '  make shell'
	@echo '  make fix_permissions'
	@echo '  make install_linters'
	@echo '  make tests'

dockerize:
	docker-compose down
	docker-compose up --build

shell:
	docker-compose exec wordpress bash

fix_permissions:
	docker-compose exec wordpress chown -R www-data:www-data /var/www/html/

install_linters:
	bin/install_linters_dependencies.sh

# Use it only inside Docker container (after `make shell` in the repo directory)
tests:
	cd /var/www/html/wp-content/plugins/social-media-popup
	./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/*.php
