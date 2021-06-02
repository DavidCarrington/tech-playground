.PHONY: up down restart

up:
	docker compose up -d app
	docker compose exec app bash -c ./setup-couchbase.sh

down:
	docker compose down -v --remove-orphans

restart: down up
