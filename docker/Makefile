start:
	systemctl start docker

stop:
	systemctl stop docker

up:
	docker compose -f docker-compose.yml up -d --force-recreate --remove-orphans --build

prune:
	docker system prune -a --volumes

down: 
	docker compose -f docker-compose.yml down
