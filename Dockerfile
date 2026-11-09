FROM php:8.3-fpm-alpine

LABEL maintainer="Forum TIPD PTKIN - SISTER Dosen Dashboard"
WORKDIR /var/www/html

# Install package sistem pendukung
RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    curl \
    curl-dev \
    mariadb-client \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    libxml2-dev \
    oniguruma-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mysqli \
        curl \
        mbstring \
        simplexml \
        fileinfo \
        gd \
        zip \
        opcache

# Setup Nginx, Supervisord & Entrypoint
RUN mkdir -p /run/nginx /var/log/supervisor /var/log/nginx
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Salin source code aplikasi
COPY . /var/www/html

# Pastikan folder writable dan permissions
RUN mkdir -p /var/www/html/cache \
             /var/www/html/upload/back_profil_foto \
             /var/www/html/upload/web_foto_upload \
             /var/www/html/upload/upload_excel \
    && chown -R www-data:www-data /var/www/html/cache /var/www/html/upload \
    && chmod -R 775 /var/www/html/cache /var/www/html/upload

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
