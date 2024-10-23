<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileUser extends Model
{
    use HasFactory;

    // Define the fillable fields
    protected $fillable = [
        'full_name',
        'username',
        'email',
        'password',
        'phone_number',
        'gender',
        'dob',
        'blood_type',
        'organ',
        'allergies',
        'status',
    ];

    // Optionally, define any relationships or methods here if needed
}
