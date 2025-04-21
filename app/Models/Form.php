<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'action',
        'method',
        'status',
        'wrapper_class',
        'custom_css',
        'user_id'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function fields()
    {
        return $this->hasMany(FormField::class);
    }

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }

}
