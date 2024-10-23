<?php

namespace App\Http\Controllers;

use App\Models\Organ;
use App\Models\MobileUser;
use App\Models\OrganRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class DashboardController extends Controller
{
    public function showDashboard()
    {
        // Fetch the data
        $totalMobileUsers = MobileUser::count(); // Count total mobile users
        $totalOrgans = Organ::count(); // Count total organs
        $totalRequestedOrgans = OrganRequest::count(); // Count total requested organs
        $totalDonatedOrgans = OrganRequest::where('status', 'approved')->count(); // Count total donated organs (accepted requests)
        
        // Fetch the latest 10 organ requests
        $recentRequests = OrganRequest::with('organ', 'user')->orderBy('id', 'desc')->take(10)->get(); // Get the 10 latest organ requests
        
        // Define organs and their corresponding emojis
        $organEmojis = [
            'Kidney' => '🟢',         // Keeping it as green to represent the kidney
            'Liver' => '🫀',          // Yellow color to signify liver (healthy)
            'Heart' => '❤️',          // Classic red heart emoji
            'Lung' => '🫁',           // Lung emoji
            'Pancreas' => '🟦',       // Blue to represent the pancreas
            'Small Intestine' => '🔴', // Red could signify a critical organ
            'Corneas' => '👁️',        // Eye emoji to represent corneas
            'Heart Valves' => '💙',    // Blue heart for heart valves
            'Bone Marrow' => '🦴',     // Bone emoji for bone marrow
            'Skin' => '🧑'            // Human emoji for skin
        ];
        

        // Return the dashboard view with the data
        return view('dashboard', [
            'totalMobileUsers' => $totalMobileUsers,
            'totalOrgans' => $totalOrgans,
            'totalRequestedOrgans' => $totalRequestedOrgans,
            'totalDonatedOrgans' => $totalDonatedOrgans,
            'recentRequests' => $recentRequests, 
            'organEmojis' => $organEmojis // Pass organ emojis to the view
        ]);
    }

    
}
