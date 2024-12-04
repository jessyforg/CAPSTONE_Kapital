const jwt = require("jsonwebtoken");

const authMiddleware = (req, res, next) => {
	// Get token from header
	const token = req.header("Authorization")?.replace("Bearer ", "");

	// Check if no token
	if (!token) {
		return res.status(401).json({ message: "No token, authorization denied" });
	}

	try {
		// Verify token
		const decoded = jwt.verify(token, process.env.JWT_SECRET);

		// Add user from payload
		req.user = decoded;
		next();
	} catch (error) {
		res.status(401).json({ message: "Token is not valid" });
	}
};

// Role-based authorization
const roleMiddleware = (roles) => {
	return (req, res, next) => {
		if (!roles.includes(req.user.role)) {
			return res.status(403).json({
				message: "Access denied",
			});
		}
		next();
	};
};

module.exports = {
	authMiddleware,
	roleMiddleware,
};
