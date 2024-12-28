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

            // Hide all fields by default
            investorFields.style.display = "none";
            jobSeekerFields.style.display = "none";
            adminNotice.style.display = "none";

            if (role === "investor") {
                investorFields.style.display = "block";
            } else if (role === "job_seeker") {
                jobSeekerFields.style.display = "block";
            } else if (role === "admin") {
                adminNotice.style.display = "block";
            }
        }

        function showOtherIndustryField(selectId, otherFieldId) {
            var industrySelect = document.getElementById(selectId);
            var otherIndustryField = document.getElementById(otherFieldId);

            if (industrySelect.value === "other") {
                otherIndustryField.style.display = "block";
            } else {
                otherIndustryField.style.display = "none";
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
                <option value="admin">Admin</option>
            </select>

            <!-- Investor Specific Fields -->
            <div id="investorFields" style="display:none;">
                <label for="investment_range_min">Investment Range (Min)</label>
                <input type="number" id="investment_range_min" name="investment_range_min" step="0.01">

                <label for="investment_range_max">Investment Range (Max)</label>
                <input type="number" id="investment_range_max" name="investment_range_max" step="0.01">

                <label for="preferred_industries_dropdown">Preferred Industries</label>
                <select id="preferred_industries_dropdown" name="preferred_industries_dropdown"
                    onchange="showOtherIndustryField('preferred_industries_dropdown', 'investorOtherIndustryField')"
                    required>
                    <option value="" disabled>Choose here</option>
                    <option value="technology">Technology</option>
                    <option value="healthcare">Healthcare</option>
                    <option value="finance">Finance</option>
                    <option value="education">Education</option>
                    <option value="other">Other</option>
                </select>

                <div id="investorOtherIndustryField" style="display:none; margin-top: 10px;">
                    <label for="other_industry">Please Specify</label>
                    <input type="text" id="other_industry" name="other_industry">
                </div>

                <label for="bio">Bio</label>
                <textarea id="bio" name="bio"></textarea>
            </div>

            <!-- Job Seeker Specific Fields -->
            <div id="jobSeekerFields" style="display:none;">
                <label for="jobSeekerPreferredIndustries">Preferred Industries</label>
                <select id="jobSeekerPreferredIndustries" name="job_seeker_preferred_industries"
                    onchange="showOtherIndustryField('jobSeekerPreferredIndustries', 'jobSeekerOtherIndustryField')"
                    required>
                    <option value="" disabled>Choose here</option>
                    <option value="technology">Technology</option>
                    <option value="healthcare">Healthcare</option>
                    <option value="finance">Finance</option>
                    <option value="education">Education</option>
                    <option value="other">Other</option>
                </select>

                <div id="jobSeekerOtherIndustryField" style="display:none; margin-top: 10px;">
                    <label for="job_seeker_other_industry">Please Specify</label>
                    <input type="text" id="job_seeker_other_industry" name="job_seeker_other_industry">
                </div>

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

            <!-- Admin Notice -->
            <div id="adminNotice" style="display:none; color: red; margin-top: 10px;">
                <p>Admins will have access to the admin panel. Please ensure this account is authorized for
                    administrative purposes.</p>
            </div>

            <button type="submit">Sign Up</button>
        </form>

        <p>Already have an account? <a href="sign_in.php">Login here</a></p>
    </div>
</body>

</html>