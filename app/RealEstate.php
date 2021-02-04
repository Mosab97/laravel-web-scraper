<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RealEstate extends Model
{
    protected $table = 'real_estate';
    protected $guarded = [];

    public function images()
    {
        return $this->hasMany(RealEstateImages::class,'real_estate_id','id');
    }
}
