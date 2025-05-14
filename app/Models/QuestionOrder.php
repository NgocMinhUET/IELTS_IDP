<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOrder extends Model
{
    use HasFactory;

    protected $fillable = ['part_id', 'table', 'question_id', 'order'];
}
