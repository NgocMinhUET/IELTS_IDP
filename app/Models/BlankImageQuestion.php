<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlankImageQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'link',
        'part_id',
        'title',
        'width',
        'height',
        'type',
        'answer_type',
        'answer_label',
        'order'
    ];

    protected $with = ['answers'];

    protected $appends = [
        'content'
    ];

    public function answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BlankImageAnswer::class, 'question_id');
    }

    public function part(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Part::class, 'part_id');
    }

    public function getContentAttribute(): string
    {
        $html = '
        <div class="image-map-wrapper" style="position: relative; display: inline-block;">
            <img src="' . $this->link . '" alt="Question Image" style="max-width: 100%; height: auto; display: block;">
        ';

        foreach ($this->answers as $answer) {
            $x = intval($answer['x']);
            $y = intval($answer['y']);
            $placeholder = htmlspecialchars($answer['placeholder'], ENT_QUOTES);
            $inputIdentify = $answer->input_identify;
            $inputId = 'input_blank_' . $inputIdentify;

            $html .= '
                <div 
                    class="blank-input" 
                    style="position: absolute; left: ' . $x . 'px; top: ' . $y . 'px; transform: translate(-50%, -50%);">
                    <input 
                        id="' . $inputId . '"
                        type="text" 
                        placeholder="' . $placeholder . '" 
                        readonly
                        data-blank-id="' . $inputIdentify . '"
                        class="form-control"
                        style="width: 120px;"
                    >
                </div>
            ';
        }

        $html .= '</div>';

        return $html;
    }
}
