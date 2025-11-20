<?php
// profile.php
require_once 'conexión.php';
secureSession();

if (!isset($_SESSION['user_id'])) {
    header('Location: logout.php');
    exit();
}

$conn = getDBConnection();
$success = '';
$error = '';

// Obtener información del usuario
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Actualizar perfil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bio = trim($_POST['bio']);
    $lookingFor = $_POST['looking_for'];
    $interestedIn = $_POST['interested_in'];
    $relationshipStatus = $_POST['relationship_status'];
    $politicalViews = trim($_POST['political_views']);
    $interests = trim($_POST['interests']);
    $favoriteMusic = trim($_POST['favorite_music']);
    $favoriteMovies = trim($_POST['favorite_movies']);
    $favoriteBooks = trim($_POST['favorite_books']);
    
    $avatarName = $user['avatar'];
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['avatar']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed) && $_FILES['avatar']['size'] < 5000000) {
            $newName = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], 'uploads/' . $newName)) {
                $avatarName = $newName;
            }
        }
    }
    
    $stmt = $conn->prepare("UPDATE users SET bio = ?, avatar = ?, looking_for = ?, interested_in = ?, relationship_status = ?, political_views = ?, interests = ?, favorite_music = ?, favorite_movies = ?, favorite_books = ? WHERE id = ?");
    $stmt->bind_param("ssssssssssi", $bio, $avatarName, $lookingFor, $interestedIn, $relationshipStatus, $politicalViews, $interests, $favoriteMusic, $favoriteMovies, $favoriteBooks, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        $_SESSION['user_avatar'] = $avatarName;
        $success = 'Profile updated successfully!';
        $user['avatar'] = $avatarName;
        $user['bio'] = $bio;
        $user['looking_for'] = $lookingFor;
        $user['interested_in'] = $interestedIn;
        $user['relationship_status'] = $relationshipStatus;
        $user['political_views'] = $politicalViews;
        $user['interests'] = $interests;
        $user['favorite_music'] = $favoriteMusic;
        $user['favorite_movies'] = $favoriteMovies;
        $user['favorite_books'] = $favoriteBooks;
    } else {
        $error = 'Error updating profile.';
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>thefacebook | edit profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="profile-edit-page">
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-left">
                <h1 class="logo"><a href="home.php">thefacebook</a></h1>
            </div>
            <div class="nav-right">
                <a href="profile.php" class="nav-link active">profile</a>
                <a href="contact.php" class="nav-link">contact</a>
                <a href="logout.php" class="nav-link">logout</a>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <div class="edit-profile-container">
            <h2>Profile</h2>
            
            <?php if ($success): ?>
                <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="profile.php" enctype="multipart/form-data">
                <div class="form-section">
                    <h3>Profile Picture</h3>
                    <div class="current-avatar">
                        <img src="imagen/facebookprofile.jpg" 
                             alt="Current Avatar">
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Basic Info</h3>
                    <div class="form-group">
                        <label for="relationship_status">Relationship Status:</label>
                        <p>Soltera</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="looking_for">Looking For:</label>
                         <p>Diversión, aventuras, amistades</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="interested_in">Interested In:</label>
                        <p>Música, pinturas, hike, comida</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="political_views">Political Views:</label>
                        <p></p>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>About You</h3>
                    <div class="form-group">
                        <label for="bio">About Me:</label>
                       <p>Hola soy Amarilis estudiante de Sistemas informáticos (termina mal)</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="interests">Interests:</label>
                        <p>libros, películas, viajes</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="favorite_music">Favorite Music:</label>
                        <p>House, reggae</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="favorite_movies">Favorite Movies:</label>
                        <p>10 thing I hate about u</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="favorite_books">Favorite Books:</label>
                        <p>Libros escritos por Walter Rizo, Dostoievsky y Murakami</p>
                    </div>
                </div>
                
               
            </form>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2004 thefacebook</p>
    </footer>
</body>
</html>