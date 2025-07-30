<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'location',
        'start_time',
        'end_time',
        'created_by',
        'max_participants',
        'is_active'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean'
    ];

    /**
     * イベントの作成者との関係
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * 参加申込との関係
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * 申込済み参加者との関係
     */
    public function registeredUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_registrations')
                    ->wherePivot('status', 'registered')
                    ->withPivot(['status', 'registered_at', 'notes'])
                    ->withTimestamps();
    }

    /**
     * 現在の参加者数を取得
     */
    public function getCurrentParticipantCount(): int
    {
        return $this->registrations()->where('status', 'registered')->count();
    }

    /**
     * 申込可能かどうか
     */
    public function canRegister(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->max_participants && $this->getCurrentParticipantCount() >= $this->max_participants) {
            return false;
        }

        return $this->start_time > now();
    }

    /**
     * ユーザーが申込済みかどうか
     */
    public function isRegisteredBy(User $user): bool
    {
        return $this->registrations()
                    ->where('user_id', $user->id)
                    ->where('status', 'registered')
                    ->exists();
    }
}
