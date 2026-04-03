<?php
session_start();

// Sécurité : on vérifie si l'utilisateur est passé par la page 1
if (!isset($_SESSION['user_tel'])) {
    header('Location: index.php');
    exit();
}

$resultat_recherche = "";

// --- LOGIQUE DE RECHERCHE ---
if (isset($_POST['chercher'])) {
    $nom_recherche = strtoupper(trim($_POST['nom_recherche']));
    $trouve = false;

    if (file_exists("registre.txt")) {
        $lignes = file("registre.txt");
        foreach ($lignes as $ligne) {
            // On sépare les données du fichier (format: NOM|PRENOM|AGE|...)
            $infos = explode("|", $ligne);
            if (trim($infos[0]) === $nom_recherche || trim($infos[1]) === $nom_recherche) {
                $resultat_recherche = "✅ Dossier trouvé : " . $infos[1] . " " . $infos[0] . " (Enregistré)";
                $trouve = true;
                break;
            }
        }
    }
    if (!$trouve) {
        $resultat_recherche = "❌ Aucun dossier trouvé à ce nom.";
    }
}

// --- LOGIQUE DE CRÉATION (Sénégalais ou Visiteur) ---
if (isset($_POST['creer_extrait'])) {
    $nom = strtoupper($_POST['nom']);
    $prenom = $_POST['prenom'];
    $date_n = $_POST['date_naiss'];
    $lieu = $_POST['lieu'];
    $pere = trim($_POST['nom_pere']);
    $mere = trim($_POST['nom_mere']);
    $nationalite = $_POST['nationalite'];

    if ($nationalite == "SN") {
        $type_acte = "EXTRAIT DE NAISSANCE OFFICIEL";
        $titre_document = "CITOYEN SENEGALAIS";
    } else {
        $type_acte = "LAISSER-PASSER TEMPORAIRE";
        $titre_document = "VISITEUR (CIRCULATION LIBRE)";
    }

    // On stocke tout en session pour la Page 4
    $_SESSION['infos_habitant'] = [
        'nom' => $nom,
        'prenom' => $prenom,
        'date_n' => $date_n,
        'lieu' => $lieu,
        'pere' => $pere,
        'mere' => $mere,
        'type_acte' => $type_acte,
        'titre' => $titre_document,
        'tel' => $_SESSION['user_tel']
    ];

    header('Location: finalisation.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mairie de Sacré-Cœur - Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; margin: 0; padding: 20px; }
        nav { background: #008751; padding: 15px; text-align: center; border-radius: 8px; margin-bottom: 20px; }
        nav a { color: white; margin: 0 15px; text-decoration: none; font-weight: bold; }
        .container { display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; }
        .card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 400px; }
        h3 { color: #008751; border-bottom: 2px solid #f0f2f5; padding-bottom: 10px; }
        input, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 6px; }
        .btn { width: 100%; padding: 12px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; color: white; }
        .btn-blue { background: #3498db; }
        .btn-green { background: #27ae60; }
        .resultat { background: #e8f4fd; padding: 10px; border-radius: 5px; margin-top: 10px; font-weight: bold; }
    </style>   

</head>
<body>

<nav>
    <a href="index.php">Accueil</a>
    <a href="demande.php" style="text-decoration: underline;">Recherche & Création</a>
    <a href="declaration.php">Déclaration Bébé</a>
    <a href="finalisation.php">Visualisation</a>
</nav>

<div class="container">
    <div class="card">
        <h3><i class="fas fa-search"></i> Rechercher un Citoyen</h3>
        <p>Vérifier si le nom existe déjà dans le registre de la mairie.</p>
        <form method="POST">
            <input type="text" name="nom_recherche" placeholder="Entrez le nom ou prénom..." required>
            <button type="submit" name="chercher" class="btn btn-blue">Lancer la recherche</button>
        </form>
        <?php if($resultat_recherche) echo "<div class='resultat'>$resultat_recherche</div>"; ?>
    </div>

    <div class="card">
        <h3><i class="fas fa-plus-circle"></i> Nouveau Document</h3>
        <form method="POST">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="number" name="age" placeholder="Âge" required>
            <input type="text" name="nom_pere" placeholder="Prénom et Nom du Père" required>
            <input type="text" name="nom_mere" placeholder="Prénom et Nom de la Mère" required>
            <input type="text" name="lieu" placeholder="Lieu de naissance" required>
            
            <label>Nationalité :</label>
            <select name="nationalite">
                <option value="SN">Sénégalaise (Extrait officiel)</option>
                <option value="EXT">Etranger (Laisser-passer visiteur)</option>
            </select>

            <button type="submit" name="creer_extrait" class="btn btn-green">Générer le document</button>
        </form>
    </div>
   
</div>

</body>
</html>
