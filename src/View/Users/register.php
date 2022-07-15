<form method="post" novalidate action="/users/register">
        
        <h1>Inscription</h1>
        <div class="inputBox">
            <input type="email" name="email" required id="email">
            <label for="email">email</label>
            <?php
                echo $errorMessages->getFirstError('email');
            ?>
        </div>
        <div class="inputBox">
            <input type="text" name="pseudo" required id="pseudo">
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
            <button type="submit">S'inscrire</button>
            <ul>
                <li><a href="#" data-text="Mot de passe oublié">Mot de passe oublié</a></li>
            </ul>
        </div>
    </form>