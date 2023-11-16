up:
	docker compose -f ./docker/docker-compose.yml up --force-recreate --build -d

down: 
	docker compose -f ./docker/docker-compose.yml down