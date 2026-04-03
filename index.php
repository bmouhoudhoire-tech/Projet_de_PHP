<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mairie de Sacré-Cœur - Accueil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="card">
    <span class="logo">🇸🇳</span>
    <h2>Mairie de Sacré-Cœur</h2>
    
    <form method="POST">
        <input type="text" name="nom" placeholder="VOTRE NOM" required>
        <input type="text" name="prenom" placeholder="VOTRE PRÉNOM" required>
        <input type="text" name="tel" placeholder="TÉLÉPHONE (7x xxx xx xx)" required>
        <button type="submit" name="verifier" class="btn-check">
            <i class="fas fa-search"></i> ANALYSER MON STATUT
        </button>
    </form>

    <div class="divider"><span>OU</span></div>

    <a href="declaration.php" class="btn-baby">
        <i class="fas fa-baby-carriage"></i> DÉCLARER UN NOUVEAU-NÉ
    </a>
</div>
<style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 35px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 360px; text-align: center; border-top: 8px solid #008751; }
        .logo { font-size: 45px; margin-bottom: 15px; display: block; }
        h2 { color: #333; margin-bottom: 20px; font-size: 22px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .btn-check { width: 100%; background: #008751; color: white; padding: 14px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 15px; margin-top: 10px; transition: 0.3s; }
        .btn-check:hover { background: #005f39; }
        
        .divider { margin: 25px 0; border-top: 1px solid #eee; position: relative; }
        .divider span { position: absolute; top: -10px; left: 42%; background: white; padding: 0 10px; color: #999; font-size: 12px; }
        
        .btn-baby { display: block; background: #e67e22; color: white; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 14px; transition: 0.3s; }
        .btn-baby:hover { background: #d35400; }
    </style>
<?php
session_start();

// Fonction d'analyse de l'utilisateur dans le fichier texte
if (isset($_POST['verifier'])) {
    $nom = strtoupper(trim($_POST['nom']));
    $prenom = ucwords(trim($_POST['prenom']));
    $tel = str_replace(' ', '', $_POST['tel']);

    $trouve = false;
    
    // On vérifie si le fichier registre.txt existe
    if (file_exists("registre.txt")) {
        $lignes = file("registre.txt");
        foreach ($lignes as $ligne) {
            // On sépare les données par la barre verticale |
            $data = explode("|", trim($ligne));
            
            // On compare NOM et PRENOM (situés en début de ligne)
            if (isset($data[0]) && isset($data[1])) {
                if (strtoupper($data[0]) == $nom && ucwords($data[1]) == $prenom) {
                    $trouve = true;
                    // On stocke les infos trouvées pour la Page 4
                    $_SESSION['infos_habitant'] = [
                        'nom' => $data[0],
                        'prenom' => $data[1],
                        'age' => $data[2] ?? 'N/A',
                        'tel' => $tel,
                        'type_acte' => "EXTRAIT DE NAISSANCE OFFICIEL"
                    ];
                    break;
                }
            }
        }
    }

    $_SESSION['user_tel'] = $tel;

    if ($trouve) {
        // Redirection vers l'extrait si trouvé
        header('Location: finalisation.php');
    } else {
        // Redirection vers la création temporaire si inconnu
        header('Location: demande.php?statut=visiteur');
    }
    exit();
}
?>

</body>
</html>

