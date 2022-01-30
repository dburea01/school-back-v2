<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory, HasUuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable =
    [
        'school_id',
        'name',
        'comment',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
