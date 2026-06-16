# Système de pointage PME — MozArtsduWeb

## Contexte d'usage choisi

Pointage sur une **tablette commune à l'accueil** de l'entreprise.

Ce choix justifie toute la solution anti-usurpation : les salariés passent devant la tablette en arrivant/partant, comme une badgeuse, sans avoir besoin de leur propre appareil.

---

## Mécanisme anti-usurpation : Sélection du nom + PIN à 6 chiffres

### Le problème à résoudre

Deux exigences contradictoires :
- Pointer **vite**, sans ressaisir un login/mot de passe complet à chaque fois
- **Aucune usurpation** : un salarié ne peut pas pointer à la place d'un collègue

### La solution

Le pointage se fait en deux étapes rapides :

1. **Sélection du nom** — L'employé clique sur son nom dans une grille/liste (20 personnes max, rapide à trouver)
2. **Saisie du PIN à 6 chiffres** — Le système vérifie que ce PIN correspond à *cette personne précise*

### Pourquoi ça résout le problème de collision

Un PIN seul (sans sélection préalable) poserait un problème : si deux employés ont le même PIN, le système ne sait pas qui pointe. En sélectionnant d'abord son nom, le PIN est vérifié contre **un seul utilisateur**, donc les collisions sont impossibles, même si deux personnes ont le même code.

C'est le même principe qu'un distributeur automatique : la **carte** (= nom sélectionné) + le **code** (= PIN) = identité certaine.

### Sécurité du PIN

- Les PINs sont **hashés en bcrypt** en base de données — jamais stockés en clair
- Connaître le nom d'un collègue ne suffit pas, il faut aussi son PIN personnel
- L'admin définit les PINs à la création des comptes (interface back-office séparée)

---

## Structure de la base de données

```sql
employees
  - id
  - first_name
  - last_name
  - pin_hash
  - created_at

clockings
  - id
  - employee_id (FK → employees)
  - type (ENUM: 'arrivée', 'départ')
  - created_at
```

---

## Stack technique

> En attente de confirmation si Symfony est autorisé. Sinon : **Laravel**.

- Back-end : Symfony **ou** Laravel (PHP)
- Front-end : HTML / CSS / JavaScript
- Base de données : MySQL

---

## Lancer le projet

> Section à compléter une fois la stack confirmée.

---

## Ce que j'aurais amélioré avec plus de temps

> Section à compléter à la fin du projet.

---

## Pourquoi le web m'intéresse

> Section à compléter avant l'envoi.
