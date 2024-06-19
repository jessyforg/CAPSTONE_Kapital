import React, { useEffect } from "react";
import AOS from "aos";
import "aos/dist/aos.css";
import icon1 from "../components/imgs/2.png";
import icon2 from "../components/imgs/3.png";
import icon3 from "../components/imgs/5.png";
import icon4 from "../components/imgs/6.png";
import icon5 from "../components/imgs/7.png";
import icon6 from "../components/imgs/8.png";

function Objective() {
  useEffect(() => {
    AOS.init({
      duration: 1000, // Animation duration
      easing: "ease-in-out", // Easing function
      once: false,
    });
  }, []);
  return (
    <div>
      <div className="font-satoshi overflow-hidden">
        <div className="cont">
          <section className="mt-16 tablet:mt-12 text-center">
            <h1
              className="font-semibold text-md tablet:text-lg tablet-m:text-2xl laptop-s:text-3xl laptop-m:text-[2.3rem] desktop-m:text-[2.9rem] laptop-s:py-5"
              data-aos="fade-up"
            >
              Objectives
            </h1>
          </section>
          <section className="tablet:grid tablet:grid-cols-2 tablet-m:grid-cols-3 tablet:px-10 laptop-s:py-10">
            <div
              className="mt-5 transition-all duration-300 hover:scale-110"
              data-aos="fade-right"
            >
              <img
                src={icon1}
                alt="1st-ico"
                className="w-52 tablet-m:w-56 mx-auto desktop-m:w-72"
              />
              <p className="text-sm tablet-m:text-[0.8rem] laptop-s:text-[1rem] laptop-m:text-[1.3rem] desktop-m:text-[1.6rem] text-center font-regular mt-1 px-16">
                Establishment of a 5-yr Regional Startup Development Plan and
                Roadmaps
              </p>
            </div>
            <div
              className="mt-5 transition-all duration-300 hover:scale-110"
              data-aos="fade-down"
            >
              <img
                src={icon2}
                alt="2nd-ico"
                className="w-52 tablet-m:w-56 mx-auto desktop-m:w-72"
              />
              <p className="text-sm tablet-m:text-[0.8rem] laptop-s:text-[1rem] laptop-m:text-[1.3rem] desktop-m:text-[1.6rem] text-center font-regular mt-1 px-16">
                Increasing Awareness about the Consortium
              </p>
            </div>
            <div
              className="mt-5 transition-all duration-300 hover:scale-110"
              data-aos="fade-left"
            >
              <img
                src={icon3}
                alt="3rd-ico"
                className="w-52 tablet-m:w-56 mx-auto desktop-m:w-72"
              />
              <p className="text-sm tablet-m:text-[0.8rem] laptop-s:text-[1rem] laptop-m:text-[1.3rem] desktop-m:text-[1.6rem] text-center font-regular mt-1 px-16">
                Upskilling and Upscaling Activities
              </p>
            </div>
            <div
              className="mt-5 transition-all duration-300 hover:scale-110"
              data-aos="fade-right"
            >
              <img
                src={icon4}
                alt="4th-ico"
                className="w-52 tablet-m:w-56 mx-auto desktop-m:w-72"
              />
              <p className="text-sm tablet-m:text-[0.8rem] laptop-s:text-[1rem] laptop-m:text-[1.3rem] desktop-m:text-[1.6rem] text-center font-regular mt-1 px-16">
                Establishment of Local Investor Network
              </p>
            </div>
            <div
              className="mt-5 transition-all duration-300 hover:scale-110"
              data-aos="fade-up"
            >
              <img
                src={icon5}
                alt="5th-ico"
                className="w-52 tablet-m:w-56 mx-auto desktop-m:w-72"
              />
              <p className="text-sm tablet-m:text-[0.8rem] laptop-s:text-[1rem] laptop-m:text-[1.3rem] desktop-m:text-[1.6rem] text-center font-regular mt-1 px-16">
                Cross Pollination Undertakings Among Stakeholders
              </p>
            </div>
            <div
              className="mt-5 transition-all duration-300 hover:scale-110"
              data-aos="fade-left"
            >
              <img
                src={icon6}
                alt="6th-ico"
                className="w-52 tablet-m:w-56 mx-auto desktop-m:w-72"
              />
              <p className="text-sm tablet-m:text-[0.8rem] laptop-s:text-[1rem] laptop-m:text-[1.3rem] desktop-m:text-[1.6rem] text-center font-regular mt-1 px-16">
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
