<?php

namespace Novaon\Menus\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Food extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'foods';
    protected $fillable = [
        'id',
        'name',
        'image',
        'description',
        'restaurant_id',
        'price',
    ];
  public function weekdays() {
    return $this->belongsToMany('Novaon\Menus\Models\Weekday', 'menu_foods', 'id_food', 'id_menu');
  }



}
