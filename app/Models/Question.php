<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'title',
        'type',
        'required',
        'options',
        'order',
        'settings'
    ];

    protected $casts = [
        'required' => 'boolean',
        'options' => 'array',
        'settings' => 'array'
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}
