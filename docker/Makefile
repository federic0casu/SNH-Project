start:
	systemctl --user start docker-desktop.service

stop:
	systemctl --user stop docker-desktop.service

up:
	docker compose -f docker-compose.yml up --force-recreate --remove-orphans --build

down: 
	docker compose -f docker-compose.yml down

###################################################################
# DEBUG (to speed up start time) (PLEASE, use it wisely)          #
###################################################################
quick-up:
	docker compose -f docker-compose.yml up --remove-orphans