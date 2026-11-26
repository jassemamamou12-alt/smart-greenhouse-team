import 'package:flutter_test/flutter_test.dart';

import 'package:smart_greenhouse/main.dart';
import 'package:smart_greenhouse/services/api_client.dart';

/// Client factice : evite tout appel reseau pendant les tests.
class _FakeApiClient implements ApiClient {
  @override
  Future<bool> checkHealth() async => true;

  @override
  Future<Map<String, dynamic>?> getJson(String path) async => null;

  @override
  void dispose() {}
}

void main() {
  testWidgets('affiche le titre et le badge de statut',
      (WidgetTester tester) async {
    await tester.pumpWidget(SmartGreenhouseApp(apiClient: _FakeApiClient()));

    expect(find.text('Smart Greenhouse'), findsWidgets);
    expect(find.text('Serre connectee'), findsOneWidget);
    expect(find.byType(StatusBadge), findsOneWidget);
  });

  testWidgets('le bouton reverifier declenche une nouvelle connexion',
      (WidgetTester tester) async {
    await tester.pumpWidget(SmartGreenhouseApp(apiClient: _FakeApiClient()));
    await tester.pumpAndSettle();

    await tester.tap(find.byTooltip('Reverifier'));
    await tester.pumpAndSettle();

    expect(find.text('API en ligne'), findsOneWidget);
  });
}
