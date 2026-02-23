<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Calculation;
use Illuminate\Http\Request;
use App\Http\Requests\CalculationRequest;
use App\Http\Resources\CalculationResource;

class CalculationController extends Controller
{
    public function create(){
        
        return Inertia::render('Calculator');
    }
    
    public function store(CalculationRequest $request){
        
        $string = $request->string;

        $result = Calculation::calculate($string);

        Calculation::create([
            'user_id' => auth()->user()?->id,
            'calculation' => $string,
            'result' => $result,
        ]);

        return $result;        
    }

    public function history(){
        return CalculationResource::collection(Calculation::where('user_id',auth()->user()->id)->get());
    }

    public function destroy(Calculation $calculation){
        info('in destroy');
        // I don't want to mess with making policies right now, so I'll use this instead
        if($calculation->user_id != auth()->user()->id){
            abort('403');
        }

        $calculation->delete();
        return true;
    }

    public function destroyAll(){
        info('in destroyAll');
        
        Calculation::where('user_id', auth()->user()->id)->delete();

        return true;
    }
}
