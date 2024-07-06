start:
	systemctl start docker

stop:
	systemctl stop docker

up:
	docker compose -f docker-compose.yml up -d --force-recreate --remove-orphans --build

down: 
	docker compose -f docker-compose.yml down

# DEBUG (to speed up start time) (PLEASE, use it wisely)
quick-up:
	docker compose -f docker-compose.yml up -d --remove-orphans