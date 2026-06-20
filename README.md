# Système de pointage PME — MozArtsduWeb

Test technique · Alternance Développement Web · Rentrée septembre 2026

---

## Lancer le projet

### Prérequis

- PHP 8.2+
- Composer
- Symfony CLI
- MySQL  (XAMPP)

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

---

## Stack technique

- **Back-end** : Symfony 7 (PHP 8.2)
- **ORM** : Doctrine
- **Front-end** : HTML, CSS, JavaScript
- **Base de données** : MySQL

---

## Pourquoi le web m'intéresse

Ce qui m'attire dans le web, c'est la satisfaction de voir un projet prendre forme et devenir utilisable concrètement. Créer quelque chose que d'autres vont réellement utiliser, c'est motivant. Le web est aussi omniprésent dans le quotidien : des services essentiels comme la déclaration d'impôts, les démarches administratives, ou des dizaines d'autres outils passent aujourd'hui par un navigateur. Participer à construire ces interfaces, c'est contribuer à quelque chose d'utile et de concret.

Ce que j'apprécie aussi, c'est la liberté que ça donne : avec les compétences web, on peut créer ses propres outils sur mesure. Par exemple, une application de suivi pour la salle de sport — avec la liste des exercices, la mémorisation des performances séance après séance, l'évolution des charges — quelque chose qu'on ne trouve pas toujours exactement comme on le voudrait dans les apps existantes. C'est cette capacité à transformer une idée en quelque chose de fonctionnel qui me plaît.

## Ce que je recherche dans cette alternance

Avant tout, acquérir de l'expérience concrète en conditions réelles — ce qu'un cours ou un projet scolaire ne peut pas vraiment reproduire. Je souhaite notamment découvrir Laravel, et progresser dessus au contact de professionnels.

Au-delà du technique, ce qui m'intéresse chez MozArtsduWeb c'est de comprendre comment fonctionne la relation client : comment trouver un client, comment cadrer un projet avec lui, gérer les échanges, et accompagner le projet de la première discussion jusqu'à la livraison finale. C'est cette vision complète du métier — pas seulement coder, mais aussi comprendre le besoin et y répondre — ;que j'aimerais développer pendant cette alternance, ce qui colle parfaitement a mon bachelor (coordinateur de projets informatiques)

## Ce que j'aurais amélioré avec plus de temps

- **Authentification responsable** : remplacer l'URL confidentielle par un vrai login sécurisé.
- **Historique complet** : filtrer les pointages par salarié et par période, pas seulement le jour courant.
- **Détection des anomalies** : alerter si un salarié pointe deux arrivées de suite sans départ intermédiaire.
- **Export CSV** : permettre au responsable d'exporter les données pour les intégrer dans un outil RH.
- **Gestion des salariés** : interface admin pour créer, modifier ou désactiver un salarié sans toucher à la base.

---

## Utilisation de l'IA

J'ai utilisé Claude (IA d'Anthropic) comme assistant tout au long du projet, principalement sur les points où je manquais de recul.

Le côté **JavaScript** est celui où l'IA a été la plus utile : je ne connaissais pas le comportement de Turbo (@hotwired/turbo) qui intercepte la navigation et empêche les scripts de se ré-exécuter entre les pages. C'est l'IA qui m'a expliqué pourquoi le pavé numérique ne fonctionnait pas après navigation, et m'a orienté vers l'événement `turbo:load`. Elle m'a aussi aidé à corriger la fuite mémoire du `setInterval` et à remplacer les `onclick` par de la délégation d'événements avec des attributs `data-*`.

Sur le **PHP/Symfony**, l'IA m'a aidé à me rappeler les bonnes pratiques PSR comme `declare(strict_types=1)`.
