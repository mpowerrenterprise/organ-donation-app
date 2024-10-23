<?php

namespace App\Http\Controllers;

use DB;
use App\Models\MobileUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class MobileUserController extends Controller
{
    function showMobileUsers(){

        return view("mobile-users");

    }

    public function processMobileUsersAjax(Request $request) {
        // Query to fetch mobile users
        $query = DB::table('mobile_users');
    
        // Search logic
        if ($request->filled('search.value')) {
            $searchValue = $request->input('search.value');
            $query->where(function ($q) use ($searchValue) {
                $q->where('full_name', 'like', $searchValue . '%')
                  ->orWhere('email', 'like', $searchValue . '%')
                  ->orWhere('phone_number', 'like', $searchValue . '%')
                  ->orWhere('blood_type', 'like', $searchValue . '%')
                  ->orWhere('organ', 'like', $searchValue . '%')
                  ->orWhere('status', 'like', $searchValue . '%');
            });
        }
    
        // Get total count before pagination
        $total = $query->count();
    
        // Implement pagination
        $limit = (int) $request->input('length', 10); // Default to 10 if not provided
        $offset = (int) $request->input('start', 0); // Default to 0 if not provided
    
        $users = $query->orderBy('id', 'DESC')
                       ->skip($offset)
                       ->take($limit)
                       ->get();
    
        // Prepare data for DataTables
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'blood_type' => $user->blood_type,
                'organ' => $user->organ,
                'status' => $user->status,
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
    
    
    public function approveUser($id) {
        $user = DB::table('mobile_users')->where('id', $id)->first();
    
        if ($user && $user->status !== 'approved') {
            // Update user status to 'approved'
            DB::table('mobile_users')->where('id', $id)->update(['status' => 'approved']);
            return response()->json(['success' => true, 'message' => 'User approved successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'User is already approved or does not exist.']);
        }
    }

    public function deleteUser($id) {
        $user = MobileUser::find($id);
    
        if ($user) {
            $user->delete();
            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        }
    
        return response()->json(['success' => false, 'message' => 'User not found.']);
    }
    
    
}
