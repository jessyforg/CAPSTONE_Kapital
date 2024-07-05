import { BrowserRouter, Routes, Route } from "react-router-dom";
import ReactDOM from "react-dom";
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
import TBI from "./components/TBI.js";
import InTTOTBI from "./components/InTTOTBI.js";

function MainPage() {
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

export default function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<MainPage />} />
        <Route path="/Tbi" element={<TBI />} />
        <Route path="/Tbi/InTTO" element={<InTTOTBI />} />
      </Routes>
    </BrowserRouter>
  );
}

ReactDOM.render(<App />, document.getElementById("root"));
