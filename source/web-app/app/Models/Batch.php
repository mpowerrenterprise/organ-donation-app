<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{

    use HasFactory;

    protected $table = 'batches'; // Specify the table name if different

    protected $fillable = [
        'batch_no', 'batch_name', 'start_date', 'end_date', 'course_id', 'status'
    ];

    // Automatically cast these fields to date instances
    protected $dates = ['start_date', 'end_date'];

    // Relationship to Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
  
      // Define the relationship with Enrollments
      public function enrollments()
      {
          return $this->hasMany(Enrollment::class, 'batch_id');
      }

    public $timestamps = false; // Disable timestamps if not needed
}
