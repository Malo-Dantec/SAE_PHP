.PHONY: run
run:
	php -S localhost:8000

.PHONY: install
install:
	composer install

.PHONY: tests
tests:
	vendor/bin/phpunit Tests/

.PHONY: coverage
coverage:
	pass

