/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      screens: {
      'phone': '360px',
      'tablet': '768px',
      'laptop': '1024px',
      'laptop-s': '1280px',
      'laptop-m': '1440px',
      'desktop-s': '1600px',
      'desktop-m': '1920px',
      },
      colors: {
      trkblack: '#151515',
      
      
      },
    },
    fontFamily: {
      poppins: ["Poppins", "sans-serif"],
      satoshi: ["Satoshi", "sans-serif"],
    },
  },
  plugins: [],
}