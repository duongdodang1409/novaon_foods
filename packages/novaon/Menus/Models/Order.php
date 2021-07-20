<?php

namespace Novaon\Menus\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'orders';
    protected $fillable = [
        'id',
        'id_customer',
        'id_food',
        'quantity',
        'price',
        'id_restaurant',
        'id_menu',
        'status'
    ];



}
