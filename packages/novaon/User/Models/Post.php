<?php

namespace Novaon\User\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Post extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'posts';
    protected $fillable = [
        'id',
        'title',
        'content',
        'brief',
        'image',
        'user_id',
        'slug',
        'tags',
        'status',
    ];

}
