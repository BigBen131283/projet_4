<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\Core\Logger;
    use App\Core\Request;
    use App\Core\Main;
    use App\Repository\BilletDB;
    use App\Repository\CommentsDB;
    use App\Validator\BilletValidator;
    use App\Validator\CommentsValidator;
   

    class BilletsController extends Controller
    { 
        public const ACTION_TYPE_INSERT = 0;
        public const ACTION_TYPE_UPDATE = 1;
        public const ACTION_TYPE_DELETE = 2;
        public const ACTION_LIKE = 1;
        public const ACTION_DISLIKE = 0;
        
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
            $commentsDB = new CommentsDB();

            $logger = new Logger(__CLASS__);
            $validator = new BilletValidator();
            $user = Main::$main->getUsersModel();
            $signaledComments = $commentsDB->getSignaledComments();

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
                        Main::$main->response->redirect('/admin/admin');
                    }    
                }
                $this->render('admin/admin', 'php', 'defaultadmin', ['loggedUser'=>$user, 'signaledComments'=>$signaledComments, 
                                                                    'errorHandler'=>$validator]);
            }
            else
            {
                $this->render('admin/admin', 'php', 'defaultadmin', ['loggedUser'=>$user, 'signaledComments'=>$signaledComments, 
                                                                    'errorHandler'=>$validator]);
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

        // JSON GET --------------------------------------------------------
        public function jsonGetLikes($billetId)
        {
            $billetDB = new BilletDB();
            $result = $billetDB->getCounters($billetId);
            if($result) 
            {
                echo json_encode(['likes'=>$result->thumbs_up,
                'dislikes'=>$result->thumbs_down,  
                'message'=> "Billet counters $billetId retrieved",  
                'error' => false,
                'billetId' => $billetId]);
            }
            else 
            {
                echo json_encode(['likes'=> 0,
                'dislikes'=> 0,  
                'message'=> "Billet counters request failed for ID : $billetId",
                'error' => true,
                'billetId' => $billetId]);
            }
        }

        // JSON GET -------------------------------------------------------
        public function jsonGetMyAdvice($userId, $billetId)
        {
            $billetDB = new BilletDB();
            $result = $billetDB->checkMyAdvice($userId, $billetId);
            if($result) 
            {
                echo json_encode(['result'=>$result['like_it'],
                                  'error' => false,
                                  'status' => true,
                                  'message' => 'you have an advice'
                                ]);
            }
            else
            {
                echo json_encode([
                                  'error' => false,
                                  'status' => false,
                                  'message' => 'you dont have an advice'
                                ]);
            }
        }

        // --------------------------------------------------------
        // Update billet counters and the likes table
        // --------------------------------------------------------
        public function jsonPostUpdateCounter()
        {
            // Check we received all required params
            $params = $this->decodePostRequest();
            $billetId = isset($params["billetId"]) ? $params["billetId"] : '';
            $actionflag = isset($params["actionflag"]) ? $params["actionflag"] : '';
            $userid = isset($params["userid"]) ? $params["userid"] : '';
            if($billetId === '' || $actionflag === '' || $userid === '') 
            {
                echo json_encode(['message'=> "KO : Missing required parameters", 
                                    'error' => true,
                                    'billetID' => $billetId,
                                    'userid' => $userid,
                                    'actionflag' => $actionflag
                                ]);
                return;
            }

            $billetDB = new BilletDB();
            $hasliked = $billetDB->checkHasAnAdvice($userid, $billetId,1);
            $hasdisliked = $billetDB->checkHasAnAdvice($userid, $billetId,0);
            
            // $actionflag = 1, it's a like
            // $actionflag = 0, it's a dislike
            $actiontype = self::ACTION_TYPE_INSERT;
            
            if($actionflag === self::ACTION_LIKE && $hasliked) 
            {    
                $actiontype = self::ACTION_TYPE_DELETE;
            }
            if($actionflag === self::ACTION_DISLIKE && $hasliked) 
            {
                $actiontype = self::ACTION_TYPE_UPDATE;
            }
            if($actionflag === self::ACTION_LIKE && $hasdisliked) 
            {
                $actiontype = self::ACTION_TYPE_UPDATE;
            }
            if($actionflag === self::ACTION_DISLIKE && $hasdisliked) 
            {
                $actiontype = self::ACTION_TYPE_DELETE;
            }
            
            // Final update of billet counters
            $result = $billetDB->UpdateCounters($billetId, $userid, $actionflag, $actiontype);
            if($result) 
            {
                echo json_encode(['message'=> "OK : Billet counters for : $billetId updated", 
                'userid' => $userid,
                'error' => false,
                'billetId' => $billetId]);
            }
            else 
            {
                echo json_encode(['message'=> "KO : Billet update counters for : $billetId", 
                        'error' => true,
                        'billetId' => $billetId]);
            }
            return;  
        }
        // ------------------------------------------------------------------------
        // Get the payload from the JSON formatted post request
        // ------------------------------------------------------------------------
        public function decodePostRequest()  
        {
          $json = file_get_contents('php://input');
          return json_decode($json, true, 16, JSON_OBJECT_AS_ARRAY | JSON_UNESCAPED_UNICODE);
        }    
    }
?>
