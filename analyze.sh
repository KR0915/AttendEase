#!/bin/bash

echo "=== AttendEase Laravel Error Analysis ==="
echo "Timestamp: $(date)"
echo "========================================"

# 1. 本番環境のテスト
echo ""
echo "1. Production Environment Tests:"
echo "--------------------------------"

echo "Basic PHP test:"
curl -s https://attendease-production.up.railway.app/test.php | head -20

echo ""
echo "Laravel error analysis:"
curl -s https://attendease-production.up.railway.app/error-analysis.php | head -50

echo ""
echo "Main application index:"
curl -s -I https://attendease-production.up.railway.app/ | head -10

# 2. ローカルファイル構造の確認
echo ""
echo "2. Local File Structure Analysis:"
echo "--------------------------------"

echo "Critical Laravel files:"
for file in "src/bootstrap/app.php" "src/config/app.php" "src/public/index.php" "src/.env" "railway.toml"; do
    if [ -f "$file" ]; then
        echo "✓ $file exists"
    else
        echo "✗ $file missing"
    fi
done

echo ""
echo "Railway configuration:"
cat railway.toml

echo ""
echo "Deployment script:"
cat deploy-simple.sh

# 3. Git状態の確認
echo ""
echo "3. Git Repository Status:"
echo "------------------------"
git status --porcelain
echo "Current branch: $(git branch --show-current)"
echo "Last commit: $(git log -1 --oneline)"

# 4. 環境変数の確認
echo ""
echo "4. Environment Variables Check:"
echo "------------------------------"
echo "Railway environment variables that should be set:"
echo "- APP_KEY"
echo "- DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD"
echo "- APP_URL"

# 5. Railway API 直接アクセスによる詳細診断
echo ""
echo "5. Railway Deployment Status (via curl):"
echo "----------------------------------------"

# プロジェクトの状態を確認
echo "Trying to get deployment information..."
if command -v jq >/dev/null 2>&1; then
    echo "jq is available for JSON parsing"
else
    echo "jq not available, using raw output"
fi

# 6. より詳細なファイル確認
echo ""
echo "6. Detailed File Analysis:"
echo "-------------------------"

echo "src/public/index.php content (first 20 lines):"
head -20 src/public/index.php

echo ""
echo "src/.env file exists and size:"
if [ -f "src/.env" ]; then
    echo "File size: $(wc -c < src/.env) bytes"
    echo "Contains APP_KEY: $(grep -c 'APP_KEY=' src/.env || echo '0')"
    echo "Contains DB_HOST: $(grep -c 'DB_HOST=' src/.env || echo '0')"
else
    echo "❌ .env file missing"
fi

echo ""
echo "Recent deployment files modification times:"
ls -la src/public/index.php src/bootstrap/app.php src/config/app.php 2>/dev/null

# 7. ネットワーク診断
echo ""
echo "7. Network Diagnostics:"
echo "----------------------"
echo "DNS resolution for attendease-production.up.railway.app:"
nslookup attendease-production.up.railway.app 2>/dev/null || echo "DNS lookup failed"

echo ""
echo "Ping test (if available):"
ping -c 3 attendease-production.up.railway.app 2>/dev/null || echo "Ping not available or failed"

# 8. Laravel 特有の診断
echo ""
echo "8. Laravel Specific Diagnostics:"
echo "--------------------------------"
echo "Composer dependencies (production):"
if [ -f "src/composer.lock" ]; then
    echo "✓ composer.lock exists"
    grep -A 5 '"name": "laravel/framework"' src/composer.lock | head -10
else
    echo "❌ composer.lock missing"
fi

echo ""
echo "Laravel version from artisan:"
cd src 2>/dev/null && php artisan --version 2>/dev/null || echo "❌ Cannot determine Laravel version"
cd ..

echo ""
echo "=== Analysis Complete ==="
echo "Recommended Next Steps:"
echo "1. Check Railway dashboard for deployment logs"
echo "2. Verify environment variables in Railway dashboard"
echo "3. Check if service is running in Railway dashboard"
echo "4. Consider redeploying with 'git push' to trigger new deployment"
