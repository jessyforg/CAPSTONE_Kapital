// src/components/UserRegistration.js
import React, { useState } from "react";
import axios from "axios";

const UserRegistration = () => {
	const [user, setUser] = useState({
		role: "",
		name: "",
		email: "",
		preferences: {},
	});

	const handleChange = (e) => {
		setUser({ ...user, [e.target.name]: e.target.value });
	};

	const handleSubmit = async (e) => {
		e.preventDefault();
		try {
			const response = await axios.post("http://localhost:5000/api/users", user);
			console.log("User  created:", response.data);
		} catch (error) {
			console.error("Error creating user:", error);
		}
	};

	return (
		<form onSubmit={handleSubmit}>
			<select name="role" onChange={handleChange} required>
				<option value="">Select Role</option>
				<option value="entrepreneur">Entrepreneur</option>
				<option value="investor">Investor</option>
				<option value="job_seeker">Job Seeker</option>
			</select>
			<input
				type="text"
				name="name"
				placeholder="Name"
				onChange={handleChange}
				required
			/>
			<input
				type="email"
				name="email"
				placeholder="Email"
				onChange={handleChange}
				required
			/>
			<textarea
				name="preferences"
				placeholder="Preferences (JSON format)"
				onChange={handleChange}></textarea>
			<button type="submit">Register</button>
		</form>
	);
};

export default UserRegistration;
