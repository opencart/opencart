.PHONY: console build

console: build
	docker run -it -v="$(PWD):/braintree-php" --net="host" braintree-php /bin/bash -l -c "\
		curl -sS https://getcomposer.org/installer | php -d suhosin.executor.include.whitelist=phar && \
		php -d suhosin.executor.include.whitelist=phar ./composer.phar install; \
		bash"

build:
	docker build -t braintree-php .
