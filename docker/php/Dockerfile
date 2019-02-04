FROM php:7.1-fpm

# Install packages
RUN apt update && apt install -y \
    curl \
    git \
    unzip \
    vim \
    wget \
    zlib1g-dev \
    libbz2-dev \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    libpng16-16 \
    libpq-dev

# Install php extensions
RUN docker-php-ext-install exif \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install iconv \
    && docker-php-ext-install intl \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install opcache \
    && docker-php-ext-install pdo \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip

# Install and configure gd extension
RUN docker-php-ext-install gd \
    && docker-php-ext-configure gd \ 
        --enable-gd-native-ttf \ 
        --with-freetype-dir=/usr/include/freetype2 \ 
        --with-png-dir=/usr/include/ \ 
        --with-jpeg-dir=/usr/include/ \ 
    && docker-php-ext-enable gd

# Install xdebug by pecl
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install yarn
# RUN curl -sLS https://deb.nodesource.com/setup_8.x | bash - && \
#    curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
#    echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list && \
#    apt update && \
#    apt install -y nodejs yarn

# update user and permissions
# RUN usermod -u 1000 www-data
# USER www-data

# Copy custom php configuration
COPY php.ini /usr/local/etc/php/conf.d/

# Expose port
EXPOSE 8000
