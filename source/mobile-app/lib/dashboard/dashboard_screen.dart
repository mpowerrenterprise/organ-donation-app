import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../main.dart';  // Import main to go back to the root of the app

class DashboardScreen extends StatelessWidget {
  const DashboardScreen({Key? key}) : super(key: key);

  // Initialize secure storage
  final _storage = const FlutterSecureStorage();

  // Function to log out the user
  Future<void> _logout(BuildContext context) async {
    // Clear the login state
    await _storage.write(key: 'isLoggedIn', value: 'false');
    // Navigate back to the main app entry point
    Navigator.pushAndRemoveUntil(
      context,
      MaterialPageRoute(builder: (context) => const MyApp()),  // Return to main.dart
          (route) => false,
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'Dashboard',
          style: TextStyle(color: Colors.white),
        ),
        backgroundColor: Colors.redAccent,
        actions: [
          IconButton(
            icon: const Icon(Icons.logout, color: Colors.white),
            onPressed: () => _logout(context),
          ),
        ],
      ),
      body: Center(
        child: Text(
          'Welcome',
          style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Colors.black87),
        ),
      ),
    );
  }
}
