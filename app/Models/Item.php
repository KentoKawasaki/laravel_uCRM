<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function purchases() {
        return $this->belongsToMany(Purchase::class)->withPivot('quantity')->withTimestamps();
    }
}
