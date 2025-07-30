<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('新しいイベントを作成') }}
            </h2>
            <a href="{{ route('events.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('events.store') }}">
                        @csrf

                        <!-- イベントタイトル -->
                        <div class="mb-6">
                            <x-input-label for="title" :value="__('イベントタイトル')" />
                            <x-text-input id="title" 
                                        class="block mt-1 w-full" 
                                        type="text" 
                                        name="title" 
                                        :value="old('title')" 
                                        required 
                                        autofocus 
                                        placeholder="例: 年次総会" />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- イベント説明 -->
                        <div class="mb-6">
                            <x-input-label for="description" :value="__('イベント説明')" />
                            <textarea id="description" 
                                    name="description" 
                                    rows="4" 
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    placeholder="イベントの詳細を入力してください...">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- 開催場所 -->
                        <div class="mb-6">
                            <x-input-label for="location" :value="__('開催場所')" />
                            <x-text-input id="location" 
                                        class="block mt-1 w-full" 
                                        type="text" 
                                        name="location" 
                                        :value="old('location')" 
                                        placeholder="例: 会議室A、オンライン" />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <!-- 日時設定 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- 開始日時 -->
                            <div>
                                <x-input-label for="start_time" :value="__('開始日時')" />
                                <x-text-input id="start_time" 
                                            class="block mt-1 w-full" 
                                            type="datetime-local" 
                                            name="start_time" 
                                            :value="old('start_time')" 
                                            required />
                                <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                            </div>

                            <!-- 終了日時 -->
                            <div>
                                <x-input-label for="end_time" :value="__('終了日時')" />
                                <x-text-input id="end_time" 
                                            class="block mt-1 w-full" 
                                            type="datetime-local" 
                                            name="end_time" 
                                            :value="old('end_time')" 
                                            required />
                                <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                            </div>
                        </div>

                        <!-- 最大参加者数 -->
                        <div class="mb-6">
                            <x-input-label for="max_participants" :value="__('最大参加者数（任意）')" />
                            <x-text-input id="max_participants" 
                                        class="block mt-1 w-full" 
                                        type="number" 
                                        name="max_participants" 
                                        :value="old('max_participants')" 
                                        min="1" 
                                        placeholder="例: 50" />
                            <x-input-error :messages="$errors->get('max_participants')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-600">空欄の場合は参加者数に制限なし</p>
                        </div>

                        <!-- ボタン -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('events.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                キャンセル
                            </a>
                            <x-primary-button>
                                {{ __('イベントを作成') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 開始日時が変更されたら、終了日時の最小値を設定
        document.getElementById('start_time').addEventListener('change', function() {
            const startTime = this.value;
            const endTimeInput = document.getElementById('end_time');
            
            if (startTime) {
                endTimeInput.min = startTime;
                
                // 終了日時が開始日時より前の場合、終了日時をクリア
                if (endTimeInput.value && endTimeInput.value <= startTime) {
                    endTimeInput.value = '';
                }
            }
        });
    </script>
</x-app-layout>