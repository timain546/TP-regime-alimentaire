<?php
namespace App\Controllers;
use app\Services\FrontOfficeService;
use app\Model\Code;
class PorteMonnaieController extends BaseController{
    public function index(){
        $client = session()->get('client');
        $idclient = $client['id_client'];
        $frontofficeservice = new FrontOfficeService();
        $solde_client = $frontofficeservice->getSoldeClient($idclient);

        return view('porte_monnaie', ['solde' => $solde_client]);
    }
    public function verifier_code(){
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }
        
        $json = $this->request->getJSON();
        $code_recharge = trim((string)($json->code ?? ''));
        $client = session()->get('client');
        
        if (!$code_recharge) {
            return $this->response->setJSON(['success' => false, 'message' => 'Code vide.']);
        }
        
        $codeModel = new Code();
        if($codeModel->code_exists($code_recharge)){
            if($codeModel->code_deja_utilise($code_recharge)){
                return $this->response->setJSON(['success' => false, 'message' => 'Ce code a déjà été utilisé.']);
            } else {
                $code_info = $codeModel->get_info_code($code_recharge);
                if ($code_info) {
                    $frontOfficeService = new FrontOfficeService();
                    $frontOfficeService->rechargerSoldeClient($client['id_client'], $code_info['valeur']);
                    $codeModel->db->table('CodeRecharge')
                        ->where('code', $code_recharge)
                        ->update(['is_utilise' => 1, 'id_client' => $client['id_client'], 'date_utilisation' => date('Y-m-d H:i:s')]);
                    return $this->response->setJSON(['success' => true, 'message' => 'Code valide ! Votre solde a été rechargé de ' . $code_info['valeur'] . ' gold.']);
                } else {
                    return $this->response->setJSON(['success' => false, 'message' => 'Code invalide.']);
                }
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Code inexistant.']);
        }
    }
}
?>