<form method="post" novalidate action="" enctype="multipart/form-data">
    
    <img src="<?php echo IMAGEROOT.$updateUser->getValue('profile_picture') ?>" alt="Photo de profil">

    <h1>Votre profil</h1>
    <?php
        use App\Core\Flash;

        $flash = new Flash();
        $flash->getFlash('update');
    ?>
    <div class="inputBox">
        <input type="email" name="email" required id="email" value="<?php echo $updateUser->getValue('email') ?>">
        <label for="email">email</label>
        <?php echo $updateUser->getFirstError('email'); ?></p>
    </div>
    <div class="inputBox">
        <input type="text" name="pseudo" required id="pseudo" value="<?php echo $updateUser->getValue('pseudo') ?>">
        <label for="pseudo">Pseudo</label>
        <?php echo $updateUser->getFirstError('pseudo'); ?></p>
    </div>
    <!-- <div class="inputBox">
        <input type="password" name="pass" required id="pass">
        <label for="pass">Mot de passe</label>
    </div>
    <div class="inputBox">
        <input type="password" name="confirm-pass" required id="confirm-pass">
        <label for="confirm-pass">Confirmer le mot de passe</label>
    </div> -->
    <div class="inputBox">
        <input type="file" name="profile_picture" id="profile_picture">
        <label for="profile_picture">Photo de profil</label>
        <?php echo $updateUser->getFirstError('uploadError'); ?></p>
    </div>
    <div class="links">
        <button type="submit">Mettre à jour</button>
    </div>
</form>