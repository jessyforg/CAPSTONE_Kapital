import * as types from "../types/authTypes"; // Recommended to use constants

const initialState = {
	token: localStorage.getItem("token"),
	isAuthenticated: false, // Change from null to false for clarity
	user: null,
	loading: false, // Changed to false by default
	error: null, // Add error handling
};

export default function authReducer(state = initialState, action) {
	switch (action.type) {
		case types.REGISTER_REQUEST:
		case types.LOGIN_REQUEST:
			return {
				...state,
				loading: true,
				error: null,
			};

		case types.REGISTER_SUCCESS:
		case types.LOGIN_SUCCESS:
			localStorage.setItem("token", action.payload.token);
			return {
				...state,
				token: action.payload.token,
				user: action.payload.user,
				isAuthenticated: true,
				loading: false,
				error: null,
			};

		case types.REGISTER_FAIL:
		case types.LOGIN_FAIL:
			localStorage.removeItem("token");
			return {
				...state,
				token: null,
				user: null,
				isAuthenticated: false,
				loading: false,
				error: action.payload, // Store error message
			};

		case types.LOGOUT:
			localStorage.removeItem("token");
			return {
				...state,
				token: null,
				user: null,
				isAuthenticated: false,
				loading: false,
				error: null,
			};

		case types.CLEAR_ERRORS:
			return {
				...state,
				error: null,
			};

		default:
			return state;
	}
}
