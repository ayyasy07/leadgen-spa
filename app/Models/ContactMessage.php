<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'reply',
        'read_at',
        'replied_at',
        'archived_at',
    ];

    protected $casts = [
        'read_at'     => 'datetime',
        'replied_at'  => 'datetime',
        'archived_at' => 'datetime',
    ];

    /* =====================
       SCOPES
    ===================== */

    public function scopeUnread($q)
    {
        return $q->where('status', 'unread');
    }

    public function scopeInbox($q)
    {
        return $q->whereNull('archived_at');
    }

    public function scopeArchived($q)
    {
        return $q->whereNotNull('archived_at');
    }

    /* =====================
       HELPERS
    ===================== */

    public function isArchived(): bool
    {
        return !is_null($this->archived_at);
    }

    public function canBeArchived(): bool
    {
        // Cuma read / replied yang bisa di-archive
        return in_array($this->status, ['read', 'replied'], true);
    }

    public function markAsRead()
    {
        if ($this->status === 'unread') {
            $this->update([
                'status'  => 'read',
                'read_at' => now(),
            ]);
        }
    }

    public function archive()
    {
        if (!$this->canBeArchived()) {
            return false;
        }
        $this->update(['archived_at' => now()]);
        return true;
    }

    public function unarchive()
    {
        $this->update(['archived_at' => null]);
        return true;
    }
}