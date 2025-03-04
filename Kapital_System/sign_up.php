<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="sign_up.css">
    <script>
        function toggleRoleFields() {
            var role = document.getElementById("role").value;
            var investorFields = document.getElementById("investorFields");
            var jobSeekerFields = document.getElementById("jobSeekerFields");
            var adminNotice = document.getElementById("adminNotice");
            investorFields.style.display = "none";
            jobSeekerFields.style.display = "none";
            adminNotice.style.display = "none";
            if (role === "investor") {
                investorFields.style.display = "block";
            } else if (role === "job_seeker") {
                jobSeekerFields.style.display = "block";
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form method="POST" action="signup_process.php">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <label for="retype_password">Retype Password</label>
            <input type="password" id="retype_password" name="retype_password" required>
            <label for="role">Role</label>
            <select id="role" name="role" onchange="toggleRoleFields()" required>
                <option value="entrepreneur">Entrepreneur</option>
                <option value="investor">Investor</option>
                <option value="job_seeker">Job Seeker</option>
            </select>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="sign_in.php">Login here</a></p>
    </div>
</body>
</html>