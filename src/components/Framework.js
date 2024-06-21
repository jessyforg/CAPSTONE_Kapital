import React, { useEffect } from "react";
import AOS from "aos";
import "aos/dist/aos.css";
import frame from "../components/imgs/framework.png";

function Framework() {
  useEffect(() => {
    AOS.init({
      duration: 1000, // Animation duration
      easing: "ease-in-out", // Easing function
      once: false,
    });
  }, []);
  return (
    <div>
      <div>
        <div className="cont">
          <section id="framework" className="mt-16 tablet:mt-12 text-center">
            <h1
              className="font-semibold text-md tablet:text-lg tablet-m:text-xl laptop-s:text-2xl laptop-m:text-[2.3rem] desktop-m:text-[2.9rem]"
              data-aos="fade-right"
              data-aos-delay="200"
            >
              Framework
            </h1>
          </section>
          <div className="mx-auto mt-5" data-aos="zoom-in" data-aos-delay="400">
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
