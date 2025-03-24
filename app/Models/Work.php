<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    // Table name (optional, only if different from the default)
    protected $table = 'work';

    // Mass assignable attributes
    protected $fillable = [
        'title',
        'description',
        'location',
        'type',
        'category',
        'status',
        'salary',
        'email',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
