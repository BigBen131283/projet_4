<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\Core\Main;


    class AdminController extends Controller
    {
        public function admin()
        {
            $user = Main::$main->getUsersModel();

            $this->render('admin/admin', 'php', 'defaultadmin', ['loggedUser'=>$user]);
        }
    }

?>