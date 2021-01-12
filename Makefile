PROJECT_NAME := "5S Ideal ERP System"

start: 
	php -S 127.0.0.1:8000 -t ./public

# Database operations
db-create:
	php ./bin/console doctrine:database:create

db-remove:
	php ./bin/console doctrine:database:drop --force

db-reset: db-remove db-create migrate load-fixtures

migrate:
	php ./bin/console doctrine:migrations:migrate -n

load-fixtures:
	php ./bin/console doctrine:fixtures:load -n
