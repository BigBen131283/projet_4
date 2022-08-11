<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\Core\Main;
    use App\Repository\CommentsDB;

    class AdminController extends Controller
    {
        public function admin()
        {
            $user = Main::$main->getUsersModel();
            $commentsDB = new CommentsDB();
            
            $signaledComments = $commentsDB->getSignaledComments();
            // var_dump($signaledComments); die;
            $this->render('admin/admin', 'php', 'defaultadmin', ['loggedUser'=>$user, 'signaledComments'=>$signaledComments]);
        }

        public function acceptComment($commentsId)
        {
            $commentsDB = new CommentsDB();
            
            $user = Main::$main->getUsersModel();

            if($commentsDB->acceptComment($commentsId))
            {
                $signaledComments = $commentsDB->getSignaledComments();
                $this->render('admin/admin', 'php', 'defaultadmin', ['loggedUser'=>$user, 'signaledComments'=>$signaledComments]);
            }
        }

        public function rejectComment($commentsId)
        {
            $commentsDB = new CommentsDB();
            
            $user = Main::$main->getUsersModel();

            if($commentsDB->rejectComment($commentsId))
            {
                $signaledComments = $commentsDB->getSignaledComments();
                $this->render('admin/admin', 'php', 'defaultadmin', ['loggedUser'=>$user, 'signaledComments'=>$signaledComments]);
            }
        }
    }

?>