const express = require("express");
const router = express.Router();
const {
	register,
	login,
	getCurrentUser,
} = require("../controllers/authController");
const {
	authMiddleware,
	roleMiddleware,
} = require("../middleware/authMiddleware");

// Public Routes
router.post("/register", register);
router.post("/login", login);

// Protected Routes
router.get("/me", authMiddleware, getCurrentUser);

module.exports = router;
