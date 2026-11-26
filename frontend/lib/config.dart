/// Configuration de l'application Smart Greenhouse.
///
/// L'URL de l'API est injectee a la construction du bundle via la variable
/// d'environnement `API_URL` :
///
///   flutter build web --dart-define=API_URL=https://mon-api.onrender.com
///
/// Sans `--dart-define`, la valeur par defaut s'applique (backend local).
class AppConfig {
  AppConfig._();

  /// URL de base de l'API Symfony.
  static const String apiBaseUrl = String.fromEnvironment(
    'API_URL',
    defaultValue: 'http://localhost:8000',
  );

  /// Endpoint de sante de l'API.
  static const String healthEndpoint = '/api/health';
}
