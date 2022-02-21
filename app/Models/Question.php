<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    /**
     * Get next question of survey based on order
     *
     * @return Question
     */
    public function next()
    {
        return self::where('survey_id', $this->survey_id)
            ->where('id', '!=', $this->id)
            ->whereNull('option_id')
            ->where('order', '>=', $this->order)
            ->with('options')
            ->orderBy('order')
            ->first();
    }
}
