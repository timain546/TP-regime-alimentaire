<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Connexion</h1>
    <form id="MyForm">
        <p>
            Email: <input type="email" id="email" required>
        </p>
        <p>
            Mot de passe: <input type="password" id="mdp" required>
        </p>
        <p><button type="submit">Se connecter</button></p>
    </form>
    <div id="message"></div>
    <p>Ou n'avez vous pas encore de compte? <a href="/client/form">S'inscrire</a></p>

    <!-- Code AJAX pour l'authentification -->
    <script>
        const formulaire = document.getElementById("MyForm");
        formulaire.addEventListener("submit", function(e) {
            e.preventDefault();

            const xhr = new XMLHttpRequest();

            const donnees = {
                "email": document.getElementById("email").value,
                "mdp": document.getElementById("mdp").value
            };

            xhr.open("POST", "http://localhost:8080/api/auth/login", true);
            xhr.setRequestHeader("Content-Type", "application/json");

            document.getElementById("message").innerText = "Connexion...";

            xhr.onreadystatechange = function() {
                if(xhr.readyState === 4) {
                    if(xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if(response.succes == true) {
                            document.getElementById("message").innerText = "Connexion reussie!!";
                            window.location.href = response.redirect;
                        } else {
                            document.getElementById("message").innerText = `Erreur: ${response.message}`;
                        }
                    }
                }
            };

            xhr.send(JSON.stringify(donnees));
        });
    </script>
</body>
</html>