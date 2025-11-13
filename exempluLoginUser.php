<!DOCTYPE html>

<!--------------------------------------------------------------------->
<!-- Verifica mail si parola din formular cu datele din baza de date -->
<!--------------------------------------------------------------------->

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Login User</h1>
        <form action="login-user.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

    <?php
    /// Incarca clasa Database pentru a gestiona conexiunea la baza de date
    require_once 'exempluDatabase.php';

    /// Verifica daca formularul a fost trimis
    if (isset($_POST['email']) && isset($_POST['password'])) {

    /// Conecteaza instanta unica a clasei Database
    try {
        $pdo = Database::getInstance()->getConnection();
        echo "Connection successful!<br>";
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    /// Cauta utilizatorul in tabela user dupa email
    try {
        /// Pregateste interogarea SQL
        $sql = "SELECT * FROM user WHERE email = :email";
        
        /// Pregateste statement-ul
        $stmt = $pdo->prepare($sql);

        /// Creeaza un array cu datele pentru interogare
        $data = [
            'email' => $_POST['email']
        ];

        /// Executa interogarea
        $stmt->execute($data);
        
        /// Preia rezultatul
        $user = $stmt->fetch();

        /// Afiseaza datele utilizatorului gasit
        var_dump($user);

        /// Verifica parola
        if ($user && password_verify($_POST['password'], $user['password'])) {
            echo "Login successful! Welcome, " . htmlspecialchars($user['name']) . ".<br>";
        } else {
            echo "Invalid email or password.<br>";
        }
    } catch (PDOException $e) {
        echo "Query failed: " . $e->getMessage();
    }
    }
    ?>