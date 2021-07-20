<?php

namespace Novaon\Menus\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Weekday extends Model
{
  use HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $table = 'weekdays';
  protected $fillable = [
    'id',
    'weekday',
  ];
  public function foods() {
    return $this->belongsToMany('Novaon\Menus\Models\Food', 'menu_foods', 'id_menu', 'id_food');
  }


}
