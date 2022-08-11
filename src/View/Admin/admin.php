<div class="site">
    <section class="sidebar">
        <div class="side-navigation">
            <div class="sidebarToggle"></div>
            <ul>
                <li class="list active" style="--clr:#f44336">
                    <a href="#">
                        <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                        <span class="text">Home</span>
                    </a>
                </li>
                <li class="list" style="--clr:#ffa117">
                    <a href="#">
                        <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
                        <span class="text">Membres</span>
                    </a>
                </li>
                <li class="list" style="--clr:#0fc70f">
                    <a href="#">
                        <span class="icon"><ion-icon name="chatbubble-outline"></ion-icon></span>
                        <span class="text">Commentaires</span>
                    </a>
                </li>
                <li class="list" style="--clr:#2196f3">
                    <a href="#">
                        <span class="icon"><ion-icon name="book-outline"></ion-icon></span>
                        <span class="text">Chapitres</span>
                    </a>
                </li>
                <li class="list" style="--clr:#b145e9">
                    <a href="#">
                        <span class="icon"><ion-icon name="pencil-outline"></ion-icon></span>
                        <span class="text">Rédaction</span>
                    </a>
                </li>
            </ul>
        </div>
    </section>
    <section class="dashboard">
        <h1>Statistiques</h1>
        <div class="statistics">
            <div class="statbox" id="billets_stats">
                <h2 class="box-title">Billets publiés</h2>
                <p class="data"><?= '18'?></p>
            </div>
            <div class="statbox" id="member_stats">
                <h2 class="box-title">Membres inscrits</h2>
                <p class="data"><?= '1675'?></p>
            </div>
            <div class="statbox" id="comments_stats">
                <h2 class="box-title">Commentaires publiés</h2>
                <p class="data"><?= '253'?></p>
            </div>
            <div class="statbox" id="modo_stats">
                <h2 class="box-title">Modération en attente</h2>
                <p class="data"><?= '14'?></p>
            </div>
        </div>
        <div class="siteadmin">
            <div class="adminbox" id="members"></div>
            <div class="adminbox" id="moderate">
                <?php foreach($signaledComments as $comment):?>
                    <article>
                        <h2><a href="/billets/chapitre/<?= $comment->billet_id?>"><?= $comment->title?></a></h2>
                        <p class="comment"><?= html_entity_decode(stripslashes($comment->content))?></p>
                        <p class="date"><?= $comment->publish_at?></p>
                        <p class="pseudo"><?= $comment->pseudo?></p>
                        <div class="advice">
                            <a href="/admin/rejectcomment/<?= $comment->id?>">Supprimer</a>
                            <a href="/admin/acceptcomment/<?= $comment->id?>">Autoriser</a>
                        </div>
                    </article>
                <?php endforeach?>
            </div>
            <div class="adminbox" id="chapters"></div>
            <div class="adminbox" id="write"></div>
        </div>
    </section>
</div>