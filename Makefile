# Makefile for OpenCart Docker Environment

# Use bash for command execution for its advanced features like arrays and functions.
SHELL := /bin/bash

# --- Color Codes ---
# Using the 8-bit (256-color) palette for maximum compatibility.
OPENCART_COLOR := \033[38;5;45m
COLOR_RESET := \033[0m

# --- Docker Compose Setup ---
# Define the base docker compose command to avoid repetition.
COMPOSE := docker compose --env-file=./docker/.env.docker

# --- Variable Handling for Options ---
# For passing extra arguments to docker compose commands.
# Example: make down options="-v --remove-orphans"
options ?=
# For optional profiles.
# Example: make up profiles="adminer redis"
profiles ?=
PROFILES_FLAGS := $(foreach p,$(profiles),--profile $(p))

# --- Helper Functions ---
# Check if a service is running
define check_service_running
	@if ! $(COMPOSE) ps --services --filter "status=running" | grep -q "^$(1)$$"; then \
		echo "Error: Service '$(1)' is not running."; \
		echo "Run 'make up' first or check 'make ps' for service status."; \
		exit 1; \
	fi
endef

# List of all known targets.
KNOWN_TARGETS := help init build up down restart logs apache php mysql exec ps

# Phony targets are not files.
.PHONY: $(KNOWN_TARGETS)

# Default target to run when 'make' is called without arguments.
.DEFAULT_GOAL := help

help: ## Show this help message
	@echo "OpenCart Docker Environment"
	@echo "---------------------------"
	@echo "Usage: make <target> [options=\"...\"] [profiles=\"...\"]"
	@echo ""
	@echo "Core Commands:"
	@echo -e "  $(OPENCART_COLOR)make init$(COLOR_RESET)           - Initialize the project (copies .env.docker)"
	@echo -e "  $(OPENCART_COLOR)make build$(COLOR_RESET)          - Build or rebuild Docker images"
	@echo -e "  $(OPENCART_COLOR)make up$(COLOR_RESET)             - Start all services"
	@echo -e "  $(OPENCART_COLOR)make down$(COLOR_RESET)           - Stop and remove all containers"
	@echo -e "  $(OPENCART_COLOR)make restart$(COLOR_RESET)        - Restart all services"
	@echo -e "  $(OPENCART_COLOR)make ps$(COLOR_RESET)             - Show status of all services"
	@echo -e "  $(OPENCART_COLOR)make logs$(COLOR_RESET)           - Show logs from all running services"
	@echo ""
	@echo "Service Interaction:"
	@echo -e "  $(OPENCART_COLOR)make php$(COLOR_RESET)            - Enter the PHP container (bash)"
	@echo -e "  $(OPENCART_COLOR)make apache$(COLOR_RESET)         - Enter the Apache container (sh)"
	@echo -e "  $(OPENCART_COLOR)make mysql$(COLOR_RESET)          - Enter the MySQL container (bash)"
	@echo -e "  $(OPENCART_COLOR)make exec$(COLOR_RESET)           - Execute a command in a service"
	@echo ""
	@echo "Examples:"
	@echo "  make up profiles=\"adminer redis\""
	@echo "  make down options=\"-v\""
	@echo "  make build options=\"--no-cache\""
	@echo "  make logs options=\"php\""
	@echo "  make exec service=php command=\"composer --version\""
	@echo ""
	@echo "Links:"
	@echo "  OpenCart:       https://www.opencart.com"
	@echo "  Live Demo:      https://www.opencart.com/index.php?route=cms/demo"
	@echo "  Documentation:  https://docs.opencart.com"
	@echo "  Support Forums: https://forum.opencart.com"
	@echo "  GitHub:         https://github.com/opencart/opencart"

# --- Project Lifecycle Targets ---
init: ## Initialize the project (copies .env.docker)
	@if [ ! -f ./docker/.env.docker ]; then \
		echo "Copying docker/.env.docker.example to docker/.env.docker..."; \
		cp docker/.env.docker.example docker/.env.docker; \
	else \
		echo "docker/.env.docker already exists. Skipping."; \
	fi

build: ## Build images. Use 'options' for flags (e.g., --no-cache)
	$(COMPOSE) build $(options)

up: ## Start services. Use 'profiles' and 'options'
	@echo "Starting services..."
	$(COMPOSE) $(PROFILES_FLAGS) up -d $(options)

down: ## Stop containers. Use 'options' for flags (e.g., -v)
	@echo "Stopping services..."
	$(COMPOSE) down $(options)

restart: ## Restart services. Passes 'options' and 'profiles'
	@$(MAKE) down options="$(options)"
	@$(MAKE) up profiles="$(profiles)" options="$(options)"

# --- Service Status Commands ---
ps: ## Show status of all services
	@$(COMPOSE) ps

logs: ## Show logs. Use 'options' to specify services (e.g., php)
	@# If specific service provided, check if it exists
	@if [ -n "$(options)" ] && ! echo "$(options)" | grep -q "^-"; then \
		if ! $(COMPOSE) config --services | grep -q "^$(options)$$"; then \
			echo "Error: Service '$(options)' not found."; \
			echo "Available services:"; \
			$(COMPOSE) config --services | sed 's/^/  - /'; \
			exit 1; \
		fi; \
	fi
	$(COMPOSE) logs -f $(options)

# --- Service Interaction Targets ---
exec: ## Execute a command. Usage: make exec service=<name> command="<command>"
	@if [ -z "$(service)" ] || [ -z "$(command)" ]; then \
		echo "Error: 'service' and 'command' variables are required."; \
		echo "Usage: make exec service=<name> command=\"<command>\""; \
		exit 1; \
	fi
	@# Validate service exists in docker-compose
	@if ! $(COMPOSE) config --services | grep -q "^$(service)$$"; then \
		echo "Error: Service '$(service)' not found in docker-compose configuration."; \
		echo "Available services:"; \
		$(COMPOSE) config --services | sed 's/^/  - /'; \
		exit 1; \
	fi
	$(call check_service_running,$(service))
	@# Use -- to prevent command injection
	@$(COMPOSE) exec $(service) sh -c "$(command)"

apache: ## Enter the Apache container (sh)
	$(call check_service_running,apache)
	$(COMPOSE) exec apache sh

php: ## Enter the PHP container (bash)
	$(call check_service_running,php)
	@$(COMPOSE) exec --user www-data --workdir /var/www/upload php bash

mysql: ## Enter the MySQL container (bash)
	$(call check_service_running,mysql)
	@$(COMPOSE) exec mysql bash
