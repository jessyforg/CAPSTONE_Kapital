// firebase-config.js
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-app.js";
import { getAuth } from "https://www.gstatic.com/firebasejs/10.6.0/firebase-auth.js";

const firebaseConfig = {
	apiKey: "AIzaSyD5rbuZ6-lS7Ht9ngBcq2bbaESXe0s1rqA",
	authDomain: "kapital-a798a.firebaseapp.com",
	databaseURL:
		"https://kapital-a798a-default-rtdb.asia-southeast1.firebasedatabase.app",
	projectId: "kapital-a798a",
	storageBucket: "kapital-a798a.firebasestorage.app",
	messagingSenderId: "955648087491",
	appId: "1:955648087491:web:85ea0183753d0047295976",
	measurementId: "G-KWL2YQWGWL",
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);

export { auth };
