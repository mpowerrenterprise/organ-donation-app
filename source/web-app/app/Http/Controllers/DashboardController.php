<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class DashboardController extends Controller
{
    public function showDashboard()
    {
        // Fetch the data
        $totalStudents = Student::count(); // Count total students
        $totalCourses = Course::count();   // Count total courses
        $totalBatches = Batch::count();    // Count total batches
        $activeBatches = Batch::where('status', 'incomplete')->count(); // Count active batches (incomplete)
    
        // Fetch the latest 10 students based on their ID
        $recentStudents = Student::orderBy('id', 'desc')->take(10)->get(); // Get the 10 latest students

        // Return the dashboard view with the data
        return view('dashboard', [
            'totalStudents' => $totalStudents,
            'totalCourses' => $totalCourses,
            'totalBatches' => $totalBatches,
            'activeBatches' => $activeBatches,
            'recentStudents' => $recentStudents, 
        ]);
    }
    
}
