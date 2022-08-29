<form method="post" novalidate action="">
    <h1>Mot de passe oublié</h1>
    <?php echo $errorHandler->getFirstError('flashmessage'); ?></p>
    <div class="inputBox">
        <input type="email" name="email" required id="email" value="<?php echo $errorHandler->getValue('email') ?>">
        <label for="email">email</label>
        <?php echo $errorHandler->getFirstError('email'); ?></p>
        
    </div>
    <div class="links">
        <button type="submit">Demander le reset</button>
    </div>
</form>