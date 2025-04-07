<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Contact extends Model
{
    use HasFactory;

    // Table name (optional, only if different from the default)
    protected $table = 'contact';

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
    ];
}
