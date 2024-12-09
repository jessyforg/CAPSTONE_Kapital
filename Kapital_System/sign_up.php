<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - StartupConnect</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form action="signup_process.php" method="POST">
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" placeholder="Enter your full name" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Enter your email address" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Create a password" required>

            <label for="retype_password">Retype Password</label>
            <input type="password" name="retype_password" id="retype_password" placeholder="Retype your password"
                required>

            <label for="role">Role</label>
            <select name="role" id="role" required>
                <option value="entrepreneur">Entrepreneur</option>
                <option value="investor">Investor</option>
                <option value="job_seeker">Job Seeker</option>
            </select>

            <button type="submit">Sign Up</button>
        </form>

        <p>Already have an account? <a href="sign_in.php">Sign In</a></p>
    </div>
</body>

</html>