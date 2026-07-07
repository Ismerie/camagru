# Camagru — Cahier des charges (sujet 42, v4.1)

Reformulation du sujet officiel (`camagru.pdf`), organisée en checklist exploitable et mise en
regard de l'architecture actuelle du repo (MVC PHP maison : `app/controllers`, `app/models`,
`app/views`, `app/core/Router.php`).

> Légende : `[ ]` à faire · `[~]` en cours / partiel · `[x]` fait

---

## 1. Objectif du projet

Petite application web permettant à un utilisateur de :

1. choisir une image "superposable" (cadre, sticker, objet — avec **canal alpha**, sinon la
   superposition n'a aucun effet) dans une liste prédéfinie,
2. prendre une photo via sa **webcam** (ou uploader une image si pas de webcam),
3. obtenir une image finale = superposition des deux images, **générée côté serveur**,
4. publier cette image dans une **galerie publique** likable et commentable.

---

## 2. Contraintes techniques générales

- Zéro erreur / warning / log en console, côté serveur **et** client. Seules les erreurs liées à
  `getUserMedia()` (absence d'HTTPS) sont tolérées.
- **Serveur** : n'importe quel langage, mais toute fonction utilisée doit avoir un équivalent dans
  la bibliothèque standard PHP → en pratique ici, PHP pur (déjà le choix fait dans ce repo).
- **Client** : HTML / CSS / JS natif obligatoire (`document.querySelector`, `fetch`, etc.).
- **Frameworks/libs interdits** sauf s'ils ont un équivalent en lib standard PHP — exception : les
  frameworks CSS sont tolérés tant qu'ils n'embarquent pas de JS "interdit" (donc pas de
  Bootstrap-JS, mais Bootstrap-CSS seul ou Tailwind OK).
- Containerisation à jour obligatoire, démarrage en **une seule commande**
  (`docker-compose up` ou équivalent) → déjà en place (`docker-compose.yml` + `Dockerfile`).
- Compatible au minimum Firefox ≥ 41 et Chrome ≥ 46.
- Toute credential/API key/variable d'env dans un `.env` **non versionné** (git-ignoré) →
  `.env` est déjà dans `.gitignore`, à vérifier qu'il n'a jamais été commit par erreur.
- Webserver libre (Apache, Nginx, built-in PHP server...) → Apache choisi (`apache-config.conf`).

---

## 3. Fonctionnalités communes (V.1)

- [~] Structurer l'app en MVC (déjà amorcé : `Router`, `Controller`, `*Model`, `views/`).
- [ ] Layout complet : header + main + footer sur toutes les pages (actuellement `navbar.php`,
  `footer.php`, `home.php`, `login.php`, `signup.php` existent — à vérifier que gallery/profil en
  disposent aussi).
- [ ] Responsive mobile + adaptation sur petites résolutions.
- [ ] **Toute la validation de formulaire + sécurisation du site est un point MANDATORY noté à
  part.** Cas explicitement listés comme NON sécurisés à éviter :
  - [ ] mots de passe en clair / non hashés en base (le hash `password_hash` est déjà utilisé côté
    `register`, bien),
  - [ ] injection HTML/JS via des variables mal échappées (XSS),
  - [ ] upload de contenu arbitraire sur le serveur (valider type MIME réel + extension + taille),
  - [ ] altération de requêtes SQL (utiliser des requêtes préparées PDO partout, jamais de
    concaténation),
  - [ ] manipulation de données privées via un formulaire/endpoint externe (vérifier l'auth +
    l'ownership sur chaque action API, pas seulement côté front).

---

## 4. Fonctionnalités utilisateur (V.2)

- [~] Inscription (email valide + username + mot de passe avec complexité minimale). Le back
  `AuthController::register` + `Validator::isValidRegister` existent déjà (`app/controllers/
  AuthController.php:18`, migration récente "feat: back signup").
- [ ] **Confirmation de compte obligatoire** via lien unique envoyé par email avant de pouvoir se
  connecter (pas encore présent — pas de génération de token ni d'envoi de mail visible dans
  `UserModel`).
- [ ] Connexion via username + password. `AuthController::login` est un stub qui ne fait
  qu'écho les données reçues (`app/controllers/AuthController.php:60`) — logique réelle à
  implémenter (vérification hash, session, cookie).
- [ ] Réinitialisation de mot de passe oublié via email.
- [ ] Déconnexion en un clic, accessible depuis n'importe quelle page. `logout` est aussi un
  stub actuellement.
- [ ] Une fois connecté, l'utilisateur doit pouvoir modifier username, email et mot de passe
  (page "profile" déjà routée vers `UserController::profile`, contrôleur pas encore créé).

---

## 5. Fonctionnalités galerie (V.3)

- [ ] Page publique (accessible sans compte) affichant **toutes** les images éditées par tous les
  utilisateurs, triées par date de création décroissante.
- [ ] Like et commentaire réservés aux utilisateurs connectés.
- [ ] Email de notification à l'auteur de l'image à chaque nouveau commentaire, **activé par
  défaut**, désactivable dans les préférences utilisateur.
- [ ] Pagination de la liste d'images, **minimum 5 éléments par page**.

État actuel : `gallery.php` (`app/views/gallery.php:1`) est une simple `<section id="masonry">`
vide, remplie côté JS (`public/js/masonry.js`) — aucun endpoint API galerie/like/comment
implémenté (les routes `api/like` et `api/comment` existent dans `Router.php` mais pointent vers
un `ApiController` qui n'existe pas encore).

---

## 6. Fonctionnalités d'édition (V.4)

Page accessible **uniquement aux utilisateurs connectés** (rejet propre — pas un crash — pour les
autres).

Layout attendu (cf. figure V.1 du sujet) :

```
┌─────────────────────────────┐
│            Header            │
├───────────────────┬───────────┤
│                   │           │
│       Main         │   Side    │
│  (webcam + liste    │(miniatures│
│  d'images +          │ des      │
│  bouton capture)     │ photos    │
│                   │ précédentes)│
├───────────────────┴───────────┤
│            Footer            │
└─────────────────────────────┘
```

- [ ] Section principale : preview webcam + liste d'images superposables sélectionnables +
  bouton de capture.
- [ ] Le bouton de capture reste **inactif** tant qu'aucune image superposable n'est sélectionnée.
- [ ] Section latérale : miniatures de toutes les photos déjà prises par l'utilisateur.
- [ ] La génération de l'image finale (superposition incluse) se fait **côté serveur**, jamais en
  canvas client seul (le client peut envoyer le snapshot webcam en base64/blob, mais le
  compositing final est serveur).
- [ ] Upload d'image en alternative à la capture webcam (tout le monde n'a pas de webcam).
- [ ] Suppression d'une image éditée par son auteur uniquement (jamais celles d'un autre user).

Rien de tout ça n'est encore implémenté (pas de contrôleur d'édition, pas d'`ImageModel`, pas de
dossier d'assets pour les images superposables).

---

## 7. Résumé des contraintes techniques imposées (V.5)

| | Serveur | Client |
|---|---|---|
| Langages autorisés | N'importe lequel, limité à l'équivalent lib standard PHP | HTML / CSS / JS (API navigateur natives uniquement) |
| Frameworks autorisés | N'importe lequel, jusqu'à équivalent lib standard PHP | Frameworks CSS tolérés, sauf s'ils ajoutent du JS interdit |

Obligation : au moins un conteneur de déploiement en une commande (`docker-compose` ou
équivalent) → déjà satisfait.

---

## 8. Partie bonus (V.6)

**⚠️ Les bonus ne sont évalués QUE si la partie obligatoire est parfaite** (intégralement faite,
sans dysfonctionnement). Un bonus doit être : utile, pertinent, fonctionnel à 100 %, techniquement
solide, cohérent avec le reste — sinon il n'est pas compté.

Pistes suggérées par le sujet :
- [ ] "AJAXifier" les échanges avec le serveur (pas de rechargement de page).
- [ ] Preview live du rendu édité directement dans le flux webcam (plus dur que ça en a l'air,
  dixit le sujet).
- [ ] Pagination infinie de la galerie.
- [ ] Partage des images sur les réseaux sociaux.
- [ ] Génération d'un GIF animé.

---

## 9. Rendu et évaluation (V.7)

- Rendu = le contenu du repo `Git` au moment de la soutenance, rien d'autre.
- Vérifier soigneusement les noms de dossiers/fichiers avant la défense.

---

## 10. Prochaines étapes suggérées (hors sujet officiel, priorisation perso)

1. Finaliser l'auth complète : confirmation de compte par email, login/logout réels avec session,
   reset password, édition du profil (`app/controllers/AuthController.php:60` à remplacer par une
   vraie implémentation ; créer `UserController`).
2. Créer `ApiController` + `ImageModel` + table `images` (+ `likes`, `comments`) en base, avec
   migrations dans `app/core/Migration.php`.
3. Page d'édition : accès webcam (`getUserMedia`), sélection d'image superposable, upload
   alternatif, envoi au serveur, compositing GD/Imagick côté PHP.
4. Galerie publique paginée + like/commentaire + notification email.
5. Sécurisation transverse : CSRF token sur tous les formulaires, échappement systématique en
   sortie, requêtes préparées partout, validation stricte des uploads (type MIME réel, taille,
   extension).
6. Responsive + polish visuel une fois le fonctionnel bouclé.
7. Bonus, seulement une fois le mandatory 100 % validé.
