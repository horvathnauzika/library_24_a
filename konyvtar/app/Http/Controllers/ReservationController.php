<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index() // könyvtáros + admin
    {
        return Reservation::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // könyvtáros + admin
    {
        $record = new Reservation();
        $record->fill($request->all());
        $record->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $user_id, string $book_id, string $start) // könyvtáros + admin
    {
        $reservation = Reservation::where('user_id', $user_id)
        ->where('book_id', $book_id)
        ->where('start', $start)
        //listát ad vissza:
        ->get();
        return $reservation[0];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $user_id, string $book_id, string $start)
    {
        $user =  $this->show($user_id, $book_id, $start);
        $user->fill($request->all());
        $user->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $user_id, string $book_id, string $start)
    {
        Reservation::show($user_id, $book_id, $start)->delete();
    }

    // spec lekérdezések
    public function reservedBooks(){
        $user=Auth::user();
        return Reservation::with('books')
        ->where('user_id', $user->id)
        ->get();
    }


    // Hány db előjegyzés van a bejelentkezett felhasználónak?
    public function reservedCount(){
        $user = Auth::user();
        $pieces = DB::table('reservations')
        ->where('user_id', $user->id)
        ->count();
        return $pieces;
    }
}