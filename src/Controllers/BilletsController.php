<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\Core\Logger;
    use App\Core\Request;
    use App\Core\Main;
    use App\Models\BilletsModel;
    use App\Repository\BilletDB;
    use App\Repository\CommentsDB;
    use App\Validator\BilletValidator;
    use App\Validator\CommentsValidator;

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
            $commentsDB = new CommentsDB();
            $result = $billetDB->readBillet($id);
            $allComments = $commentsDB->getComments($id);
            $user = Main::$main->getUsersModel();
            $validator = new CommentsValidator();
            
            // var_dump($result); die;

            $this->render('billets/chapitre', 'php', 'defaultchapter',
            ['errorHandler' => $validator,'billet' => $result, 'loggedUser' => $user, 'comments' => $allComments]);
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
                $this->render('billets/editbillet', "php", 'defaultchapter', ['workInProgress' => $validator,'loggedUser' => $user]);
            }
            else
            {
                $billetDB = new BilletDB();
                $data = $billetDB->retrieveBillet($id);
                $validator->addValue('title', $data['title']);
                $validator->addValue('abstract', $data['abstract']);
                $validator->addValue('chapter', $data['chapter']);

                $this->render('billets/editbillet', "php", 'defaultchapter', ['workInProgress' => $validator,'loggedUser' => $user]);
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
   
            $billetDB = new BilletDB();
            
            $updatedBillets = $billetDB->updatePublishedStatus();
            date_default_timezone_set('Europe/Brussels');
            $current = strtotime(date('Y-m-d H:i:s'));
            echo json_encode(['published'=>'done',
                              'updated' => $updatedBillets,
                              'date' => $current]);
        }

        public function likeIt($billetId, $userId)
        {
            // var_dump($userId, $billetId);die;
            $billetDB = new BilletDB();
            $hasLiked = $billetDB->checkHasAnAdvice($userId, $billetId,1);
            $hasDisliked = $billetDB->checkHasAnAdvice($userId, $billetId,0);
            
            $validator = new BilletValidator();
            $commentsDB = new CommentsDB();
            $result = $billetDB->readBillet($billetId);
            $user = Main::$main->getUsersModel();
            $allComments = $commentsDB->getComments($billetId);
            
            if($hasLiked === 0)
            {
                if($billetDB->like($userId, $billetId))
                {
                    $validator->addError('likestatus', "message de remerciement");
                    // $this->render('billets/chapitre', 'php', 'defaultchapter',
                    // ['errorHandler' => $validator,'billet' => $result, 'loggedUser' => $user, 'comments' => $allComments]);
                }
                else
                {
                    $validator->addError('likestatus', "Problème de connexion");    
                }
            }
            else
            {
                $validator->addError('likestatus', "Go to Hell");
            }
            $this->render('billets/chapitre', 'php', 'defaultchapter',
                ['errorHandler' => $validator,'billet' => $result, 'loggedUser' => $user, 'comments' => $allComments]);
        }

        public function dislikeIt($billetId, $userId)
        {
            $billetDB = new BilletDB();
            $hasLiked = $billetDB->checkHasAnAdvice($userId, $billetId,1);
            $hasDisliked = $billetDB->checkHasAnAdvice($userId, $billetId,0);
            
            $validator = new BilletValidator();
            $commentsDB = new CommentsDB();
            $result = $billetDB->readBillet($billetId);
            $user = Main::$main->getUsersModel();
            $allComments = $commentsDB->getComments($billetId);
            
            if($hasDisliked === 0)
            {
                if($billetDB->dislike($userId, $billetId))
                {
                    $validator->addError('likestatus', "Vous n'aimez pas");
                    // $this->render('billets/chapitre', 'php', 'defaultchapter',
                    // ['errorHandler' => $validator,'billet' => $result, 'loggedUser' => $user, 'comments' => $allComments]);
                }
                else
                {
                    $validator->addError('likestatus', "Problème de connexion");    
                }
            }
            else
            {
                $validator->addError('likestatus', "On a compris!");
            }
            $this->render('billets/chapitre', 'php', 'defaultchapter',
                ['errorHandler' => $validator,'billet' => $result, 'loggedUser' => $user, 'comments' => $allComments]);
        }

        public function jsonGetLikes($billetId)
        {
            $billetDB = new BilletDB();
            $logger = new Logger(__CLASS__);
            
            $result = $billetDB->getCounters($billetId);
            // $logger->console($result); die;

            echo json_encode(['likes'=>$result->thumbs_up,
                              'dislikes'=>$result->thumbs_down,  
                              'billetId' => $billetId]);
        }
    }
?>
