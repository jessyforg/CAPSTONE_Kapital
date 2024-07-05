import React, { useEffect } from "react";
import AOS from "aos";
import "aos/dist/aos.css";
import frame from "../components/imgs/framework.webp";

function Framework() {
  useEffect(() => {
    AOS.init({
      duration: 800, // Animation duration
      easing: "ease", // Easing function
      once: false,
    });
  }, []);
  return (
    <div>
      <div>
        <div className="cont">
          <section id="framework" className="mt-16 tablet:mt-12 text-center">
            <h1
              className="font-semibold text-md tablet:text-lg tablet-m:text-xl laptop-s:text-2xl laptop-m:text-[2.3rem] desktop-m:text-[2.9rem] aos-init"
              data-aos="zoom-in"
            >
              Framework
            </h1>
          </section>
          <div
            className="mx-auto tablet:px-[1.8rem] tablet-m:px-[2.3rem] mt-5 aos-init"
            data-aos="zoom-in"
          >
            <img
              src={frame}
              alt="awareness"
              className="w-72 tablet:w-[92%] tablet-m:w-[94%] mx-auto rounded-lg desktop-m:w-[85%] desktop-m:h-[70%]"
            />
          </div>
        </div>
      </div>
    </div>
  );
}
export default Framework;
