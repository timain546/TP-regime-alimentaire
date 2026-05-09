<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
    <form id="MyForm">
        <p>
            Nom: <input type="text" id="nom" required>
        </p>
        <p>
            Email: <input type="email" id="email" required>
        </p>
        <p>
            Genre:
            <select id="genre">
                <option value="homme">Homme</option>
                <option value="femme">Femme</option>
            </select>
        </p>
        <p>
            Mot de passe: <input type="password" id="mdp" required>
        </p>
        <p><button type="submit">Valider</button></p>
    </form>
    <div id="message"></div>

    <script>
        const formulaire = document.getElementById("MyForm");
        formulaire.addEventListener("submit", function(e) {
            e.preventDefault();

            const xhr = new XMLHttpRequest();

            const donnees = {
                "nom": document.getElementById("nom").value,
                "email": document.getElementById("email").value,
                "genre": document.getElementById("genre").value,
                "mdp": document.getElementById("mdp").value
            };

            xhr.open("POST", "http://localhost:8080/api/client/create", true);
            xhr.setRequestHeader("Content-Type", "application/json");

            xhr.onreadystatechange = function() {
                if(xhr.readyState === 4) {
                    if(xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if(response.succes == true) {
                            document.getElementById("message").innerText = "Insertion reussie!!";
                            window.location.href = response.redirect + '/' + response.id;
                        } else {
                            document.getElementById("message").innerText = `Erreur: ${response.message}`;
                        }
                    }
                }
            }
            xhr.send(JSON.stringify(donnees));
        });
    </script>
</body>
</html>