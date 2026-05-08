# Regim - Projet S4

Application CodeIgniter 4 pour la selection de regimes alimentaires.

## Installation locale

1. Demarrer Apache et MySQL depuis XAMPP.
2. Importer la base :

```bash
/opt/lampp/bin/mysql -u root < database/regim.sql
```

3. Verifier la configuration dans `.env` :

```ini
database.default.database = regim
database.default.username = root
database.default.password =
```

4. Ouvrir le back-office :

```text
http://localhost/S4/regim/public/admin/login
```

Ou avec le serveur CodeIgniter :

```bash
php spark serve --host 127.0.0.1 --port 8091
```

## Compte admin de test

- Email : `admin@regim.test`
- Mot de passe : `admin123`

## Partie realisee

- Script SQL complet avec donnees minimales.
- Configuration CodeIgniter 4 et base MySQL.
- Login admin avec session separee.
- Filtre de protection des routes admin.
- Dashboard avec graphes et statistiques.
- CRUD regimes avec validation `viande + poisson + volaille = 100`.
- CRUD activites sportives.
- Generation et validation des codes wallet.
- Historique des codes utilises.
- CRUD simple des parametres globaux.

## Note Git

Le dossier `.git` present dans ce workspace est vide et en lecture seule. Il faut le supprimer ou le remplacer hors sandbox, puis executer :

```bash
git init --initial-branch=main
git add .
git commit -m "Initialise CodeIgniter back office"
git checkout -b dev
```
