<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    const TYPE_TEXT = 'text';
    const TYPE_SELECT = 'select';

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function subQuestion()
    {
        return $this->hasOne(Question::class, 'option_id');
    }
}
