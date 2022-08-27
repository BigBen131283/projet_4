<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Main;
use App\Repository\AdminDB;
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
        $AdminDb = new AdminDB();
        ini_set('upload_max_filesize', 5);
        
        $signaledComments = $commentsDB->getSignaledComments();
        $adminBillets = $billetDB->adminBillets();
        $statistics = $AdminDb->siteStatistics();
        // var_dump($signaledComments); die;
        $this->render('admin/admin', 'php', 'defaultadmin', [
            'loggedUser'=>$user,
            'signaledComments'=>$signaledComments,
            'errorHandler'=>$validator,
            'adminBillets'=>$adminBillets,
            'statistics'=>$statistics
        ]);
    }

    public function acceptComment($commentsId)
    {
        $commentsDB = new CommentsDB();


        if($commentsDB->acceptComment($commentsId))
        {
            $user = Main::$main->getUsersModel();
            $validator = new BilletValidator();
            $billetDB = new BilletDB();
            $AdminDb = new AdminDB();
            $statistics = $AdminDb->siteStatistics();
            $adminBillets = $billetDB->adminBillets();

            $signaledComments = $commentsDB->getSignaledComments();
            $this->render('admin/admin', 'php', 'defaultadmin', [
                'loggedUser'=>$user, 
                'signaledComments'=>$signaledComments, 
                'errorHandler'=>$validator, 
                'adminBillets'=>$adminBillets,
                'statistics'=>$statistics
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
            $AdminDb = new AdminDB();
            $adminBillets = $billetDB->adminBillets();
            $statistics = $AdminDb->siteStatistics();

            $signaledComments = $commentsDB->getSignaledComments();
            $this->render('admin/admin', 'php', 'defaultadmin', [
                'loggedUser'=>$user, 
                'signaledComments'=>$signaledComments, 
                'errorHandler'=>$validator, 
                'adminBillets'=>$adminBillets,
                'statistics'=>$statistics
            ]);
        }
    }

    public function jsonGetStats()
    {
        $AdminDb = new AdminDB();
        $statistics = $AdminDb->siteStatistics();

        if($statistics !== null)
        {
            echo json_encode([
                'message'=>'Statistics',
                'allCounters' => $statistics
            ]);
            http_response_code(200);
        }
        else
        {
            echo json_encode([
                'message'=>'Erreur lors de la récupération des données'
            ]);
            http_response_code(500);
        }
    }
}

?>