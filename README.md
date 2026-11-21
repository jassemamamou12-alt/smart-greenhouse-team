# Smart Greenhouse — Équipe de 4

Projet Smart Greenhouse reparti **à zéro**.
Développé dans le cadre du cours *Programmation Embarquée Mobile* (Dr. Atef Ftirich).

## Équipe et rôles

| Membre | Rôle | Périmètre |
|---|---|---|
| Membre 1 | Backend | API Symfony (`greenhouse_api`) |
| Membre 2 | Backend | API Symfony (`greenhouse_api`) |
| Membre 3 | Frontend | Application Flutter |
| Membre 4 | DevOps / Git | Docker, CI, accès, workflow Git |

## Structure du dépôt

Le dépôt contient **trois branches**, une par environnement :

| Branche | Rôle |
|---|---|
| `development` | intégration quotidienne du travail de toute l'équipe |
| `preprod` | recette : version candidate, tests avant mise en production |
| `prod` | production : le code qui tourne réellement |

## Convention de branches

- Flux : `development` → `preprod` → `prod` (chaque étape par pull request).
- `prod` et `preprod` sont **protégées** : pas de push direct, merge uniquement via PR.
- Fonctionnalités : branche `feature/tpXX-sujet` créée depuis `development`,
  puis PR avec revue croisée avant merge.

## Règles de commit

- Messages en **français**, détaillés (sujet + corps listant les changements).
- Un commit = une idée cohérente ; jamais de fichiers de secrets (`.env.local`,
  clés JWT `*.pem`, `var/`, `build/`).
