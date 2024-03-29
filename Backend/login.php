<?php
session_start();

require_once('./configdb.php');
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));

    if (empty($data->email) || empty($data->password)) {
        $response['error'] = "Veuillez fournir un nom d'utilisateur et un mot de passe";
    } else {
        $email = $data->email;
        $password = $data->password;
        $_SESSION['email'] = $email;

        $connexion = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
        if ($connexion->connect_error) {
            $response['error'] = "La connexion à la base de données a échoué : " . $connexion->connect_error;
        } else {
            $stmt = $connexion->prepare("SELECT * FROM Utilisateurs WHERE email = ?");

            if ($stmt === FALSE) {
                $response['error'] = "Erreur lors de la préparation de la requête : " . $connexion->error;
            } else {
                $stmt->bind_param("s", $email);

                if ($stmt->execute() === TRUE) {
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $user = $result->fetch_assoc();

                        if ($password === $user['password']) {
                            // Utilisation d'une fonction définie précédemment
                            
                            echo json_encode(array(
                                'status' => 'success',
                                'id' => $user['numUtilisateur'],
                                'login' => $user['login'],
                                'name' => $user['nom'],
                                'surname' => $user['prenom'],
                                'email' => $user['email'],
                                ));
                                error_log($user['numUtilisateur']);
                        } else {
                            error_log("Mot de passe incorrect. Données reçues : " . json_encode($data));
                            $response['error'] = "Mot de passe incorrect";
                        }
                    } else {
                        $response['error'] = "Nom d'utilisateur incorrect";
                    }
                } else {
                    $response['error'] = "Erreur lors de l'exécution de la requête : " . $stmt->error;
                }

                $stmt->close();
            }

            $connexion->close();
        }
    }
}

header('Content-Type: application/json');
?>
