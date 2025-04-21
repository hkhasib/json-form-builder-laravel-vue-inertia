<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{

    protected $fillable = [
        'form_id',
        'label',
        'type',
        'placeholder',
        'required',
        'validation_rules',
        'position',
        'class_name',
        'custom_style',
        'name',
        'options',
    ];

    protected $casts = [
        'required' => 'boolean',
        'validation_rules' => 'array',
        'options' => 'array',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function submissionValues()
    {
        return $this->hasMany(FormSubmissionValue::class);
    }
}
