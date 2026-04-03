<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mairie de SACRE KEUR - Déclaration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
       <style>
        body { font-family: Arial; background: #f8f9fa; }
        nav { background: #333; padding: 15px; text-align: center; }
        nav a { color: white; margin: 0 15px; text-decoration: none; }
        .card { background: white; max-width: 450px; margin: 30px auto; padding: 25px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .btn { width: 100%; background: #eeea0e; color: white; padding: 12px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <nav>
        <a href="index.php">Accueil</a>
        <a href="declaration.php" style="border-bottom: 2px solid white;">Déclaration</a>
        <a href="finalisation.php">Visualisation</a>
    </nav>

    <div class="card">
        <h2 style="color: #22e62c; text-align: center;"><i class="fas fa-baby"></i> Déclaration de Nouveau-né</h2>
        <form method="POST">
            <input type="text" name="nom_bebe" placeholder="Nom de l'enfant" required>
            <input type="text" name="prenom_bebe" placeholder="Prénom(s)" required>
            <input type="date" name="date_naiss" required>
            <input type="text" name="lieu_naiss" placeholder="Lieu de naissance" required>
            <input type="text" name="nom_pere" placeholder="Prénom et Nom du Père" required>
            <input type="text" name="nom_mere" placeholder="Prénom et Nom de la Mère" required>
            <button type="submit" name="declarer" class="btn">GÉNÉRER L'EXTRAIT</button>
        </form>
    </div>
 
    <?php
session_start();

if (isset($_POST['declarer'])) {
    // Récupération des données du formulaire [cite: 8]
    $nom = strtoupper(trim($_POST['nom_bebe']));
    $prenom = ucwords(trim($_POST['prenom_bebe']));
    $date_n = $_POST['date_naiss'];
    $lieu = trim($_POST['lieu_naiss']);
    $pere = trim($_POST['nom_pere']);
    $mere = trim($_POST['nom_mere']);

    // : Écriture dans le registre de Sacré Keur
    $ligne = "NOUVEAU-NE|$nom|$prenom|$date_n|$lieu|$pere|$mere\n";
    $f = fopen("registre.txt", "a");
    if ($f) {
        fputs($f, $ligne);
        fclose($f);
    }

    // Mise à jour de la session pour la Page 4 [cite: 5]
    $_SESSION['infos_habitant'] = [
        'nom' => $nom,
        'prenom' => $prenom,
        'date_n' => $date_n,
        'lieu' => $lieu,
        'pere' => $pere,
        'mere' => $mere,
        'is_bebe' => true // Indicateur pour la mise en page
    ];

    header('Location: finalisation.php');
    exit();
}
?>
</body>
</html>