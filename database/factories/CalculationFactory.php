<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Calculation;
use Illuminate\Database\Eloquent\Factories\Factory;

class CalculationFactory extends Factory
{
    public function definition(): array
    {
        return [
            //
        ];
    }

    public static function createCalculations(){
        $userId = User::first()->id;
        
        Calculation::create([
            'calculation' => '6+3(5-2)',
            'result' => '15',
            'user_id' => $userId,
        ]);

        Calculation::create([
            'calculation' => '((((8+8)^2)))+3*2.5',
            'result' => '263.5',
            'user_id' => $userId,
        ]);

        Calculation::create([
            'calculation' => '(3^2*2)^2+(3^2*2)^2',
            'result' => '648',
            'user_id' => $userId,
        ]);

        Calculation::create([
            'calculation' => '√(3+6)+5',
            'result' => '8',
            'user_id' => $userId,
        ]);
    }
}
