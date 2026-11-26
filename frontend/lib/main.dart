import 'package:material_ui/material_ui.dart';

import 'config.dart';
import 'services/api_client.dart';

void main() {
  runApp(const SmartGreenhouseApp());
}

/// Point d'entree de l'application Smart Greenhouse.
class SmartGreenhouseApp extends StatelessWidget {
  const SmartGreenhouseApp({super.key, this.apiClient});

  final ApiClient? apiClient;

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Smart Greenhouse',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.green),
        useMaterial3: true,
      ),
      home: HomePage(apiClient: apiClient ?? ApiClient()),
    );
  }
}

/// Ecran d'accueil : affiche l'etat de connexion a l'API.
class HomePage extends StatefulWidget {
  const HomePage({super.key, required this.apiClient});

  final ApiClient apiClient;

  @override
  State<HomePage> createState() => _HomePageState();
}

enum ApiStatus { unknown, checking, online, offline }

class _HomePageState extends State<HomePage> {
  ApiStatus _status = ApiStatus.unknown;

  @override
  void initState() {
    super.initState();
    _checkApi();
  }

  Future<void> _checkApi() async {
    setState(() => _status = ApiStatus.checking);
    final ok = await widget.apiClient.checkHealth();
    if (!mounted) return;
    setState(() => _status = ok ? ApiStatus.online : ApiStatus.offline);
  }

  String get _baseUrl => AppConfig.apiBaseUrl;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
        title: const Text('Smart Greenhouse'),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.eco, size: 96, color: Colors.green),
            const SizedBox(height: 24),
            Text(
              'Serre connectee',
              style: Theme.of(context).textTheme.headlineSmall,
            ),
            const SizedBox(height: 16),
            StatusBadge(status: _status),
            const SizedBox(height: 8),
            Text(
              'API : $_baseUrl',
              style: Theme.of(context).textTheme.bodySmall,
              textAlign: TextAlign.center,
            ),
          ],
        ),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: _checkApi,
        tooltip: 'Reverifier',
        child: const Icon(Icons.refresh),
      ),
    );
  }
}

/// Pastille d'etat de l'API.
class StatusBadge extends StatelessWidget {
  const StatusBadge({super.key, required this.status});

  final ApiStatus status;

  @override
  Widget build(BuildContext context) {
    final (label, color, icon) = switch (status) {
      ApiStatus.unknown => ('Non verifie', Colors.grey, Icons.help_outline),
      ApiStatus.checking => ('Connexion...', Colors.orange, Icons.hourglass_top),
      ApiStatus.online => ('API en ligne', Colors.green, Icons.check_circle),
      ApiStatus.offline => ('API hors ligne', Colors.red, Icons.error),
    };

    return Chip(
      avatar: Icon(icon, color: color, size: 18),
      label: Text(label, style: TextStyle(color: color)),
    );
  }
}
