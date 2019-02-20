<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'name',
        'company',
        'dateOfDoc',
        'typeOfDoc',
        'numberOnDoc',
        'description',
        'status'
    ];
}
