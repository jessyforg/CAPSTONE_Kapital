import React, { useEffect } from "react";
import rc1 from "../components/imgs/rc1.JPG";
import rc2 from "../components/imgs/rc2.jpg";
import rc3 from "../components/imgs/rc3.JPG";
import rc4 from "../components/imgs/rc4.JPG";
import rc5 from "../components/imgs/rc5.JPG";
import rc6 from "../components/imgs/rc6.JPG";

import "./styles.css";

function Events() {
  useEffect(() => {
    // Initialize Swiper when component mounts
    // eslint-disable-next-line no-unused-vars
    const swiper = new window.Swiper(".mySwiperEvent", {
      slidesPerView: "auto",
      centeredSlides: true,
      spaceBetween: 20,
      scrollbar: {
        el: ".swiper-scrollbar",
        hide: true,
      },
      breakpoints: {
        768: {
          slidesPerView: 2,
          spaceBetween: 10,
          freeMode: true,
          centeredSlides: false,
        },
      },
    });
  }, []);

  return (
    <div>
      <div className="cont px-8">
        <section className="mt-16 tablet:mt-12 text-left">
          <h1 className="font-semibold text-md tablet:text-lg">Events</h1>
        </section>
        <div className="swiper mySwiperEvent">
          <div className="swiper-wrapper tablet:h-[17rem] tablet:w-full">
            <div className="swiper-slide shadow-lg bg-white flex flex-col justify-center items-center w-full tablet:h-[15rem] h-40 my-2">
              <div
                className="bg-cover bg-center w-full h-full tablet:h-[15rem]"
                style={{ backgroundImage: `url(${rc1})` }}
              ></div>
              <h1 className="text-center text-xs font-semibold px-10">
                Regional Caravan 1.1
              </h1>
            </div>
            <div className="swiper-slide shadow-lg bg-white flex flex-col justify-center items-center w-full tablet:h-[15rem] h-40 my-2">
              <div
                className="bg-cover bg-center w-full h-full tablet:h-[15rem]"
                style={{ backgroundImage: `url(${rc2})` }}
              ></div>
              <h1 className="text-center text-xs font-semibold px-10">
                Regional Caravan 1.2
              </h1>
            </div>
            <div className="swiper-slide shadow-lg bg-white flex flex-col justify-center items-center tablet:h-[15rem] w-full h-40 my-2">
              <div
                className="bg-cover bg-center w-full h-full tablet:h-[15rem]"
                style={{ backgroundImage: `url(${rc3})` }}
              ></div>
              <h1 className="text-center text-xs font-semibold px-10">
                Regional Caravan 1.3
              </h1>
            </div>
            <div className="swiper-slide shadow-lg bg-white flex flex-col justify-center items-center w-full tablet:h-[15rem] h-40 my-2">
              <div
                className="bg-cover bg-center w-full h-full tablet:h-[15rem]"
                style={{ backgroundImage: `url(${rc4})` }}
              ></div>
              <h1 className="text-center text-xs font-semibold px-10">
                Regional Caravan 1.4
              </h1>
            </div>
            <div className="swiper-slide shadow-lg bg-white flex flex-col justify-center items-center w-full tablet:h-[15rem] h-40 my-2">
              <div
                className="bg-cover bg-center w-full h-full tablet:h-[15rem]"
                style={{ backgroundImage: `url(${rc5})` }}
              ></div>
              <h1 className="text-center text-xs font-semibold px-10">
                Regional Caravan 1.5
              </h1>
            </div>
            <div className="swiper-slide shadow-lg bg-white flex flex-col justify-center items-center w-full h-40 tablet:h-[15rem] my-2">
              <div
                className="bg-cover bg-center w-full h-full tablet:h-[15rem]"
                style={{ backgroundImage: `url(${rc6})` }}
              ></div>
              <h1 className="text-center text-xs font-semibold px-10">
                Regional Caravan 1.6
              </h1>
            </div>
          </div>
          <div className="swiper-scrollbar"></div>
        </div>
      </div>
    </div>
  );
}
export default Events;
