# Smart Greenhouse — Frontend Flutter

Application Flutter (web) de la serre connectée **Smart Greenhouse**.

## Prérequis

- Flutter **3.50+** (`flutter --version`)
- Le backend tourne sur `http://localhost:8000` (`docker compose up` à la racine)

## Démarrage

```powershell
cd frontend
flutter pub get
flutter run -d chrome
```

## Configuration de l'API

L'URL de l'API est injectée **au moment du build** via `--dart-define` :

```powershell
# Local (valeur par défaut : http://localhost:8000)
flutter run -d chrome

# Contre l'API de production Render
flutter build web --release --dart-define=API_URL=https://smart-greenhouse-api-em2b.onrender.com
```

La variable est lue dans [`lib/config.dart`](lib/config.dart).

## Structure

```
lib/
├── config.dart                 # URL de l'API (String.fromEnvironment)
├── main.dart                   # App + écran d'accueil + badge de statut
└── services/
    └── api_client.dart         # Client HTTP (health, getJson)
test/
└── widget_test.dart            # 2 tests, sans appel réseau (client factice)
```

## Tests & analyse

```powershell
flutter analyze
flutter test
```

> La CI GitHub (`App Flutter` + `Build Flutter Web`) exécute exactement ces
> deux commandes à chaque PR.

## Convention de travail

```
git checkout development && git pull
git checkout -b feature/nom-courte
# ... modifications ...
git push -u origin feature/nom-courte
# → ouvrir une PR vers `development`
```

**Jamais de push direct sur `development`, `main` ou `prod`.**
