<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    //
    protected $fillable = ['zip','month','rent','food','medical','other','sector']; 
}
