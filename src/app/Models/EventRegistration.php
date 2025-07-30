<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'registered_at',
        'cancelled_at',
        'notes'
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'status' => 'string'
    ];

    /**
     * 申込対象のイベント
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * 申込したユーザー
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 申込済みかどうか
     */
    public function isRegistered(): bool
    {
        return $this->status === 'registered';
    }

    /**
     * キャンセル済みかどうか
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
    /**
     * 申込をキャンセルする
     */
    public function cancel(): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);
    }
}
