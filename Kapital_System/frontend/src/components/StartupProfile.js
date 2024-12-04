import React, { useState } from "react";
import axios from "axios";
import "./StartupProfile.css";

function StartupProfile() {
	const [startupData, setStartupData] = useState({
		name: "",
		industry: "",
		foundedYear: "",
		teamSize: "",
		description: "",
		fundingStage: "",
		technologies: [],
		socialLinks: {
			website: "",
			linkedin: "",
			twitter: "",
		},
	});

	const handleSubmit = async (e) => {
		e.preventDefault();
		try {
			await axios.post("/api/startup-profiles", startupData);
			// Redirect or show success message
		} catch (error) {
			console.error("Error creating startup profile", error);
		}
	};

	const handleChange = (e) => {
		const { name, value } = e.target;
		setStartupData((prev) => ({
			...prev,
			[name]: value,
		}));
	};

	return (
		<div className="startup-profile-container">
			<h1>Create Startup Profile</h1>
			<form onSubmit={handleSubmit}>
				<div className="form-grid">
					<input
						type="text"
						name="name"
						placeholder="Startup Name"
						value={startupData.name}
						onChange={handleChange}
						required
					/>
					<select
						name="industry"
						value={startupData.industry}
						onChange={handleChange}
						required>
						<option value="">Select Industry</option>
						<option value="tech">Technology</option>
						<option value="fintech">FinTech</option>
						{/* More options */}
					</select>
					<input
						type="number"
						name="foundedYear"
						placeholder="Founded Year"
						value={startupData.foundedYear}
						onChange={handleChange}
					/>
					<textarea
						name="description"
						placeholder="Startup Description"
						value={startupData.description}
						onChange={handleChange}
						required
					/>
					<button type="submit">Create Profile</button>
				</div>
			</form>
		</div>
	);
}

export default StartupProfile;
