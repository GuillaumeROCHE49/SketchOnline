<?php

require_once('./configdb.php');
$connexion = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);

// Vérification de la connexion
if ($connexion->connect_error) {
    // Retourner un message d'erreur avec un status 'failure'
    echo json_encode(["status" => "failure", "message" => "Échec de la connexion à la base de données"]);
    die();
}

// Exécuter la requête
$requete = $connexion->prepare("SELECT c.numConcours, c.titre
FROM Utilisateurs u
JOIN Competiteur comp ON u.numUtilisateur = comp.numCompetiteur
JOIN ParticipeComp pc ON comp.numCompetiteur = pc.numCompetiteur
JOIN Concours c ON pc.numConcours = c.numConcours
WHERE u.email = ?");
$requete->bind_param("s", $_GET['email']);

$requete->execute();
$result = $requete->get_result();

$competitionList = [];
// Récupérer les résultats avec une boucle
while ($row = $result->fetch_assoc()) {
    $competitionList[] = {
        "id" => $row['numConcours'],
        "title" => $row['titre']
    };
}

$data = [
    "status" => "success", // Ajouter le champ 'status'
    "events" => $competitionList
];

// Fermeture de la requête et de la connexion
$requete->close();
$connexion->close();

// Retourner la réponse
echo json_encode($data);

?>
