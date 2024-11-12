<?php

namespace App\Http\Controllers;


use App\Models\Lending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LendingController extends Controller
{
    public function index()
    {
        return Lending::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $record = new Lending();
        $record->fill($request->all());
        $record->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $user_id, $copy_id, $start)
    {
        $lending = Lending::where('user_id', $user_id)
        ->where('copy_id', $copy_id)
        ->where('start', $start)
        //listát ad vissza:
        ->get();
        return $lending[0];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user_id, $copy_id, $start)
    {
        $record = $this->show($user_id, $copy_id, $start);
        $record->fill($request->all());
        $record->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id, $copy_id, $start)
    {
        $this->show($user_id, $copy_id, $start)->delete();
    }

    // egyéb lekérdezések
    public function lendingsWithCopies(){
        $user = Auth::user();	//bejelentkezett felhasználó
        return Lending::with('copies') // copies itt a függvény neve
        ->where('user_id','=',$user->id)
        ->get(); 
    }

    public function dateSpecific(){
        return Lending::with('specificDate') 
        ->where('start','=',"2018-09-06")
        ->get(); 
    }

    public function copySpecific($copy_id){
        return Lending::with('copies') 
        ->where('copy_id','=',$copy_id)
        ->get(); 
    }

    // hány kölcsönzés / felhasználó
    public function lendingCount(){
        $user = Auth::user();
        $lendings = DB::table('lendings as l')
        ->where('user_id', $user->id)
        ->count();
        return $lendings;
    }

    // aktív k9lcs9nzések
    public function activelendingCount(){
        $user = Auth::user();
        $lendings = DB::table('lendings as l')
        ->where('user_id', $user->id)
        ->whereNull('end')
        ->count();
        return $lendings;
    }

    // kölcsönzött könyvek száma
    public function lendingsBooksCount(){
        $user = Auth::user();
        $books = DB::table('lendings as l')
        ->join('copies as c', 'l.copy_id', 'c.copy_id')
        ->where('user_id', $user->id)
        ->distinct('book_id')
        ->count();
        return $books;
    }

    // kölcsönzött könyvek adatai
    public function lendingsBooksData(){
        $user = Auth::user();
        $books = DB::table('lendings as l')
        ->join('copies as c', 'l.copy_id', 'c.copy_id')
        ->join('books as b', 'c.book_id', 'b.book_id')
        ->select('b.book_id', 'author', 'title')
        ->where('user_id', $user->id)
        ->groupBy('b.book_id')
        ->get();
        return $books;
    }

}
