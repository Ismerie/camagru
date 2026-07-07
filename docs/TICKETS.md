# Camagru — Backlog de tickets

Découpage du travail restant en tickets exploitables, basé sur l'état réel du code (cf.
`docs/SUBJECT.md` pour le sujet complet). Chaque ticket a un ID, une priorité, ce qu'il faut
faire, les fichiers concernés et des critères d'acceptation.

Priorités :
- **P0** — bloquant, rien ne marche sans ça
- **P1** — mandatory du sujet, mais l'app tourne sans (pour l'instant)
- **P2** — sécurité/robustesse transverse, mandatory mais peut se faire en continu
- **P3** — bonus, seulement si tout le P0/P1/P2 est fait et parfait

Ordre suggéré : traiter les épics dans l'ordre où ils apparaissent (Auth → Données → Édition →
Galerie → Sécurité → Responsive → Mail → Bonus), mais P2 (sécurité) est transverse : à appliquer
au fur et à mesure, pas seulement à la fin.

---

## Épic A — Authentification complète

### TICKET-A1 · P0 · Implémenter `login()` réellement
`AuthController::login()` (`app/controllers/AuthController.php:60`) ne fait qu'écho les données
reçues. À faire :
- Récupérer `username`/`password` du body JSON.
- `UserModel::findByUsername()`, vérifier `password_verify()`.
- Rejeter si `is_verified = false` (message clair : "confirme ton compte d'abord").
- Créer une session PHP (`$_SESSION['user_id']`) — session déjà démarrée dans `public/index.php`.
- Réponses JSON cohérentes avec `register()` (codes HTTP 200/401).
- Câbler `login.js` : `loginUser()` (`public/js/auth/login.js:11`) doit appeler
  `postRequest("api/login", { username, password })` (actuellement aucun `fetch` n'est envoyé).
- Rediriger vers la home / afficher un toast succès après connexion.

**Acceptance** : un compte vérifié peut se connecter, un compte non vérifié ou un mauvais
mot de passe est rejeté proprement, une session est posée.

---

### TICKET-A2 · P0 · Implémenter `logout()` réellement
- `session_destroy()` / `unset($_SESSION['user_id'])` côté `AuthController::logout()`.
- Bouton de déconnexion visible sur **toutes les pages** une fois connecté (navbar), en un clic.

**Acceptance** : cliquer déconnexion détruit la session et redirige vers la home.

---

### TICKET-A3 · P0 · Middleware / garde de session
Créer un helper (ex. `Auth::requireLogin()` dans `app/core/`) utilisé par les controllers
protégés (édition, profil, like, comment) pour rejeter poliment (redirect ou 401 JSON) les
utilisateurs non connectés. Actuellement rien ne protège ces routes.

**Acceptance** : accéder à `/edit` ou appeler `api/like` sans session renvoie une erreur propre,
pas un crash ni un accès silencieux aux données.

---

### TICKET-A4 · P1 · Confirmation de compte par email
Colonnes déjà prévues en base (`is_verified`, `verification_token` dans `Migration.php:25-26`).
- À l'inscription (`AuthController::register`), générer un token unique (`bin2hex(random_bytes(32))`),
  le stocker, envoyer un email avec un lien `GET /verify?token=...`.
- Nouvelle route + controller action qui marque `is_verified = true` si le token est valide.
- Voir TICKET-F1 pour l'envoi d'email en local (pas de vrai SMTP dispo en dev).

**Acceptance** : un compte fraîchement créé ne peut pas se connecter tant que le lien reçu par
mail n'a pas été cliqué.

---

### TICKET-A5 · P1 · Mot de passe oublié
- Formulaire "mot de passe oublié" (email) → génère `reset_token` (colonne déjà présente),
  envoie un lien `GET /reset-password?token=...`.
- Formulaire de saisie du nouveau mot de passe, revalidé par `Validator::password()`.
- Invalider le token après usage.

**Acceptance** : un utilisateur qui a oublié son mot de passe peut en redéfinir un via email,
sans jamais voir/soumettre l'ancien.

---

### TICKET-A6 · P1 · Page profil (`UserController` manquant)
La route `GET /profile` pointe déjà vers `UserController::profile` (`app/core/Router.php:16`)
mais **le fichier n'existe pas** → crash fatal actuellement si on visite `/profile`.
- Créer `app/controllers/UserController.php` avec garde de session (TICKET-A3).
- Vue `profile.php` : modifier username / email / password (avec re-validation, unicité).
- Endpoint API `POST api/profile/update`.
- Inclure ici le toggle "notifications par email sur commentaire" (défaut `true`, cf TICKET-D3).

**Acceptance** : connecté, on peut changer username/email/password depuis `/profile` sans
recharger toute la page ; déconnecté, la route rejette proprement.

---

## Épic B — Modèle de données images

### TICKET-B1 · P0 · Modèles `ImageModel`, `LikeModel`, `CommentModel`
Tables déjà créées par `Migration.php` (`images`, `likes`, `comments`). Créer les modèles PHP
correspondants (mêmes conventions que `UserModel` : requêtes préparées uniquement).
- `ImageModel` : `create`, `findById`, `findByUser`, `findAllPaginated($page, $perPage)`, `delete`
  (avec vérif ownership côté controller, pas dans le modèle).
- `LikeModel` : `toggle($userId, $imageId)`, `countForImage`, `hasLiked`.
- `CommentModel` : `create`, `findByImage`.

**Acceptance** : couche données testable indépendamment des controllers, aucune requête SQL
concaténée à la main ailleurs dans le code.

---

### TICKET-B2 · P0 · Stock des images superposables (overlays)
Le sujet exige une **liste prédéfinie** d'images à superposer, avec canal alpha (PNG).
- Créer `public/overlays/` (ou équivalent) avec quelques PNG transparents.
- Endpoint ou simple liste statique exposée au front pour peupler le sélecteur d'overlays.

**Acceptance** : au moins 3-4 images overlay avec transparence réelle, visibles et sélectionnables
côté front.

---

### TICKET-B3 · P0 · Dossier de stockage des images générées/uploadées
- `public/uploads/` (ou hors `public/` servi via un endpoint contrôlé — à trancher, impact sécu).
- Permissions correctes pour l'utilisateur `www-data` du container (déjà `chown` dans le
  `Dockerfile`, à vérifier que le sous-dossier créé au runtime hérite bien des droits).
- Nommage de fichier non prévisible (hash/uuid), jamais le nom original de l'upload utilisateur.

**Acceptance** : aucune image n'est accessible/devinable par simple incrémentation d'URL.

---

## Épic C — Page d'édition

### TICKET-C1 · P0 · Route + garde `/edit`
- Nouveau `EditController` (ou logique dans `UserController`), protégé par TICKET-A3.
- Vue avec le layout à 2 zones du sujet (main = webcam + overlays + bouton capture ; side =
  miniatures des photos précédentes de l'utilisateur).

---

### TICKET-C2 · P0 · Accès webcam + sélection overlay
- `getUserMedia()` pour la preview live.
- Liste des overlays (TICKET-B2) cliquables/sélectionnables (état visuel "sélectionné").
- Bouton "Capturer" **désactivé tant qu'aucun overlay n'est sélectionné** (exigence explicite du
  sujet).

---

### TICKET-C3 · P0 · Upload alternatif (sans webcam)
- Input file classique, réutilise le même flux que la capture webcam côté serveur.
- Validation stricte type MIME réel (pas juste l'extension) + taille max (cf TICKET-E3).

---

### TICKET-C4 · P0 · Compositing serveur (GD)
- Endpoint `POST api/upload` (déjà routé vers `ApiController::upload`, **le fichier n'existe
  pas encore** → crash actuellement).
- Réception du snapshot webcam (dataURL/blob) ou du fichier uploadé + l'ID de l'overlay choisi.
- Fusion des deux images via l'extension **GD** (incluse dans PHP standard, pas de lib externe) :
  `imagecreatefrompng`, `imagecopy`/`imagealphablending`, `imagepng`.
- Sauvegarde via `ImageModel::create`, retour JSON avec l'URL de l'image finale.

**Acceptance** : la fusion se fait bien côté serveur (vérifiable en coupant le JS de preview :
l'image finale doit quand même être correcte), jamais un simple screenshot du canvas client.

---

### TICKET-C5 · P0 · Miniatures + suppression
- Panneau latéral : liste des images de l'utilisateur connecté (via `ImageModel::findByUser`).
- Suppression (`DELETE`/`POST api/image/delete`) avec vérification stricte que
  `image.user_id === session.user_id` avant toute suppression.

**Acceptance** : impossible de supprimer l'image d'un autre utilisateur même en forgeant l'ID
dans la requête (403, pas juste caché côté front).

---

## Épic D — Galerie publique

### TICKET-D1 · P0 · `ApiController` + listing paginé
- Créer `app/controllers/ApiController.php` (référencé par le Router mais absent → crash actuel
  sur `api/like`, `api/comment`, `api/upload`).
- Endpoint GET paginé (≥ 5 images/page), triées par date de création décroissante, public (pas de
  garde de session ici).

---

### TICKET-D2 · P0 · Like / commentaire (auth uniquement)
- `ApiController::like` : toggle like, garde de session (TICKET-A3), 401 si non connecté.
- `ApiController::comment` : création de commentaire, garde de session, échappement en sortie à
  l'affichage (XSS).

---

### TICKET-D3 · P1 · Notification email sur nouveau commentaire
- À chaque commentaire créé, si `users.notify_on_comment` (nouvelle colonne, défaut `true`) est
  vrai pour l'auteur de l'image, envoyer un email (voir TICKET-F1).
- Toggle dans `/profile` (TICKET-A6) pour désactiver.

---

### TICKET-D4 · P1 · Vue galerie front (masonry + pagination)
- Brancher `public/js/masonry.js` sur l'endpoint réel (TICKET-D1) au lieu d'un `<section>` vide
  (`app/views/gallery.php:1`).
- UI like/commentaire, pagination (boutons ou infinie si bonus D-bis).

---

## Épic E — Sécurité transverse (mandatory, mais à traiter en continu)

### TICKET-E1 · P2 · CSRF sur tous les formulaires/POST
- Token CSRF en session, injecté dans chaque formulaire/`fetch`, vérifié côté serveur sur chaque
  action qui modifie de l'état (register, login, like, comment, upload, delete, profil).

### TICKET-E2 · P2 · Échappement systématique en sortie
- Audit de toutes les vues (`app/views/*.php`) : tout affichage de donnée utilisateur (username,
  commentaire, etc.) doit passer par `htmlspecialchars()`.

### TICKET-E3 · P2 · Validation stricte des uploads
- Vérifier le type MIME réel (`finfo`/`getimagesize`, pas juste l'extension du nom de fichier),
  taille max, dimensions raisonnables, re-encodage systématique via GD (empêche l'upload d'un
  fichier PHP déguisé en `.png`).

### TICKET-E4 · P2 · Ownership sur toute donnée "privée"
- Revue de chaque endpoint API : vérifier que `session.user_id` est bien comparé au propriétaire
  de la ressource avant lecture/écriture/suppression (profil, images, préférences).

### TICKET-E5 · P2 · Remplacer le CDN Tailwind par un build compilé
Actuellement `<script src="https://cdn.tailwindcss.com">` dans `header.php`/`index.html` — génère
un warning console en prod (viole la règle "zéro warning", cf. discussion précédente). Passer par
Tailwind CLI → fichier `.css` statique servi comme les autres depuis `public/style/`.

---

## Épic F — Infra mail (dev local)

### TICKET-F1 · P0 (bloque A4/A5/D3) · Service mail en local
Sans SMTP configuré, aucun email (vérification, reset password, notif commentaire) ne peut être
testé en local. Ajouter un conteneur "mail catcher" (ex. **Mailpit** ou **Mailhog**) au
`docker-compose.yml`, configuré comme relais SMTP pour la fonction `mail()` de PHP (`sendmail_path`
dans `php.ini` ou lib SMTP simple). Interface web du catcher exposée sur un port dédié pour lire
les emails envoyés pendant les tests.

**Acceptance** : envoyer un email depuis PHP dans le container `app` doit apparaître dans
l'interface web du mail catcher, sans dépendre d'un vrai fournisseur SMTP.

---

## Épic G — Layout & responsive (V.1 du sujet)

### TICKET-G1 · P1 · Layout header/main/footer partout
Vérifier que gallery, profil, édition ont bien header + main + footer (actuellement seuls
home/login/signup sont couverts par `Controller::render`, `app/controllers/Controller.php:8`).

### TICKET-G2 · P1 · Responsive mobile
Passe responsive sur toutes les pages, y compris la page d'édition (webcam + side panel sur
petit écran).

---

## Épic H — Bonus (uniquement si mandatory 100% parfait)

- **TICKET-H1** — AJAXifier entièrement les échanges (déjà partiellement le cas via `fetch`).
- **TICKET-H2** — Preview live du rendu superposé directement sur le flux webcam (canvas +
  overlay CSS/2D avant capture).
- **TICKET-H3** — Pagination infinie de la galerie.
- **TICKET-H4** — Partage réseaux sociaux (Web Share API ou liens type "share to Twitter/X").
- **TICKET-H5** — Génération d'un GIF animé (ex. capture rafale + encodage GIF côté serveur).

---

## Récapitulatif priorisé (ordre d'attaque recommandé)

1. TICKET-F1 (mail catcher) — débloque tout ce qui touche l'email.
2. TICKET-A1, A2, A3 — login/logout/session fonctionnels.
3. TICKET-A4, A5 — vérification compte + reset password.
4. TICKET-A6 — page profil (`UserController` manquant, crash actuellement).
5. TICKET-B1, B2, B3 — modèles + stockage.
6. TICKET-D1 — `ApiController` (crash actuellement sur 3 routes).
7. TICKET-C1 → C5 — page d'édition complète.
8. TICKET-D2 → D4 — galerie complète.
9. TICKET-E1 → E5 — sécurité transverse (en continu, pas juste à la fin).
10. TICKET-G1, G2 — layout/responsive.
11. TICKET-H1 → H5 — bonus, seulement si tout le reste est parfait.
