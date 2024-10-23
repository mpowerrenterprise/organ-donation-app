<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{

    use HasFactory;

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    protected $table = 'enrollments'; // Specify the table name if different

    protected $fillable = [
        'student_id',
        'course_id',
        'batch_id',
    ];

    public $timestamps = false; // Disable timestamps

}
