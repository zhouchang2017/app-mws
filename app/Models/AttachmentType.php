<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttachmentType extends Model
{
    protected $fillable = [
        'name',
        'rate',
    ];
}
