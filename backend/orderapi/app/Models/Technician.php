<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\Node\Block\Document;

class Technician extends Model
{
    use HasFactory;
    
    protected $table = 'technician';
    protected $fillable = [

        'document',
        'name',
        'especiality',
        'phone'
    ];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
