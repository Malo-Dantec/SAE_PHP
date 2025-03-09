.PHONY: run
run:
	php -S localhost:8000

.PHONY: install
install:
	composer install
	make loaddb

.PHONY: tests
tests:
	vendor/bin/phpunit Tests/

.PHONY: loaddb
loaddb:
	sqlite3 Data/database.db ".read Data/bd.sql"
	php Classes/Config/import_restaurant.php "Data/database.db" 

.PHONY: loaddb_tests
loaddb_tests:
	sqlite3 Tests/Data/test_db.db ".read Data/bd.sql"
	php Classes/Config/import_restaurant.php "Tests/Data/test_db.db" 

.PHONY: coverage
coverage:
	vendor/bin/phpunit --coverage-html coverage-report --coverage-filter ./Classes Tests/

