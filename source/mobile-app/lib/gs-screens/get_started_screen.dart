import 'package:flutter/material.dart';
import 'gs_request_organs_screen.dart';

class GetStartedScreen extends StatelessWidget {
  const GetStartedScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          crossAxisAlignment: CrossAxisAlignment.center, // Center-aligns children horizontally
          children: [
            // Heading at the top
            Padding(
              padding: const EdgeInsets.only(top: 20.0),
              child: Center( // Centering the text widget
                child: Text(
                  'ORGAN DONATION APP', // Uppercase text
                  style: TextStyle(
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                    color: Colors.orange, // Change color to orange
                    fontFamily: 'Helvetica', // Set font to Helvetica
                  ),
                ),
              ),
            ),
            Center(
              child: Image.asset(
                'assets/images/logo.png', // Ensure image path is correct
                height: 350,
                width: 350,
              ),
            ),
            // Gradient button at the bottom
            Padding(
              padding: const EdgeInsets.only(bottom: 20.0),
              child: Center( // Centering the button
                child: GestureDetector(
                  onTap: () {
                    // Navigate to RequestOrgansScreen
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => const RequestOrgansScreen(),
                      ),
                    );
                  },
                  child: Container(
                    width: 300,
                    height: 50,
                    decoration: BoxDecoration(
                      gradient: LinearGradient(
                        colors: [Colors.red, Colors.orange],
                        begin: Alignment.centerLeft,
                        end: Alignment.centerRight,
                      ),
                      borderRadius: BorderRadius.circular(25),
                    ),
                    alignment: Alignment.center,
                    child: const Text(
                      'Get Started',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
