<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\Core\Main;
    use App\Repository\BilletDB;
    use App\Repository\CommentsDB;
    use App\Validator\BilletValidator;

    class AdminController extends Controller
    {
        public function admin()
        {
            $user = Main::$main->getUsersModel();
            $commentsDB = new CommentsDB();
            $billetDB = new BilletDB();
            $validator = new BilletValidator();
            ini_set('upload_max_filesize', 5);
            
            $signaledComments = $commentsDB->getSignaledComments();
            $adminBillets = $billetDB->adminBillets();
            // var_dump($signaledComments); die;
            $this->render('admin/admin', 'php', 'defaultadmin', ['loggedUser'=>$user, 'signaledComments'=>$signaledComments, 
                                                                'errorHandler'=>$validator, 'adminBillets'=>$adminBillets]);
        }

        public function acceptComment($commentsId)
        {
            $commentsDB = new CommentsDB();


            if($commentsDB->acceptComment($commentsId))
            {
                $user = Main::$main->getUsersModel();
                $validator = new BilletValidator();
                $billetDB = new BilletDB();
                $adminBillets = $billetDB->adminBillets();

                $signaledComments = $commentsDB->getSignaledComments();
                $this->render('admin/admin', 'php', 'defaultadmin', [
                    'loggedUser'=>$user, 
                    'signaledComments'=>$signaledComments, 
                    'errorHandler'=>$validator, 
                    'adminBillets'=>$adminBillets
                ]);
            }
        }

        public function rejectComment($commentsId)
        {
            $commentsDB = new CommentsDB();
            

            if($commentsDB->rejectComment($commentsId))
            {
                
                $user = Main::$main->getUsersModel();
                $validator = new BilletValidator();
                $billetDB = new BilletDB();
                $adminBillets = $billetDB->adminBillets();

                $signaledComments = $commentsDB->getSignaledComments();
                $this->render('admin/admin', 'php', 'defaultadmin', [
                    'loggedUser'=>$user, 
                    'signaledComments'=>$signaledComments, 
                    'errorHandler'=>$validator, 
                    'adminBillets'=>$adminBillets
                ]);
            }
        }
    }

?>