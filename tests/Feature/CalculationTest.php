<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Calculation;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\CalculationController;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CalculationTest extends TestCase
{
    public function test_order_of_operations() {
        $this->assertTrue(Calculation::calculate("8+3*4-1") == 19);
        $this->assertTrue(Calculation::calculate("8+3/4-1") == 7.75);
        $this->assertTrue(Calculation::calculate("8-3/4+1") == 8.25);
    }

    public function test_order_of_operations_with_parentheses() {
        $this->assertTrue(Calculation::calculate("(4+5)*(4-3)") == 9);
        $this->assertTrue(Calculation::calculate("(4+5)/(4-1)") == 3);
        $this->assertTrue(Calculation::calculate("(9+3)*4") == 48);
        $this->assertTrue(Calculation::calculate("(9+3)*(4-3)") == 12);
    }

    public function test_exponents_with_parentheses() {    
        $this->assertTrue(Calculation::calculate("(8*8)^2+4*6") == 4120);
        $this->assertTrue(Calculation::calculate("(8*8)^2+4*2") == 4104);
        $this->assertTrue(Calculation::calculate("(8+8)^2+4*2") == 264);
    }

    public function test_nested_and_multiple_squares() {
        $this->assertTrue(Calculation::calculate("((((2+1)^2)^2)^2)+4*2") == 6569);
        $this->assertTrue(Calculation::calculate("(3^2*2)^2+(3^2*2)^2") == 648); 
    }

    public function test_nested_parentheses() {
        $this->assertTrue(Calculation::calculate("((((8+8)^2)))+3*2.5") == 263.5);
    }

    public function test_multiplication_shorthand() {
        $this->assertTrue(Calculation::calculate("6+3(5-2)") == 15);
    }

    public function test_square_roots() {
        $this->assertTrue(Calculation::calculate("√(3+6)+5") == 8);
        $this->assertTrue(Calculation::calculate("7√(9+16)") == 35); // With multiplication shortcut
        $this->assertTrue(Calculation::calculate("2√(9+(4+12))+4-5") == 9); 
        $this->assertTrue(Calculation::calculate("2√(9+(4+12))+(4-6)^2") == 14); 
    }
        
    //$this->assertTrue(Calculation::calculate("") == ); 
    
}
