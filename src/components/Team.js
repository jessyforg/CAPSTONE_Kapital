import React, { useEffect } from 'react';
import './styles.css';

function TarakiTeam() {
  useEffect(() => {
    // Initialize Swiper when component mounts
      // eslint-disable-next-line no-unused-vars
      const swiper = new window.Swiper(".mySwiper", {
          slidesPerView: "auto",
      centeredSlides: true,
      spaceBetween: 20,
      scrollbar: {
        el: ".swiper-scrollbar",
        hide: true,
      },
    });
  }, []);

  return (
    <div>
      <div className="font-satoshi overflow-x-hidden">
        <div className="cont">
          <section className="mt-16 text-center">
            <h1 className="font-semibold text-md">Taraki's Team</h1>
            <p className="font-light text-sm px-10 mt-5">
              At TARAKI, our team is dedicated to driving technological
              innovation and fostering a collaborative environment for growth
              and advancement. Our experts bring diverse backgrounds and a
              shared commitment to our mission.
            </p>
          </section>
          <section className="mx-auto mt-5">
            
            <div className="swiper mySwiper">
              <div className="swiper-wrapper">
                <div className="swiper-slide bg-gray-500 text-center w-72 h-40 mx-1">
                <div></div>
                </div>
                              <div className="swiper-slide bg-gray-500 text-center w-72 h-40">
                                  
                </div>
                <div className="swiper-slide bg-gray-500 text-center w-72 h-40">Slide 3</div>
                <div className="swiper-slide bg-gray-500 text-center w-72 h-40">Slide 4</div>
                <div className="swiper-slide bg-gray-500 text-center w-72 h-40">Slide 5</div>
                <div className="swiper-slide bg-gray-500 text-center w-72 h-40">Slide 6</div>
                <div className="swiper-slide bg-gray-500 text-center w-72 h-40">Slide 7</div>
              </div>
              <div className="swiper-scrollbar"></div>
            </div>
          </section>
        </div>
      </div>
    </div>
  );
}

export default TarakiTeam;
