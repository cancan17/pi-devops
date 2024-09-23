<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'alert',
        'repeat',
        'duration',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
