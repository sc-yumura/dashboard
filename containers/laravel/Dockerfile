FROM composer:latest

RUN apk add --no-cache nodejs npm libpq-dev php-pdo_pgsql
RUN docker-php-ext-install pdo_pgsql pgsql

# # 作業ディレクトリを設定
WORKDIR /var/www/html

# # Composerの依存関係をインストール
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# # npmの依存関係をインストール（ビルド用にdevDependenciesも含める）
COPY package.json package-lock.json ./
RUN npm ci

# # アプリケーションコードをコピー
COPY . .

# Viteのビルドを実行
# RUN npm run build

# ビルド後にdevDependenciesを削除してイメージサイズを最適化
# RUN npm prune --production

# Composerのオートローダーを最適化
# RUN composer dump-autoload --optimize --no-dev

# 権限を設定
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache
