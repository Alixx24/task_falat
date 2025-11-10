<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class File extends Model
{
     use HasFactory;

    protected $fillable = ['note_id', 'file_path', 'file_name'];

    
    public function note()
    {
        return $this->belongsTo(Note::class); 
    }
}
