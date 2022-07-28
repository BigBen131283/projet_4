<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\Core\Logger;
    use App\Core\Request;
    use App\Core\Main;
    use App\Models\BilletsModel;
    use App\Repository\BilletDB;
    use App\Validator\BilletValidator;

    class BilletsController extends Controller
    {       
        public function index()
        {
            // On instancie le modèle correspondant à la table "billets"
            $billetsModel = new BilletsModel();

            // On va chercher tous les billets publiés (published = 1)
            $published = $billetsModel->findBy(['published' => 1]);

            // On génère la vue
            $this->render('billets/index', 'php', 'default', ['billets' => $published]);
        }

        public function chapitre(int $id)
        {
            // On instancie le modèle
            $billetsModel = new BilletsModel();

            // On va chercher un chapitre
            $billet = $billetsModel->find($id);

            // On envoie à la vue
            $this->render('billets/chapitre', 'php', 'default',compact('billet'));
        }

        public function createBillet()
        {
            $billetsModel = new BilletsModel();
            $request = new Request();

            $logger = new Logger(__CLASS__);
            $validator = new BilletValidator();

            if($request->isPost())
            {
                $body = $request->getBody();
                $errorList = $validator->checkBilletEntries($body);

                if(!$validator->hasError())
                {
                    $billetDB = new BilletDB();
                    
                    if($billetDB->createBillet($body))
                    {
                        // Main::$main->login($credentials->id);
                        Main::$main->response->redirect('/');
                    }    
                }
                $this->render('billets/createbillet', "php", 'default', ['errorHandler' => $validator]);
            }
            else
            {
                $this->render('billets/createbillet', "php", 'default', ['errorHandler' => $validator]);
            }
        }

        public function editBillet($id = null)
        {
            $billetsModel = new BilletsModel();
            $request = new Request();

            $logger = new Logger(__CLASS__);
            $validator = new BilletValidator();

            if($request->isPost())
            {
                $body = $request->getBody();
                $errorList = $validator->checkBilletEntries($body);

                if(!$validator->hasError())
                {
                    $billetDB = new BilletDB();
                    
                    if($billetDB->updateBillet($body))
                    {
                        // Main::$main->login($credentials->id);
                        Main::$main->response->redirect('/');
                    }    
                }
                $this->render('billets/editbillet', "php", 'default', ['errorHandler' => $validator]);
            }
            else
            {
                $billetDB = new BilletDB();
                $data = $billetDB->readBillet($id);
                $validator->addValue('title', $data['title']);
                $validator->addValue('abstract', $data['abstract']);
                $validator->addValue('chapter', $data['chapter']);

                $this->render('billets/editbillet', "php", 'default', ['errorHandler' => $validator]);
            }
        }
    }
?>
