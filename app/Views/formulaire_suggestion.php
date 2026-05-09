<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="/suggestion/voir" method="post">
        <p>Quel objectif vous voulez atteindre ? </p>

        <input type="radio" id="augmenter" name="objectif" value="augmenter">
        <label for="augmenter">Augmenter de poids</label>
        
        <input type="radio" id="reduire" name="objectif" value="reduire">
        <label for="reduire">Réduire de poids</label>

        <input type="radio" id="imc_ideal" name="objectif" value="imc_ideal">
        <label for="imc_ideal">Atteindre l'IMC idéal</label>

        <div id="kgobjectif" style="display:none; margin-top: 12px;">
            <label for="kg">Combien de kg ?</label>
            <input type="number" id="kg" name="kg" min="1" step="0.1" placeholder="Ex: 5">
        </div>

        <p>Quels sont vos priorités ? </p>
        <input type="checkbox" id="efficacite" name="priorite" value="efficacite">
        <label for="efficacite">Efficacité</label>

        <input type="checkbox" id="cout" name="priorite" value="cout">
        <label for="cout">Coût</label>

        <input type="checkbox" id="intensite" name="priorite" value="intensite">
        <label for="intensite">Intensité</label>

        <button type="submit">Valider vos choix</button>
    </form>

    <script>
        const objectifs = document.querySelectorAll('input[name="objectif"]');
        const kgobjectif = document.getElementById('kgobjectif');
        const kgInput = document.getElementById('kg');

        function afficherkgInput() {
            const objectifSelectionne = document.querySelector('input[name="objectif"]:checked');
            const afficherKg = objectifSelectionne && (objectifSelectionne.value === 'augmenter' || objectifSelectionne.value === 'reduire');

            kgobjectif.style.display = afficherKg ? 'block' : 'none';
            kgInput.required = afficherKg;

            if (!afficherKg) {
                kgInput.value = '';
            }
        }

        objectifs.forEach(function (objectif) {
            objectif.addEventListener('change', afficherkgInput);
        });
    </script>
</body>
</html>