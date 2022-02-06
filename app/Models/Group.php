<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory, HasUuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable =
    [
        'school_id',
        'name',
        'address1',
        'address2',
        'address3',
        'zip_code',
        'city',
        'country_id',
        'comment',
        'status',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
