<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('イベント詳細') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('events.index') }}" 
                   style="background-color: #6b7280; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block; margin-right: 8px;"
                   onmouseover="this.style.backgroundColor='#4b5563'"
                   onmouseout="this.style.backgroundColor='#6b7280'">
                    一覧に戻る
                </a>
                @if ($event->created_by === auth()->id())
                    <a href="{{ route('events.edit', $event) }}" 
                       style="background-color: #eab308; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block; margin-right: 8px;"
                       onmouseover="this.style.backgroundColor='#a16207'"
                       onmouseout="this.style.backgroundColor='#eab308'">
                        編集
                    </a>
                    <form action="{{ route('events.destroy', $event) }}" 
                          method="POST" 
                          onsubmit="return confirm('本当に削除しますか？')" 
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                style="background-color: #ef4444; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; border: none; cursor: pointer; margin-right: 8px;"
                                onmouseover="this.style.backgroundColor='#dc2626'"
                                onmouseout="this.style.backgroundColor='#ef4444'">
                            削除
                        </button>
                    </form>
                    <a href="{{ route('events.participants', $event) }}" 
                       style="background-color: #8b5cf6; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;"
                       onmouseover="this.style.backgroundColor='#7c3aed'"
                       onmouseout="this.style.backgroundColor='#8b5cf6'">
                        参加者一覧
                    </a>
                @endif
                
                <!-- 参加申込ボタン（作成者以外） -->
                @if ($event->created_by !== auth()->id())
                    @if ($event->isRegisteredBy(auth()->user()))
                        <!-- キャンセルボタン -->
                        <form action="{{ route('events.unregister', $event) }}" 
                              method="POST" 
                              onsubmit="return confirm('参加申込をキャンセルしますか？')" 
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    style="background-color: #f97316; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; border: none; cursor: pointer;"
                                    onmouseover="this.style.backgroundColor='#c2410c'"
                                    onmouseout="this.style.backgroundColor='#f97316'">
                                申込キャンセル
                            </button>
                        </form>
                    @elseif ($event->canRegister())
                        <!-- 申込ボタン -->
                        <form action="{{ route('events.register', $event) }}" 
                              method="POST" 
                              onsubmit="return confirm('このイベントに参加申込しますか？')" 
                              class="inline">
                            @csrf
                            <button type="submit" 
                                    style="background-color: #10b981; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; border: none; cursor: pointer;"
                                    onmouseover="this.style.backgroundColor='#059669'"
                                    onmouseout="this.style.backgroundColor='#10b981'">
                                参加申込
                            </button>
                        </form>
                    @else
                        <!-- 申込不可 -->
                        <span style="background-color: #9ca3af; color: white; font-weight: bold; padding: 8px 16px; border-radius: 4px; cursor: not-allowed;">
                            申込終了
                        </span>
                    @endif
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- 成功メッセージ -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- イベントタイトル -->
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">
                            {{ $event->title }}
                        </h1>
                        <div class="flex items-center text-sm text-gray-500">
                            <span class="mr-4">
                                👤 作成者: <span class="font-medium">{{ $event->creator->name }}</span>
                            </span>
                            <span>
                                📅 作成日: {{ $event->created_at->format('Y年m月d日') }}
                            </span>
                        </div>
                    </div>

                    <!-- イベント情報カード -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- 日時情報 -->
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-800 mb-4">📅 開催日時</h3>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-sm text-blue-600 font-medium">開始:</span>
                                    <div class="text-lg text-blue-900">
                                        {{ $event->start_time->format('Y年m月d日 (D) H:i') }}
                                    </div>
                                </div>
                                <div>
                                    <span class="text-sm text-blue-600 font-medium">終了:</span>
                                    <div class="text-lg text-blue-900">
                                        {{ $event->end_time->format('Y年m月d日 (D) H:i') }}
                                    </div>
                                </div>
                                <div class="pt-2 border-t border-blue-200">
                                    <span class="text-sm text-blue-600">
                                        期間: {{ $event->start_time->diffInHours($event->end_time) }}時間
                                        {{ $event->start_time->diffInMinutes($event->end_time) % 60 }}分
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- 場所・参加者情報 -->
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-800 mb-4">📍 開催情報</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm text-green-600 font-medium">場所:</span>
                                    <div class="text-lg text-green-900">
                                        {{ $event->location ?? '未設定' }}
                                    </div>
                                </div>
                                @if ($event->max_participants)
                                    <div>
                                        <span class="text-sm text-green-600 font-medium">定員:</span>
                                        <div class="text-lg text-green-900">
                                            {{ $event->max_participants }}名
                                        </div>
                                    </div>
                                @else
                                    <div>
                                        <span class="text-sm text-green-600 font-medium">定員:</span>
                                        <div class="text-lg text-green-900">制限なし</div>
                                    </div>
                                @endif
                                <div>
                                    <span class="text-sm text-green-600 font-medium">ステータス:</span>
                                    <div class="text-lg">
                                        @if ($event->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                🟢 アクティブ
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                ⚫ 非アクティブ
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- イベント説明 -->
                    @if ($event->description)
                        <div class="bg-gray-50 p-6 rounded-lg mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">📝 イベント説明</h3>
                            <div class="text-gray-700 whitespace-pre-line leading-relaxed">
                                {{ $event->description }}
                            </div>
                        </div>
                    @endif

                    <!-- 今後の機能予告 -->
                    <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-yellow-800 mb-3">🚧 今後追加予定の機能</h3>
                        <ul class="space-y-2 text-yellow-700">
                            <li>• 📝 参加申込機能</li>
                            <li>• 👥 参加者一覧表示</li>
                            <li>• ✅ 出席確認機能</li>
                            <li>• 📊 出席率統計</li>
                            <li>• 📧 通知機能</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>