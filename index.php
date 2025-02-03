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
</head>
<body>
    <header>
        <nav>
            <h1 class="titre"><a href="//localhost/projet_web/index.php">TECHIN<span>NOVA</span></a></h1>

            <ul>
                <li><a href="">Acceuil</a></li>
                <li><a href="">Projets R&D</a></li>
                <li><a href="">Idées innovantes</a></li>
                <li><a href="">À Propos</a></li>
            </ul>
            <button id="conn" class="btn_conn">Connexion</button>
            <button id="inscript">Inscription</button>
        </nav>
    </header>
    <main>
        <section class="top">
            <div class="text">
                <p><span>TechInnova ?</span><br>Nous sommes la solution idéale pour transformer vos  <br> ambitions 
                technologiques en réalité. </p><br>
                <div class="text_btn">
                    <button class="btn1">Rejoignez-nous</button>
                    <button class="btn2">blabla</button>
                </div>
                

            </div>
            <div class="image">
                <img src="img/imagepp2.png" alt="image?">
            </div>
        </section>

        <section class="mid">
            <div class="mid1">
                <img src="img/image1.png" alt="image1">
                <div class="texte">
                    <h2>Engagement envers l'innovation</h2>
                    <p>Chez TechInnova, nous nous engageons à repousser les limites de la technologie. Notre équipe de R&D travaille sans relâche pour développer des solutions innovantes qui répondent aux besoins du marché.</p>
                </div>
                
            </div>
            <div class="mid2">
                <img src="img/image3.png" alt="image1">
                <div class="texte">
                    <h2>Collaboration et partenariats</h2>
                    <p>Nous croyons en la force des partenariats. En collaborant avec des entreprises et des institutions, nous enrichissons notre processus d'innovation et élargissons notre portée.</p>
                </div>
            </div>
            <div class="mid3">
                <img src="img/image2.png" alt="image1">
                <div class="texte">
                    <h2>Expertise technique</h2>
                    <p>Notre équipe d'experts en technologie possède une vaste expérience dans divers domaines. Nous utilisons cette expertise pour concevoir des produits et services qui font la différence.</p>
                </div>
            </div>
        </section>

        <section class="after">
            <div class="after_top">
                <h1>Nos services innovants</h1>
                <h3>Des solutions sur mesure pour chaque besoin technologique.</h3>
            </div>
            <div class="after1">
                <img src="img/images_11.png" alt="image1">
                <div class="texte2">
                    <h2>Développement de produits</h2>
                    <p>Nous concevons et développons des produits technologiques adaptés aux exigences spécifiques de nos clients, en garantissant qualité et performance.</p>
                </div>
            </div>


            <div class="after2">
                <div class="texte22">
                    <h2>Consultation R&D</h2>
                    <p>Notre équipe offre des services de consultation en recherche et développement pour aider les entreprises à maximiser leur potentiel d'innovation.</p>
                </div>
                <img src="img/images12.png" alt="image1">
            </div>


            <div class="after3">
                <img src="img/images13.png" alt="image1">
                <div class="texte2">
                    <h2>Formation et support</h2>
                    <p>Nous proposons des formations et un support technique pour assurer une utilisation optimale de nos solutions et technologies.</p>
                </div>
            </div>


        </section>

        <section class="ultime">
            <div class="ultime_top">
                    <h1>Ce que nos clients disent</h1>
                    <h3>Des témoignages qui parlent d'eux-mêmes.</h3>
                </div>
                <div class="ultime1">
                    <img src="img/image33.png" alt="image1">
                    <div class="texte3">
                        <h2>Une expérience transformative</h2>
                        <p>« Grâce à TechInnova, notre processus d'innovation a été transformé. Leur expertise nous a permis de lancer de nouveaux produits sur le marché en un temps record. »</p>
                    </div>
                </div>
                <div class="ultime2">
                    <div class="texte3">
                        <h2>Partenariat fructueux</h2>
                        <p>« Travailler avec TechInnova a été un véritable plaisir. Leur compréhension de nos besoins et leur capacité à innover nous ont aidés à atteindre nos objectifs. »</p>
                    </div>
                    <img src="img/image32.png" alt="image1">
                </div>
                <div class="ultime3">
                    <img src="img/image31.png" alt="image1">
                    <div class="texte3">
                        <h2>Un soutien exceptionnel</h2>
                        <p>« Le support technique et la formation offerts par TechInnova sont d'une grande valeur. Ils nous ont aidés à optimiser l'utilisation de leurs solutions. »</p>
                    </div>
                </div>
        </section>


        
    </main>

    <footer>
        &copy; Copyright TechInnova 2025
    </footer>


<!--
    
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
-->

   
</body>
<script src="script.js" defer></script>

</html>
