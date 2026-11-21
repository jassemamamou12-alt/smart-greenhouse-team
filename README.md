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

Le dépôt contient **trois branches de travail**, une par pôle :

| Branche | Contenu |
|---|---|
| `backend` | API Symfony — work des 2 développeurs backend |
| `frontend` | Application Flutter — work du développeur frontend |
| `devops` | Docker (compose, Dockerfiles), CI, documentation d'exploitation |

## Convention de branches

- `main` = **stable, jamais de push direct** ; on ne merge que via pull request.
- Chaque pôle travaille sur **sa branche** (`backend`, `frontend`, `devops`).
- Fonctionnalités : branche `feature/tpXX-sujet` créée depuis la branche du pôle,
  puis PR avec revue croisée avant merge.

## Règles de commit

- Messages en **français**, détaillés (sujet + corps listant les changements).
- Un commit = une idée cohérente ; jamais de fichiers de secrets (`.env.local`,
  clés JWT `*.pem`, `var/`, `build/`).
