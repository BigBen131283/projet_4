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
        public function chapterlist()
        {
            $billetDB = new BilletDB();
            $result = $billetDB->publishedBillets();

            if(!empty($result))
            {
                $this->render('billets/chapterlist', 'php', 'default', ['billets' => $result]);
            }
        }

        public function chapitre(int $id)
        {
            $billetDB = new BilletDB();
            $result = $billetDB->readBillet($id);
            
            // var_dump($result); die;

            $this->render('billets/chapitre', 'php', 'default',['billet' => $result]);
        }

        public function createBillet()
        {
        
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
                $this->render('billets/editbillet', "php", 'default', ['workInProgress' => $validator]);
            }
            else
            {
                $billetDB = new BilletDB();
                $data = $billetDB->retrieveBillet($id);
                $validator->addValue('title', $data['title']);
                $validator->addValue('abstract', $data['abstract']);
                $validator->addValue('chapter', $data['chapter']);

                $this->render('billets/editbillet', "php", 'default', ['workInProgress' => $validator]);
            }
        }
    }
?>
