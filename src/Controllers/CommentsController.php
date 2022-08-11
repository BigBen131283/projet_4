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
            $commentsDB = new CommentsDB();
            $allComments = $commentsDB->getComments($id);

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
                        Main::$main->response->redirect('/billets/chapitre/'.$id);
                    }    
                }
                $this->render('billets/chapitre', "php", 'defaultchapter', 
                        ['errorHandler' => $validator, 'loggedUser' => $user, 'billet' => $result, 'comments' => $allComments]);
            }
            else
            {
                var_dump($validator, $user);
                $this->render('billets/chapitre', "php", 'defaultchapter', 
                        ['errorHandler' => $validator, 'loggedUser' => $user, 'billet' => $result, 'comments' =>  $allComments]);
            }
        }

        public function signalComment($billetId, $commentsId)
        {
            $commentsDB = new CommentsDB();
            $billetDB = new BilletDB();
            $validator = new CommentsValidator();

            $result = $billetDB->readBillet($billetId);
            
            $user = Main::$main->getUsersModel();

            if($commentsDB->signalComment($commentsId))
            {
                $allComments = $commentsDB->getComments($billetId);
                $this->render('billets/chapitre', "php", 'defaultchapter', 
                        ['errorHandler' => $validator, 'loggedUser' => $user, 'billet' => $result, 'comments' => $allComments]);
            }
        }
    }
?>