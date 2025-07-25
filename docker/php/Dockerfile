FROM php:8.3-apache

# Node.jsとnpmをインストール
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 必要なPHP拡張をインストール
RUN apt-get update && apt-get install -y \
    libonig-dev libzip-dev unzip zip git curl libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip xml dom session

# Composerインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ApacheのドキュメントルートをLaravelのpublicに設定
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Apacheのrewriteモジュールを有効化
RUN a2enmod rewrite

# www-dataユーザーがファイルにアクセスできるように設定
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# エントリーポイントスクリプトをコピー
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# エントリーポイントを設定
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
