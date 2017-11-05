build:
	cd laradock && docker-compose build nginx mysql
run:
	cd laradock && docker-compose up -d nginx mysql
shell:
	cd laradock && docker-compose exec workspace bash
destroy:
	cd laradock && docker-compose down