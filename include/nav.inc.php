        <nav class="navbar navbar-expand-md navbar-dark bg-success py-4">
            <a class="navbar-brand" href="#">Ma Boutique de dingue !!!</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarsExample04">
                <ul class="navbar-nav mr-auto">

                <?php if(connect()): ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?= URL ?>profil.php">Mon compte</a>
                    </li>
                    

                <?php else: //liens visiteur lambda, non identifie sur le site ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?= URL ?>inscription.php">Creer mon compte</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?= URL ?>connexion.php">Identifiez-vous</a>
                    </li>

                <?php endif; ?>
                <li class="nav-item active">
                        <a class="nav-link" href="<?= URL ?>boutique.php">Acces a la boutique</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?= URL ?>panier.php">Votre panier</a>
                    </li>

                <?php if(connect()): ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?= URL ?>connexion.php?action=deconnexion">Deconnexion</a>
                    </li>
                <?php endif; ?>

                    <!-- Si l'internaute et identifie sur le site et que dans la session le statut est 'admin', alors on entre dans la condition et le menu du backoffice apparait dans la nav-->

                    <?php if(adminConnect()): ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">BACKOFFICE</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown04">
                            <a class="dropdown-item" href="<?= URL ?>admin/gestion_boutique.php">Gestion de la boutique</a>
                            <a class="dropdown-item" href="#">Gestion des utilisateurs</a>
                            <a class="dropdown-item" href="#">Gestion des commandes</a>
                        </div>
                    </li>
                    <?php endif; ?>


                </ul>
                <?php 
                if(isset($_SESSION['user']))
                {
                    echo "<span class='text-white badge badge-secondary p-2'> Bonjour <strong>" . ' ' . $_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom'] . "</strong></span>";
                }
                ?>
                
                <form class="form-inline my-2 my-md-0 m2-3">
                    <input class="form-control" type="text" placeholder="Rechercher">
                </form>
            </div>
        </nav>
    </header>
    <main style="min-height: 90vh;">