<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login admin - Regim</title>
    <style>
        body{margin:0;font-family:Arial,sans-serif;background:#eef2ff;display:grid;place-items:center;min-height:100vh}
        form{width:min(380px,92vw);background:#fff;border:1px solid #ddd;border-radius:8px;padding:24px}
        input{width:100%;box-sizing:border-box;padding:10px;margin:6px 0 14px;border:1px solid #cbd5e1;border-radius:6px}
        button{width:100%;border:0;background:#2563eb;color:#fff;padding:11px;border-radius:6px;cursor:pointer}
        .error{background:#fee2e2;padding:10px;border-radius:6px}.success{background:#dcfce7;padding:10px;border-radius:6px}
        .hint{display:none;margin:-6px 0 12px;color:#047857;font-size:14px}
        .password-wrap{position:relative}
        .password-wrap input{padding-right:92px}
        .toggle-password{position:absolute;right:6px;top:6px;width:auto;background:#e5e7eb;color:#111827;padding:7px 10px}
    </style>
</head>
<body>
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
