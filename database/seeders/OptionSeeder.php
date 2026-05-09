<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $options = [
            [
                'name' => 'Talla',
                'type' => 1,
                'features' => [
                    [
                        'value' => 's',
                        'description' => 'small'
                    ],
                    [
                        'value' => 'm',
                        'description' => 'medium'
                    ],
                    [
                        'value' => 'l',
                        'description' => 'large'
                    ],
                    [
                        'value' => 'xl',
                        'description' => 'extra large'
                    ],
                    [
                        'value' => 'xxl',
                        'description' => 'extra extra large'
                    ]

                ]
            ],
            [
                'name' => 'Color',
                'type' => 2,
                'features' => [
                    [
                        'value' => '#000000',
                        'description' => 'black'
                    ],
                    [
                        'value' => '#FFFFFF',
                        'description' => 'white'
                    ],
                    [
                        'value' => '#00FF00',
                        'description' => 'green'
                    ],
                    [
                        'value' => '#FF0000',
                        'description' => 'red'
                    ],
                    [
                        'value' => '#0000FF',
                        'description' => 'blue'
                    ],
                    [
                        'value' => '#FFFF00',
                        'description' => 'yellow'
                    ],
                    [
                        'value' => '#FF00FF',
                        'description' => 'magenta'
                    ],
                    [
                        'value' => '#00FFFF',
                        'description' => 'cyan'
                    ],
                    [
                        'value' => '#808080',
                        'description' => 'gray'
                    ]

                ]
            ],
            [
                'name' => 'Sexo',
                'type' => 1,
                'features' => [
                    [
                        'value' => 'masculino',
                        'description' => 'Masculino'
                    ],
                    [
                        'value' => 'femenino',
                        'description' => 'Femenino'
                    ]
                ]
            ]

        ];

        foreach ($options as $option) {
            $optionModel = Option::create([
                'name' => $option['name'],
                'type' => $option['type']
            ]);

            foreach ($option['features'] as $feature) {
                $optionModel->features()->create([

                    'value' => $feature['value'],
                    'description' => $feature['description']
                ]);
            }
        }
    }
}
