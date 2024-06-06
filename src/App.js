import Navbar from "./components/Navbar.js";
import Home from "./components/Home.js";
import About from "./components/About.js";
import Objective from "./components/Objectives.js";
import Team from "./components/Team.js";
import Program from "./components/Program.js";
import Framework from "./components/Framework.js";
import Events from "./components/Events.js";
import FAQ from "./components/FAQ.js";
import Contact from "./components/Contact.js";
import Footer from "./components/Footer.js";

export default function App() {
  return (
    <>
      <Navbar />
      <Home />
      <About />
      <Objective />
      <Team />
      <Program />
      <Framework />
      <Events />
      <FAQ />
      <Contact />
      <Footer />
    </>
  );
}
