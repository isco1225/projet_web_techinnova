<?php
session_start();
if (isset($_SESSION['message'])) {
    echo "<p style='color: green;'>{$_SESSION['message']}</p>";
    unset($_SESSION['message']); // Supprime le message après affichage
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
    <script src="script.js" defer></script>
</head>
<body>
    <header>
        <nav>
            <h1 class="titre"><a href="//localhost/projet_web/index.php">TECHIN<span>NOVA</span></a></h1>

            <ul>
                <li><a href="">Acceuil</a></li>
                <li><a href="">Projets R&D</a></li>
                <li><a href="">Idées innovantes</a></li>
                <li><a href="">Documents</a></li>
            </ul>
            <button>Connexion</button>
            <button>Inscription</button>
        </nav>
    </header>
    <main>
        <section class="top">
            <div class="text">
                <p><span>TechInnova</span> est la solution idéale pour transformer vos ambitions  <br>
                technologiques en réalité. Que vous soyez chercheur, entrepreneur ou collaborateur, <br>
                TechInnova est la solution idéale pour transformer vos ambitions technologiques en réalité. <br> 
                Simplifiez votre gestion, stimulez la créativité et atteignez vos objectifs plus rapidement.<br>
                L'innovation commence ici. Construisons l'avenir ensemble.
                </p><br>
                <div class="text_btn">
                    <button class="btn1">Rejoignez-nous</button>
                    <button class="btn2">blabla</button>
                </div>
                

            </div>
            <div class="image">
                <img src="#" alt="image?">
            </div>
        </section>

        <section class="mid">
            <div class="mid1">
                <h2>Engagement envers l'innovation</h2>
            </div>
            <div class="mid2">
                <h2>Collaboration et partenariats</h2>
            </div>
            <div class="mid3">
                <h2>Expertise technique</h2>
            </div>
        </section>

        <section class="after">
            <div class="after1">

            </div>
        </section>


        
    </main>
    
    <a href="connexions/page_inscription.php">inscription</a>
    <a href="espace_admin/admin.php">espace admin</a>
    <a href="connexions/page_connexion.php">connexion</a>
    <main>
        <button id="btn">Afficher</button>
        <div id="liste">
            <p>Vous êtes sur a liste</p>
        </div>
    </main>

    <a href="espace_chercheur/ajout_projet.php">Ajouter projet</a>
    <a href="espace_admin/creation_compte.php">creer compte</a>

    <div style="width: 50%; margin: 20px 0; background-color: #e0e0e0; border-radius: 8px; overflow: hidden;">
        <div style="width: 75%; background-color: #4caf50; text-align: center; color: white; padding: 5px 0; border-radius: 8px 0 0 8px;">
            75%
        </div>
    </div>
    

   
</body>
</html>
