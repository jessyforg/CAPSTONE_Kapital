import React, { useState, useEffect } from "react";
import axios from "axios";
import "./Dashboard.css";

function Dashboard() {
	const [user, setUser] = useState(null);
	const [recommendations, setRecommendations] = useState([]);

	useEffect(() => {
		// Fetch user data and recommendations
		const fetchData = async () => {
			try {
				const userResponse = await axios.get("/api/user");
				setUser(userResponse.data);

				const recommendationsResponse = await axios.get("/api/recommendations");
				setRecommendations(recommendationsResponse.data);
			} catch (error) {
				console.error("Error fetching dashboard data", error);
			}
		};

		fetchData();
	}, []);

	if (!user) return <div>Loading...</div>;

	return (
		<div className="dashboard-container">
			<header className="dashboard-header">
				<h1>Welcome, {user.name}</h1>
				<p>Role: {user.role}</p>
			</header>

			<div className="dashboard-grid">
				<section className="profile-section">
					<h2>Your Profile</h2>
					{/* Profile preview */}
				</section>

				<section className="recommendations-section">
					<h2>Recommendations</h2>
					{recommendations.map((recommendation) => (
						<div key={recommendation.id} className="recommendation-card">
							<h3>{recommendation.name}</h3>
							<p>{recommendation.description}</p>
							<button>Connect</button>
						</div>
					))}
				</section>

				<section className="activity-section">
					<h2>Recent Activity</h2>
					{/* Activity feed */}
				</section>
			</div>
		</div>
	);
}

export default Dashboard;
