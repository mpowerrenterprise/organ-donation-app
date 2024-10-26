import 'package:flutter/material.dart';
import 'package:organ_client_app/gs-screens/get_started_screen.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Organ Donation App',
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.deepPurple),
        useMaterial3: true,
      ),
      home: const GetStartedScreen(),  // Updated to GetStartedScreen
    );
  }
}
