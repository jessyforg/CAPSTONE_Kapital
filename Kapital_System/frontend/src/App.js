// src/App.js
import React from "react";
import { BrowserRouter as Router, Route, Link, Routes } from "react-router-dom";
import UserRegistration from "./components/UserRegistration";
import Home from "./components/Home";
import Dashboard from "./components/Dashboard";
import StartupProfile from "./components/StartupProfile";
import InvestorProfile from "./components/InvestorProfile";
import JobSeekerProfile from "./components/JobSeekerProfile";

function App() {
	return (
		<Router>
			<div className="App">
				<nav>
					<ul>
						<li>
							<Link to="/">Home</Link>
						</li>
						<li>
							<Link to="/register">Register</Link>
						</li>
						<li>
							<Link to="/dashboard">Dashboard</Link>
						</li>
					</ul>
				</nav>

				<Routes>
					<Route exact path="/" component={Home} />
					<Route path="/register" component={UserRegistration} />
					<Route path="/dashboard" component={Dashboard} />
					<Route path="/startup-profile" component={StartupProfile} />
					<Route path="/investor-profile" component={InvestorProfile} />
					<Route path="/job-seeker-profile" component={JobSeekerProfile} />
				</Routes>
			</div>
		</Router>
	);
}

export default App;
