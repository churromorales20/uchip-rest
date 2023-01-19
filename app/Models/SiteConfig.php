<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteConfig extends Model
{
    use HasFactory;
    protected $table = 'site_configurations';
    protected $fillable = [
        'key_config',
        'value',
        'type',
    ];
    public function getValueAttribute($value)
    {
        return $this->type == '2' ? json_decode($value) : $value;
    }
}
