import React from "react";
import { Link } from "react-router-dom";
import "./Home.css"; // You can keep this if you have other styles, but it's not needed for Tailwind

function Home() {
	return (
		<div className="p-5 font-sans">
			<div className="text-center bg-gray-100 p-10 rounded-lg mb-5">
				<h1 className="text-4xl mb-2">
					Connecting Entrepreneurs, Investors, and Talent
				</h1>
				<p className="text-lg text-gray-600">
					Discover opportunities, grow your network, and accelerate your success
				</p>

				<div className="flex justify-around flex-wrap mt-5">
					<div className="bg-white border border-gray-300 rounded-lg p-5 m-2 shadow-md w-1/3">
						<img
							src="/entrepreneur-icon.svg"
							alt="Entrepreneur"
							className="w-12 h-12 mb-3 mx-auto"
						/>
						<h3 className="text-xl mb-2">Entrepreneurs</h3>
						<p className="text-gray-700">
							Find investors, build your team, and scale your startup
						</p>
						<Link
							to="/startup-profile"
							className="inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
							Create Startup Profile
						</Link>
					</div>

					<div className="bg-white border border-gray-300 rounded-lg p-5 m-2 shadow-md w-1/3">
						<img
							src="/investor-icon.svg"
							alt="Investor"
							className="w-12 h-12 mb-3 mx-auto"
						/>
						<h3 className="text-xl mb-2">Investors</h3>
						<p className="text-gray-700">
							Discover promising startups and investment opportunities
						</p>
						<Link
							to="/investor-profile"
							className="inline-block bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">
							Create Investor Profile
						</Link>
					</div>

					<div className="bg-white border border-gray-300 rounded-lg p-5 m-2 shadow-md w-1/3">
						<img
							src="/jobseeker-icon.svg"
							alt="Job Seeker"
							className="w-12 h-12 mb-3 mx-auto"
						/>
						<h3 className="text-xl mb-2">Job Seekers</h3>
						<p className="text-gray-700">Find exciting roles in innovative startups</p>
						<Link
							to="/job-seeker-profile"
							className="inline-block bg-teal-500 text-white py-2 px-4 rounded hover:bg-teal-600">
							Create Job Seeker Profile
						</Link>
					</div>
				</div>
			</div>

			<section className="mt-10 text-center">
				<h2 className="text-3xl mb-5">Platform Features</h2>
				<div className="flex justify-around flex-wrap">
					<div className="bg-white border border-gray-300 rounded-lg p-5 m-2 shadow-md w-1/3">
						<h4 className="text-xl mb-2">AI Matchmaking</h4>
						<p className="text-gray-700">Smart algorithms connect the right people</p>
					</div>
					<div className="bg-white border border-gray-300 rounded-lg p-5 m-2 shadow-md w-1/3">
						<h4 className="text-xl mb-2">Secure Networking</h4>
						<p className="text-gray-700">Protected communication channels</p>
					</div>
					<div className="bg-white border border-gray-300 rounded-lg p-5 m-2 shadow-md w-1/3">
						<h4 className="text-xl mb-2">Comprehensive Profiles</h4>
						<p className="text-gray-700">Detailed professional information</p>
					</div>
				</div>
			</section>
		</div>
	);
}

export default Home;
