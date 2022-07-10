<h1>Liste des membres</h1>

<?php foreach($users as $user):?>
    <article>
        <h2><a href="/users/profil/<?= $user->id?>"><?= $user->pseudo?></a></h2>
        <p><?= $user->email?></p>
    </article>
<?php endforeach?>