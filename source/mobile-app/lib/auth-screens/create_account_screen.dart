import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'login_screen.dart'; // Import the LoginScreen
import '../config.dart'; // Import the config file

class CreateAccountScreen extends StatefulWidget {
  const CreateAccountScreen({Key? key}) : super(key: key);

  @override
  _CreateAccountScreenState createState() => _CreateAccountScreenState();
}

class _CreateAccountScreenState extends State<CreateAccountScreen> {
  final _formKey = GlobalKey<FormState>();
  String _errorMessage = ''; // To store the error message

  final backendPoint = Uri.parse('${Config.baseUrl}/register/mobile'); // Use the base URL from Config

  // Define controllers for text input fields
  final TextEditingController _fullNameController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final TextEditingController _phoneController = TextEditingController();
  final TextEditingController _dobController = TextEditingController();
  final TextEditingController _allergiesController = TextEditingController();

  // Dropdown options for Blood Type and Organ
  String? _selectedGender;
  String? _selectedBloodType;
  String? _selectedOrgan;

  final List<String> _genders = ['Male', 'Female'];
  final List<String> _bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
  final List<String> _organs = [
    'Kidney', 'Liver', 'Heart', 'Lung', 'Pancreas', 'Small Intestine', 'Corneas', 'Heart Valves', 'Bone Marrow', 'Skin'
  ];

  Future<void> registerUser() async {
    try {
      final response = await http.post(
        backendPoint, // Use the centralized backendPoint variable
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'full_name': _fullNameController.text,
          'email': _emailController.text,
          'password': _passwordController.text,
          'phone_number': _phoneController.text,
          'gender': _selectedGender,
          'dob': _dobController.text,
          'blood_type': _selectedBloodType,
          'organ': _selectedOrgan,
          'allergies': _allergiesController.text,
        }),
      );

      if (response.statusCode == 201) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Registration successful!')),
        );
        setState(() {
          _errorMessage = ''; // Clear any previous error messages
        });
        // Navigate to LoginScreen
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (context) => const LoginScreen()),
        );
      } else {
        final errorData = jsonDecode(response.body);
        setState(() {
          _errorMessage = errorData['message'] ?? 'Registration failed';
        });
      }
    } catch (error) {
      setState(() {
        _errorMessage = 'Error: $error';
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'Create Account',
          style: TextStyle(color: Colors.white),
        ),
        backgroundColor: Colors.redAccent,
      ),
      body: Padding(
        padding: const EdgeInsets.all(20.0),
        child: Form(
          key: _formKey,
          child: ListView(
            children: [
              // Full Name
              TextFormField(
                controller: _fullNameController,
                decoration: const InputDecoration(labelText: 'Full Name'),
                validator: (value) => value!.isEmpty ? 'Enter full name' : null,
              ),
              const SizedBox(height: 10),
              // Email
              TextFormField(
                controller: _emailController,
                decoration: const InputDecoration(labelText: 'Email'),
                keyboardType: TextInputType.emailAddress,
                validator: (value) => value!.isEmpty ? 'Enter email' : null,
              ),
              const SizedBox(height: 10),
              // Password
              TextFormField(
                controller: _passwordController,
                decoration: const InputDecoration(labelText: 'Password'),
                obscureText: true,
                validator: (value) => value!.isEmpty ? 'Enter password' : null,
              ),
              const SizedBox(height: 10),
              // Phone Number
              TextFormField(
                controller: _phoneController,
                decoration: const InputDecoration(labelText: 'Phone Number'),
                keyboardType: TextInputType.phone,
                validator: (value) => value!.isEmpty ? 'Enter phone number' : null,
              ),
              const SizedBox(height: 10),
              // Gender Dropdown
              DropdownButtonFormField<String>(
                decoration: const InputDecoration(labelText: 'Gender'),
                value: _selectedGender,
                items: _genders.map((gender) {
                  return DropdownMenuItem(
                    value: gender,
                    child: Text(gender),
                  );
                }).toList(),
                onChanged: (value) {
                  setState(() {
                    _selectedGender = value;
                  });
                },
                validator: (value) => value == null ? 'Select gender' : null,
              ),
              const SizedBox(height: 10),
              // Date of Birth
              TextFormField(
                controller: _dobController,
                decoration: const InputDecoration(labelText: 'Date of Birth (YYYY-MM-DD)'),
                keyboardType: TextInputType.datetime,
                validator: (value) => value!.isEmpty ? 'Enter date of birth' : null,
              ),
              const SizedBox(height: 10),
              // Blood Type Dropdown
              DropdownButtonFormField<String>(
                decoration: const InputDecoration(labelText: 'Blood Type'),
                value: _selectedBloodType,
                items: _bloodTypes.map((bloodType) {
                  return DropdownMenuItem(
                    value: bloodType,
                    child: Text(bloodType),
                  );
                }).toList(),
                onChanged: (value) {
                  setState(() {
                    _selectedBloodType = value;
                  });
                },
                validator: (value) => value == null ? 'Select blood type' : null,
              ),
              const SizedBox(height: 10),
              // Organ Dropdown
              DropdownButtonFormField<String>(
                decoration: const InputDecoration(labelText: 'Organ'),
                value: _selectedOrgan,
                items: _organs.map((organ) {
                  return DropdownMenuItem(
                    value: organ,
                    child: Text(organ),
                  );
                }).toList(),
                onChanged: (value) {
                  setState(() {
                    _selectedOrgan = value;
                  });
                },
                validator: (value) => value == null ? 'Select organ' : null,
              ),
              const SizedBox(height: 10),
              // Allergies
              TextFormField(
                controller: _allergiesController,
                decoration: const InputDecoration(labelText: 'Allergies'),
                maxLines: 3,
              ),
              const SizedBox(height: 20),
              // Display Error Message
              if (_errorMessage.isNotEmpty)
                Text(
                  _errorMessage,
                  style: TextStyle(color: Colors.red),
                  textAlign: TextAlign.center,
                ),
              const SizedBox(height: 10),
              // Submit Button
              ElevatedButton(
                onPressed: () {
                  if (_formKey.currentState!.validate()) {
                    registerUser();
                  }
                },
                style: ElevatedButton.styleFrom(
                  padding: const EdgeInsets.symmetric(vertical: 15),
                  backgroundColor: Colors.redAccent,
                ),
                child: const Text(
                  'Create Account',
                  style: TextStyle(color: Colors.white, fontSize: 18),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
