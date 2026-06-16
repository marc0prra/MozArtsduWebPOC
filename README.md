# Système de pointage PME — MozArtsduWeb

## Contexte d'usage choisi

Pointage sur une **tablette commune à l'accueil** de l'entreprise.

Ce choix justifie toute la solution anti-usurpation : les salariés passent devant la tablette en arrivant/partant, comme une badgeuse, sans avoir besoin de leur propre appareil.

---

## Mécanisme anti-usurpation : Liste des employés + PIN à 6 chiffres

### Le problème à résoudre

Deux exigences contradictoires :
- Pointer **vite**, sans ressaisir un login/mot de passe complet à chaque fois
- **Aucune usurpation** : un salarié ne peut pas pointer à la place d'un collègue

### La solution — Flux en 3 étapes

```
[Écran d'accueil]           [Saisie PIN]                [Page de pointage]
┌──────────────────┐        ┌──────────────────┐        ┌──────────────────┐
│ Jean Dupont      │ ──────>│ Bonjour Jean     │ ──────>│ Bonjour Jean !   │
│ Marie Martin     │  clic  │                  │  PIN   │                  │
│ Paul Bernard     │        │ [_][_][_][_][_][_]│  OK   │ [Arrivée] [Départ]│
│ ...              │        │                  │        │                  │
└──────────────────┘        └──────────────────┘        └──────────────────┘
```

1. **Liste des employés** — Grille avec prénom + nom de tous les salariés, affichée en permanence à l'écran
2. **Clic sur son nom** — L'interface de saisie du PIN à 6 chiffres s'affiche (les autres noms disparaissent)
3. **PIN validé** — Redirection vers la page de pointage personnelle où l'employé choisit arrivée ou départ

### Pourquoi ça résout le problème de collision

Même si deux employés avaient le même PIN, il n'y a aucune ambiguïté : le PIN est toujours vérifié contre **une seule personne** (celle qui a été cliquée). Le système ne cherche jamais un PIN dans toute la base, il vérifie juste `PIN saisi == PIN de Jean Dupont`.

C'est le même principe qu'un distributeur automatique : la **carte** (= nom cliqué) + le **code** (= PIN) = identité certaine.

### Sécurité du PIN

- Les PINs sont **hashés en bcrypt** en base de données — jamais stockés en clair
- Connaître le nom d'un collègue ne suffit pas, il faut aussi son PIN personnel à 6 chiffres
- L'admin définit les PINs à la création des comptes via une interface back-office séparée

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

> Parce que j'aime bien le résultat final.
