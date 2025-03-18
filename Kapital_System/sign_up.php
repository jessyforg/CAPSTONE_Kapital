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
        <form method="POST" action="signup_process.php" enctype="multipart/form-data">
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

            <!-- Job Seeker Fields -->
            <div id="jobSeekerFields" style="display: none;">
                <label for="resume">Resume (Optional)</label>
                <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx">
                <small>Supported formats: PDF, DOC, DOCX (Max size: 5MB)</small>
                
                <label for="skills">Skills (Optional)</label>
                <input type="text" id="skills" name="skills" placeholder="Enter skills separated by commas">
                
                <label for="experience_level">Experience Level</label>
                <select id="experience_level" name="experience_level" required>
                    <option value="entry">Entry Level</option>
                    <option value="mid">Mid Level</option>
                    <option value="senior">Senior Level</option>
                </select>
                
                <label for="desired_role">Desired Role (Optional)</label>
                <input type="text" id="desired_role" name="desired_role" placeholder="e.g., Software Developer">
                
                <label for="location_preference">Preferred Location (Optional)</label>
                <input type="text" id="location_preference" name="location_preference" placeholder="e.g., New York">
            </div>

            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="sign_in.php">Login here</a></p>
    </div>
</body>
</html>