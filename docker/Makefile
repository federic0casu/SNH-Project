start:
	systemctl --user start docker-desktop.service

stop:
	systemctl --user stop docker-desktop.service

up:
	docker compose -f docker-compose.yml up -d --force-recreate --remove-orphans --build

down: 
	docker compose -f docker-compose.yml down