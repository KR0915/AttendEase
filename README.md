# AttendEase - 出席管理システム

AttendEase（アテンダンス + イージー）は、Laravel 11とDockerを使用して構築された出席管理アプリケーションです。

## 機能

- ✅ ユーザー認証（登録・ログイン・ログアウト）
- ✅ ダッシュボード
- 🚧 イベント管理（開発予定）
- 🚧 出席管理（開発予定）
- 🚧 レポート・分析（開発予定）

## 技術スタック

- **Backend**: Laravel 11 (PHP 8.3)
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Database**: MySQL 8.0
- **Infrastructure**: Docker, Docker Compose
- **Web Server**: Apache

## セットアップ

### 前提条件

- Docker
- Docker Compose

### インストール手順

1. リポジトリをクローン
```bash
git clone <repository-url>
cd AttendEase
```

2. 環境設定ファイルをコピー
```bash
cp .env.example .env
```

3. Dockerコンテナを起動
```bash
docker-compose up -d
```

4. 依存関係をインストール
```bash
docker-compose exec app composer install
docker-compose exec app npm install
```

5. アプリケーションキーを生成
```bash
docker-compose exec app php artisan key:generate
```

6. データベースマイグレーション
```bash
docker-compose exec app php artisan migrate
```

7. フロントエンドアセットをビルド
```bash
docker-compose exec app npm run build
```

## アクセス

- **アプリケーション**: http://localhost:8000
- **ログイン**: http://localhost:8000/login
- **ユーザー登録**: http://localhost:8000/register
- **ダッシュボード**: http://localhost:8000/dashboard（認証後）

## 開発

### よく使用するコマンド

```bash
# コンテナの状態確認
docker-compose ps

# Laravelコマンド実行
docker-compose exec app php artisan <command>

# フロントエンド開発
docker-compose exec app npm run dev

# テスト実行
docker-compose exec app php artisan test

# ログ確認
docker-compose logs app
```

### プロジェクト構造

```
AttendEase/
├── docker/                 # Docker設定
│   └── php/
│       ├── Dockerfile
│       └── entrypoint.sh
├── src/                     # Laravelアプリケーション
│   ├── app/
│   ├── resources/
│   ├── routes/
│   └── ...
├── docker-compose.yml      # Docker Compose設定
├── .env.example           # 環境設定テンプレート
└── README.md              # このファイル
```

## ライセンス

このプロジェクトは学習目的で作成されています。

## 開発者

AttendEase Development Team
