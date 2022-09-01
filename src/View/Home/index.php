<header id="bg" class="header">
    <h1 class="titre">Billet simple pour l'Alaska</h1>
    <p class="author">Une histoire de </p>
    <h2 class="gad">Jean Forteroche</h2>
    <a href="#accueil" class="access-button">Accéder au site</a>
</header>
<div class="site">
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
            <li><a href="#accueil"><ion-icon name="home-outline"></ion-icon>Accueil</a></li>
            <li><a href="#auteur"><ion-icon name="body-outline"></ion-icon>L'Auteur</a></li>
            <li><a href="billets/chapterlist"><ion-icon name="book-outline"></ion-icon>L'Aventure</a></li>
            <!-- S'affichent quand le user est connecté -->
            <?php if($loggedUser->isLogged()):?>
                <li><a href="/users/profil"><ion-icon name="person-outline"></ion-icon>Mon Profil</a></li>
                <?php if($loggedUser->isAdmin()):?>
                    <!-- <li><a href="/billets/createbillet"><ion-icon name="clipboard-outline"></ion-icon>Gestion</a></li> -->
                    <li><a href="/admin/admin"><ion-icon name="settings-outline"></ion-icon>Gestion</a></li>
                <?php endif;?>
                <li><a href="/users/logout"><ion-icon name="log-out-outline"></ion-icon>Se Déconnecter</a></li>
            <?php endif;?>
        </ul>
    </nav>
    <section class="projet" id="accueil">
        <h1>Le Projet :</h1>
        <p>
            C'est une idée un peu folle. Mais c'est aussi une incroyable expérience humaine. Traverser l'Alaska à pied et à traineau d'Anchorage à 
            Prudhoe Bay.<br/><br/>
            Les récits et les films ont façonné notre imaginaire, de Jack London à Christopher Nolan, de Sean Penn à  Didier van Cauwelaert. 
            Je rêvais dans mon enfance des pionniers du Klondike, des animaux sauvages, de la solitude de l'aventurier. Cette terre hostile 
            est un des derniers territoires encore vierge. C'est ici que je vais me perdre, en pleine nature.<br/><br/>
            Je ne vais cependant pas partir seul. Je souhaite vous associer à mon aventure et vous emmener,un peu, avec moi. Découvrez mon périple 
            à travers mon récit que je publierai sur ce Blog.<br/><br/>
            Parce que j'ai toujours apprécié d'échanger avec mes lecteurs vous pourrez réagir à chaque publication. Un peu à la manière d'un roman intéractif,
            devenez le héros, ou l'héroïne, de ce voyage.<br/><br/>
            L'histoire commence donc à Anchorage, après un long voyage en train... Bienvenue au pays des saumons, territoire des ours, et royaume des moustiques... 
        </p>
    </section>
    <section id="auteur">
        <h1>L'Auteur : Jean Forteroche</h1>
            <div class="full-bio">
                <div class="box">
                    <div class="content">
                        <img src="/images/auteur.jpeg" alt="Photo de l'auteur">
                        <h2>A propos de l'auteur<br/><span>Sa vie, son oeuvre</span></h2>
                        <button class="bio_toggle">Biographie</button>
                    </div>
                </div>
                <p class="displayed_bio">
                    Né le 10 mai 1981 dans la banlieue de Neuilly-sur-Seine, Jean Forteroche grandit entre la modeste maison familiale de la Villa Madrid, 
                    le petit pied-à-terre de l'île de Ré et le chalet de Méribel.<br/><br/>
                    A peine âgé de 18 ans son premier roman, Je serai le prochain Rastignac (2001), est un succès qui le propulse sur le devant de la scène. Qualifié de chef d'oeuvre
                    de la pensée du vide, il devient le chef de file des nouveaux penseurs de notre temps.<br/><br/>
                    Ses autres romans, Atomes (2003), Le pas diagonale (2007), Les mûres en hiver (2012), Vers l'été éternel (2017) sont disponibles chez Jägerstedt Publishing. 
                </p>
            </div>
    </section>
    <section id="adventure">
        <h1>L'Aventure</h1>
        <div class="last-post">
            <div class="last-post-content">
                <h2><?= $billet->title?></h2>
                <br/>
                <p class="abstract"><?=$specialFX?></p>
            </div>
        </div>
        <a href="/billets/chapterlist">Lire la suite</a>
    </section>
</div>