import React, { useState } from "react";
import axios from "axios";
import "./JobSeekerProfile.css";

function JobSeekerProfile() {
	const [jobSeekerData, setJobSeekerData] = useState({
		name: "",
		email: "",
		skills: "",
		experience: "",
		resume: null,
	});

	const handleSubmit = async (e) => {
		e.preventDefault();
		const formData = new FormData();
		for (const key in jobSeekerData) {
			formData.append(key, jobSeekerData[key]);
		}
		try {
			await axios.post("/api/job-seeker-profiles", formData);
			// Redirect or show success message
		} catch (error) {
			console.error("Error creating job seeker profile", error);
		}
	};

	const handleChange = (e) => {
		const { name, value } = e.target;
		setJobSeekerData((prev) => ({
			...prev,
			[name]: value,
		}));
	};

	const handleFileChange = (e) => {
		setJobSeekerData((prev) => ({
			...prev,
			resume: e.target.files[0],
		}));
	};

	return (
		<div className="job-seeker-profile-container">
			<h1>Create Job Seeker Profile</h1>
			<form onSubmit={handleSubmit}>
				<input
					type="text"
					name="name"
					placeholder="Your Name"
					value={jobSeekerData.name}
					onChange={handleChange}
					required
				/>
				<input
					type="email"
					name="email"
					placeholder="Your Email"
					value={jobSeekerData.email}
					onChange={handleChange}
					required
				/>
				<input
					type="text"
					name="skills"
					placeholder="Skills (comma separated)"
					value={jobSeekerData.skills}
					onChange={handleChange}
				/>
				<textarea
					name="experience"
					placeholder="Experience"
					value={jobSeekerData.experience}
					onChange={handleChange}
				/>
				<input type="file" name="resume" onChange={handleFileChange} required />
				<button type="submit">Create Profile</button>
			</form>
		</div>
	);
}

export default JobSeekerProfile;
