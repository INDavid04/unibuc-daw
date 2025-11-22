<!DOCTYPE html>
<!------------------------------------------------------------->
<!-- Creeaza un formular pentru inregistrarea utilizatorilor -->
<!------------------------------------------------------------->

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Create User</h1>
        <!-- Formular care trimite catre exempluCreateUser.php prin metoda post -->
        <form action="exempluCreateUser.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Create User</button>
        </form>
    </div>

    <?php
    /// Incarca clasa Database pentru a gestiona conexiunea la baza de date
    require_once 'exempluDatabase.php';

    /// Verifica daca formularul a fost trimis
    if (isset($_POST['username']) || isset($_POST['email']) || isset($_POST['password'])) {

        /// Conecteaza instanta unica a clasei Database
        try {
            /// PDO = PHP Data Objects
            $pdo = Database::getInstance()->getConnection();
            echo "Connection successful!<br>";
        } catch (PDOException $e) {
            /// Opreste executia scriptului
            die("Connection failed: " . $e->getMessage());
        }

        /// Insereaza un utilizator in tabela user
        try {
            $sql = "INSERT INTO user (name, email, password) 
                    VALUES (:username, :email, :password)";
            
            $stmt = $pdo->prepare($sql);

            /// Creeaza un array cu datele de inserat
            $data = [
                'username' => $_POST['username'],
                'email'    => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
            ];

            /// Executa instructiunea pregatita cu datele din array
            $stmt->execute($data);
            echo "User created successfully!<br>";
        } catch (PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
        }
    }