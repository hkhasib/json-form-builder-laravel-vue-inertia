<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmissionValue extends Model
{
    protected $fillable = [
        'submission_id',
        'form_field_id',
        'field_name',
        'value',
    ];

    public function submission()
    {
        return $this->belongsTo(FormSubmission::class);
    }

    public function field()
    {
        return $this->belongsTo(FormField::class, 'form_field_id');
    }

}
