<?php

namespace App;
use Illuminate\Notifications\Notifiable;


use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use Notifiable;
    protected $fillable = [
        'name', 'email', 'logo','website'
    ];
}
