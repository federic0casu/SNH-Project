start:
	systemctl start docker

stop:
	systemctl stop docker

up:
	docker compose -f docker-compose.yml up --force-recreate --remove-orphans --build

down: 
	docker compose -f docker-compose.yml down
