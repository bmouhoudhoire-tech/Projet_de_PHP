   <?php
session_start();

// Sécurité : Si aucune donnée n'est présente, on renvoie à l'accueil
if (!isset($_SESSION['infos_habitant'])) {
    header('Location: index.php');
    exit();
}

$data = $_SESSION['infos_habitant'];

// Préparation des textes selon le type de document
$titre_document = $data['type_acte'] ?? "EXTRAIT D'ACTE DE NAISSANCE";
$statut = $data['statut'] ?? "REGISTRE COMMUNAL";

// Données à afficher
$nom = strtoupper(htmlspecialchars($data['nom']));
$prenom = ucwords(htmlspecialchars($data['prenom']));
$date_n = htmlspecialchars($data['date_n'] ?? "Non précisée");
$lieu = htmlspecialchars($data['lieu'] ?? "Sacré-Cœur, Dakar");
$pere = htmlspecialchars($data['pere'] ?? "Non renseigné");
$mere = htmlspecialchars($data['mere'] ?? "Non renseignée");
$tel = htmlspecialchars($_SESSION['user_tel'] ?? "770000000");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mairie de Sacré-Cœur - Document Officiel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Times New Roman', serif; background: #555; padding: 20px; margin: 0; }
        nav { background: #333; padding: 10px; text-align: center; margin-bottom: 20px; }
        nav a { color: white; margin: 0 15px; text-decoration: none; font-family: Arial; }
        
        /* Style de la feuille A4 */
        .page-a4 {
            background: white;
            width: 700px;
            margin: auto;
            padding: 50px;
            border: 2px solid #000;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            position: relative;
        }
        
        .header-top { display: flex; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .titre-central { text-align: center; margin: 30px 0; }
        .titre-central h1 { text-decoration: underline; font-size: 24px; margin-bottom: 5px; }
        
        .corps-acte { line-height: 2; font-size: 18px; margin-top: 20px; }
        .label { font-weight: normal; }
        .valeur { font-weight: bold; text-transform: uppercase; border-bottom: 1px dotted #000; }

        /* Zone de signature demandée */
        .signatures { margin-top: 60px; display: flex; justify-content: space-between; }
        .bloc-signature { width: 45%; text-align: center; }
        .espace-sign { height: 80px; border: 1px dashed #ddd; margin: 10px 0; }
        
        .tampon {
            width: 100px; height: 100px; border: 3px double #008751; border-radius: 50%;
            color: #008751; display: flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: bold; margin: auto; transform: rotate(-15deg);
            opacity: 0.7;
        }

        .btn-wa { background: #25D366; color: white;
         padding: 12px 25px; border-radius: 50px; text-decoration: none; font-weight: 
         bold; display: inline-flex; align-items: center; }
        
        /* Cacher les boutons à l'impression */
        @media print { .no-print { display: none; } body { background: white; padding: 0; }
         .page-a4 { box-shadow: none; border: none; } }
.bouton-email {
    display: inline-block;
    padding: 10px 20px;
    background-color: #1a73e8; /* Bleu Google */
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-family: sans-serif;
    font-weight: bold;
  }
  .bouton-email:hover {
    background-color: #1557b0;
  }
  
  

    </style>
</head>
<body>

    <nav class="no-print">
        <a href="index.php">Accueil</a>
        <a href="demande.php">Services</a>
        <a href="declaration.php">Naissance</a>
        <a href="finalisation.php"><b>Visualisation</b></a>
    </nav>

    <div class="page-a4">
        <div class="header-top">
            <div><strong>REGION :</strong> DAKAR<br><strong>COMMUNE :</strong> SACRÉ-CŒUR</div>
            <div style="text-align: right;">REPUBLIQUE DU SENEGAL<br><em>Unité - Travail - Progrès</em></div>
        </div>

        <div class="titre-central">
            <h1><?php echo $titre_document; ?></h1>
            <p>STATUT : <?php echo $statut; ?></p>
        </div>

        <div class="corps-acte">
            <p>Le nom Complet(e) : <span class="valeur"><?php echo $prenom . " " . $nom; ?></span></p>
            <p>Né (e) le : <span class="valeur"><?php echo $date_n; ?></span></p>
            <p>À : <span class="valeur"><?php echo $lieu; ?></span></p>
            <p>Fils de : <span class="valeur"><?php echo $pere; ?></span></p>
            <p>Et de : <span class="valeur"><?php echo $mere; ?></span></p>
        </div>

        <div class="signatures">
            <div class="bloc-signature">
                <p><strong>Signature du Père<br>(Le Déclarant)</strong></p>
                <div class="espace-sign"></div>
                <p><?php echo $pere !== "Non renseigné" ? $pere : $nom; ?></p>
            </div>

            <div class="bloc-signature">
                <p>Fait à Sacré-Cœur, le <?php echo date('d/m/Y'); ?></p>
                <p><strong>L'Officier d'État-Civil</strong></p>
                <div class="tampon">MAIRIE DE<br>SACRÉ-CŒUR<br>SÉNÉGAL</div>
            </div>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 30px; padding-bottom: 50px;">
        <button onclick="window.print()" style="padding:12px; cursor:pointer; font-weight:bold; margin-right: 10px;">
            <i class="fas fa-print"></i> Imprimer / PDF
        </button>
        
        <a href="https://wa.me/221<?php echo $tel; ?>" class="btn-wa">
            <i class="fab fa-whatsapp" style="margin-right: 10px; font-size: 20px;"></i> 
            Envoyer au <?php echo $tel; ?>
        </a>
        
        <a href="https://gmail.com" class="bouton-email">
  Envoyer un E-mail
</a>   <br><br>
<a href="https://www.whatsapp.com" class="whatsapp"  style="margin-right: 50px; font-size: 50px;">
                <i class="fab fa-whatsapp"></i></a>
       
            <a href="https://www.facebook.com" class="facebook"  style="margin-right: 50px; font-size: 50px;">
                <i class="fab fa-facebook"></i></a>
    </div>
    
 

</body>
</html>