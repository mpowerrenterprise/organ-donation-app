<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class BatchController extends Controller
{
    public function showBatches()
    {
        // Fetch all available courses to display in the dropdown
        $courses = Course::all();
    
        // Fetch all available students to display in the multi-select field
        $students = Student::all();


        // Return the batches view with the courses, students, and generated batch number
        return view('batches', compact('courses', 'students'));
    }


    public function addBatch(Request $request)
    {
        // Validate the form data
        $request->validate([
            'batch_name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'students' => 'required|array',
            'students.*' => 'exists:students,id', // Ensure all selected students exist in the database
        ]);
    
        // Start a database transaction
        DB::beginTransaction();
        
        try {
            // Find the current max batch_no for the selected course
            $courseId = $request->input('course_id');
            $maxBatchNo = Batch::where('course_id', $courseId)->max('batch_no');
    
            // If no batches exist for this course, start from 1, otherwise increment
            $batchNo = $maxBatchNo ? $maxBatchNo + 1 : 1;
    
            // Create a new batch
            $batch = new Batch();
            $batch->batch_no = $batchNo;
            $batch->batch_name = $request->input('batch_name');
            $batch->course_id = $courseId;
            $batch->start_date = $request->input('start_date');
            $batch->end_date = $request->input('end_date');
            $batch->status = 'incomplete'; // Set the initial status as 'incomplete'
            $batch->save();
    
            // Attach students to the batch
            $students = $request->input('students');
            
            // Insert enrollments for each student into the enrollments table
            foreach ($students as $studentId) {
                Enrollment::create([
                    'student_id' => $studentId,
                    'course_id' => $courseId,
                    'batch_id' => $batch->id,
                ]);
            }
    
            // Commit the transaction
            DB::commit();
    
           // Redirect or respond with success, passing the batch ID as a parameter
           return redirect()->route('view.batch', ['id' => $batch->id])->with('success', 'Batch and enrollments added successfully!');
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add the batch and enrollments. Please try again.');
        }
    }


    public function editBatch(Request $request, $id)
    {
        // Validate the form data
        $request->validate([
            'batch_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:incomplete,completed', // Assuming status can be 'incomplete' or 'completed'
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Find the batch by ID
            $batch = Batch::findOrFail($id);

            // Update the batch details
            $batch->batch_name = $request->input('batch_name');
            $batch->start_date = $request->input('start_date');
            $batch->end_date = $request->input('end_date');
            $batch->status = $request->input('status');
            
            // Save the updated batch information
            $batch->save();

            // Commit the transaction
            DB::commit();

            // Redirect or respond with success
            return redirect()->route('view.batch', ['id' => $batch->id])->with('success', 'Batch details updated successfully!');
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update the batch. Please try again.');
        }
    }
    

    public function viewBatch($id)
    {
        // Fetch the batch with its course and enrolled students
        $batch = Batch::with('course', 'enrollments.student')->findOrFail($id);
    
        // Get the list of students enrolled in the batch
        $enrolledStudentIds = $batch->enrollments->pluck('student_id')->toArray();
    
        // Fetch all students who are NOT enrolled in this batch
        $nonEnrolledStudents = Student::whereNotIn('id', $enrolledStudentIds)->get();
    
        // Return the view with the batch, enrolled students, and non-enrolled students data
        return view('batch-view', [
            'batch' => $batch,
            'enrolledStudents' => $batch->enrollments->pluck('student'), // Pass the enrolled students
            'nonEnrolledStudents' => $nonEnrolledStudents, // Pass the non-enrolled students
        ]);
    }
    


    public function removeStudentFromBatch($batch_id, $student_id)
    {
        // Find the enrollment record for the student in the batch
        $enrollment = Enrollment::where('batch_id', $batch_id)
                                ->where('student_id', $student_id)
                                ->first();

        // If the enrollment exists, delete it
        if ($enrollment) {
            $enrollment->delete();
            return redirect()->back()->with('success', 'Student removed from batch successfully.');
        }

        return redirect()->back()->with('error', 'Unable to remove student. Please try again.');
    }


    public function addStudentsToBatch(Request $request, $batch_id)
    {
        // Validate the form data
        $request->validate([
            'students' => 'required|array',
            'students.*' => 'exists:students,id', // Ensure that all selected students exist in the database
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Find the batch by ID
            $batch = Batch::findOrFail($batch_id);

            // Get the course ID from the batch
            $course_id = $batch->course_id;

            // Get the selected students from the form
            $students = $request->input('students');

            // Insert enrollments for each student into the enrollments table
            foreach ($students as $student_id) {
                Enrollment::create([
                    'student_id' => $student_id,
                    'course_id' => $course_id,
                    'batch_id' => $batch_id,
                ]);
            }

            // Commit the transaction
            DB::commit();

            // Redirect back to the batch view with a success message
            return redirect()->route('view.batch', ['id' => $batch_id])->with('success', 'Students added to the batch successfully!');
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add students to the batch. Please try again.');
        }
    }


    

    public function processBatchesAjax(Request $request)
    {
        // Initialize the query with batches, courses, and count of enrollments
        $query = Batch::with('course')
                      ->withCount('enrollments'); // This will add an 'enrollments_count' field to each batch
    
        // Check the status filter and apply it if not "All"
        $statusFilter = $request->input('statusFilter', ''); // Get the filter value or default to empty
    
        // Apply the status filter only if "Incomplete" or "Completed" is selected
        if ($statusFilter === 'incomplete' || $statusFilter === 'completed') {
            $query->where('status', $statusFilter);
        }
    
        // Implement search functionality (if search input is provided)
        if ($request->filled('search.value')) {
            $searchValue = $request->input('search.value');
            $query->where(function ($q) use ($searchValue) {
                $q->where('batch_no', 'like', "%$searchValue%")
                  ->orWhere('batch_name', 'like', "%$searchValue%")
                  ->orWhereHas('course', function ($q2) use ($searchValue) {
                      $q2->where('course_name', 'like', "%$searchValue%");
                  });
            });
        }
    
        // Get total count of records before pagination
        $totalRecords = $query->count();
    
        // Implement pagination
        $limit = (int) $request->input('length', 10);  // Default to 10 if not provided
        $offset = (int) $request->input('start', 0);   // Default to 0 if not provided
    
        // Get the paginated data
        $batches = $query->orderBy('id', 'desc')
                         ->skip($offset)    // Apply the offset
                         ->take($limit)     // Apply the limit
                         ->get();
    
        // Prepare data for DataTables
        $data = $batches->map(function ($batch) {
            return [
                'id' => $batch->id,
                'batch_no' => $batch->batch_no,
                'batch_name' => $batch->batch_name,
                'course_name' => $batch->course->course_name,
                'total_students' => $batch->enrollments_count,  // Use the enrollments_count for total students
                'start_date' => $batch->start_date ? date('Y-m-d', strtotime($batch->start_date)) : null,
                'end_date' => $batch->end_date ? date('Y-m-d', strtotime($batch->end_date)) : null,
                'status' => ucfirst($batch->status)
            ];
        });
    
        // Return DataTables response
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,  // Adjust if needed for more advanced searches
            'data' => $data
        ]);
    }
    
    public function deleteBatch($id)
    {
        // Start a database transaction to ensure data integrity
        DB::beginTransaction();
    
        try {
            // Find the batch by its ID
            $batch = Batch::findOrFail($id);
            
            // Get the batch name before deleting
            $batchName = $batch->batch_name;
    
            // Delete the related enrollments first (cascade effect)
            Enrollment::where('batch_id', $batch->id)->delete();
    
            // Now delete the batch itself
            $batch->delete();
    
            // Commit the transaction
            DB::commit();
    
            // Redirect or return a success response with the batch name in the message
            return redirect()->route('show.batches')->with('success', "Batch '$batchName' and related enrollments deleted successfully.");
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();
    
            // Redirect back with an error message
            return redirect()->back()->with('error', 'Failed to delete the batch. Please try again.');
        }
    }
    
    
}
