import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../config.dart';
import 'organ_view.dart';
import 'profile_info.dart';
import 'change_password.dart';
import '../main.dart'; // For navigation after logout
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'message_sent_screen.dart';

const Color customOrange = Color(0xFFFF4444); // Your branding color

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({Key? key}) : super(key: key);

  @override
  _DashboardScreenState createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  final _storage = const FlutterSecureStorage();
  int _selectedIndex = 0;

  static final List<Widget> _screens = [
    OrganTab(),
    MyMatchScreen(),
    MyRequestsScreen(),
    MessageScreen(),
  ];

  static const List<String> _screenTitles = [
    'Organs',
    'My Match',
    'My Requests',
    'Messages',
  ];

  Future<void> _logout(BuildContext context) async {
    await _storage.deleteAll();
    Navigator.pushAndRemoveUntil(
      context,
      MaterialPageRoute(builder: (context) => const MyApp()),
          (route) => false,
    );
  }

  void _handleMenuSelection(String choice) {
    switch (choice) {
      case 'Profile Info':
        Navigator.push(context, MaterialPageRoute(builder: (context) => const ProfileInfoScreen()));
        break;
      case 'Change Password':
        Navigator.push(context, MaterialPageRoute(builder: (context) => const ChangePasswordScreen()));
        break;
      case 'Logout':
        _logout(context);
        break;
    }
  }

  void _onTabSelected(int index) {
    setState(() {
      _selectedIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(
          _screenTitles[_selectedIndex],
          style: const TextStyle(color: Colors.white),
        ),
        backgroundColor: customOrange,
        automaticallyImplyLeading: false,
        actions: [
          PopupMenuButton<String>(
            onSelected: _handleMenuSelection,
            icon: const Icon(Icons.person, color: Colors.white),
            itemBuilder: (BuildContext context) => <PopupMenuEntry<String>>[
              const PopupMenuItem<String>(value: 'Profile Info', child: Text('Profile Info')),
              const PopupMenuItem<String>(value: 'Change Password', child: Text('Change Password')),
              const PopupMenuItem<String>(value: 'Logout', child: Text('Logout')),
            ],
          ),
        ],
      ),
      body: _screens[_selectedIndex],
      bottomNavigationBar: BottomNavigationBar(
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.home), label: 'Organs'),
          BottomNavigationBarItem(icon: Icon(Icons.favorite), label: 'My Match'),
          BottomNavigationBarItem(icon: Icon(Icons.list), label: 'My Requests'),
          BottomNavigationBarItem(icon: Icon(Icons.message), label: 'Messages'),
        ],
        currentIndex: _selectedIndex,
        selectedItemColor: Colors.white,
        unselectedItemColor: Colors.white70,
        backgroundColor: customOrange,
        onTap: _onTabSelected,
        type: BottomNavigationBarType.fixed,
      ),
    );
  }
}

// OrganTab widget defined within the same file
class OrganTab extends StatefulWidget {
  const OrganTab({Key? key}) : super(key: key);

  @override
  _OrganTabState createState() => _OrganTabState();
}

class _OrganTabState extends State<OrganTab> {
  List<Map<String, dynamic>> _organs = [];
  List<Map<String, dynamic>> _filteredOrgans = [];
  String _selectedOrgan = '';
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchOrgans();
  }

  Future<void> _fetchOrgans() async {
    final url = '${Config.baseUrl}/organs/mobile';
    try {
      final response = await http.get(Uri.parse(url));
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        setState(() {
          _organs = List<Map<String, dynamic>>.from(data);
          _filteredOrgans = _organs;
          _isLoading = false;
        });
      } else {
        throw Exception('Failed to load organs');
      }
    } catch (error) {
      setState(() {
        _isLoading = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: $error')),
      );
    }
  }

  void _filterOrgans() {
    setState(() {
      if (_selectedOrgan.isEmpty || _selectedOrgan == 'all') {
        _filteredOrgans = _organs;
      } else {
        _filteredOrgans = _organs.where((organ) => organ['organ_name'] == _selectedOrgan).toList();
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return _isLoading
        ? const Center(child: CircularProgressIndicator())
        : Column(
      children: [
        Padding(
          padding: const EdgeInsets.all(8.0),
          child: DropdownButtonFormField<String>(
            value: _selectedOrgan.isEmpty ? null : _selectedOrgan,
            decoration: InputDecoration(
              labelText: 'Select Organ',
              border: OutlineInputBorder(borderRadius: BorderRadius.circular(8.0)),
            ),
            items: [
              const DropdownMenuItem<String>(value: 'all', child: Text('All')),
              const DropdownMenuItem<String>(value: 'Kidney', child: Text('Kidney')),
              const DropdownMenuItem<String>(value: 'Liver', child: Text('Liver')),
              const DropdownMenuItem<String>(value: 'Heart', child: Text('Heart')),
              const DropdownMenuItem<String>(value: 'Lung', child: Text('Lung')),
              const DropdownMenuItem<String>(value: 'Pancreas', child: Text('Pancreas')),
              const DropdownMenuItem<String>(value: 'Small Intestine', child: Text('Small Intestine')),
              const DropdownMenuItem<String>(value: 'Corneas', child: Text('Corneas')),
              const DropdownMenuItem<String>(value: 'Heart Valves', child: Text('Heart Valves')),
              const DropdownMenuItem<String>(value: 'Bone Marrow', child: Text('Bone Marrow')),
              const DropdownMenuItem<String>(value: 'Skin', child: Text('Skin')),
            ],
            onChanged: (value) {
              setState(() {
                _selectedOrgan = value ?? 'all';
                _filterOrgans();
              });
            },
          ),
        ),
        const SizedBox(height: 10), // Add space between dropdown and list
        Expanded(
          child: ListView.builder(
            itemCount: _filteredOrgans.length,
            itemBuilder: (context, index) {
              final organ = _filteredOrgans[index];
              return Card(
                margin: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                child: ListTile(
                  title: Text(organ['organ_name']),
                  subtitle: Text('Blood Type: ${organ['blood_type']}'),
                  trailing: ElevatedButton(
                    onPressed: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => OrganViewScreen(organId: organ['id']),
                        ),
                      );
                    },
                    style: ElevatedButton.styleFrom(backgroundColor: customOrange),
                    child: const Text('View', style: TextStyle(color: Colors.white)),
                  ),
                ),
              );
            },
          ),
        ),
      ],
    );
  }
}




class MyMatchScreen extends StatefulWidget {
  const MyMatchScreen({Key? key}) : super(key: key);

  @override
  _MyMatchScreenState createState() => _MyMatchScreenState();
}

class _MyMatchScreenState extends State<MyMatchScreen> {
  final _storage = FlutterSecureStorage();
  List<Map<String, dynamic>> _matchedOrgans = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchMatchingOrgans();
  }

  Future<void> _fetchMatchingOrgans() async {
    final userId = await _storage.read(key: 'userId');
    if (userId == null) return;

    final url = '${Config.baseUrl}/organs/matching/mobile';
    try {
      final response = await http.post(
        Uri.parse(url),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'user_id': int.parse(userId)}),
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        setState(() {
          _matchedOrgans = List<Map<String, dynamic>>.from(data);
          _isLoading = false;
        });
      } else {
        throw Exception('Failed to fetch matching organs');
      }
    } catch (error) {
      setState(() {
        _isLoading = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: $error')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _matchedOrgans.isEmpty
          ? Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: const [
            Text(
              '😔',
              style: TextStyle(fontSize: 80),
            ),
            SizedBox(height: 20),
            Text(
              'No matching organs found',
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Colors.grey),
              textAlign: TextAlign.center,
            ),
          ],
        ),
      )
          : Column(
        children: [
          const Padding(
            padding: EdgeInsets.all(8.0),
            child: Text(
              'Matching Organs',
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
          ),
          Expanded(
            child: ListView.builder(
              itemCount: _matchedOrgans.length,
              itemBuilder: (context, index) {
                final organ = _matchedOrgans[index];
                return Card(
                  margin: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                  child: ListTile(
                    title: Text(organ['organ_name']),
                    subtitle: Text('Blood Type: ${organ['blood_type']}'),
                    trailing: ElevatedButton(
                      onPressed: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => OrganViewScreen(organId: organ['id']),
                          ),
                        );
                      },
                      style: ElevatedButton.styleFrom(backgroundColor: customOrange),
                      child: const Text('View', style: TextStyle(color: Colors.white)),
                    ),
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}


class MyRequestsScreen extends StatefulWidget {
  const MyRequestsScreen({Key? key}) : super(key: key);

  @override
  _MyRequestsScreenState createState() => _MyRequestsScreenState();
}

class _MyRequestsScreenState extends State<MyRequestsScreen> {
  final _storage = FlutterSecureStorage();
  List<Map<String, dynamic>> _userRequests = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchUserRequests();
  }

  Future<void> _fetchUserRequests() async {
    final userId = await _storage.read(key: 'userId');
    if (userId == null) return;

    final url = '${Config.baseUrl}/organ-requests/mobile';
    try {
      final response = await http.post(
        Uri.parse(url),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'user_id': int.parse(userId)}),
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        setState(() {
          _userRequests = List<Map<String, dynamic>>.from(data['data']);
          _isLoading = false;
        });
      } else {
        throw Exception('Failed to fetch requests');
      }
    } catch (error) {
      setState(() {
        _isLoading = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: $error')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _userRequests.isEmpty
          ? Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: const [
            Text(
              '😕', // Empty emoji
              style: TextStyle(fontSize: 100),
            ),
            SizedBox(height: 10),
            Text(
              'No Requests',
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
          ],
        ),
      )
          : ListView.builder(
        itemCount: _userRequests.length,
        itemBuilder: (context, index) {
          final request = _userRequests[index];
          return Card(
            margin: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
            child: ListTile(
              title: Text(request['organ']['organ_name'] ?? 'Unknown Organ'),
              subtitle: Text('Date: ${request['date']}'),
              trailing: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    decoration: BoxDecoration(
                      color: request['status'] == 'pending'
                          ? Colors.grey // Pending: gray
                          : request['status'] == 'approved'
                          ? Colors.green // Approved: green
                          : const Color(0xFFE0115F), // Rejected: #EE4B2B
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Text(
                      request['status'].toString().capitalize(),
                      style: const TextStyle(
                        color: Colors.white,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                  const SizedBox(width: 8),
                  ElevatedButton(
                    onPressed: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => OrganViewScreen(organId: request['organ_id']),
                        ),
                      );
                    },
                    style: ElevatedButton.styleFrom(backgroundColor: customOrange),
                    child: const Text('View', style: TextStyle(color: Colors.white)),
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}

// Extension to capitalize the first letter of each status
extension StringExtension on String {
  String capitalize() {
    return this[0].toUpperCase() + substring(1);
  }
}

class MessageScreen extends StatefulWidget {
  const MessageScreen({Key? key}) : super(key: key);

  @override
  _MessageScreenState createState() => _MessageScreenState();
}

class _MessageScreenState extends State<MessageScreen> {
  final _storage = FlutterSecureStorage();
  final _messageController = TextEditingController();
  String? _selectedOrgan;
  String? _selectedBloodType;
  bool _isLoading = false;

  final List<String> _organs = [
    'Kidney', 'Liver', 'Heart', 'Lung', 'Pancreas', 'Small Intestine',
    'Corneas', 'Heart Valves', 'Bone Marrow', 'Skin'
  ];

  final List<String> _bloodTypes = [
    'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'
  ];

  Future<void> _sendMessage() async {
    String? userId = await _storage.read(key: 'userId');
    if (userId == null) return;

    final url = '${Config.baseUrl}/send-message/mobile';

    setState(() {
      _isLoading = true;
    });

    try {
      final response = await http.post(
        Uri.parse(url),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'user_id': int.parse(userId),
          'organ_name': _selectedOrgan,
          'blood_type': _selectedBloodType,
          'message': _messageController.text,
        }),
      );

      if (response.statusCode == 201) {
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (context) => const MessageSentScreen()),
        );
      } else {
        throw Exception('Failed to send message');
      }
    } catch (error) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: $error')),
      );
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            DropdownButtonFormField<String>(
              decoration: const InputDecoration(
                labelText: 'Select Organ',
                border: OutlineInputBorder(),
              ),
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
            ),
            const SizedBox(height: 15),
            DropdownButtonFormField<String>(
              decoration: const InputDecoration(
                labelText: 'Select Blood Type',
                border: OutlineInputBorder(),
              ),
              value: _selectedBloodType,
              items: _bloodTypes.map((type) {
                return DropdownMenuItem(
                  value: type,
                  child: Text(type),
                );
              }).toList(),
              onChanged: (value) {
                setState(() {
                  _selectedBloodType = value;
                });
              },
            ),
            const SizedBox(height: 15),
            TextField(
              controller: _messageController,
              maxLines: 4,
              decoration: const InputDecoration(
                labelText: 'Enter your message',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 20),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: _isLoading ? null : _sendMessage,
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFFFF4444),
                  padding: const EdgeInsets.symmetric(vertical: 15),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(20),
                  ),
                ),
                child: _isLoading
                    ? const CircularProgressIndicator(color: Colors.white)
                    : const Text('Send Message', style: TextStyle(color: Colors.white, fontSize: 18)),
              ),
            ),
          ],
        ),
      ),
    );
  }
}