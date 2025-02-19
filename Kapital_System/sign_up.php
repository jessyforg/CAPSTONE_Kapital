<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="sign_up.css">

    <!-- ✅ Fixed Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-auth-compat.js"></script>

    <script>
        // ✅ Firebase Configuration
        const firebaseConfig = {
            apiKey: "AIzaSyD5rbuZ6-lS7Ht9ngBcq2bbaESXe0s1rqA",
            authDomain: "kapital-a798a.firebaseapp.com",
            databaseURL: "https://kapital-a798a-default-rtdb.asia-southeast1.firebasedatabase.app",
            projectId: "kapital-a798a",
            storageBucket: "kapital-a798a.appspot.com",
            messagingSenderId: "955648087491",
            appId: "1:955648087491:web:85ea0183753d0047295976",
            measurementId: "G-KWL2YQWGWL"
        };

        // ✅ Initialize Firebase
        firebase.initializeApp(firebaseConfig);
    </script>
</head>

<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form id="signup_form" method="POST" action="signup_process.php">
            <input type="hidden" id="firebase_token" name="firebase_token">
            
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="retype_password">Retype Password</label>
            <input type="password" id="retype_password" name="retype_password" required>

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="entrepreneur">Entrepreneur</option>
                <option value="investor">Investor</option>
                <option value="job_seeker">Job Seeker</option>
            </select>

            <button type="submit" onclick="firebaseSignUp(event)">Sign Up</button>
        </form>

        <p>Already have an account? <a href="sign_in.php">Login here</a></p>
    </div>

    <!-- ✅ Fixed Firebase Signup Script -->
    <script>
        function firebaseSignUp(event) {
            event.preventDefault();

            let email = document.getElementById("email").value.trim();
            let password = document.getElementById("password").value;
            let retypePassword = document.getElementById("retype_password").value;

            if (password !== retypePassword) {
                alert("Passwords do not match!");
                return;
            }

            firebase.auth().createUserWithEmailAndPassword(email, password)
                .then((userCredential) => {
                    return userCredential.user.getIdToken();
                })
                .then((token) => {
                    document.getElementById("firebase_token").value = token;
                    document.getElementById("signup_form").submit(); // ✅ Submit only after getting the token
                })
                .catch((error) => {
                    alert("Error: " + error.message);
                });
        }
    </script>
</body>
</html>
