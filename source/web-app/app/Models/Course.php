<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{

    use HasFactory;

    protected $table = 'courses'; // Specify the table name if it's different from the pluralized model name

    protected $fillable = [
        'id',
        'course_name',
        'course_description',
        'duration',
        'fee',
    ];

    public $timestamps = false; // Disable timestamps if not needed

}
