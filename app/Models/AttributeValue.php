<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AttributeValue extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->{$user->getKeyName()})) {
                $user->{$user->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    protected $fillable = ['entity_id', 'attribute_id', 'value'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'entity_id');
    }
}
