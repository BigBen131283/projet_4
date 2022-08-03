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
            $user = Main::$main->getUsersModel();

            if(!empty($result))
            {
                $this->render('billets/chapterlist', 'php', 'defaultadventure', ['billets' => $result, 'loggedUser' => $user]);
            }
        }

        public function chapitre(int $id)
        {
            $billetDB = new BilletDB();
            $result = $billetDB->readBillet($id);
            $user = Main::$main->getUsersModel();
            
            // var_dump($result); die;

            $this->render('billets/chapitre', 'php', 'defaultadventure',['billet' => $result, 'loggedUser' => $user]);
        }

        public function createBillet()
        {
        
            $request = new Request();

            $logger = new Logger(__CLASS__);
            $validator = new BilletValidator();
            $user = Main::$main->getUsersModel();

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
                $this->render('billets/createbillet', "php", 'defaultadventure', ['errorHandler' => $validator, 'loggedUser' => $user]);
            }
            else
            {
                $this->render('billets/createbillet', "php", 'defaultadventure', ['errorHandler' => $validator,'loggedUser' => $user]);
            }
        }

        public function editBillet($id = null)
        {
            $request = new Request();

            $logger = new Logger(__CLASS__);
            $validator = new BilletValidator();
            $user = Main::$main->getUsersModel();

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
                $this->render('billets/editbillet', "php", 'defaultadventure', ['workInProgress' => $validator,'loggedUser' => $user]);
            }
            else
            {
                $billetDB = new BilletDB();
                $data = $billetDB->retrieveBillet($id);
                $validator->addValue('title', $data['title']);
                $validator->addValue('abstract', $data['abstract']);
                $validator->addValue('chapter', $data['chapter']);

                $this->render('billets/editbillet', "php", 'defaultadventure', ['workInProgress' => $validator,'loggedUser' => $user]);
            }
        }

        public function deleteBillet($id)
        {
            $billetDB = new BilletDB();

            if($id)
            {
                $billetDB->deleteBillet($id);
            }
            $this->chapterlist();
        }

        public function checkPublishStatus()
        {
            // return json_encode(['published'=>'done']);
            date_default_timezone_set('Europe/Brussels');
            $current = strtotime(date('Y-m-d H:i:s'));
            echo json_encode(['published'=>'done',
                              'updated' => 15,
                              'date' => $current]);
        }
    }
?>
