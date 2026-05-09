<?php
session_start();

if(!isset($_SESSION['user_id'])){
    $_SESSION['user_id'] = 1;
}

?>
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

        <p>Quels sont vos priorités ? </p>
        <input type="checkbox" id="efficacite" name="priorite" value="efficacite">
        <label for="efficacite">Efficacité</label>

        <input type="checkbox" id="cout" name="priorite" value="cout">
        <label for="cout">Coût</label>

        <input type="checkbox" id="intensite" name="priorite" value="intensite">
        <label for="intensite">Intensité</label>

        <button type="submit">Valider vos choix</button>
    </form>
</body>
</html>