<body>
    <h1 style="margin-bottom: 0.5em;">Admin</h1>
    <div class="login">
        <form method="post" class="<?= $errorlog ? 'wrong' : '' ?>">
            <div class="marg input-icone">
                <i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i><input type="text" name="user" placeholder="Utilisateur" class="saisie">
            </div>
            <div class="marg input-icone">
                <input type="password" placeholder="Password" name="pwrd" class="saisie">
            </div>
            <div class="marg submit">
                <input type="submit" value="Connexion">
            </div>
        </form>
    </div>
</body>