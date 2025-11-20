<?php
require_once 'conexión.php'; 
secureSession();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$conn = getDBConnection();
if (!$conn) {
    die('Error de conexión: ' . mysqli_connect_error());
}

// Consulta usuario
$sqlUser = "SELECT first_name, last_name FROM users WHERE id = ?";
$stmt = $conn->prepare($sqlUser);

if (!$stmt) {
    die('Error en prepare() (user query): ' . $conn->error);
}

$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result ? $result->fetch_assoc() : null;
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>thefacebook | contact</title>
    <link rel="stylesheet" href="styles.css">

</head>

<body class="contact-page">
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-left">
                <h1 class="logo"><a href="home.php">thefacebook</a></h1>
            </div>
            <div class="nav-right">
                
                <a href="profile.php" class="nav-link">profile</a>
                <a href="contact.php" class="nav-link active">contact</a>
                <a href="logout.php" class="nav-link">logout</a>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <h2>Contact</h2>

        <!-- Contenedor que une texto + foto -->
        <div class="contact-wrapper-right">
            <div class="contact-info">
                <p>Welcome,
                    <strong>
                        <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                    </strong>
                </p>
                <p>This is the contact page of your thefacebook clone.
                </p>
            </div>

        
        </div>
    </div>

     <div class="contact-photo">
                <img src="imagen/facebookprofile.jpg" alt="Developer">
            </div>

<style>
        .contact-wrapper-left{
            display: flex;
            align-items: left;
            gap: 20px;
            margin-top: 20px;
        }

        .contact-photo img {
            width: 150px;   
            height: 100px;   
            object-fit: cover;
            border-radius: 4px;
        }
    </style>

    <footer class="footer">
        <p>&copy; 2004 thefacebook | recreation</p>
    </footer


