<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\Models\BilletsModel;

    class BilletsController extends Controller
    {
        public function index()
        {
            // On instancie le modèle correspondant à la table "billets"
            $billetsModel = new BilletsModel;

            // On va chercher tous les billets publiés (published = 1)
            $published = $billetsModel->findBy(['published' => 1]);

            // On génère la vue
            $this->render('billets/index', 'php', 'default', ['billets' => $published]);
        }

        public function chapitre(int $id)
        {
            // On instancie le modèle
            $billetsModel = new BilletsModel;

            // On va chercher un chapitre
            $billet = $billetsModel->find($id);

            // On envoie à la vue
            $this->render('billets/chapitre', 'php', 'default',compact('billet'));
        }
    }
?>
