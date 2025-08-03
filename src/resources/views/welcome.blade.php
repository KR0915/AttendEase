<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AttendEase - イベント管理システム</title>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">🎉 AttendEase</h1>
                <p class="text-gray-600 mb-6">イベント管理システム</p>
                <div class="space-y-4">
                    <a href="{{ route('login') }}" 
                       class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded block text-center">
                        ログイン
                    </a>
                    <a href="{{ route('register') }}" 
                       class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded block text-center">
                        新規登録
                    </a>
                    <a href="{{ route('events.index') }}" 
                       class="w-full bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded block text-center">
                        イベント一覧
                    </a>
                </div>
                <div class="mt-6 text-sm text-gray-500">
                    <p>✅ Railway デプロイ成功！</p>
                    <p>🚀 本番環境で動作中</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>