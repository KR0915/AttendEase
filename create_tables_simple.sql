-- AttendEase 基本テーブル作成SQL

-- migrationsテーブル（Laravel必須）
CREATE TABLE IF NOT EXISTS migrations (
    id int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    migration varchar(255) NOT NULL,
    batch int NOT NULL
);

-- usersテーブル
CREATE TABLE IF NOT EXISTS users (
    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(255) NOT NULL,
    email varchar(255) NOT NULL UNIQUE,
    email_verified_at timestamp NULL,
    password varchar(255) NOT NULL,
    remember_token varchar(100) NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL
);

-- eventsテーブル
CREATE TABLE IF NOT EXISTS events (
    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title varchar(255) NOT NULL,
    description text NULL,
    location varchar(255) NULL,
    start_time timestamp NOT NULL,
    end_time timestamp NOT NULL,
    max_participants int NULL,
    is_public tinyint(1) NOT NULL DEFAULT 1,
    created_at timestamp NULL,
    updated_at timestamp NULL
);

-- event_registrationsテーブル
CREATE TABLE IF NOT EXISTS event_registrations (
    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    event_id bigint unsigned NOT NULL,
    user_id bigint unsigned NOT NULL,
    registration_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status enum('registered', 'cancelled') NOT NULL DEFAULT 'registered',
    created_at timestamp NULL,
    updated_at timestamp NULL,
    FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_event (user_id, event_id)
);

-- sessionsテーブル
CREATE TABLE IF NOT EXISTS sessions (
    id varchar(255) NOT NULL PRIMARY KEY,
    user_id bigint unsigned NULL,
    ip_address varchar(45) NULL,
    user_agent text NULL,
    payload longtext NOT NULL,
    last_activity int NOT NULL,
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
);

-- サンプルデータの挿入
INSERT INTO events (title, description, location, start_time, end_time, max_participants, is_public, created_at, updated_at) VALUES
('Laravel学習会 - 基礎から応用まで', 'Laravel初心者向けの勉強会です。基本的な概念からMVCパターン、データベース操作まで学びます。初心者大歓迎！', '東京都渋谷区のコワーキングスペース', '2025-08-15 13:00:00', '2025-08-15 17:00:00', 30, 1, NOW(), NOW()),
('Web技術カンファレンス 2025', '最新のWeb技術トレンドを学べるカンファレンスです。React、Vue.js、Laravel、Node.jsなど様々な技術について専門家が講演します。', '新宿区の大手町カンファレンスセンター', '2025-08-22 10:00:00', '2025-08-22 16:00:00', 100, 1, NOW(), NOW()),
('チームビルディングイベント', '部署メンバー間の親睦を深めるためのチームビルディングイベントです。ゲームやディスカッションを通じてコミュニケーションを活性化します。', '品川区のイベントホール', '2025-09-05 09:30:00', '2025-09-05 18:00:00', 20, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE id=id;
