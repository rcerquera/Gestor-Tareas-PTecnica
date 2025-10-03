<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Keyword;

class Task extends Model
{
    protected $fillable = ['title', 'is_done'];

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class);
    }
}
