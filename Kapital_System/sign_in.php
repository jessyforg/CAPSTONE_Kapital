<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Kapital</title>
    <link rel="stylesheet" href="sign_in.css">
</head>
<body>
    <div class="container">
        <h2>Sign In</h2>
        <form action="signin_process.php" method="POST">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Sign In</button>
        </form>
        <p>Don't have an account? <a href="sign_up.php" class="sign-up-btn">Sign Up</a></p> <!-- Button updated -->
    </div>
</body>
</html>