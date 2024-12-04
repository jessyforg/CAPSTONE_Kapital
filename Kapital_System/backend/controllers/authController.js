const User = require("../models/User");
const jwt = require("jsonwebtoken");

exports.register = async (req, res) => {
	try {
		const { firstName, lastName, email, password, role } = req.body;

		// Check if user exists
		const existingUser = await User.findOne({ email });
		if (existingUser) {
			return res.status(400).json({
				message: "User already exists",
			});
		}

		// Create new user
		const user = new User({
			firstName,
			lastName,
			email,
			password,
			role,
		});
		await user.save();

		// Generate token
		const token = jwt.sign(
			{
				id: user._id,
				email: user.email,
				role: user.role,
			},
			process.env.JWT_SECRET,
			{ expiresIn: "1d" }
		);

		res.status(201).json({
			message: "User registered successfully",
			token,
			user: {
				id: user._id,
				firstName: user.firstName,
				lastName: user.lastName,
				email: user.email,
				role: user.role,
			},
		});
	} catch (error) {
		res.status(500).json({
			message: "Registration failed",
			error: error.message,
		});
	}
};

exports.login = async (req, res) => {
	try {
		const { email, password } = req.body;

		// Find user
		const user = await User.findOne({ email, isActive: true });
		if (!user) {
			return res.status(401).json({
				message: "Invalid credentials",
			});
		}

		// Compare password
		const isMatch = await user.comparePassword(password);
		if (!isMatch) {
			return res.status(401).json({
				message: "Invalid credentials",
			});
		}

		// Generate token
		const token = jwt.sign(
			{
				id: user._id,
				email: user.email,
				role: user.role,
			},
			process.env.JWT_SECRET,
			{ expiresIn: "1d" }
		);

		res.json({
			token,
			user: {
				id: user._id,
				firstName: user.firstName,
				lastName: user.lastName,
				email: user.email,
				role: user.role,
			},
		});
	} catch (error) {
		res.status(500).json({
			message: "Login failed",
			error: error.message,
		});
	}
};

exports.getCurrentUser = async (req, res) => {
	try {
		const user = await User.findById(req.user.id)
			.select("-password")
			.select("-__v");

		if (!user) {
			return res.status(404).json({ message: "User not found" });
		}

		res.json(user);
	} catch (error) {
		res.status(500).json({
			message: "Error fetching user",
			error: error.message,
		});
	}
};
