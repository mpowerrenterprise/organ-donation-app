<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CourseController extends Controller
{
    function showCourses(){

        return view("courses");
        
    }

    public function addCourse(Request $request)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'course_name' => 'required|string|max:255',
            'course_description' => 'nullable|string',
            'duration' => 'required|string|max:100',
            'fee' => 'required|numeric|min:0',
        ]);
    
        // Create a new course record
        $course = new Course();
        $course->course_name = $request->input('course_name');
        $course->course_description = $request->input('course_description');
        $course->duration = $request->input('duration');
        $course->fee = $request->input('fee');
    
        // Save the course to the database
        $course->save();
    
        // Redirect to the view.course route and send the course ID
        return redirect()->route('view.course', ['id' => $course->id])->with('success', 'Course added successfully.');
    }

    public function processCoursesAjax(Request $request) {
        // Query to fetch course data
        $query = Course::query();
    
        // Search logic (if necessary)
        if ($request->filled('search.value')) {
            $searchValue = $request->input('search.value');
            $query->where(function ($q) use ($searchValue) {
                $q->where('course_name', 'like', $searchValue . '%')
                  ->orWhere('course_description', 'like', $searchValue . '%')
                  ->orWhere('duration', 'like', $searchValue . '%')
                  ->orWhere('fee', 'like', $searchValue . '%');
            });
        }
    
        // Get total count before pagination
        $total = $query->count();
    
        // Implement pagination
        $limit = (int) $request->input('length', 10); // Default to 10 if not provided
        $offset = (int) $request->input('start', 0); // Default to 0 if not provided
    
        $courses = $query->orderBy('id', 'DESC')
                         ->skip($offset)
                         ->take($limit)
                         ->get();
    
        // Prepare data for DataTables
        $data = [];
        foreach ($courses as $course) {
            $data[] = [
                'id' => $course->id,
                'course_id' => $course->id,
                'course_name' => $course->course_name,
                'course_description' => $course->course_description,
                'duration' => $course->duration,
                'fee' => $course->fee,
            ];
        }
    
        // Prepare response for DataTables
        $response = [
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ];
    
        return response()->json($response);
    }
    
    

    public function viewCourse($id){

        $course = Course::find($id);
        return view('course-view', ['course' => $course]);

    }

    public function editCourse(Request $request, $data)
    {
        // Decode the URL-encoded data
        $dataArray = json_decode(urldecode($data), true);
        $courseId = $dataArray['id'];
    
        // Validate incoming request
        $validatedData = $request->validate([
            'course_name' => 'required|string|max:255',
            'course_description' => 'nullable|string|max:2000',
            'duration' => 'required|string|max:100',
            'fee' => 'required|numeric|min:0',
        ]);
    
        try {
            // Update course record in the database based on $courseId
            $course = Course::findOrFail($courseId);
            $course->course_name = $validatedData['course_name'];
            $course->course_description = $validatedData['course_description'];
            $course->duration = $validatedData['duration'];
            $course->fee = $validatedData['fee'];
            $course->save();
    
            // Redirect or return response as needed
            return redirect()->back()->with('success', 'Course details updated successfully.');
        } catch (Exception $e) {
            // Log the error
            Log::error('Error updating course: ' . $e->getMessage());
            // Redirect with error message
            return redirect()->back()->with('error', 'Failed to update course details. Please try again.');
        }
    }
    

    public function deleteCourse($id)
    {

        // Find the course by ID
        $course = Course::find($id);
    
        // Check if the course exists
        if (!$course) {
            return redirect()->route('show.courses')->with('error', 'Course not found.');
        }
    
        // Check if the course has any associated batches
        $hasBatches = Batch::where('course_id', $id)->exists();
    
        // If the course has batches, do not delete the course
        if ($hasBatches) {
            return redirect()->route('view.course', ['id' => $id])
                ->with('error', 'This course cannot be deleted as it has associated batches.');
        }
    
        // Delete the course
        $course->delete();
    
        // Redirect with a success message
        return redirect()->route('show.courses')->with('success', 'Course deleted successfully.');
    }
    
}
