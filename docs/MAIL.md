# Service mail (dev local)

Camagru envoie des emails réels (confirmation de compte, reset password, notification de
commentaire — cf. `docs/TICKETS.md` épics A et D). En local, ces emails ne partent nulle part sur
Internet : ils sont interceptés par **Mailpit**, un catcher SMTP avec une interface web, pour
pouvoir les lire sans configurer un vrai fournisseur (Gmail, SendGrid...).

## Comment ça marche

```
PHP mail()  →  msmtp (sendmail local, dans le conteneur app)  →  SMTP :1025  →  Mailpit  →  UI web :8025
```

- Le code PHP n'utilise que la fonction standard `mail()` (contrainte du sujet : pas de lib externe
  type PHPMailer). C'est `msmtp` qui joue le rôle de `sendmail` système et relaie vers Mailpit.
- `mail()` ne "envoie" jamais rien à un vrai destinataire en dev : tout est capturé par Mailpit.

## Fichiers concernés

| Fichier | Rôle |
|---|---|
| `docker-compose.yml` (service `mail`) | Conteneur `axllent/mailpit`, expose `1025` (SMTP) et `8025` (UI web) |
| `Dockerfile` | Installe `msmtp`/`msmtp-mta` dans l'image `app`, copie la config | 
| `docker/msmtprc` | Config msmtp : relaie tout vers `mail:1025`, sans auth ni TLS (Mailpit accepte tout en dev) |
| `docker/php-mail.ini` | Définit `sendmail_path` de PHP vers `msmtp -t -i` |
| `app/core/Mailer.php` | Wrapper PHP autour de `mail()`, utilisé par les controllers (ex. `AuthController::register()`) |

## Utilisation en dev

1. Démarrer normalement : `docker-compose up` (ou `docker compose up`) — le service `mail` démarre
   avec les autres.
2. Ouvrir **http://localhost:8025** pour voir tous les emails envoyés par l'application
   (inscription, reset password, notifications...).
3. Chaque email affiche le rendu HTML, le texte brut, les headers — pratique pour vérifier un lien
   de confirmation sans avoir à le générer à la main.

Pas besoin de vider la boîte manuellement, mais si besoin (ex. avant une démo) :
```bash
curl -X DELETE http://localhost:8025/api/v1/messages
```

## Débuggage

- **Aucun email n'apparaît dans Mailpit** :
  - Vérifier que le conteneur `mail` tourne : `docker compose ps`.
  - Vérifier que `Mailer::send()` retourne bien `true` (sinon `AuthController` logge
    `[AuthController] Failed to send verification email to ...` dans les logs du conteneur `app`).
  - Vérifier que `docker/php-mail.ini` est bien chargé : `docker exec camagru_app php -i | grep sendmail_path`
    doit afficher `/usr/bin/msmtp -t -i`.
- **`mail()` renvoie `false` ou lève un warning PHP** : généralement un souci de résolution DNS du
  hostname `mail` (le service doit être sur le même réseau docker-compose que `app`, ce qui est le
  cas par défaut) ou une permission incorrecte sur `/etc/msmtprc` (doit être lisible, `chmod 644`,
  déjà fait dans le `Dockerfile`).
- **Tester manuellement l'envoi** depuis le conteneur :
  ```bash
  docker exec -it camagru_app php -r 'var_dump(mail("test@example.com", "Test", "Hello depuis Camagru"));'
  ```
  Le mail doit apparaître dans http://localhost:8025 quelques secondes après.

## Important : ça reste un outil de dev

Mailpit **n'envoie jamais réellement d'email** — c'est tout l'intérêt pour le développement (pas
de spam accidentel, pas de compte SMTP à configurer). Pour un vrai déploiement (hors périmètre de
la soutenance 42), il faudrait remplacer le service `mail` par un vrai relais SMTP (ex. un compte
SMTP transactionnel) et adapter `docker/msmtprc` en conséquence — le code PHP applicatif
(`Mailer::send()`, `AuthController`) n'aurait pas besoin de changer.
