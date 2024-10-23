<?php

namespace App\Http\Controllers;

use App\Models\Organ;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


class OrganController extends Controller
{
    function showOrgans(){

        return view("organ");
        
    }

    function showRequestedOrgans(){

        
    }

    public function processOrgansAjax(Request $request) {
        // Query to fetch organ data
        $query = Organ::query();
    
        // Search logic
        if ($request->filled('search.value')) {
            $searchValue = $request->input('search.value');
            $query->where(function ($q) use ($searchValue) {
                $q->where('organ_name', 'like', $searchValue . '%')
                  ->orWhere('donor_name', 'like', $searchValue . '%')
                  ->orWhere('blood_type', 'like', $searchValue . '%')
                  ->orWhere('organ_condition', 'like', $searchValue . '%')
                  ->orWhere('organ_type', 'like', $searchValue . '%')
                  ->orWhere('donor_age', 'like', $searchValue . '%')
                  ->orWhere('donor_gender', 'like', $searchValue . '%');
            });
        }
    
        // Get total count before pagination
        $total = $query->count();
    
        // Implement pagination
        $limit = (int) $request->input('length', 10); // Default to 10 if not provided
        $offset = (int) $request->input('start', 0); // Default to 0 if not provided
    
        $organs = $query->orderBy('id', 'DESC')
                        ->skip($offset)
                        ->take($limit)
                        ->get();
    
        // Prepare data for DataTables
        $data = [];
        foreach ($organs as $organ) {
            $data[] = [
                'id' => $organ->id,
                'organ_name' => $organ->organ_name,
                'blood_type' => $organ->blood_type,
                'donor_name' => $organ->donor_name,
                'donor_age' => $organ->donor_age,  // Added donor_age
                'donor_gender' => $organ->donor_gender, // Added donor_gender
                'organ_type' => $organ->organ_type,
                'organ_condition' => $organ->organ_condition,
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
    
    
    public function addOrgan(Request $request) {

        // Validate incoming request
        $validatedData = $request->validate([
            'organ_name' => 'required|string|max:255',
            'blood_type' => 'required|string|max:3',
            'donor_name' => 'required|string|max:255',
            'donor_age' => 'required|integer|min:0',
            'donor_gender' => 'required|in:male,female',
            'organ_type' => 'required|string|max:255',
            'organ_condition' => 'required|string|in:Fresh,Stored,Preserved',
        ]);
    
        // Create a new organ record
        $organ = new Organ();
        $organ->organ_name = $request->input('organ_name');
        $organ->blood_type = $request->input('blood_type');
        $organ->donor_name = $request->input('donor_name');
        $organ->donor_age = $request->input('donor_age');
        $organ->donor_gender = $request->input('donor_gender');
        $organ->organ_type = $request->input('organ_type');
        $organ->organ_condition = $request->input('organ_condition');
    
        // Save the organ record
        $organ->save();
    
        // Optionally, return a response or redirect as needed
        return redirect()->route('show.organs', ['id' => $organ->id])->with('success', 'Organ added successfully.');
    }
    

    public function deleteOrgan($id) {
        $organ = Organ::find($id);
        
        if ($organ) {
            $organ->delete();
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Organ not found']);
    }
    
}
