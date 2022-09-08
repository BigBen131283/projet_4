<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billet simple pour l'Alaska</title>
    <link rel="icon" type="image/gif" href="/images/favicon.png"/>
    <link rel="stylesheet" href="/styles/mobile/chapterlist.css">
    <link rel="stylesheet" href="/styles/tablet/chapterlist.css" media="screen AND (min-width: 600px)">
    <link rel="stylesheet" href="/styles/desktop/chapterlist.css" media="screen AND (min-width: 1024px)">
    <link rel="stylesheet" href="/styles/mobile/mainmenu.css">
    <link rel="stylesheet" href="/styles/tablet/mainmenu.css" media="screen AND (min-width: 600px)">
    <link rel="stylesheet" href="/styles/desktop/mainmenu.css" media="screen AND (min-width: 1024px)">
    <link rel="stylesheet" href="/styles/desktop/mainmenuLS.css" media="screen AND (min-width: 1550px)">
    <link rel="stylesheet" href="/styles/mobile/footer.css">
    <link rel="stylesheet" href="/styles/tablet/footer.css" media="screen AND (min-width: 600px)">
    <link rel="stylesheet" href="/styles/desktop/footer.css" media="screen AND (min-width: 1024px)">
    <script type="module" src="/scripts/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>



<body class="sitepage">
    <nav class="main_menu">
        <div class="sup">
            <?php if($loggedUser->isLogged()):?>    
                <img src="<?php echo IMAGEROOT.$loggedUser->getProfile_picture() ?>" alt="Photo de profil" class="profile-picture">
                <!-- S'affiche à la place de bonjour tant qu'il n'est pas connecté -->
                <p class="user_name"><?= 'Bonjour '.$loggedUser->getPseudo(); ?></p>
            <?php else:?>
                <img src="/images/profile_pictures/defaultuserpicture.png" alt="Photo de profil" class="profile-picture">
                <a href="/users/login"><ion-icon name="log-in-outline"></ion-icon>Se connecter</a>
            <?php endif;?>
            <div class="menu_toggle"></div>
        </div>
        <ul class="menu_access">
            <li><a href="/#accueil"><ion-icon name="home-outline"></ion-icon>Accueil</a></li>
            <li><a href="/#auteur"><ion-icon name="body-outline"></ion-icon>L'Auteur</a></li>
            <li><a href="/billets/chapterlist"><ion-icon name="book-outline"></ion-icon>L'Aventure</a></li>
            <!-- S'affichent quand le user est connecté -->
            <?php if($loggedUser->isLogged()):?>
                <li><a href="/users/profil"><ion-icon name="person-outline"></ion-icon>Mon Profil</a></li>
                <?php if($loggedUser->isAdmin()):?>
                    <li><a href="/admin/admin"><ion-icon name="settings-outline"></ion-icon>Gestion</a></li>
                <?php endif;?>
                <li><a href="/users/logout"><ion-icon name="log-out-outline"></ion-icon>Se Déconnecter</a></li>
            <?php endif;?>
        </ul>
    </nav>
    <?= $contenu?>

    <footer>
        <div class="contenu-footer">
            <div class="bloc footer-site-plan">
                <h3>Plan du site</h3>
                <ul class="site-plan">
                    <li><a href="/#accueil">Accueil</a></li>
                    <li><a href="/billets/chapterlist">L'aventure</a></li>
                    <li><a href="/users/login">Se connecter</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                </ul>
            </div>
            <div class="bloc footer-goodies">
                <h3>Acheter les autres romans</h3>
                <ul class="goodies">
                    <li><a href="#"><ion-icon name="logo-amazon"></ion-icon>La boutique amazon de Jean</a></li>
                </ul>
            </div>
            <div class="bloc footer-contact">
                <h3>Jägerstedt Publishing</h3>
                <ul class="contact">
                    <li>+46 (55)-666-40-77</li>
                    <li>jagerstedt-publishing@jll.se</li>
                    <li>Västra Trädgårdsgatan 2, 111 53 Stockholm, Sverige</li>
                </ul>
            </div>
            <div class="bloc footer-social-wetwork">
                <h3>Nos réseaux</h3>
                <ul class="social-network">
                    <li><a href="#"><ion-icon name="logo-facebook"></ion-icon>La page facebook</a></li>
                    <li><a href="#"><ion-icon name="logo-youtube"></ion-icon>Nos vidéos</a></li>
                    <li><a href="#"><ion-icon name="logo-twitter"></ion-icon>Le Twitter de l'aventure</a></li>
                    <li><a href="#"><ion-icon name="logo-discord"></ion-icon>Le discord de Jean</a></li>
                </ul>
            </div>
        </div>
        <div class="disclaimer">
            <h2>Designed by BigBen</h2>
            <p>
                Ce site a été réalisé dans le cadre d'une formation et toutes ressemblances avec des 
                personnes existantes seraient totalement fortuites. 
            </p>
        </div>
    </footer>
</body>

</html>