<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        
        if (!$user) {
            $this->command->error('ユーザーが存在しません。まずユーザーを作成してください。');
            return;
        }

        $events = [
            [
                'title' => 'Laravel学習会 - 基礎から応用まで',
                'description' => 'Laravel初心者向けの勉強会です。基本的な概念からMVCパターン、データベース操作まで学びます。初心者大歓迎！',
                'location' => '東京都渋谷区のコワーキングスペース',
                'start_time' => '2025-08-15 13:00:00',
                'end_time' => '2025-08-15 17:00:00',
                'created_by' => $user->id,
                'max_participants' => 20,
                'is_active' => true
            ],
            [
                'title' => 'チームビルディングイベント',
                'description' => '部署メンバー間の親睦を深めるためのチームビルディングイベントです。ゲームやディスカッションを通じてコミュニケーションを活性化します。',
                'location' => '品川区のイベントホール',
                'start_time' => '2025-08-22 10:00:00',
                'end_time' => '2025-08-22 16:00:00',
                'created_by' => $user->id,
                'max_participants' => 30,
                'is_active' => true
            ],
            [
                'title' => 'Web技術カンファレンス 2025',
                'description' => '最新のWeb技術トレンドを学べるカンファレンスです。React、Vue.js、Laravel、Node.jsなど様々な技術について専門家が講演します。',
                'location' => '新宿区の大手町カンファレンスセンター',
                'start_time' => '2025-09-05 09:30:00',
                'end_time' => '2025-09-05 18:00:00',
                'created_by' => $user->id,
                'max_participants' => 100,
                'is_active' => true
            ],
            [
                'title' => 'プロジェクト管理セミナー',
                'description' => 'アジャイル開発手法とプロジェクト管理ツールの使い方を学ぶセミナーです。実践的なワークショップも含まれます。',
                'location' => 'オンライン開催（Zoom）',
                'start_time' => '2025-08-30 14:00:00',
                'end_time' => '2025-08-30 16:30:00',
                'created_by' => $user->id,
                'max_participants' => null, // 制限なし
                'is_active' => true
            ],
            [
                'title' => '年末懇親会 2025',
                'description' => '今年一年お疲れ様でした！年末の懇親会を開催します。美味しい料理とお酒を楽しみながら、一年を振り返りましょう。',
                'location' => '六本木の居酒屋「和楽」',
                'start_time' => '2025-12-27 18:00:00',
                'end_time' => '2025-12-27 21:00:00',
                'created_by' => $user->id,
                'max_participants' => 25,
                'is_active' => true
            ]
        ];

        foreach ($events as $eventData) {
            Event::create($eventData);
        }

        $this->command->info('✅ ' . count($events) . '個のテストイベントを作成しました！');
    }
}
