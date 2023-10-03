<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Books extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'author_id',
        'image',
    ];

    function author() : BelongsTo {
        return $this->belongsTo(Author::class);
    }

    function category() : BelongsTo {
        return $this->belongsTo(Category::class);
    }
}
