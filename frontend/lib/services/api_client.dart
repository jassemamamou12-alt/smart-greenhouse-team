import 'dart:convert';

import 'package:http/http.dart' as http;

import '../config.dart';

/// Petit client HTTP pour parler a l'API Symfony.
///
/// Aucun framework : juste `http`. A completer par l'equipe frontend
/// (authentification, endpoints metier, etc.).
class ApiClient {
  ApiClient({http.Client? client, String? baseUrl})
      : _client = client ?? http.Client(),
        _baseUrl = baseUrl ?? AppConfig.apiBaseUrl;

  final http.Client _client;
  final String _baseUrl;

  /// Appelle GET /api/health et renvoie `true` si l'API repond.
  Future<bool> checkHealth() async {
    try {
      final response = await _client
          .get(Uri.parse('$_baseUrl${AppConfig.healthEndpoint}'))
          .timeout(const Duration(seconds: 8));
      return response.statusCode == 200;
    } catch (_) {
      // API hors ligne, timeout, CORS... on considere que c'est KO.
      return false;
    }
  }

  /// GET generique renvoyant un Map, ou `null` en cas d'echec.
  Future<Map<String, dynamic>?> getJson(String path) async {
    try {
      final response = await _client
          .get(Uri.parse('$_baseUrl$path'))
          .timeout(const Duration(seconds: 8));
      if (response.statusCode != 200) return null;
      final decoded = jsonDecode(response.body);
      return decoded is Map<String, dynamic> ? decoded : null;
    } catch (_) {
      return null;
    }
  }

  void dispose() => _client.close();
}
