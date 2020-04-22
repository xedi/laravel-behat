# Variables

cmd-composer-install = install --ignore-platform-reqs --no-scripts ${DOWNLOAD_PROGRESS}

cmd-composer-update = update --ignore-platform-reqs --no-scripts ${DOWNLOAD_PROGRESS}

cmd-composer-install-dev = 	install --ignore-platform-reqs --no-scripts --dev ${DOWNLOAD_PROGRESS}

# Coverage

.PHONY: coverage-html coverage-text coverage-clover coverage-interactive
.SILENT: coverage-html coverage-text coverage-clover coverage-interactive

coverage-html:
	docker run --rm --interactive --tty \
	--volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/web/html \
	--user $(id -u):$(id -g) \
	xediltd/xdebug /bin/bash -ci "vendor/bin/phpunit --coverage-html=coverage -d zend.enable_gc=0"

coverage-text:
	docker run --rm --interactive --tty \
	--volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/web/html \
	--user $(id -u):$(id -g) \
	xediltd/xdebug /bin/bash -ci "vendor/bin/phpunit --coverage-text"

coverage-clover:
	docker run --rm --interactive --tty \
	--volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/web/html \
	--user $(id -u):$(id -g) \
	xediltd/xdebug /bin/bash -ci "vendor/bin/phpunit --coverage-clover clover.xml -d zend.enable_gc=0"

coverage-interactive:
	docker run --rm --interactive --tty \
	--volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/web/html \
	--user $(id -u):$(id -g) \
	xediltd/xdebug /bin/bash

# Testing and Analysis

.PHONY: codacy-upload phpunit phpunit-interactive phpcs phpcs-interactive phpcs-changes phpmd tests
.SILENT: codacy-upload phpunit phpunit-interactive phpcs phpcs-interactive phpcs-changes phpmd tests

codacy-upload: coverage-clover
	docker run --rm --interactive --tty \
	--volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/web/html \
	--user $(id -u):$(id -g) \
	--env-file .env \
	-e "GIT_COMMIT=$$(git rev-parse HEAD)" \
	xediltd/codacy-upload

phpunit:
	docker run --rm --interactive --tty \
	--volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/web/html \
	--user $(id -u):$(id -g) \
	xediltd/phpunit

phpunit-interactive:
	docker run --rm --interactive --tty \
	--volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/web/html \
	--user $(id -u):$(id -g) \
	xediltd/phpunit /bin/bash

phpcs:
	docker run --rm --interactive --tty \
	--volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/web/html \
	--user $(id -u):$(id -g) \
	xediltd/phpcs

phpcs-interactive:
	docker run --rm --interactive --tty \
	--volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/web/html \
	--user $(id -u):$(id -g) \
	xediltd/phpcs /bin/bash

phpcs-changes:
	docker run --rm --interactive --tty \
	--volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/web/html \
	--user $(id -u):$(id -g) \
	xediltd/phpcs /bin/bash -ci "vendor/bin/phpcs -a -- $(shell git diff --staged --name-only --diff-filter d)"

phpmd:
	docker run --rm --interactive --tty \
	--volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/web/html \
	--user $(id -u):$(id -g) \
	xediltd/phpmd /bin/bash -ci "vendor/bin/phpmd src,tests text codesize.xml --ignore-violations-on-exit"

tests: phpunit phpcs

# Utilities

.PHONY: xuidentity
.SILENT: xuidentity

xuidentity:
	docker build -t xuidentity resources/xuidentity
	docker run --rm --interactive --tty \
	--volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/web/html \
	--user $(id -u):$(id -g) \
	xuidentity

# Composer

.PHONY: composer-install composer-update composer-install-dev composer-dump-auto
.SILENT: composer-install composer-update composer-install-dev composer-dump-auto

composer-install:
	docker run --rm \
	--volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/app \
	--env-file .env \
	--user $(id -u):$(id -g) \
	composer $(cmd-composer-install)
	rm -f auth.json

composer-update:
	docker run --rm --interactive --tty \
	--volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/app \
	--env-file .env \
	--user $(id -u):$(id -g) \
	composer $(cmd-composer-update)
	rm -f auth.json

composer-install-dev:
	docker run --rm --interactive --tty \
	-v $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/app \
	--env-file .env \
	--user $(id -u):$(id -g) \
	composer $(cmd-composer-install-dev)
	rm -f auth.json

composer-dump-auto:
	docker run --rm \
	--volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/app \
	--env-file .env \
	--user $(id -u):$(id -g) \
	composer dump-autoload
	rm -f auth.json

composer-add-dep:
	docker run --rm --interactive --tty \
	--volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/app \
	--env-file .env \
	--user $(id -u):$(id -g) \
	composer /bin/bash -ci "composer require $(module) $(version) --ignore-platform-reqs --no-scripts"
	rm -f auth.json

composer-add-dev-dep:
	docker run --rm --interactive --tty \
	--volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/app \
	--env-file .env \
	--user $(id -u):$(id -g) \
	composer /bin/bash -ci "composer require $(module) $(version) --dev --ignore-platform-reqs --no-scripts"
	rm -f auth.json

# CICD

.PHONY: cicd-composer-install
.SILENT: cicd-composer-install

cicd-composer-install:
	composer $(cmd-composer-install)
	rm auth.json
