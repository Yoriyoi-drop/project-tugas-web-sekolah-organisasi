.PHONY: help build up down restart logs shell migrate seed fresh test clean

# Default target
help:
	@echo "Available commands:"
	@echo ""
	@echo "  Development:"
	@echo "    make build-dev      Build development containers"
	@echo "    make dev            Start development environment"
	@echo "    make stop-dev       Stop development environment"
	@echo ""
	@echo "  Production:"
	@echo "    make build          Build production containers"
	@echo "    make up             Start production environment"
	@echo "    make down           Stop production environment"
	@echo ""
	@echo "  Utilities:"
	@echo "    make restart        Restart production environment"
	@echo "    make logs           View production logs"
	@echo "    make logs-dev       View development logs"
	@echo "    make shell          Open shell in production app"
	@echo "    make shell-dev      Open shell in development app"
	@echo "    make migrate        Run database migrations"
	@echo "    make seed           Run database seeders"
	@echo "    make fresh          Fresh migration with seeding"
	@echo "    make test           Run tests"
	@echo "    make clean          Remove all containers and volumes"

# ============================================
# Development
# ============================================
build-dev:
	docker compose --profile dev build

dev:
	docker compose --profile dev up -d

stop-dev:
	docker compose --profile dev down

# ============================================
# Production
# ============================================
build:
	docker compose build app

up:
	docker compose up -d

down:
	docker compose down

restart:
	docker compose restart

# ============================================
# Logs
# ============================================
logs:
	docker compose logs -f app

logs-dev:
	docker compose --profile dev logs -f app-dev

# ============================================
# Shell Access
# ============================================
shell:
	docker compose exec app sh

shell-dev:
	docker compose --profile dev exec app-dev bash

# ============================================
# Database
# ============================================
migrate:
	docker compose exec app php artisan migrate --force

seed:
	docker compose exec app php artisan db:seed --force

fresh:
	docker compose exec app php artisan migrate:fresh --seed --force

# ============================================
# Testing
# ============================================
test:
	docker compose --profile dev exec app-dev php artisan test

# ============================================
# Cleanup
# ============================================
clean:
	docker compose --profile dev down -v --rmi local --remove-orphans
	docker compose down -v --rmi local --remove-orphans
