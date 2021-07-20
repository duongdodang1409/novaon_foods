<?php

namespace Novaon\Menus\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Restaurant extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'restaurants';
    protected $fillable = [
        'id',
        'brand',
        'address',
        'phone',
    ];



}
