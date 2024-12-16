#!/bin/bash

# Definir cores para o terminal
RESET='\033[0m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'

# Subir os contêineres em segundo plano (detached mode)
echo -e "${BLUE}Subindo os contêineres...${RESET}"
docker-compose up -d --build

# Esperar até o banco de dados ficar pronto (tempo de espera ajustável)
echo -e "${YELLOW}Esperando o banco de dados iniciar...${RESET}"

# executa wait-for-it.sh
./wait-for-it.sh localhost:5432 -- echo "Postgres is up"

# Rodar as migrações
echo -e "${GREEN}Rodando as migrações...${RESET}"
docker-compose exec app php artisan migrate

#finalizar
echo -e "${GREEN}Pronto!${RESET}"