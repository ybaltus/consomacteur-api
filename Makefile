#=================Variables======================================#
#--- MAKE
MAKE = make

#--- PHP CS FIXER
PHPCSFIXER = php vendor/bin/php-cs-fixer

#--- PHPStan
PHPSTAN = php vendor/bin/phpstan

#--- PHP Insights
PHPINSIGHTS = php vendor/bin/phpinsights

#--- PHPUnit
PHPUNIT = php bin/phpunit

#--- Composer
COMPOSER = composer
COMPOSER_INSTALL = $(COMPOSER) install
COMPOSER_UPDATE = $(COMPOSER) update

#--- Symfony cli
SYMFONY = symfony
SYMFONY_CONSOLE = $(SYMFONY) console
SYMFONY_SERVER_START = $(SYMFONY) server:start -d
SYMFONY_SERVER_STOP = $(SYMFONY) server:stop


#=================Commands========================================#

##-----------------🐛 Quality code 🐛-------------#
qa-php-cs-fixer-dry-run-verbose: ## Run php-cs-fixer with dry run and verbose
	$(PHPCSFIXER) fix src --dry-run --verbose

qa-php-cs-fixer-dry-run-diff: ## Run php-cs-fixer with dry run and diff
	$(PHPCSFIXER) fix src --dry-run --diff

qa-php-cs-fixer: ## Run php-cs-fixer
	$(PHPCSFIXER) fix src --verbose

qa-php-stan: ## Run PHPStan
	$(PHPSTAN) analyse

qa-php-insights: ## RUN PHP Insights
	$(PHPINSIGHTS) --no-interaction

qa-sf-security-checker: ## Run symfony security checker.
	$(SYMFONY) security:check

qa-all: ## Run PHP-CS-FIXER, PHP-Stan and PHP-Insights
	$(MAKE) qa-php-cs-fixer
	$(MAKE) qa-php-stan
	$(MAKE) qa-sf-security-checker
	$(MAKE) qa-php-insights

##----------------- ✅ Execute tests ✅ -------------#
test-only:## Run only the PHPUnit
	$(PHPUNIT) --testdox

test:## Execute the fixtures and run the PHPUnit
	$(MAKE) sf-rdb-test
	$(SYMFONY_CONSOLE) doctrine:fixtures:load --no-interaction --env=test
	$(PHPUNIT) --testdox

##----------------- ✅ GRUMPHP QA & Tests ✅ -------------#
grumphp-checker: ## Commands for Grumphp tool
	$(MAKE) qa-php-cs-fixer
	$(MAKE) qa-php-stan
	$(MAKE) qa-sf-security-checker


##----------------- 📦️ Composer 📦️ -------------#
composer-install: ## Install composer dependencies.
	$(COMPOSER_INSTALL)

composer-update: ## Update composer dependencies.
	$(COMPOSER_UPDATE)

##----------------- 🧑‍💻 SYMFONY 🧑‍💻 -------------#
sf-start: ## Start symfony server.
	$(SYMFONY_SERVER_START)

sf-stop: ## Stop symfony server.
	$(SYMFONY_SERVER_STOP)

sf-cc: ## Clear symfony cache.
	$(SYMFONY_CONSOLE) cache:clear

sf-log: ## Show symfony logs.
	$(SYMFONY) server:log

sf-ddc: ## Create symfony database.
	$(SYMFONY_CONSOLE) doctrine:database:create --if-not-exists

sf-ddd: ## Drop symfony database.
	$(SYMFONY_CONSOLE) doctrine:database:drop --if-exists --force

sf-dmm: ## Update symfony schema database.
	$(SYMFONY_CONSOLE) doctrine:migrations:migrate --no-interaction

sf-dfl: ## Execute doctrine fixtures.
	$(SYMFONY_CONSOLE) doctrine:fixtures:load --no-interaction

sf-mm: ## Make migrations.
	$(SYMFONY_CONSOLE) make:migration

sf-rdb: ## Reset database
	$(MAKE) sf-ddd
	$(MAKE) sf-ddc
	$(MAKE) sf-dmm

sf-rdb-test: ## Reset database for tests
	$(SYMFONY_CONSOLE) doctrine:database:drop --if-exists --force --env=test
	$(SYMFONY_CONSOLE) doctrine:database:create --if-not-exists --env=test
	$(SYMFONY_CONSOLE) doctrine:migrations:migrate --no-interaction --env=test

##----------------- 🎉 First install 🎉 -------------#
first-install: composer-install qa-sf-security-checker sf-ddc sf-dmm ## First installation

###----------------- 🎉 Messenger 🎉 -------------#
messenger-consume:## Consume message
	php bin/console messenger:consume async --memory-limit=512M --time-limit=300 -vv

messenger-stop-workers:## Stop the workers
	$(SYMFONY_CONSOLE) messenger:stop-workers

###----------------- 🎉 Systemd worker 🎉 -------------#
systemd-create-link: ## Create the link for the service (Use absolute path for both)
	sudo ln -s /home/yannis/Projets/Perso/consomacteur-api/consomacteur-worker.service /etc/systemd/system/consomacteur-worker.service

systemd-worker-start: ## Start the worker with systemd
	sudo systemctl start consomacteur-worker.service

systemd-worker-stop: ## Stop the worker with systemd
	sudo systemctl stop consomacteur-worker.service

systemd-disable-service: ## Disable and remove the service
	systemctl disable consomacteur-worker.service

systemd-rm-service: ## Remove the link service
	sudo rm /etc/systemd/system/consomacteur-worker.service

systemd-worker-status: ## Status of the worker with systemd
	systemctl status consomacteur-worker.service

systemd-daemon-reload: ## Reload systemd
	sudo systemctl daemon-reload

systemd-journalctl: ## Show the logs
	sudo journalctl -xfeu consomacteur-worker.service

##----------------- 🆘  HELP 🆘  -------------#
help: ## Show this help.
	@echo "Consomacteur Makefile"
	@echo "---------------------------"
	@echo "Usage: make [target]"
	@echo ""
	@echo "Targets:"
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
#---------------------------------------------#

