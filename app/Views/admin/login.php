<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login admin - Regim</title>
    <link rel="stylesheet" href="<?= base_url('css/admin.css') ?>">
</head>
<body class="admin-login">
    <form method="post" action="<?= site_url('admin/login') ?>">
        <h1>Administration</h1>
        <?php if (session('error')): ?><p class="error"><?= esc(session('error')) ?></p><?php endif ?>
        <?php if (session('success')): ?><p class="success"><?= esc(session('success')) ?></p><?php endif ?>
        <label>Email</label>
        <input id="email" type="email" name="email" value="<?= old('email', 'admin@regim.test') ?>" list="admin-emails" autocomplete="email" required>
        <datalist id="admin-emails">
            <option value="admin@regim.test"></option>
        </datalist>
        <p id="email-hint" class="hint">Email admin reconnu. Vous pouvez saisir le mot de passe.</p>
        <label>Mot de passe</label>
        <div class="password-wrap">
            <input id="password" type="password" name="password" placeholder="Mot de passe" autocomplete="current-password" required>
            <button id="toggle-password" class="toggle-password" type="button">Voir</button>
        </div>
        <button type="submit">Se connecter</button>
    </form>
    <script>
        const email = document.getElementById('email');
        const emailHint = document.getElementById('email-hint');
        const password = document.getElementById('password');
        const togglePassword = document.getElementById('toggle-password');
        const adminEmail = 'admin@regim.test';

        function updateEmailHint() {
            emailHint.style.display = email.value.trim().toLowerCase() === adminEmail ? 'block' : 'none';
        }

        email.addEventListener('input', updateEmailHint);
        updateEmailHint();

        togglePassword.addEventListener('click', () => {
            const hidden = password.type === 'password';
            password.type = hidden ? 'text' : 'password';
            togglePassword.textContent = hidden ? 'Masquer' : 'Voir';
        });
    </script>
</body>
</html>
