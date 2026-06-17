<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Message extends Model
{
    use HasFactory;

        protected $fillable = [
        'phone',
        'content',
        'is_sent',
        'external_message_id',
        'sent_at',
        ];

        protected $casts = [
            'is_sent' => 'boolean',
            'sent_at' => 'datetime',
        ];

        public function scopePending(Builder $query): Builder
        {
            return $query->where('is_sent', false);
        }

        public function scopeSent(Builder $query): Builder
        {
            return $query->where('is_sent', true);
        }
}
