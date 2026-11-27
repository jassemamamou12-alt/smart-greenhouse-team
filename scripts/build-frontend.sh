#!/usr/bin/env bash
# ---------------------------------------------------------------
# Smart Greenhouse — build du frontend Flutter pour Render
# Utilise par render.yaml (Static Site).
#
# Render n'a PAS Flutter preinstallé : on l'installe (version epinglee
# = meme version qu'en local) puis on build en web.
#
# Usage local :  bash scripts/build-frontend.sh
# ---------------------------------------------------------------
set -euo pipefail

FLUTTER_VERSION="3.50.0"
FLUTTER_DIR="${FLUTTER_DIR:-$HOME/flutter}"

echo "=== Smart Greenhouse — build frontend (Flutter ${FLUTTER_VERSION}) ==="

# 1) Flutter SDK : installe s'il n'existe pas (les builds suivants le reutilisent)
if [ ! -x "${FLUTTER_DIR}/bin/flutter" ]; then
    echo "--- Installation du Flutter SDK ${FLUTTER_VERSION} dans ${FLUTTER_DIR}"
    git clone --depth 1 --branch "${FLUTTER_VERSION}" \
        https://github.com/flutter/flutter.git "${FLUTTER_DIR}"
else
    echo "--- Flutter deja present dans ${FLUTTER_DIR}"
fi

export PATH="${FLUTTER_DIR}/bin:${PATH}"
export PUB_CACHE="${PUB_CACHE:-$HOME/.pub-cache}"

flutter --version

# 2) Dependances + analyse + tests + build web
cd frontend

echo "--- flutter pub get"
flutter pub get

echo "--- flutter analyze"
flutter analyze --no-fatal-infos

echo "--- flutter test"
flutter test

echo "--- flutter build web --release"
# API_URL est fourni par render.yaml (envVars) ; defaut = API Render.
flutter build web --release \
    --dart-define="API_URL=${API_URL:-https://smart-greenhouse-api-em2b.onrender.com}"

# 3) Verification de la sortie attendue par staticPublishPath
test -f build/web/index.html

echo "=== Build OK -> frontend/build/web ==="
