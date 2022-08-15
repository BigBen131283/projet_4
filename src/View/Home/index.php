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
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nulla nesciunt error quaerat, magni, 
            repellat accusantium recusandae quidem nihil, voluptatem corporis iste necessitatibus officiis consequatur! 
            Corporis at recusandae laboriosam labore adipisci odio deleniti qui nobis unde modi est veritatis amet, 
            exercitationem suscipit quas et repellendus, molestiae minus? Debitis consequuntur officiis, 
            possimus reiciendis fugiat minima architecto praesentium obcaecati mollitia quos? <br/><br/>
            Odit, autem est corrupti quae eius doloribus consequatur voluptatem. Non iusto est velit. 
            Quaerat iure ut temporibus laborum sapiente minima officiis eum numquam repudiandae, exercitationem maxime 
            at enim consequatur voluptate libero voluptates aspernatur sequi, dolore cumque sed nam corrupti id 
            nulla recusandae! Inventore cumque, molestias repudiandae sapiente enim quo! Corrupti architecto rem dolores.<br/><br/>
            Blanditiis explicabo architecto, iste ducimus obcaecati, quae excepturi voluptas quisquam hic porro 
            quaerat repudiandae illo sint totam doloribus esse aut voluptatum ipsa! Animi, odio neque placeat quaerat 
            debitis hic voluptatum!</p>
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
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.<br/><br/> 
                    Architecto dolor dolore id aperiam laudantium vero tempore placeat minima autem inventore, 
                    doloribus, deserunt excepturi! Iure laborum doloremque sint quisquam, fuga quas error corrupti 
                    voluptate deleniti porro quasi eum saepe reprehenderit, nemo id ratione architecto minima. 
                    Deserunt, eaque ducimus assumenda reiciendis illo vitae ipsam quis est eveniet maxime, 
                    sint doloremque. Impedit, ut voluptatibus nesciunt perferendis libero quo perspiciatis 
                    nemo possimus nihil. Temporibus excepturi nulla architecto iusto amet accusamus magni eos odit 
                    vero quae corporis error ipsam, laudantium, soluta dolores enim ullam tenetur sed unde dolore 
                    deserunt dolorem!<br/><br/>
                    Expedita accusantium aspernatur sed vitae.
                </p>
            </div>
    </section>
    <section id="adventure">
        <h1>L'Aventure</h1>
        <div class="last-post">
            <div class="last-post-content">
                <h2>Titre du dernier chapitre</h2>
                <br/>
                <p>
                    Début du nouveau chapitre, idéalement une sélection d'une cinquantaine de mots, 
                    les 50 premiers écrits auxquels on concatenne "..." afin de faire commencer la lecture
                    au visiteur, puis d'aller en découvrir plus : 
                </p>
            </div>
        </div>
        <a href="/billets/chapterlist">Lire la suite</a>
    </section>
</div>