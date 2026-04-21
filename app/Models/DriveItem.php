<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriveItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'parent_id', 'name', 'type', 
        'size', 'mime', 'path', 'is_deleted', 'deleted_at'
    ];
}
