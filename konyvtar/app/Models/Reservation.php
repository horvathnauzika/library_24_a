<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'book_id',
        'start',
        'message'
    ];

    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('user_id', '=', $this->getAttribute('user_id'))
            ->where('book_id', '=', $this->getAttribute('book_id'))
            ->where('start', '=', $this->getAttribute('start'));
        return $query;
    }

    public function books(){
        return $this->belongsTo(Book:: class, 'book_id', 'book_id');
    }

}
