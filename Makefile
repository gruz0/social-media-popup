.PHONY: help dockerize shell fix_permissions install_linters

help:
	@echo 'Available targets:'
	@echo '  make dockerize'
	@echo '  make shell'
	@echo '  make fix_permissions'
	@echo '  make install_linters'

dockerize:
	docker-compose down
	docker-compose up --build

shell:
	docker-compose exec wordpress bash

fix_permissions:
	docker-compose exec wordpress chown -R www-data:www-data /var/www/html/

install_linters:
	bin/install_linters_dependencies.sh
