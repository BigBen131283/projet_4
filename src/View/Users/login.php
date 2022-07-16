<form method="post" novalidate action="/users/login">
    <h1>Connexion</h1>
    <div class="inputBox">
        <input type="text" name="pseudo" required id="pseudo" value="<?php echo $errorHandler->getValue('pseudo') ?>">
        <label for="pseudo">Pseudo</label>
    </div>
    <div class="inputBox">
        <input type="password" name="pass" required id="pass">
        <label for="pass">Mot de passe</label>
    </div>
    <p><?php echo $errorHandler->getFirstError('loginerror'); ?></p>
    <div class="links">
        <button type="submit">Se connecter</button>
        <ul>
            <li><a href="/users/register" data-text="S'inscrire">S'inscrire</a></li>
            <li><a href="#" data-text="Mot de passe oublié">Mot de passe oublié</a></li>
        </ul>
    </div>
</form>