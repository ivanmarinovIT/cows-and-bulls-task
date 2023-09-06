<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Game;
use App\Models\User;

class GameController extends Controller
{
    public function index()
    {

        if (!session()->has('number')) {
            session(['number' => $this->generateNumber()]);
            session(['tries' => 1]);
        }

        $number = session('number');
        $tries = session('tries');

        return view('dashboard', compact('number', 'tries'));
    }

    protected function generateNumber() 
    {
        if (empty(session('number'))) {
            $digits = range(0, 9);
            shuffle($digits);
            $digits = array_slice($digits, 0, 4);

            $hasOneAndEight = in_array(1, $digits) && in_array(8, $digits);
            $hasFourOrFive = in_array(4, $digits) || in_array(5, $digits);

            if ($hasOneAndEight) {
                $indicesOne = array_keys($digits, 1);
                $indicesEight = array_keys($digits, 8);

                $positionOne = min($indicesOne);
                $positionEight = min($indicesEight);

                if (abs($positionOne - $positionEight) != 1) {
                    $temp = $digits[$positionOne];
                    $digits[$positionOne] = $digits[$positionOne + 1];
                    $digits[$positionOne + 1] = $temp;
                }
            }
            
            if ($hasFourOrFive) {
                $nonEvenIndexes = [0, 2];
                $fourIndex = array_search(4, $digits);
                $fiveIndex = array_search(5, $digits);
        
                if (in_array($fourIndex, $nonEvenIndexes)) {
                    $evenIndex = (isset($fiveIndex) && $fiveIndex == 1) ? 3 : 1;
                    $temp = $digits[$fourIndex];
                    $digits[$fourIndex] = $digits[$evenIndex];
                    $digits[$evenIndex] = $temp;
                }
        
                if (in_array($fiveIndex, $nonEvenIndexes)) {
                    $evenIndex = (isset($fourIndex) && $fourIndex == 1) ? 3 : 1;
                    $temp = $digits[$fiveIndex];
                    $digits[$fiveIndex] = $digits[$evenIndex];
                    $digits[$evenIndex] = $temp;
                }
            }
        
            return implode('', $digits);
        } else {
            return session('number');
        }
    }

    protected function evaluateGuess($guess, $number)
    {
        $bulls = 0;
        $cows = 0;


        for ($i = 0; $i < 4; $i++) {
            if ($guess[$i] === $number[$i]) {
                $bulls++;
            } elseif (strpos($number, $guess[$i]) !== false) {
                $cows++;
            }
        }

        return ['bulls' => $bulls, 'cows' => $cows, 'success' => $bulls == 4];
    }

    public function makeGuess(Request $request)
    {
        $guess = $request->input('guess');
        $number = session('number');
        $tries = session('tries');

        if (!empty($guess)) {
            $result = $this->evaluateGuess($guess, $number);
    
            if ($result['success']) {
                $game = new Game;
                $game->name = Auth::user()->name;
                $game->number = $number;
                $game->tries = $tries;
                
                if (!$game->save()) {
                    $this->tryAgain();
                }
    
                $tries = 1;
                session()->forget('number');
            } else {
                $tries++;
            }

            session(['tries' => $tries]);

            return redirect('dashboard')
                ->with('result', $result)
                ->with('guess', $guess);
        }

        return redirect('dashboard');
    }

    public function tryAgain()
    {
        session()->forget('number');
        session(['tries' => 1]);

        return redirect('dashboard');
    }

    public function rankings()
    {
        $rankings = Game::orderBy('tries', 'asc')->limit(10)->get();
        return view('rankings')->with('rankings', $rankings);
    }
}
