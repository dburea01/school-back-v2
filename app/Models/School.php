<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory, HasUuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable =

    [

        'name',
        'address1',
        'address2',
        'address3',
        'zip_code',
        'city',
        'country_id',
        'max_users',
        'comment',
        'status',
    ];

    public function setCountryIdAttribute($value)
    {
        $this->attributes['country_id'] = strtoupper($value);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
