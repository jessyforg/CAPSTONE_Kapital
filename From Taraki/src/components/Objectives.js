import React, { useEffect } from "react";
import AOS from "aos";
import "aos/dist/aos.css";
import icon1 from "../components/imgs/2.webp";
import icon2 from "../components/imgs/3.webp";
import icon3 from "../components/imgs/5.webp";
import icon4 from "../components/imgs/6.webp";
import icon5 from "../components/imgs/7.webp";
import icon6 from "../components/imgs/8.webp";

function Objective() {
  useEffect(() => {
    AOS.init({
      duration: 800, // Animation duration
      easing: "ease", // Easing function
      once: false,
    });
  }, []);
  return (
    <div>
      <div className="font-satoshi overflow-hidden">
        <div className="cont">
          <section id="objectives" className="mt-16 tablet:mt-12 text-center">
            <h1
              className="font-semibold text-md tablet:text-lg tablet-m:text-2xl laptop-s:text-3xl laptop-m:text-[2.3rem] desktop-m:text-[2.9rem] laptop-s:py-5 aos-init"
              data-aos="fade-up"
            >
              Objectives
            </h1>
          </section>
          <section className="tablet:grid tablet:grid-cols-2 tablet-m:grid-cols-3 phone:pb-[0.85rem] tablet:px-10 laptop-s:py-10 leading-loose">
            <div className="mt-5 transition-all duration-300 hover:scale-110">
              <img
                src={icon1}
                alt="1st-ico"
                className="w-52 tablet-m:w-56 mx-auto desktop-m:w-72 aos-init"
                data-aos="fade-right"
              />
              <p
                className="text-sm tablet-m:text-[0.8rem] laptop-s:text-[1rem] laptop-m:text-[1.3rem] desktop-m:text-[1.6rem] text-center font-regular mt-1 px-16 aos-init"
                data-aos="fade-right"
              >
                Establishment of a 5-yr Regional Startup Development Plan and
                Roadmaps
              </p>
            </div>
            <div className="mt-5 transition-all duration-300 hover:scale-110">
              <img
                src={icon2}
                alt="2nd-ico"
                className="w-52 tablet-m:w-56 mx-auto desktop-m:w-72 aos-init"
                data-aos="fade-down"
              />
              <p
                className="text-sm tablet-m:text-[0.8rem] laptop-s:text-[1rem] laptop-m:text-[1.3rem] desktop-m:text-[1.6rem] text-center font-regular mt-1 px-16 aos-init"
                data-aos="fade-down"
              >
                Increasing Awareness about the Consortium
              </p>
            </div>
            <div className="mt-5 transition-all duration-300 hover:scale-110">
              <img
                src={icon3}
                alt="3rd-ico"
                className="w-52 tablet-m:w-56 mx-auto desktop-m:w-72 aos-init"
                data-aos="fade-left"
              />
              <p
                className="text-sm tablet-m:text-[0.8rem] laptop-s:text-[1rem] laptop-m:text-[1.3rem] desktop-m:text-[1.6rem] text-center font-regular mt-1 px-16 aos-init"
                data-aos="fade-left"
              >
                Upskilling and Upscaling Activities
              </p>
            </div>
            <div className="mt-5 transition-all duration-300 hover:scale-110">
              <img
                src={icon4}
                alt="4th-ico"
                className="w-52 tablet-m:w-56 mx-auto desktop-m:w-72 aos-init"
                data-aos="fade-right"
              />
              <p
                className="text-sm tablet-m:text-[0.8rem] laptop-s:text-[1rem] laptop-m:text-[1.3rem] desktop-m:text-[1.6rem] text-center font-regular mt-1 px-16 aos-init"
                data-aos="fade-right"
              >
                Establishment of Local Investor Network
              </p>
            </div>
            <div className="mt-5 transition-all duration-300 hover:scale-110">
              <img
                src={icon5}
                alt="5th-ico"
                className="w-52 tablet-m:w-56 mx-auto desktop-m:w-72 aos-init"
                data-aos="fade-up"
              />
              <p
                className="text-sm tablet-m:text-[0.8rem] laptop-s:text-[1rem] laptop-m:text-[1.3rem] desktop-m:text-[1.6rem] text-center font-regular mt-1 px-16 aos-init"
                data-aos="fade-up"
              >
                Cross Pollination Undertakings Among Stakeholders
              </p>
            </div>
            <div className="mt-5 transition-all duration-300 hover:scale-110">
              <img
                src={icon6}
                alt="6th-ico"
                className="w-52 tablet-m:w-56 mx-auto desktop-m:w-72 aos-init"
                data-aos="fade-left"
              />
              <p
                className="text-sm tablet-m:text-[0.8rem] laptop-s:text-[1rem] laptop-m:text-[1.3rem] desktop-m:text-[1.6rem] text-center font-regular mt-1 px-16 aos-init"
                data-aos="fade-left"
              >
                Activating startup activity hubs in lesser active provinces in
                the regions
              </p>
            </div>
          </section>
        </div>
      </div>
    </div>
  );
}

export default Objective;
