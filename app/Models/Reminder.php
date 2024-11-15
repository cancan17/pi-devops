<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reminder extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'alert',
        'repeat',
        'duration',
        'color',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
