.PHONY: up down provision restart

up:
	docker compose up -d app
	$(MAKE) provision

provision:
	docker compose exec app bash -c ./setup-couchbase.sh

down:
	docker compose down -v --remove-orphans

restart: down up
