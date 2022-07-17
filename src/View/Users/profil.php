<form method="post" novalidate action="">
        
        <h1>Votre profil</h1>
        <div class="inputBox">
            <input type="email" name="email" required id="email" value="<?php echo $loggedUser->getEmail('email') ?>">
            <label for="email">email</label>
        </div>
        <div class="inputBox">
            <input type="text" name="pseudo" required id="pseudo" value="<?php echo $loggedUser->getPseudo('pseudo') ?>">
            <label for="pseudo">Pseudo</label>
        </div>
        <div class="inputBox">
            <input type="password" name="pass" required id="pass">
            <label for="pass">Mot de passe</label>
        </div>
        <div class="inputBox">
            <input type="password" name="confirm-pass" required id="confirm-pass">
            <label for="confirm-pass">Confirmer le mot de passe</label>
        </div>
        <div class="links">
            <button type="submit">Mettre à jour</button>
            <button><a href="/home/index">Retour</a></button>
        </div>
    </form>