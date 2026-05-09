<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Information santé</title>
</head>
<body>
    <form id="MyForm">
        <p><input type="hidden" id="id_client" value="<?= $identifiant_client ?>"></p>
        <p>
            taille(m): <input type="number" id="taille" required>
        </p>
        <p>
            poids(kg): <input type="number" id="poids" required>
        </p>
        <p><button type="submit">VALIDER</button></p>
    </form>
    <div id="message"></div>

    <script>
        const formulaire = document.getElementById("MyForm");
        formulaire.addEventListener("submit", function(e) {
            e.preventDefault();

            const xhr = new XMLHttpRequest();

            const donnees = {
                "id_client": document.getElementById("id_client").value,
                "taille": document.getElementById("taille").value,
                "poids": document.getElementById("poids").value
            };

            xhr.open("POST", "http://localhost:8080/api/sante/create", true);
            xhr.setRequestHeader("Content-Type", "application/json");

            xhr.onreadystatechange = function() {
                if(xhr.readyState === 4) {
                    if(xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if(response.succes == true) {
                            document.getElementById("message").innerText = "Insertion reussie!!";
                            window.location.href = response.redirect;
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