<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\Core\Logger;
    use App\Core\Request;
    use App\Core\Main;
    use App\Repository\BilletDB;
    use App\Repository\CommentsDB;
    use App\Repository\AdminDB;
    use App\Validator\BilletValidator;
    use App\Validator\CommentsValidator;
    use Exception;
   

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
            // var_dump($result);die;

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
            $billetDB = new BilletDB();
            $AdminDb = new AdminDB();

            $logger = new Logger(__CLASS__);
            $validator = new BilletValidator();
            $user = Main::$main->getUsersModel();
            $signaledComments = $commentsDB->getSignaledComments();
            $adminBillets = $billetDB->adminBillets();
            $statistics = $AdminDb->siteStatistics();

            if($request->isPost())
            {
                $filename = $_FILES["chapter_picture"]["name"];
                $filetype = $_FILES["chapter_picture"]["type"];
                
                $body = $request->getBody();
                $body["chapter_picture"] = $filename.'.'.$filetype;
                $errorList = $validator->checkBilletEntries($body);

                if(!$validator->hasError())
                {
                    $billetDB = new BilletDB();
                    $newfilename = $this->uploadImage($validator);
                    if(!$validator->hasError())
                    {
                        $body["chapter_picture"] = $newfilename;
                        if($billetDB->createBillet($body))
                        {
                            // Main::$main->login($credentials->id);
                            Main::$main->response->redirect('/admin/admin');
                        }
                    }    
                }
                $this->render('admin/admin', 'php', 'defaultadmin', [
                    'loggedUser'=>$user, 
                    'signaledComments'=>$signaledComments, 
                    'errorHandler'=>$validator, 
                    'adminBillets'=>$adminBillets,
                    'statistics'=>$statistics
                ]);
            }
            else
            {
                $this->render('admin/admin', 'php', 'defaultadmin', [
                    'loggedUser'=>$user, 
                    'signaledComments'=>$signaledComments, 
                    'errorHandler'=>$validator, 
                    'adminBillets'=>$adminBillets,
                    'statistics'=>$statistics
                ]);
            }
        }

        public function editBillet($id = null)
        {
            $request = new Request();
            $commentsDB = new CommentsDB();
            $billetDB = new BilletDB();
            $AdminDb = new AdminDB();

            $logger = new Logger(__CLASS__);
            $validator = new BilletValidator();
            $user = Main::$main->getUsersModel();
            $signaledComments = $commentsDB->getSignaledComments();
            $adminBillets = $billetDB->adminBillets();
            $statistics = $AdminDb->siteStatistics();

            if($request->isPost())
            {                
                $filename = $_FILES["chapter_picture"]["name"];
                $filetype = $_FILES["chapter_picture"]["type"];
                
                $body = $request->getBody();
                $body["chapter_picture"] = $filename.'.'.$filetype;
                // Set the publish date to NOW if the user did not changed it 
                // because it's most probably in the past now !! 
                date_default_timezone_set('Europe/Brussels');
                $previouspubdate = strtotime($body["publish_at"]);
                $current = strtotime(date('Y-m-d H:i:s'));
                if($current > $previouspubdate) {
                    $body["publish_at"] = date('Y-m-d H:i:s');
                }
                $errorList = $validator->checkBilletEntries($body);

                if(!$validator->hasError())
                {
                    // Get the current image in case the user did not change it
                    $editedbillet = $billetDB->retrieveBillet($id);
                    $previousimage = $editedbillet["chapter_picture"];
                    $newfilename = $this->uploadImage($validator);

                    // If no image selected by user, use current
                    $imagechanged = false;
                    if(!$validator->hasError())
                    {
                        $body["chapter_picture"] = $newfilename;
                        $imagechanged = true;
                    }
                    else 
                    { // User did not change the image, so get the current one                        
                        $body["chapter_picture"] = $previousimage;
                    }
                    
                    $body['id'] = $id;
                    
                    if($billetDB->updateBillet($body))
                    {
                        if($imagechanged)
                        {
                            try 
                            {
                                unlink(ROOT."/public".IMAGEROOTCHAPTER.$previousimage);
                            }
                            catch(Exception $e) 
                            {
                                $logger->console('Cannot remove file');
                            }                        
                        }
                        Main::$main->response->redirect('/admin/admin');
                    }
                }
                $this->render('admin/admin', 'php', 'defaultadmin', [
                    'loggedUser'=>$user, 
                    'signaledComments'=>$signaledComments, 
                    'errorHandler'=>$validator, 
                    'adminBillets'=>$adminBillets,
                    'statistics'=>$statistics
                ]);
            }
        }

        protected function uploadImage($validator)
        {
            
            if($_FILES['chapter_picture']['error'] === UPLOAD_ERR_OK) 
            { 
                $filename = $_FILES["chapter_picture"]["name"];
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); 
                // $logger->console('*** Uploaded file :'.$filename.'.'.$filetype);
     
                
                $target_dir = "/images/chapter_pictures";
                $target_file = $target_dir .'\/'.$_FILES["chapter_picture"]["name"];
               
                // On génère un nom unique
                $newname = md5(uniqid());
                // On génère le chemin complet
                $newfilename = ROOT."/public".IMAGEROOTCHAPTER."$newname.$extension";

                // On déplace le fichier de tmp à uploads en le renommant
                if(!move_uploaded_file($_FILES["chapter_picture"]["tmp_name"], $newfilename))
                {
                    $validator->addError("chapter_picture", "Une erreur est survenue lors de l'enregistrement du fichier.");
                    return;
                }
                else
                {   
                    chmod($newfilename, 0644);  
                    return "$newname.$extension";
                }
            }
            else 
            {
                if($_FILES['chapter_picture']['error'] === UPLOAD_ERR_NO_FILE)
                {
                    $validator->addError("chapter_picture", "Merci de choisir une photo.");
                    return;
                }
                if($_FILES['chapter_picture']['error'] === UPLOAD_ERR_INI_SIZE)
                {
                    $validator->addError("chapter_picture", "Fichier trop volumineux (Limite Serveur 5MB).");
                    return;
                }
            }
            return;
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

        public function jsonDeleteBillet()
        {
            $params = $this->decodePostRequest();
            if(!isset($params["billetId"])) 
            {
                echo json_encode([  
                    'message'=> "ID du billet non communiqué.",  
                    'error' => true
                ]);
                http_response_code(400);    // Bad request
                return;
            }
            $billetId = $params["billetId"];
            $billetDB = new BilletDB();
            $result = $billetDB->deleteBillet($billetId);
            /*
                echo json_encode([  
                    'message'=> "Debug",  
                    'error' => false,
                    'json payload' => $params,
                    'billetid' => $params["billetId"],
                    'db action status' => $result
                ]);
                http_response_code(200);    // All is fine
                return;
            */
            if( $result === 1)
            {
                echo json_encode([  
                    'message'=> "Billet $billetId effacé.",  
                    'error' => false
                ]);
                http_response_code(200);    // All is fine
            }
            else
            {
                echo json_encode([  
                    'message'=> "Impossible d'effacer le billet ID : $billetId",  
                    'error' => true
                ]);
                http_response_code(500); // Server problem. Maybe a DB error
            }
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

                // JSON GET --------------------------------------------------------
                public function jsonGetBillet($billetId)
                {
                    $billetDB = new BilletDB();
                    $result = $billetDB->readBillet($billetId);
                    if($result) 
                    {
                        echo json_encode([
                        'billetId' => $result->id,
                        'title' => $result->title,
                        'abstract' => html_entity_decode(stripslashes($result->abstract)),
                        'chapter' => html_entity_decode(stripslashes($result->chapter)),
                        'publish_at' => $result->publish_at,
                        'chapter_picture' => $result->chapter_picture,
                        'message'=> "Billet reçu : $billetId",
                        'error' => false
                        ]);
                    }
                    else 
                    {
                        echo json_encode([  
                        'message'=> "Billet counters request failed for ID : $billetId",
                        'error' => true
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
