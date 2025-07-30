<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('イベント一覧') }}
            </h2>
            <a href="{{ route('events.create') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                新しいイベントを作成
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 成功メッセージ -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($events->count() > 0)
                        <div class="grid gap-6">
                            @foreach ($events as $event)
                                <div class="border rounded-lg p-6 hover:shadow-lg transition-shadow">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                                <a href="{{ route('events.show', $event) }}" 
                                                   class="hover:text-blue-600">
                                                    {{ $event->title }}
                                                </a>
                                            </h3>
                                            
                                            @if ($event->description)
                                                <p class="text-gray-600 mb-3">
                                                    {{ Str::limit($event->description, 150) }}
                                                </p>
                                            @endif

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-500">
                                                <div>
                                                    <span class="font-medium">📍 場所:</span>
                                                    {{ $event->location ?? '未設定' }}
                                                </div>
                                                <div>
                                                    <span class="font-medium">🕐 開始:</span>
                                                    {{ $event->start_time->format('Y/m/d H:i') }}
                                                </div>
                                                <div>
                                                    <span class="font-medium">🕐 終了:</span>
                                                    {{ $event->end_time->format('Y/m/d H:i') }}
                                                </div>
                                            </div>

                                            <div class="mt-3 text-sm text-gray-500">
                                                <span class="font-medium">👤 作成者:</span>
                                                {{ $event->creator->name }}
                                                @if ($event->max_participants)
                                                    <span class="ml-4 font-medium">👥 定員:</span>
                                                    {{ $event->max_participants }}名
                                                @endif
                                            </div>
                                        </div>

                                        @if ($event->created_by === auth()->id())
                                            <div class="flex space-x-2 ml-4">
                                                <a href="{{ route('events.edit', $event) }}" 
                                                   class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                    編集
                                                </a>
                                                <form action="{{ route('events.destroy', $event) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('本当に削除しますか？')" 
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                        削除
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- ページネーション -->
                        <div class="mt-6">
                            {{ $events->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500 text-lg mb-4">
                                📅 まだイベントがありません
                            </div>
                            <a href="{{ route('events.create') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                最初のイベントを作成する
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
