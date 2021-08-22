# Smart Short
透過 Laravel 所開發的短網址服務 Demo，系統架構詳情可以查閱目錄下的 SA/SD PDF 檔案

## 軟體版本
PHP 7.4  
MySQL 8.0

## 環境配置
本 Demo 專案使用容器化運行演示，可透過 Docker Compose 一鍵運行， 環境設定完成後即可透過 Postman 等 API 工具使用 API 服務。  
由於本服務為 Demo，在 short-url-demo 目錄下已提供 .env 以及 .env.testing 環境配置檔案，可直接使用，亦可編輯。

### 添加域名 (以 Mac OS 為例)
```bash
# 編輯系統設定檔
sudo vim /etc/hosts

# 加入以下兩個域名
127.0.0.1 api.docker.shorten.com
127.0.0.1 www.shorten.com
```

### 啟動容器
```bash
docker-compose up -d workspace nginx redis mysql mysql2
```

### 安裝套件
```bash
# 進入 docker 容器
docker exec -it $(docker ps | awk '$0 ~ /workspace/{print $1}') /bin/bash

# 進入 short-url-demo 目錄
cd short-url-demo/

# composer 安裝套件
composer install
```

### 建立資料表
```bash
# 進入 docker 容器
docker exec -it $(docker ps | awk '$0 ~ /workspace/{print $1}') /bin/bash

# 進入 short-url-demo 目錄
cd short-url-demo/

# 建立資料表
php artisan migrate
```

### 運行測試
```bash
# 進入 docker 容器
docker exec -it $(docker ps | awk '$0 ~ /workspace/{print $1}') /bin/bash

# 進入 short-url-demo 目錄
cd short-url-demo/

# 建立測試用資料表
php artisan migrate --env=testing

# 執行測試指令
php artisan test
```
