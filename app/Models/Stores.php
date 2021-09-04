<?php

namespace App\Models;

use App\Models\Platforms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stores extends Model
{
    use HasFactory;

    protected $primaryKey = 'storeId';

    protected $fillable = [
        'name',
        'sellerId',
        'logo',
    ];


    public function platform()
    {
        return $this->hasOne(Platforms::class);
    }
}
