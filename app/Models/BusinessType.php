<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessType extends Model
{
    protected $fillable = ['code', 'name', 'description'];

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
