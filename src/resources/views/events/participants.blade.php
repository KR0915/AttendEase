<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                参加者一覧: {{ $event->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('events.show', $event) }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    イベント詳細に戻る
                </a>
                <a href="{{ route('events.index') }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    一覧に戻る
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- イベント情報サマリー -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600">
                                {{ $registrations->count() }}
                            </div>
                            <div class="text-sm text-gray-600">現在の参加者数</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">
                                {{ $event->max_participants ?? '∞' }}
                            </div>
                            <div class="text-sm text-gray-600">定員</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold {{ $event->canRegister() ? 'text-green-600' : 'text-red-600' }}">
                                {{ $event->canRegister() ? '受付中' : '受付終了' }}
                            </div>
                            <div class="text-sm text-gray-600">申込状況</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 参加者一覧 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">👥 参加者一覧</h3>
                    
                    @if($registrations->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            #
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            参加者名
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            メールアドレス
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            申込日時
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            備考
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($registrations as $index => $registration)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                            <span class="text-blue-600 font-medium">
                                                                {{ substr($registration->user->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $registration->user->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $registration->user->email }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $registration->registered_at->format('Y/m/d H:i') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $registration->notes ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-lg mb-2">👥</div>
                            <p class="text-gray-500">まだ参加者がいません。</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- CSV エクスポート機能（将来拡張用） -->
            @if($registrations->count() > 0)
                <div class="mt-6 text-center">
                    <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                        <p class="text-yellow-800 text-sm">
                            💡 今後の機能: CSV エクスポート、出席確認、参加者への一括メール送信など
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
