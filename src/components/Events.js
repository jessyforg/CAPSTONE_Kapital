import React, { useEffect } from "react";
import AOS from "aos";
import "aos/dist/aos.css";
import rc1 from "../components/imgs/rc1.webp";
import rc2 from "../components/imgs/rc2.webp";
import rc3 from "../components/imgs/rc3.webp";
import rc4 from "../components/imgs/rc4.webp";
import rc5 from "../components/imgs/rc5.webp";
import rc6 from "../components/imgs/rc6.webp";
import next from "../components/imgs/next-new.svg";
import prev from "../components/imgs/prev-new.svg";

function Events() {
  useEffect(() => {
    // Initialize Swiper when component mounts
    // eslint-disable-next-line no-unused-vars
    const swiper = new window.Swiper(".mySwiperEvent", {
      slidesPerView: "auto",
      centeredSlides: true,
      spaceBetween: 20,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      breakpoints: {
        768: {
          slidesPerView: 2,
          spaceBetween: 10,
          freeMode: true,
          centeredSlides: false,
          pagination: {
            el: ".swiper-pagination",
            clickable: true,
          },
        },
        1024: {
          slidesPerView: 2,
          spaceBetween: 10,
          freeMode: true,
          centeredSlides: false,
          pagination: {
            el: ".swiper-pagination",
            clickable: true,
          },
        },
        1280: {
          slidesPerView: 2,
          spaceBetween: 10,
          freeMode: true,
          centeredSlides: false,
          pagination: {
            el: ".swiper-pagination",
            clickable: true,
          },
        },
        1440: {
          slidesPerView: 2,
          spaceBetween: 10,
          freeMode: true,
          centeredSlides: false,
          pagination: {
            el: ".swiper-pagination",
            clickable: true,
          },
        },
        1600: {
          slidesPerView: 2,
          spaceBetween: 10,
          freeMode: true,
          centeredSlides: false,
          pagination: {
            el: ".swiper-pagination",
            clickable: true,
          },
        },
        1920: {
          slidesPerView: 2,
          spaceBetween: 10,
          freeMode: true,
          centeredSlides: false,
          pagination: {
            el: ".swiper-pagination",
            clickable: true,
          },
        },
      },
    });
    swiper.on("slideChange", () => {
      console.log("Slide changed");
    });
    AOS.init({
      duration: 800, // Animation duration
      easing: "ease", // Easing function
      once: false,
    });
  }, []);

  return (
    <div>
      <div className="px-8">
        <section id="events" className="mt-16 tablet:mt-12">
          <h1
            className="font-semibold text-md text-center laptop-s:text-2xl laptop-m:text-[2.3rem] desktop-m:text-[2.9rem] aos-init"
            data-aos="zoom-in"
          >
            Regional Startup Caravans
          </h1>
        </section>
        <div
          className="swiper mySwiperEvent tablet:mx-[1.8rem] tablet-m:mx-[2.3rem] mt-2 aos-init"
          data-aos="zoom-in"
        >
          <div className="swiper-wrapper tablet:h-[17rem] tablet-m:h-[20rem] tablet:w-full laptop-s:h-[25rem] desktop-m:h-[28rem]">
            <div className="swiper-slide shadow-xl bg-white flex flex-col justify-center items-center w-full tablet:h-[15rem] tablet-m:h-[18rem] h-40 my-2 laptop-s:h-[23.5rem] desktop-m:h-[28rem]">
              <div
                className="bg-cover bg-center w-full h-full tablet:h-[15rem] tablet-m:h-[18rem] laptop-s:h-[25rem]"
                style={{ backgroundImage: `url(${rc1})` }}
              ></div>
              <h1 className="text-center text-xs font-semibold px-10 laptop-s:text-xl laptop-m:text-[1.3rem] text-orange-600">
                Regional Caravan 1.1
              </h1>
            </div>
            <div className="swiper-slide shadow-xl bg-white flex flex-col justify-center items-center w-full tablet:h-[15rem] tablet-m:h-[18rem] h-40 my-2 laptop-s:h-[23.5rem] desktop-m:h-[28rem]">
              <div
                className="bg-cover bg-center w-full h-full tablet:h-[15rem] tablet-m:h-[18rem] laptop-s:h-[25rem]"
                style={{ backgroundImage: `url(${rc2})` }}
              ></div>
              <h1 className="text-center text-xs font-semibold px-10 laptop-s:text-xl laptop-m:text-[1.3rem] text-orange-600">
                Regional Caravan 1.2
              </h1>
            </div>
            <div className="swiper-slide shadow-xl bg-white flex flex-col justify-center items-center tablet:h-[15rem] tablet-m:h-[18rem] w-full h-40 my-2 laptop-s:h-[23.5rem] desktop-m:h-[28rem]">
              <div
                className="bg-cover bg-center w-full h-full tablet:h-[15rem] tablet-m:h-[18rem] laptop-s:h-[25rem]"
                style={{ backgroundImage: `url(${rc3})` }}
              ></div>
              <h1 className="text-center text-xs font-semibold px-10 laptop-s:text-xl laptop-m:text-[1.3rem] text-orange-600">
                Regional Caravan 1.3
              </h1>
            </div>
            <div className="swiper-slide shadow-xl bg-white flex flex-col justify-center items-center w-full tablet:h-[15rem] tablet-m:h-[18rem] h-40 my-2 laptop-s:h-[23.5rem] desktop-m:h-[28rem]">
              <div
                className="bg-cover bg-center w-full h-full tablet:h-[15rem] tablet-m:h-[18rem] laptop-s:h-[25rem]"
                style={{ backgroundImage: `url(${rc4})` }}
              ></div>
              <h1 className="text-center text-xs font-semibold px-10 laptop-s:text-xl laptop-m:text-[1.3rem] text-orange-600">
                Regional Caravan 1.4
              </h1>
            </div>
            <div className="swiper-slide shadow-xl bg-white flex flex-col justify-center items-center w-full tablet:h-[15rem] tablet-m:h-[18rem] h-40 my-2 laptop-s:h-[23.5rem] desktop-m:h-[28rem]">
              <div
                className="bg-cover bg-center w-full h-full tablet:h-[15rem] tablet-m:h-[18rem] laptop-s:h-[25rem]"
                style={{ backgroundImage: `url(${rc5})` }}
              ></div>
              <h1 className="text-center text-xs font-semibold px-10 laptop-s:text-xl laptop-m:text-[1.3rem] text-orange-600">
                Regional Caravan 1.5
              </h1>
            </div>
            <div className="swiper-slide shadow-xl bg-white flex flex-col justify-center items-center w-full h-40 tablet:h-[15rem] tablet-m:h-[18rem] my-2 laptop-s:h-[23.5rem] desktop-m:h-[28rem]">
              <div
                className="bg-cover bg-center w-full h-full tablet:h-[15rem] tablet-m:h-[18rem] laptop-s:h-[25rem]"
                style={{ backgroundImage: `url(${rc6})` }}
              ></div>
              <h1 className="text-center text-xs font-semibold px-10 laptop-s:text-xl laptop-m:text-[1.3rem] text-orange-600">
                Regional Caravan 1.6
              </h1>
            </div>
          </div>
          <div className="">
            <img
              src={next}
              className="swiper-button-next phone:hidden tablet:hidden tablet-m:hidden laptop-s:block w-12 h-12"
              alt=""
            />
          </div>
          <div className="">
            <img
              src={prev}
              className="swiper-button-prev phone:hidden tablet:hidden tablet-m:hidden laptop-s:block w-12 h-12"
              alt=""
            />
          </div>
          <div className="pt-5 place-content-center desktop-m:pt-12">
            <div className="swiper-pagination"></div>
          </div>
        </div>
      </div>
    </div>
  );
}
export default Events;
