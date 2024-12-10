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

            investorFields.style.display = "none";
            jobSeekerFields.style.display = "none";

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

            <!-- Investor Specific Fields -->
            <div id="investorFields" style="display:none;">
                <label for="investment_range_min">Investment Range (Min)</label>
                <input type="number" id="investment_range_min" name="investment_range_min" step="0.01">

                <label for="investment_range_max">Investment Range (Max)</label>
                <input type="number" id="investment_range_max" name="investment_range_max" step="0.01">

                <label for="preferred_industries">Preferred Industries (Comma-separated)</label>
                <input type="text" id="preferred_industries" name="preferred_industries">

                <label for="bio">Bio</label>
                <textarea id="bio" name="bio"></textarea>
            </div>

            <!-- Job Seeker Specific Fields -->
            <div id="jobSeekerFields" style="display:none;">
                <label for="desired_role">Desired Role</label>
                <input type="text" id="desired_role" name="desired_role">

                <label for="bio">Bio</label>
                <textarea id="bio" name="bio"></textarea>

                <label for="skills">Skills (Comma-separated)</label>
                <input type="text" id="skills" name="skills">

                <label for="experience_level">Experience Level</label>
                <select id="experience_level" name="experience_level" required>
                    <option value="entry">Entry</option>
                    <option value="mid">Mid</option>
                    <option value="senior">Senior</option>
                </select>

                <label for="location_preference">Location Preference</label>
                <input type="text" id="location_preference" name="location_preference">
            </div>

            <button type="submit">Sign Up</button>
        </form>

        <p>Already have an account? <a href="sign_in.php">Login here</a></p>
    </div>
</body>

</html>