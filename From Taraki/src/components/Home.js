import React, { useEffect } from "react";
import { Link } from "react-scroll";
import AOS from "aos";
import "aos/dist/aos.css";

import dost from "./imgs/dost.webp";
import uc from "./imgs/uc.webp";
import dict from "./imgs/dict.webp";
import neda from "./imgs/neda.webp";
import dti from "./imgs/dti.webp";
import da from "./imgs/da.webp";
import peza from "./imgs/peza.webp";
import video from "../components/imgs/taraki-home-video.webm";

function Home() {
  useEffect(() => {
    document.title = "Welcome to TARAKI";
  }, []);
  useEffect(() => {
    AOS.init({
      duration: 800, // Animation duration
      easing: "ease", // Easing function
      once: false,
    });
  }, []);

  return (
    <div>
      <div className="phone:bg-trkblack tablet:bg-transparent font-satoshi overflow-x-hidden pt-12">
        <section
          id="home"
          className="text-center pt-16 tablet-m:py-28 laptop-s:py-32 laptop-m:py-32 desktop-m:py-[18rem]"
        >
          <div className="w-full h-full overflow-hidden">
            <video
              autoPlay
              loop
              muted
              playsInline
              className=" phone:hidden tablet:block absolute tablet:top-16 laptop-s:top-24 laptop-m:top-28 left-0 m-auto w-full"
              loading="lazy"
            >
              <source src={video} type="video/webm" />
              Your browser does not support the video tag.
            </video>
            <div className="relative z-10 tablet:pt-[3rem] tablet-m:pt-[7rem] laptop-s:pt-32 laptop-m:pt-56 desktop-m:pt-32">
              <h1
                className="text-white text-3xl tablet:text-4xl laptop-s:text-5xl desktop-s:text-[3.6rem] desktop-m:text-[3.8rem] font-bold px-8 tablet:px-52"
                data-aos="fade-right" // AOS attribute
              >
                Wherever we <span className="text-orange-600">GO, </span>
                we <span className="text-orange-600">EXCEED!</span>
              </h1>
              <p
                className="text-white font-extralight text-[0.9rem] laptop-s:text-[1rem] laptop-m:text-xl desktop-m:text-2xl desktop-m:mx-60 leading-relaxed mt-5 px-8 tablet:px-52 tablet-m:px-[23rem] aos-init"
                data-aos="fade-left" // AOS attribute
              >
                A Technological Consortium for Awareness, Readiness, and
                Advancement of Knowledge in Innovation
              </p>
              <Link
                to="objectives"
                spy={true}
                smooth={true}
                duration={1600}
                offset={-100}
              >
                <button
                  className=" bg-white py-1 px-4 mt-5 mb-7 tablet-m:mt-5 tablet:mb-12 tablet-m:mb-0 text-[0.8rem] laptop-s:text-sm laptop-s:px-8 laptop-s:py-3 desktop-m:px-10 desktop-m:py-5 laptop-m:text-lg desktop-s:text-[1.4rem] desktop-m:text-[1.7rem] border border-white rounded-md hover:bg-trkblack hover:text-white hover:border-orange-600 aos-init"
                  data-aos="fade-up" // AOS attribute
                >
                  Learn More
                </button>
              </Link>
            </div>
          </div>
        </section>
        <div className="relative flex flex-col justify-center overflow-hidden bg-gray-50 border border-b-gray-400 tablet:mt-10 tablet-m:mt-20 laptop-s:mt-32 laptop-m:mt-52 desktop-s:mt-60 desktop-m:pt-0">
          <div className="pointer-events-none flex overflow-hidden">
            <div className="animate-marquee flex min-w-full shrink-0 items-center gap-10 tablet:gap-14 tablet-m:gap-24 laptop-s:gap-32 laptop-m:gap-32 desktop-s:gap-36 desktop-m:gap-48 p-3">
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 aos-init"
                src={dost}
                alt=""
                data-aos="zoom-in" // AOS attribute
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 aos-init"
                src={uc}
                alt=""
                data-aos="zoom-in" // AOS attribute
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 aos-init"
                src={dict}
                alt=""
                data-aos="zoom-in" // AOS attribute
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 aos-init"
                src={neda}
                alt=""
                data-aos="zoom-in" // AOS attribute
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 aos-init"
                src={dti}
                alt=""
                data-aos="zoom-in" // AOS attribute
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 aos-init"
                src={da}
                alt=""
                data-aos="zoom-in" // AOS attribute
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 aos-init"
                src={peza}
                alt=""
                data-aos="zoom-in" // AOS attribute
              />
            </div>
            <div className="animate-marquee flex min-w-full shrink-0 items-center gap-10 tablet:gap-14 tablet-m:gap-24 laptop-s:gap-32 laptop-m:gap-32 desktop-s:gap-36 desktop-m:gap-48 p-3">
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 aos-init"
                src={dost}
                alt=""
                data-aos="zoom-in" // AOS attribute
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 aos-init"
                src={uc}
                alt=""
                data-aos="zoom-in" // AOS attribute
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 aos-init"
                src={dict}
                alt=""
                data-aos="zoom-in" // AOS attribute
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 aos-init"
                src={neda}
                alt=""
                data-aos="zoom-in" // AOS attribute
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 aos-init"
                src={dti}
                alt=""
                data-aos="zoom-in" // AOS attribute
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 aos-init"
                src={da}
                alt=""
                data-aos="zoom-in" // AOS attribute
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 aos-init"
                src={peza}
                alt=""
                data-aos="zoom-in" // AOS attribute
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default Home;
