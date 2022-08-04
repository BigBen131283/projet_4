<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\Core\Logger;
    use App\Core\Request;
    use App\Core\Main;
    use App\Models\CommentsModel;
    use App\Repository\CommentsDB;
    use App\Repository\BilletDB;
    use App\Validator\CommentsValidator;

    class CommentsController extends Controller
    {       
        public function createComment($id)
        {
        
            $request = new Request();
            $billetDB = new BilletDB();
            $result = $billetDB->readBillet($id);

            $logger = new Logger(__CLASS__);
            $validator = new CommentsValidator();
            $user = Main::$main->getUsersModel();

            if($request->isPost())
            {
                $body = $request->getBody();
                $body['billetID'] = $id;
                $errorList = $validator->checkCommentsEntries($body);
                if(!$validator->hasError())
                {
                    $commentsDB = new CommentsDB();
                    
                    if($commentsDB->createComment($body))
                    {
                        // Main::$main->login($credentials->id);
                        Main::$main->response->redirect('/');
                    }    
                }
                $this->render('billets/chapitre', "php", 'defaultchapter', ['errorHandler' => $validator, 'loggedUser' => $user, 'billet' => $result]);
            }
            else
            {
                var_dump($validator, $user);
                $this->render('billets/chapitre', "php", 'defaultchapter', ['errorHandler' => $validator,'loggedUser' => $user, 'billet' => $result]);
            }
        }
        
    }
?>