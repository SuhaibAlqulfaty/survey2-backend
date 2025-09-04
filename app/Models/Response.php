<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'question_id',
        'contact_id',
        'answer',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'answer' => 'array'
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
