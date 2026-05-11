<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggestion - Objectif</title>
    <link rel="stylesheet" href="<?= base_url('css/suggestion_form.css') ?>">
</head>
<body>
    <div class="page">
        <section class="hero">
            <h1>Définir mon objectif</h1>
            <p>
                Choisissez votre objectif puis vos priorités. L'algorithme proposera une combinaison de régimes et d'activités adaptée.
            </p>
        </section>

        <section class="panel">
            <form class="form" action="<?= base_url('suggestion/voir') ?>" method="post">
                <div class="block">
                    <h2>Objectif</h2>
                    <div class="options">
                        <div class="option">
                            <input type="radio" id="objectif_augmenter" name="objectif" value="augmenter" required>
                            <label for="objectif_augmenter">
                                Augmenter de poids
                                <span class="hint">Prise de masse progressive.</span>
                            </label>
                        </div>

                        <div class="option">
                            <input type="radio" id="objectif_reduire" name="objectif" value="reduire" required>
                            <label for="objectif_reduire">
                                Réduire de poids
                                <span class="hint">Perte de poids et équilibre.</span>
                            </label>
                        </div>

                        <div class="option">
                            <input type="radio" id="objectif_imc" name="objectif" value="imc_ideal" required>
                            <label for="objectif_imc">
                                Atteindre l'IMC idéal
                                <span class="hint">Stabilisation vers un IMC normal.</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div id="kgobjectif" class="field" style="display:none;">
                    <label for="kg">Combien de kg ?</label>
                    <input type="number" id="kg" name="kg" min="0.1" step="0.1" placeholder="Ex: 5">
                </div>

                <div class="block">
                    <h2>Priorités</h2>
                    <div class="options">
                        <div class="option">
                            <input type="checkbox" id="prio_efficacite" name="priorite[]" value="efficacite">
                            <label for="prio_efficacite">
                                Efficacité
                                <span class="hint">Objectif atteint plus rapidement.</span>
                            </label>
                        </div>

                        <div class="option">
                            <input type="checkbox" id="prio_cout" name="priorite[]" value="cout">
                            <label for="prio_cout">
                                Coût
                                <span class="hint">Programme moins cher.</span>
                            </label>
                        </div>

                        <div class="option">
                            <input type="checkbox" id="prio_intensite" name="priorite[]" value="intensite">
                            <label for="prio_intensite">
                                Intensité
                                <span class="hint">Activités moins difficiles.</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">Voir la suggestion</button>
                    <a class="btn btn-secondary" href="<?= base_url('/') ?>">Retour</a>
                </div>
            </form>
        </section>
    </div>

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

        // État initial.
        afficherkgInput();
    </script>
</body>
</html>