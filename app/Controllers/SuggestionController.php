<?php
namespace App\Controllers;
use App\Models\Regime;
use App\Models\Activite;
use App\Models\Combinaison;
use App\Models\Client;
use App\Models\SanteModel;

class SuggestionController extends BaseController {
    
    private $regimeModel;
    private $frontOfficeService;

    public function __construct() {
        $this->regimeModel = model('Regime');
        $this->frontOfficeService = new \App\Services\FrontOfficeService();
    }

    public function index() {
        return view('formulaire_suggestion');
    }

    public function voir() {
        if (!session()->get('is_connected')) {
            return redirect()->to(base_url('auth/login'));
        }

        $client = session()->get('client');
        $objectif = (string) $this->request->getPost('objectif');
        $kg = (float) $this->request->getPost('kg');
        $priorites = (array) $this->request->getPost('priorite');

        if (!in_array($objectif, ['augmenter', 'reduire', 'imc_ideal'], true)) {
            return redirect()->to(base_url('suggestion'));
        }

        if (in_array($objectif, ['augmenter', 'reduire'], true) && $kg <= 0) {
            return redirect()->to(base_url('suggestion'));
        }
        $clientModel = new Client();
        $activiteModel = new Activite();
        $combinaisonModel = new Combinaison();
        $poids_user = $clientModel->get_poids_actuel($client['id_client']);
        $poids_cible = 0;
        
        if ($objectif == 'augmenter') {
            $poids_cible = $kg;
        } elseif ($objectif == 'reduire') {
            $poids_cible = -$kg;
        } elseif ($objectif == 'imc_ideal') {
            $taille_user = $clientModel->get_taille($client['id_client']);
            $taille_user_modif = $taille_user / 100;
            $imc_actuel = $poids_user / ($taille_user_modif * $taille_user_modif);
            $poids_cible = (22.5 * $poids_user) / $imc_actuel - $poids_user;
        }

        $regimeModelAll = new Regime();
        $regimesCandidats = $regimeModelAll->getAll();
        $activitesCandidats = $activiteModel->getAll();

        // Filtre simple par objectif pour réduire l'espace de recherche.
        $regimesCandidats = array_values(array_filter($regimesCandidats, static function ($r) use ($objectif) {
            $obj = $r['objectif'] ?? 'imc_ideal';
            return $obj === $objectif || $obj === 'imc_ideal';
        }));

        $activitesCandidats = array_values(array_filter($activitesCandidats, static function ($a) use ($objectif) {
            $obj = $a['objectif'] ?? 'imc_ideal';
            return $obj === $objectif || $obj === 'imc_ideal';
        }));

        $meilleure_combinaison = (new Combinaison())->get_meilleure_combinaison_robuste(
            $regimesCandidats,
            $activitesCandidats,
            $poids_cible,
            in_array('efficacite', $priorites, true) ? 0.5 : 0.2,
            in_array('cout', $priorites, true) ? 0.3 : 0.1,
            in_array('intensite', $priorites, true) ? 0.2 : 0.1
        );

        $regimes = $meilleure_combinaison['regimes'] ?? [];
        $activites = $meilleure_combinaison['activites'] ?? [];
        
        $estUnGain = (float) $poids_cible >= 0;
        $poidsCibleAbs = abs((float) $poids_cible);
        $goldActif = !empty($client['is_gold']);
        $remiseGold = $goldActif ? 0.15 : 0;
        $combinaisonModel = new Combinaison();
        $estimationJours = $combinaisonModel->get_efficacite_combinaison($regimes, $activites, $poidsCibleAbs);

        $prixBaseEstime = (float) $this->regimeModel->get_cout_totals_regimes($regimes, $estimationJours);
        $prixFinalEstime = (float) $this->regimeModel->cout_plus_gold($regimes, $estimationJours, $remiseGold);

        $data = [
            'combinaison' => $meilleure_combinaison,
            'poids_cible' => $poids_cible,
            'poids_cible_abs' => $poidsCibleAbs,
            'est_un_gain' => $estUnGain,
            'objectif' => $objectif,
            'gold_actif' => $goldActif,
            'remise_gold' => (int)($remiseGold * 100),
            'estimation_jours' => $estimationJours,
            'prix_base_estime' => $prixBaseEstime,
            'prix_final_estime' => $prixFinalEstime,
            'client' => $client,
        ];

        $santeModel = new SanteModel();
        $sante = $santeModel->where('id_client', $client['id_client'])
            ->orderBy('date', 'DESC')
            ->first();
        $data['sante'] = $sante ?? [];

        session()->set('suggestion_pdf_data', $data);

        return view('suggestion_resultat', $data);
    }

    public function pdf() {
        if (!session()->get('is_connected')) {
            return redirect()->to(base_url('auth/login'));
        }

        $data = session()->get('suggestion_pdf_data');
        if (empty($data)) {
            return redirect()->to(base_url('suggestion'));
        }

        if (!class_exists('\\Mpdf\\Mpdf')) {
            return $this->response->setStatusCode(500)->setBody('mPDF n\'est pas installe. Installez-le avec: composer require mpdf/mpdf');
        }

        $cssPath = FCPATH . 'css/suggestion_pdf.css';
        $css = is_file($cssPath) ? (string) file_get_contents($cssPath) : '';
        $html = view('suggestion_pdf', $data);
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => WRITEPATH . 'cache',
            'mode' => 'utf-8',
            'format' => 'A4',
        ]);

        if ($css !== '') {
            $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        }
        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
        $fileName = 'suggestion_' . date('Ymd_His') . '.pdf';
        $i = 0;
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->setBody($mpdf->Output($fileName, 'S'));
    }
}