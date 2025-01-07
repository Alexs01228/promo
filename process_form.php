<?php
include 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $coins = isset($_POST['coins']) ? (int)$_POST['coins'] : 0;

   
    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        echo "Todos los campos son requeridos.";
        exit;
    }

    if ($password !== $confirm) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo "Ese correo ya está registrado.";
            exit;
        }

        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, coins) VALUES (:name, :email, :password, :coins)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':coins', $coins);
        $stmt->execute();

        echo "Se ha registrado correctamente.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
