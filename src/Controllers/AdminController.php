<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\Core\Main;
    use App\Repository\CommentsDB;
    use App\Validator\BilletValidator;

    class AdminController extends Controller
    {
        public function admin()
        {
            $user = Main::$main->getUsersModel();
            $commentsDB = new CommentsDB();
            $validator = new BilletValidator();
            
            $signaledComments = $commentsDB->getSignaledComments();
            // var_dump($signaledComments); die;
            $this->render('admin/admin', 'php', 'defaultadmin', ['loggedUser'=>$user, 'signaledComments'=>$signaledComments, 
                                                                'errorHandler'=>$validator]);
        }

        public function acceptComment($commentsId)
        {
            $commentsDB = new CommentsDB();
            
            $user = Main::$main->getUsersModel();
            $validator = new BilletValidator();

            if($commentsDB->acceptComment($commentsId))
            {
                $signaledComments = $commentsDB->getSignaledComments();
                $this->render('admin/admin', 'php', 'defaultadmin', ['loggedUser'=>$user, 'signaledComments'=>$signaledComments, 
                                                                    'errorHandler'=>$validator]);
            }
        }

        public function rejectComment($commentsId)
        {
            $commentsDB = new CommentsDB();
            
            $user = Main::$main->getUsersModel();
            $validator = new BilletValidator();

            if($commentsDB->rejectComment($commentsId))
            {
                $signaledComments = $commentsDB->getSignaledComments();
                $this->render('admin/admin', 'php', 'defaultadmin', ['loggedUser'=>$user, 'signaledComments'=>$signaledComments, 
                                                                    'errorHandler'=>$validator]);
            }
        }
    }

?>