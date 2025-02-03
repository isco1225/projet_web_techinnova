<?php
// Ajouter la connexion à la base de données
require_once("C:/xampp/htdocs/projet_web/config/connexion.php");

session_start();

// Vérifier si le projet_id est passé dans l'URL
if (isset($_GET['projet_id'])) {
    $projet_id = $_GET['projet_id'];
} else {
    // Si aucun projet_id n'est passé, rediriger vers la liste des projets
    header("location: projetsr&d.php");
    exit;
}

// Vérifier si le formulaire a été soumis
if (isset($_POST['submit'])) {
    $document = $_FILES['document'];
    $categorie = $_POST['categorie']; // Récupérer la catégorie du document

    // Assurez-vous que le fichier a été téléchargé avec succès
    if ($document['error'] == 0) {
        $targetDir = "uploads/";  // Dossier pour enregistrer les fichiers
        $targetFile = $targetDir . basename($document['name']);
        
        // Déplacez le fichier téléchargé dans le dossier d'uploads
        if (move_uploaded_file($document['tmp_name'], $targetFile)) {
            // Récupérer l'ID de l'utilisateur (uploader) depuis la session
            $id_uploader = $_SESSION['utilisateur']['id'];

            // Enregistrez les informations dans la base de données
            $sql = "INSERT INTO documents (nom_fichier, chemin, categorie, id_projet, id_uploader) 
                    VALUES (:nom_fichier, :chemin, :categorie, :id_projet, :id_uploader)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':nom_fichier', $document['name']);
            $stmt->bindParam(':chemin', $targetFile);
            $stmt->bindParam(':categorie', $categorie);
            $stmt->bindParam(':id_projet', $projet_id);
            $stmt->bindParam(':id_uploader', $id_uploader);
            $stmt->execute();

            // Rediriger vers la page de détail du projet après l'ajout du document
            header("location: projet.php?id=" . $projet_id);
            exit;
        } else {
            echo "Erreur lors du téléchargement du fichier.";
        }
    } else {
        echo "Aucun fichier sélectionné ou erreur lors de l'upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Document</title>
    <link rel="stylesheet" href="css/add_doc.css">
</head>
<body>

    <!-- Carré au centre de la page -->
    <div class="upload-container">
        <h2>Ajoutez un Document au Projet</h2>
        
        <!-- Formulaire d'ajout de document -->
        <form action="" method="POST" enctype="multipart/form-data">
            <!-- Carré où l'utilisateur clique pour sélectionner un fichier -->
            <div class="upload-box" onclick="document.getElementById('file-input').click();">
                <span>Cliquez ici pour sélectionner un fichier</span>
            </div>
            <input type="file" id="file-input" name="document" style="display:none;" required>

            <!-- Choisir la catégorie du document -->
            <div class="form-group">
                <label for="categorie">Catégorie du document :</label>
                <select id="categorie" name="categorie" required>
                    <option value="Rapport">Rapport</option>
                    <option value="Etude">Etude</option>
                    <option value="Brevets">Brevets</option>
                    <option value="Articles">Articles</option>
                </select>
            </div>

            <!-- Bouton pour soumettre le formulaire -->
            <button type="submit" name="submit" class="btn">Ajouter le Document</button>
        </form>
    </div>

</body>
</html>
