<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'active',
        'zip_file',
        'user_id',
        'index_html_path',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
