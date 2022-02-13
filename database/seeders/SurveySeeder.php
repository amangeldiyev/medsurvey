<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use App\Models\Survey;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    protected $questions = [
        [
            'title' => 'Дата рождения',
            'order' => 1,
            'options' => [
                ['title' => 'Дата', 'type' => 'date']
            ]
        ],
        [
            'title' => 'Ваш рост',
            'order' => 2,
            'options' => [
                ['title' => 'Рост', 'type' => 'number']
            ]
        ],
        [
            'title' => 'Ваш вес',
            'order' => 3,
            'options' => [
                ['title' => 'Вес', 'type' => 'number']
            ]
        ],
        [
            'title' => 'Вы курите?',
            'order' => 4,
            'options' => [
                ['title' => 'Да', 'type' => 'select'],
                ['title' => 'Нет', 'type' => 'select'],
            ]
        ],
        [
            'title' => 'Сколько раз в день?',
            'order' => 5,
            'option_id' => '4.1',
            'options' => [
                ['title' => 'До 10 сигарет', 'type' => 'select'],
                ['title' => '10-20 сигарет', 'type' => 'select'],
                ['title' => 'Более 20 сигарет', 'type' => 'select'],
            ]
        ],
        [
            'title' => 'С какого возраста началась менструация?',
            'order' => 6,
            'options' => [
                ['title' => 'Возраст', 'type' => 'number'],
            ]
        ],
        [
            'title' => 'Регулярные или нет?',
            'order' => 7,
            'options' => [
                ['title' => 'Регулярные', 'type' => 'select'],
                ['title' => 'Не регулярные', 'type' => 'select'],
            ]
        ],
        [
            'title' => 'Регуляная ли половая жизнь?',
            'order' => 8,
            'options' => [
                ['title' => '2 и более раз в неделю', 'type' => 'select'],
                ['title' => '1 раз в неделю', 'type' => 'select'],
                ['title' => '1-2 раза в месяц', 'type' => 'select'],
                ['title' => '1-3 раза в год', 'type' => 'select'],
            ]
        ],
        [
            'title' => 'Концептрация в данный момент',
            'order' => 9,
            'options' => [
                ['title' => 'ВМС', 'type' => 'select'],
                ['title' => 'КОК', 'type' => 'select'],
                ['title' => 'Презерватив', 'type' => 'select'],
                ['title' => 'Спермициды', 'type' => 'select'],
                ['title' => 'ППА', 'type' => 'select'],
                ['title' => 'Календарный', 'type' => 'select'],
                ['title' => 'Не использую', 'type' => 'select'],
            ]
        ],
        [
            'title' => 'Сколько месяцев',
            'order' => 10,
            'option_id' => '9.1',
            'options' => [
                ['title' => 'месяцев', 'type' => 'number'],
            ]
        ],
        [
            'title' => 'Сколько месяцев',
            'order' => 11,
            'option_id' => '9.2',
            'options' => [
                ['title' => 'месяцев', 'type' => 'number'],
            ]
        ],
        [
            'title' => 'Планируете ли Вы беременность?',
            'order' => 12,
            'options' => [
                ['title' => 'Да', 'type' => 'select'],
                ['title' => 'Нет, но хочу знать о своих репродуктивных возможностях', 'type' => 'select'],
            ]
        ],
        [
            'title' => 'Как долго Вы пробуете забеременеть?',
            'order' => 13,
            'option_id' => '12.1',
            'options' => [
                ['title' => 'Менее 6 месяцев', 'type' => 'select'],
                ['title' => 'Менее 1 года', 'type' => 'select'],
                ['title' => 'Более 1 года', 'type' => 'select'],
            ]
        ],
        [
            'title' => 'Сдавали ли Вы гормон АМГ?',
            'order' => 14,
            'options' => [
                ['title' => 'Да', 'type' => 'select'],
                ['title' => 'Нет', 'type' => 'select'],
            ]
        ],
        [
            'title' => 'Когда сдавали?',
            'order' => 15,
            'option_id' => '14.1',
            'options' => [
                ['title' => 'До 1 года', 'type' => 'select'],
                ['title' => 'Более 1 года назад', 'type' => 'select'],
                ['title' => 'Значение', 'type' => 'number'],
            ]
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $survey = Survey::factory()->create(['title' => 'Default']);

        foreach ($this->questions as $question_data) {
            $option_id = null;
            
            if (isset($question_data['option_id'])) {
                $option = explode('.', $question_data['option_id']);
                $parent_question = Question::where('order', $option[0])->first();
                $option_id = $parent_question->options()->where('order', $option[1])->first()->id;
            }

            $question = Question::factory()->create([
                'title' => $question_data['title'],
                'order' => $question_data['order'],
                'survey_id' => $survey->id,
                'option_id' => $option_id
            ]);

            foreach ($question_data['options'] as $order => $option) {
                Option::factory()->for($question)->create([
                    'title' => $option['title'],
                    'type' => $option['type'],
                    'order' => $order + 1
                ]);
            }
        }
    }
}
