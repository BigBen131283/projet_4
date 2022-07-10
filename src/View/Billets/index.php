<h1>Liste des chapitres</h1>

<?php foreach($billets as $billet):?>
    <article>
        <h2> <a href="/billets/chapitre/<?= $billet->id?>"><?= $billet->title?></a></h2>
        <p>Publié le : <?= $billet->publish_at?></p>
    </article>
<?php endforeach?>