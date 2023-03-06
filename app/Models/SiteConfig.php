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
        'field_type',
    ];
    public function getValueAttribute($value)
    {
        switch ($this->field_type) {
            case 'json':
                return json_decode($value);
                break;
            case 'boolean':
                return (int) $value === 1;
                break;
            case 'text':
            default:
                return $value;
                break;
        }
    }
}
