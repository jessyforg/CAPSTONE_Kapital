<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="sign_in.css">
</head>
<body>
    <div class="container">
        <h2>Sign In</h2>
        <form id="signin-form">
            <label for="email">Email</label>
            <input type="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" required>

            <button type="submit">Sign In</button>
        </form>
        <p>Don't have an account? <a href="sign_up.php">Sign Up</a></p>
    </div>

    <!-- Firebase SDKs (Use v8 for compatibility) -->
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-auth.js"></script>

    <script>
        // Firebase Configuration
        const firebaseConfig = {
            apiKey: "AIzaSyD5rbuZ6-lS7Ht9ngBcq2bbaESXe0s1rqA",
            authDomain: "kapital-a798a.firebaseapp.com",
            projectId: "kapital-a798a",
            storageBucket: "kapital-a798a.appspot.com",
            messagingSenderId: "955648087491",
            appId: "1:955648087491:web:85ea0183753d0047295976",
            measurementId: "G-KWL2YQWGWL"
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        const auth = firebase.auth();

        document.getElementById("signin-form").addEventListener("submit", function (event) {
            event.preventDefault();
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            auth.signInWithEmailAndPassword(email, password)
                .then((userCredential) => {
                    return userCredential.user.getIdToken();
                })
                .then((token) => {
                    return fetch("signin_process.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ firebase_token: token })
                    });
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect; // Redirect on success
                    } else {
                        alert(data.error); // Show error message
                    }
                })
                .catch(error => alert("Error: " + error.message));
        });
    </script>
</body>
</html>
