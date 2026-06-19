# Système de pointage PME — MozArtsduWeb

Test technique · Alternance Développement Web · Rentrée septembre 2026

---

## Lancer le projet

### Prérequis

- PHP 8.2+
- Composer
- Symfony CLI
- MySQL / MariaDB (ex : XAMPP)

### Installation

```bash
# 1. Cloner le dépôt
git clone <url-du-repo>
cd MozArtsduWeb

# 2. Installer les dépendances PHP
composer install

# 3. Configurer la base de données
cp .env .env.local
# Modifier DATABASE_URL dans .env.local :
# DATABASE_URL="mysql://root:@127.0.0.1:3306/mozartsduweb"

# 4. Créer la base et appliquer les migrations
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# 5. Charger les données de test
php bin/console doctrine:fixtures:load

# 6. Lancer le serveur
symfony serve
```

L'application est accessible sur `http://127.0.0.1:8000`.

### Comptes de test

| Prénom  | Nom     | Code PIN |
|---------|---------|----------|
| Alice   | Martin  | 123456   |
| Bob     | Dupont  | 234567   |
| Camille | Bernard | 345678   |

---

## Contexte d'usage

L'application est conçue pour une **tablette fixe à l'accueil** de l'entreprise, en mode kiosque (sans barre d'URL visible). Les salariés n'ont accès qu'à l'écran de pointage — ils ne peuvent naviguer nulle part ailleurs.

La **vue responsable** n'est pas accessible depuis la tablette. Elle est consultable via une URL confidentielle, bookmarkée par le responsable sur son propre appareil (téléphone ou PC).

---

## Mécanisme anti-usurpation : code PIN à 6 chiffres

### Le problème

Deux exigences s'opposent : pointer vite (pas de long identifiant/mot de passe) et empêcher qu'un salarié pointe à la place d'un collègue.

### La solution

Chaque salarié dispose d'un **code PIN personnel à 6 chiffres**. Le flux est :

```
[Liste des salariés]    [Saisie PIN]              [Arrivée / Départ]
┌───────────────────┐   ┌───────────────────┐   ┌───────────────────┐
│  Alice Martin     │──>│  Alice Martin     │──>│  Bonjour Alice !  │
│  Bob Dupont       │   │  ● ● ● ○ ○ ○      │   │  14:32:07         │
│  Camille Bernard  │   │  [1][2][3]        │   │ [Arrivée][Départ] │
└───────────────────┘   │  [4][5][6]  ...   │   └───────────────────┘
                        └───────────────────┘
```

1. Le salarié sélectionne son prénom parmi la liste.
2. Il saisit son PIN à 6 chiffres sur le pavé numérique tactile.
3. Si le PIN est correct, il choisit **Arrivée** ou **Départ**.
4. Le pointage est enregistré en base avec l'horodatage exact.

### Pourquoi ce choix est adapté

- **Rapide** : 3 gestes — sélectionner, saisir 6 chiffres (soumission automatique), appuyer sur un bouton.
- **Sécurisé** : même si un collègue connaît votre prénom, il ne connaît pas votre PIN. 6 chiffres = 1 000 000 combinaisons.
- **Adapté au tactile** : le pavé numérique a de grandes touches, conçues pour une utilisation sur tablette.
- **Même principe qu'un distributeur automatique** : carte (= prénom sélectionné) + code (= PIN) = identité certaine.

### Sécurité technique

- Les PINs sont **hachés en bcrypt** en base de données, jamais stockés en clair.
- Le PIN est **vérifié deux fois côté serveur** : à la validation du formulaire PIN, puis à nouveau au moment d'enregistrer le pointage — pour éviter toute soumission forgée qui contournerait l'étape d'authentification.

---

## Stack technique

- **Back-end** : Symfony 7 (PHP 8.2)
- **ORM** : Doctrine
- **Front-end** : HTML, CSS, JavaScript vanilla
- **Base de données** : MySQL

---

## Pourquoi le web m'intéresse


## Ce que je recherche dans cette alternance


## Ce que j'aurais amélioré avec plus de temps

- **Authentification responsable** : remplacer l'URL confidentielle par un vrai login sécurisé.
- **Historique complet** : filtrer les pointages par salarié et par période, pas seulement le jour courant.
- **Détection des anomalies** : alerter si un salarié pointe deux arrivées de suite sans départ intermédiaire.
- **Export CSV** : permettre au responsable d'exporter les données pour les intégrer dans un outil RH.
- **Gestion des salariés** : interface admin pour créer, modifier ou désactiver un salarié sans toucher à la base.
- **Tests automatisés** : écrire des tests fonctionnels (PHPUnit / Panther) pour fiabiliser le flux de pointage.
