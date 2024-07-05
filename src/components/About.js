import React, { useEffect } from "react";
import AOS from "aos";
import "aos/dist/aos.css";
import animation from "../components/imgs/taraki-animation.webm";
function About() {
  useEffect(() => {
    AOS.init({
      duration: 1000, // Animation duration
      easing: "ease-in-out", // Easing function
      once: false,
    });
  }, []);
  return (
    <div>
      <div className="font-satoshi overflow-x-hidden">
        <div className="cont tablet:flex tablet:justify-between tablet:items-center laptop-s:items-center tablet:mt-10 tablet:px-14">
          <section
            id="about"
            className="text-center tablet:text-left mt-16 tablet:mt-0"
          >
            <h1
              className="font-semibold text-md tablet:text-lg tablet-m:text-2xl tablet:px-0 laptop-s:text-3xl laptop-m:text-[2.2rem] desktop-m:text-[2.6rem] aos-init"
              data-aos="fade-right"
            >
              About Us
            </h1>
            <p
              className="font-light text-sm tablet:text-md tablet-m:text-xl laptop-s:text-xl laptop-m:text-[1.5rem] desktop-m:text-[1.8rem] px-10 tablet:px-0 mt-5 tablet:mt-0 tablet-m:mr-5 tablet-m:mt-4 tablet:leading-6 tablet-m:leading-8 laptop-s:leading-loose aos-init"
              data-aos="fade-right"
            >
              <span className="font-semibold text-orange-600">TARAKI</span>{" "}
              envisions to be the country&apos;s leading technological
              consortium in building and transforming the Cordilleran Startup
              Ecosystem. We cultivate ingenuity for innovators by fostering
              collaboration and community-driven initiatives, aiming to become
              globally renowned.
            </p>
          </section>
          <section className="mt-5 aos-init" data-aos="fade-left">
            <video
              autoPlay
              loop
              muted
              playsInline
              loading="lazy"
              className="header-video m-auto w-72 tablet:w-[95%] tablet-m:w-[100%] desktop-s:w-[195rem] laptop-s:w-[165rem] laptop-m:w-[185rem] desktop-m:w-[235rem]"
            >
              <source src={animation} type="video/webm" />
              Your browser does not support the video tag.
            </video>
          </section>
        </div>
      </div>
    </div>
  );
}

export default About;
