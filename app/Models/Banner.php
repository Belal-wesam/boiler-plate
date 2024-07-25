<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model
{
    use HasFactory;
    protected $fillable = ['image', 'status'];
    protected $appends = ['image_url'];


    public function getImageUrlAttribute()
    {
        return url('/') . Storage::url($this->image);
    }
}
