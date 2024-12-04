import React, { useState } from "react";
import axios from "axios";
import "./InvestorProfile.css";

function InvestorProfile() {
	const [investorData, setInvestorData] = useState({
		name: "",
		firmName: "",
		investmentStage: "",
		industries: [], // Consider making this a multi-select
		checkSize: "",
		location: "",
		bio: "",
	});

	// Added error state for form validation
	const [errors, setErrors] = useState({});

	const validateForm = () => {
		const newErrors = {};

		if (!investorData.name.trim()) {
			newErrors.name = "Name is required";
		}

		if (!investorData.investmentStage) {
			newErrors.investmentStage = "Investment stage is required";
		}

		// Add more validation as needed
		setErrors(newErrors);
		return Object.keys(newErrors).length === 0;
	};

	const handleSubmit = async (e) => {
		e.preventDefault();

		// Validate form before submission
		if (!validateForm()) {
			return;
		}

		try {
			const response = await axios.post("/api/investor-profiles", investorData);

			// Added success handling
			console.log("Profile created successfully", response.data);

			// Optional: Reset form or redirect
			// history.push('/dashboard');
		} catch (error) {
			console.error("Error creating investor profile", error);

			// Handle specific error scenarios
			if (error.response) {
				// The request was made and the server responded with a status code
				alert(`Error: ${error.response.data.message}`);
			} else if (error.request) {
				// The request was made but no response was received
				alert("No response received from server");
			} else {
				// Something happened in setting up the request
				alert("Error creating profile");
			}
		}
	};

	const handleChange = (e) => {
		const { name, value } = e.target;
		setInvestorData((prev) => ({
			...prev,
			[name]: value,
		}));

		// Clear error when user starts typing
		if (errors[name]) {
			setErrors((prev) => ({
				...prev,
				[name]: "",
			}));
		}
	};

	// Added method to handle multi-select for industries
	const handleIndustriesChange = (e) => {
		const selectedIndustries = Array.from(
			e.target.selectedOptions,
			(option) => option.value
		);
		setInvestorData((prev) => ({
			...prev,
			industries: selectedIndustries,
		}));
	};

	return (
		<div className="investor-profile-container">
			<h1>Investor Profile</h1>
			<form onSubmit={handleSubmit}>
				<div className="form-group">
					<input
						type="text"
						name="name"
						placeholder="Your Name"
						value={investorData.name}
						onChange={handleChange}
						required
					/>
					{errors.name && <span className="error">{errors.name}</span>}
				</div>

				<div className="form-group">
					<input
						type="text"
						name="firmName"
						placeholder="Firm Name"
						value={investorData.firmName}
						onChange={handleChange}
					/>
				</div>

				<div className="form-group">
					<select
						name="investmentStage"
						value={investorData.investmentStage}
						onChange={handleChange}
						required>
						<option value="">Select Investment Stage</option>
						<option value="seed">Seed</option>
						<option value="series-a">Series A</option>
						<option value="series-b">Series B</option>
						<option value="series-c">Series C</option>
						<option value="growth">Growth</option>
					</select>
					{errors.investmentStage && (
						<span className="error">{errors.investmentStage}</span>
					)}
				</div>

				<div className="form-group">
					<select
						multiple
						name="industries"
						value={investorData.industries}
						onChange={handleIndustriesChange}
						placeholder="Select Industries">
						<option value="tech">Technology</option>
						<option value="fintech">FinTech</option>
						<option value="healthcare">Healthcare</option>
						<option value="ai">Artificial Intelligence</option>
						<option value="biotech">Biotechnology</option>
					</select>
				</div>

				<div className="form-group">
					<input
						type="text"
						name="checkSize"
						placeholder="Typical Check Size ($)"
						value={investorData.checkSize}
						onChange={handleChange}
					/>
				</div>

				<div className="form-group">
					<input
						type="text"
						name="location"
						placeholder="Location"
						value={investorData.location}
						onChange={handleChange}
					/>
				</div>

				<div className="form-group">
					<textarea
						name="bio"
						placeholder="Professional Bio"
						value={investorData.bio}
						onChange={handleChange}
						rows="4"
					/>
				</div>

				<button type="submit" className="submit-btn">
					Create Profile
				</button>
			</form>
		</div>
	);
}

export default InvestorProfile;
