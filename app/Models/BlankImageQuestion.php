<?php

namespace App\Models;

use App\Enum\AnswerType;
use App\Enum\QuestionTypeAPI;
use App\Models\Traits\HasQuestionOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlankImageQuestion extends Model
{
    use HasFactory;
    use HasQuestionOrder;

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
        'content',
        'type', // detail question type for API
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
        $imgW = $this->width;
        $imgH = $this->height;

        $html = '
        <div class="image-map-wrapper" style="display: inline-block; max-width: 100%;">
            <div style="position: relative; width: 100%; max-width: ' . $imgW . 'px; height: ' . $imgH . 'px;">
                <img 
                    src="' . $this->link . '" 
                    alt="Question Image" 
                    style="position: absolute; width: 100%; height: 100%; top: 0; left: 0"
                >';

                foreach ($this->answers as $answer) {
                    if (empty($answer->input_identify)) continue;

                    $x = intval($answer['x']);
                    $y = intval($answer['y']);
                    $leftPercent = round(($x / $imgW) * 100, 4);
                    $topPercent = round(($y / $imgH) * 100, 4);
                    $placeholder = htmlspecialchars($answer['placeholder'], ENT_QUOTES);
                    $inputIdentify = $answer->input_identify;
                    $inputId = 'input_blank_' . $inputIdentify;

                    $inputStyle = 'width: 120px; height: 30px; border-radius: 5px; text-align: center;';
                    if ($this->answer_type == AnswerType::DRAG_DROP->value) {
                        $inputStyle = 'width: 120px; height: 30px; border: 2px dashed #c5c5c5; border-radius: 5px; text-align: center;';
                    }

                    $html .= '
                    <div 
                        class="blank-input" 
                        style="position: absolute; left: ' . $leftPercent . '%; top: ' . $topPercent . '%;">
                        <input 
                            id="' . $inputId . '"
                            type="text" 
                            placeholder="' . $placeholder . '" 
                            data-blank-id="' . $inputIdentify . '"
                            style="' . $inputStyle . '"
                        >
                    </div>
                ';
                }
            $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    public function getTypeAttribute(): int
    {
        return $this->answer_type == AnswerType::FILL->value ? QuestionTypeAPI::FILL_IMAGE->value :
            QuestionTypeAPI::DRAG_DROP_IMAGE->value;
    }
}
