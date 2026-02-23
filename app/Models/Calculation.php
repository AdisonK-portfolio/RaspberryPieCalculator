<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calculation extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public static function calculate($string){
        info($string);

        /* If the string is just a number, return the number */
        $numberRegex = '(\-?[0-9]+|[0-9]+\.[0-9]+)';
        if(preg_match('/^' . $numberRegex . '$/', $string, $match)){
            info('found number: ' . $match[1]);
            return $string;
        }
        
        /* Find all of the sets of parentheses and resolve them first */
        preg_match_all('/\(([^\)\(]+)\)/', $string, $matches, PREG_OFFSET_CAPTURE);
        // have to use $matches[0] (not $matches[1]), so that we have the proper locations to take the ()s out of the full string
        while(count($matches[0]) > 0){
            info('parentheses');
            info($matches[0]);
            
            /* For each set of (), take out the contents and resolve it (recursively) */
            foreach(collect($matches[0])->reverse() as $match){
                $cutout = substr($match[0], 1, -1); // removes the ()s
                $startLoc = $match[1];
                $endLoc = $match[1] + strlen($match[0]);
                
                info($cutout . " at: " . $startLoc . " to " . $endLoc);
                info('substrings: ' . substr($string, 0, $startLoc) . "___" . substr($string, $endLoc));

                /* Multiplication shorthand: adding * if there was a number immediately before or after ()s */
                $beforeStr = substr($string, 0, $startLoc);
                if(preg_match('/[0-9]/', substr($beforeStr, -1))){
                    $beforeStr = $beforeStr . "*";
                    info('before string: ' . $beforeStr);
                }

                $afterStr = substr($string, $endLoc);
                if(preg_match('/[0-9]/', substr($afterStr, 0, 1))){
                    $afterStr = "*" . $afterStr;
                    info('after string: ' . $afterStr);
                }
                $string = $beforeStr . Calculation::calculate($cutout) . $afterStr;
            }

            preg_match_all('/\(([^\)\(]+)\)/', $string, $matches, PREG_OFFSET_CAPTURE);
        }
        info('ending string: ' . $string);
        
        /* Squared */
        if(preg_match('/^' . $numberRegex . '\^2$/', $string, $match)){
            info($match[1] . ' squared');
            $subresult = Calculation::calculate($match[1]);
            return $subresult * $subresult;

        /* Square root with multiplication shortcut */
        }else if(preg_match('/^'. $numberRegex . '\√' . $numberRegex . '$/', $string, $match)){
            info(' square root of ' . $match[2] . ' times ' . $match[1]);
            $subresult = Calculation::calculate($match[2]);
            return $match[1] * sqrt($subresult);

        /* Square root */
        }else if(preg_match('/^\√' . $numberRegex . '$/', $string, $match)){
            info($match[1] . ' square root');
            $subresult = Calculation::calculate($match[1]);
            return sqrt($subresult);

        /* Addition */
        }else if(preg_match('/^(.*)\+(.*)$/', $string, $match)){
            info($match[1] . ' add to ' . $match[2]);
            return Calculation::calculate($match[1]) + Calculation::calculate($match[2]);

        /* Subtraction */
        }else if(preg_match('/^(.*)\-(.*)$/', $string, $match)){
            info($match[1] . ' subtract by ' . $match[2]);
            return Calculation::calculate($match[1]) - Calculation::calculate($match[2]);
        
        /* Multiplication */
        }else if(preg_match('/^(.*)\*(.*)$/', $string, $match)){
            info($match[1] . ' multiply by ' . $match[2]);
            return Calculation::calculate($match[1]) * Calculation::calculate($match[2]);
        
        /* Division */
        }else if(preg_match('/^(.*)\/(.*)$/', $string, $match)){
            info($match[1]. ' divided by ' . $match[2]);
            return Calculation::calculate($match[1]) / Calculation::calculate($match[2]);
        }
        
        abort('422', "Oops, looks like there's a problem with your equation.");
    }
}
