import React, { useEffect } from "react";
import AOS from "aos";
import "aos/dist/aos.css";
import ateJez from "../components/imgs/team/ate-jez.webp";
import maamT from "../components/imgs/team/thelma.webp";
import sirjade from "../components/imgs/team/jade.webp";
import "./styles.css";

function TarakiTeam() {
  useEffect(() => {
    // eslint-disable-next-line no-unused-vars
    const swiper = new window.Swiper(".mySwiper", {
      effect: "cards",
      grabCursor: true,
      scrollbar: {
        el: ".swiper-scrollbar",
        hide: false,
      },
      breakpoints: {
        768: {
          slidesPerView: 1,
          spaceBetween: 10,
          freeMode: true,
          centeredSlides: false,
        },
      },
    });
    AOS.init({
      easing: "ease", // Easing function
      once: false,
    });
  }, []);

  return (
    <section>
      <div className="font-satoshi overflow-x-hidden">
        <div className="cont tablet:px-8 phone:py-2">
          <section className="mt-16 tablet:mt-12 text-center">
            <h1
              className="font-semibold text-md tablet:text-lg tablet-m:text-2xl laptop-s:text-3xl laptop-m:text-[2.3rem] desktop-m:text-[2.9rem] desktop-m:mb-7 aos-init"
              data-aos="fade-down"
              data-aos-delay="200"
            >
              TARAKIs
            </h1>
            <p
              className="font-light tablet:font-normal tablet:leading-6 text-sm tablet:text-md tablet-m:text-lg tablet-m:px-24 tablet:px-20 px-10 mt-5 tablet:mt-2 laptop-s:text-lg laptop-m:text-[1.4rem] desktop-m:text-[1.6rem] laptop-s:mx-40 laptop-m:mx-80 aos-init"
              data-aos="fade-up"
              data-aos-delay="600"
            >
              At TARAKI, our team is dedicated to driving technological
              innovation and fostering a collaborative environment for growth
              and advancement. Our experts bring diverse backgrounds and a
              shared commitment to our mission.
            </p>
          </section>
          <section id="team" className="mx-auto mt-5 tablet:px-3 tablet-m:px-8">
            <div className="swiper mySwiper px-10 tablet:hidden">
              <div className="swiper-wrapper aos-init" data-aos="flip-right">
                <div className="swiper-slide shadow-lg bg-white flex flex-col justify-center items-center w-[100%] tablet:w-full tablet:my-4  tablet:h-64 tablet-m:h-80 h-[26rem] my-5">
                  <div
                    className="bg-cover bg-center w-[100%] tablet:w-[100%] h-full"
                    style={{ backgroundImage: `url(${maamT})` }}
                  ></div>
                  <div className="flex flex-col justify-center px-2 py-2 tablet:px-3">
                    <h1 className="text-[1.1rem] tablet:text-[1.5rem] tablet-m:text-[1.7rem] font-semibold text-orange-700">
                      Dr. Thelma D. Palaoag
                    </h1>
                    <h1 className="text-[0.9rem] tablet:text-lg tablet-m:text-xl">
                      Project Leader
                    </h1>
                    <p className="bg-white font-extralight text-[0.8rem] tablet:mt-2 tablet-m:mt-6 tablet:text-[1rem] tablet-m:text-[1.1rem] leading-relaxed">
                      Dr. Thelma brings over 20 years of experience in
                      technology and innovation. Her visionary leadership has
                      been instrumental in shaping TARAKI's strategic direction.
                    </p>
                    <section className="flex space-x-7 tablet:space-x-8 mt-1 tablet:mt-2 tablet-m:mt-6">
                      <svg
                        className="tablet:w-8 h-8"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <g clipPath="url(#a)">
                          <path
                            fill="#C73907"
                            d="M24 0C10.745 0 0 10.745 0 24c0 11.255 7.75 20.7 18.203 23.293V31.334h-4.95V24h4.95v-3.16c0-8.169 3.697-11.955 11.716-11.955 1.521 0 4.145.298 5.218.596v6.648c-.566-.06-1.55-.09-2.773-.09-3.935 0-5.455 1.492-5.455 5.367V24h7.84L33.4 31.334h-6.49v16.49C38.793 46.39 48 36.271 48 24 48 10.745 37.255 0 24 0Z"
                          />
                        </g>
                        <defs>
                          <clipPath id="a">
                            <path fill="#2f2f38" d="M0 0h48v48H0z" />
                          </clipPath>
                        </defs>
                      </svg>

                      <svg
                        className="tablet:w-8 h-8"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <g clipPath="url(#a)">
                          <path
                            fill="#C73907"
                            d="M24 4.322c6.413 0 7.172.028 9.694.14 2.343.104 3.61.497 4.453.825 1.116.432 1.922.957 2.756 1.791.844.844 1.36 1.64 1.79 2.756.329.844.723 2.12.826 4.454.112 2.53.14 3.29.14 9.693 0 6.413-.028 7.172-.14 9.694-.103 2.344-.497 3.61-.825 4.453-.431 1.116-.957 1.922-1.79 2.756-.845.844-1.642 1.36-2.757 1.791-.844.328-2.119.722-4.453.825-2.532.112-3.29.14-9.694.14-6.413 0-7.172-.028-9.694-.14-2.343-.103-3.61-.497-4.453-.825-1.115-.431-1.922-.956-2.756-1.79-.844-.844-1.36-1.641-1.79-2.757-.329-.844-.723-2.119-.826-4.453-.112-2.531-.14-3.29-.14-9.694 0-6.412.028-7.172.14-9.694.103-2.343.497-3.609.825-4.453.431-1.115.957-1.921 1.79-2.756.845-.844 1.642-1.36 2.757-1.79.844-.329 2.119-.722 4.453-.825 2.522-.113 3.281-.141 9.694-.141ZM24 0c-6.516 0-7.331.028-9.89.14-2.55.113-4.304.526-5.822 1.116-1.585.619-2.926 1.435-4.257 2.775-1.34 1.332-2.156 2.672-2.775 4.247C.666 9.806.253 11.55.141 14.1.028 16.669 0 17.484 0 24s.028 7.331.14 9.89c.113 2.55.526 4.304 1.116 5.822.619 1.585 1.435 2.925 2.775 4.257a11.732 11.732 0 0 0 4.247 2.765c1.528.591 3.272 1.003 5.822 1.116 2.56.112 3.375.14 9.89.14 6.516 0 7.332-.028 9.891-.14 2.55-.113 4.303-.525 5.822-1.116a11.732 11.732 0 0 0 4.247-2.765 11.732 11.732 0 0 0 2.766-4.247c.59-1.528 1.003-3.272 1.115-5.822.113-2.56.14-3.375.14-9.89 0-6.516-.027-7.332-.14-9.891-.112-2.55-.525-4.303-1.115-5.822-.591-1.594-1.407-2.935-2.747-4.266a11.732 11.732 0 0 0-4.247-2.765C38.194.675 36.45.262 33.9.15 31.331.028 30.516 0 24 0Z"
                          />
                          <path
                            fill="#C73907"
                            d="M24 11.672c-6.806 0-12.328 5.522-12.328 12.328 0 6.806 5.522 12.328 12.328 12.328 6.806 0 12.328-5.522 12.328-12.328 0-6.806-5.522-12.328-12.328-12.328Zm0 20.325a7.998 7.998 0 0 1 0-15.994 7.998 7.998 0 0 1 0 15.994Zm15.694-20.813a2.879 2.879 0 1 1-2.878-2.878 2.885 2.885 0 0 1 2.878 2.878Z"
                          />
                        </g>
                        <defs>
                          <clipPath id="a">
                            <path fill="#C73907" d="M0 0h48v48H0z" />
                          </clipPath>
                        </defs>
                      </svg>

                      <svg
                        className="tablet:w-8 h-8"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <g clipPath="url(#a)">
                          <path
                            fill="#C73907"
                            d="M44.447 0H3.544C1.584 0 0 1.547 0 3.46v41.07C0 46.444 1.584 48 3.544 48h40.903C46.407 48 48 46.444 48 44.54V3.46C48 1.546 46.406 0 44.447 0ZM14.24 40.903H7.116V17.991h7.125v22.912ZM10.678 14.87a4.127 4.127 0 0 1-4.134-4.125 4.127 4.127 0 0 1 4.134-4.125 4.125 4.125 0 0 1 0 8.25Zm30.225 26.034h-7.115V29.766c0-2.653-.047-6.075-3.704-6.075-3.703 0-4.265 2.896-4.265 5.887v11.325h-7.107V17.991h6.826v3.13h.093c.947-1.8 3.272-3.702 6.731-3.702 7.21 0 8.541 4.744 8.541 10.912v12.572Z"
                          />
                        </g>
                        <defs>
                          <clipPath id="a">
                            <path fill="#C73907" d="M0 0h48v48H0z" />
                          </clipPath>
                        </defs>
                      </svg>
                    </section>
                  </div>
                </div>
                <div className="swiper-slide shadow-lg bg-white flex flex-col justify-center items-center w-[100%] tablet:w-full tablet:my-4  tablet:h-64 tablet-m:h-80 h-[26rem] my-5">
                  <div
                    className="bg-cover bg-center w-[100%] tablet:w-[100%] h-full"
                    style={{ backgroundImage: `url(${ateJez})` }}
                  ></div>
                  <div className="flex flex-col justify-center px-2 py-2 tablet:px-3">
                    <h1 className="text-[1.1rem] tablet:text-[1.5rem] tablet-m:text-[1.7rem] font-semibold text-orange-700">
                      Jezelle Q. Oliva
                    </h1>
                    <h1 className="text-[0.9rem] tablet:text-lg tablet-m:text-xl">
                      Startup Community Enabler
                    </h1>
                    <p className="bg-white font-extralight text-[0.8rem] tablet:mt-2 tablet-m:mt-6 tablet:text-[1rem] tablet-m:text-[1.1rem] leading-relaxed">
                      Jezelle is an educator, empowering TARAKI startups, and
                      fosters community growth through spearheading innovative
                      initiatives.
                    </p>
                    <section className="flex space-x-7 tablet:space-x-8 mt-1 tablet:mt-2 tablet-m:mt-6">
                      <svg
                        className="tablet:w-8 h-8"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <g clipPath="url(#a)">
                          <path
                            fill="#C73907"
                            d="M24 0C10.745 0 0 10.745 0 24c0 11.255 7.75 20.7 18.203 23.293V31.334h-4.95V24h4.95v-3.16c0-8.169 3.697-11.955 11.716-11.955 1.521 0 4.145.298 5.218.596v6.648c-.566-.06-1.55-.09-2.773-.09-3.935 0-5.455 1.492-5.455 5.367V24h7.84L33.4 31.334h-6.49v16.49C38.793 46.39 48 36.271 48 24 48 10.745 37.255 0 24 0Z"
                          />
                        </g>
                        <defs>
                          <clipPath id="a">
                            <path fill="#2f2f38" d="M0 0h48v48H0z" />
                          </clipPath>
                        </defs>
                      </svg>

                      <svg
                        className="tablet:w-8 h-8"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <g clipPath="url(#a)">
                          <path
                            fill="#C73907"
                            d="M24 4.322c6.413 0 7.172.028 9.694.14 2.343.104 3.61.497 4.453.825 1.116.432 1.922.957 2.756 1.791.844.844 1.36 1.64 1.79 2.756.329.844.723 2.12.826 4.454.112 2.53.14 3.29.14 9.693 0 6.413-.028 7.172-.14 9.694-.103 2.344-.497 3.61-.825 4.453-.431 1.116-.957 1.922-1.79 2.756-.845.844-1.642 1.36-2.757 1.791-.844.328-2.119.722-4.453.825-2.532.112-3.29.14-9.694.14-6.413 0-7.172-.028-9.694-.14-2.343-.103-3.61-.497-4.453-.825-1.115-.431-1.922-.956-2.756-1.79-.844-.844-1.36-1.641-1.79-2.757-.329-.844-.723-2.119-.826-4.453-.112-2.531-.14-3.29-.14-9.694 0-6.412.028-7.172.14-9.694.103-2.343.497-3.609.825-4.453.431-1.115.957-1.921 1.79-2.756.845-.844 1.642-1.36 2.757-1.79.844-.329 2.119-.722 4.453-.825 2.522-.113 3.281-.141 9.694-.141ZM24 0c-6.516 0-7.331.028-9.89.14-2.55.113-4.304.526-5.822 1.116-1.585.619-2.926 1.435-4.257 2.775-1.34 1.332-2.156 2.672-2.775 4.247C.666 9.806.253 11.55.141 14.1.028 16.669 0 17.484 0 24s.028 7.331.14 9.89c.113 2.55.526 4.304 1.116 5.822.619 1.585 1.435 2.925 2.775 4.257a11.732 11.732 0 0 0 4.247 2.765c1.528.591 3.272 1.003 5.822 1.116 2.56.112 3.375.14 9.89.14 6.516 0 7.332-.028 9.891-.14 2.55-.113 4.303-.525 5.822-1.116a11.732 11.732 0 0 0 4.247-2.765 11.732 11.732 0 0 0 2.766-4.247c.59-1.528 1.003-3.272 1.115-5.822.113-2.56.14-3.375.14-9.89 0-6.516-.027-7.332-.14-9.891-.112-2.55-.525-4.303-1.115-5.822-.591-1.594-1.407-2.935-2.747-4.266a11.732 11.732 0 0 0-4.247-2.765C38.194.675 36.45.262 33.9.15 31.331.028 30.516 0 24 0Z"
                          />
                          <path
                            fill="#C73907"
                            d="M24 11.672c-6.806 0-12.328 5.522-12.328 12.328 0 6.806 5.522 12.328 12.328 12.328 6.806 0 12.328-5.522 12.328-12.328 0-6.806-5.522-12.328-12.328-12.328Zm0 20.325a7.998 7.998 0 0 1 0-15.994 7.998 7.998 0 0 1 0 15.994Zm15.694-20.813a2.879 2.879 0 1 1-2.878-2.878 2.885 2.885 0 0 1 2.878 2.878Z"
                          />
                        </g>
                        <defs>
                          <clipPath id="a">
                            <path fill="#C73907" d="M0 0h48v48H0z" />
                          </clipPath>
                        </defs>
                      </svg>

                      <svg
                        className="tablet:w-8 h-8"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <g clipPath="url(#a)">
                          <path
                            fill="#C73907"
                            d="M44.447 0H3.544C1.584 0 0 1.547 0 3.46v41.07C0 46.444 1.584 48 3.544 48h40.903C46.407 48 48 46.444 48 44.54V3.46C48 1.546 46.406 0 44.447 0ZM14.24 40.903H7.116V17.991h7.125v22.912ZM10.678 14.87a4.127 4.127 0 0 1-4.134-4.125 4.127 4.127 0 0 1 4.134-4.125 4.125 4.125 0 0 1 0 8.25Zm30.225 26.034h-7.115V29.766c0-2.653-.047-6.075-3.704-6.075-3.703 0-4.265 2.896-4.265 5.887v11.325h-7.107V17.991h6.826v3.13h.093c.947-1.8 3.272-3.702 6.731-3.702 7.21 0 8.541 4.744 8.541 10.912v12.572Z"
                          />
                        </g>
                        <defs>
                          <clipPath id="a">
                            <path fill="#C73907" d="M0 0h48v48H0z" />
                          </clipPath>
                        </defs>
                      </svg>
                    </section>
                  </div>
                </div>

                <div className="swiper-slide shadow-lg bg-white flex flex-col justify-center items-center w-[100%] tablet:w-full tablet:my-4  tablet:h-64 tablet-m:h-80 h-[26rem] my-5">
                  <div
                    className="bg-cover bg-center w-[100%] tablet:w-[100%] h-full"
                    style={{ backgroundImage: `url(${sirjade})` }}
                  ></div>
                  <div className="flex flex-col justify-center px-2 py-2 tablet:px-3">
                    <h1 className="text-[1.1rem] tablet:text-[1.5rem] tablet-m:text-[1.7rem] font-semibold text-orange-700">
                      Jade P. Jimenez
                    </h1>
                    <h1 className="text-[0.9rem] tablet:text-lg tablet-m:text-xl">
                      Project Assistant
                    </h1>
                    <p className="bg-white font-extralight text-[0.7rem] tablet:mt-2 tablet-m:mt-6 tablet:text-[1rem] tablet-m:text-[1.1rem] leading-relaxed">
                      Jade is a seasoned technologist with a passion for
                      cutting-edge innovations. He oversees all technology
                      initiatives, ensuring TARAKI remains at the forefront of
                      industry advancements.
                    </p>
                    <section className="flex space-x-7 tablet:space-x-8 mt-1 tablet:mt-2 tablet-m:mt-6">
                      <svg
                        className="tablet:w-8 h-8"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <g clipPath="url(#a)">
                          <path
                            fill="#C73907"
                            d="M24 0C10.745 0 0 10.745 0 24c0 11.255 7.75 20.7 18.203 23.293V31.334h-4.95V24h4.95v-3.16c0-8.169 3.697-11.955 11.716-11.955 1.521 0 4.145.298 5.218.596v6.648c-.566-.06-1.55-.09-2.773-.09-3.935 0-5.455 1.492-5.455 5.367V24h7.84L33.4 31.334h-6.49v16.49C38.793 46.39 48 36.271 48 24 48 10.745 37.255 0 24 0Z"
                          />
                        </g>
                        <defs>
                          <clipPath id="a">
                            <path fill="#2f2f38" d="M0 0h48v48H0z" />
                          </clipPath>
                        </defs>
                      </svg>

                      <svg
                        className="tablet:w-8 h-8"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <g clipPath="url(#a)">
                          <path
                            fill="#C73907"
                            d="M24 4.322c6.413 0 7.172.028 9.694.14 2.343.104 3.61.497 4.453.825 1.116.432 1.922.957 2.756 1.791.844.844 1.36 1.64 1.79 2.756.329.844.723 2.12.826 4.454.112 2.53.14 3.29.14 9.693 0 6.413-.028 7.172-.14 9.694-.103 2.344-.497 3.61-.825 4.453-.431 1.116-.957 1.922-1.79 2.756-.845.844-1.642 1.36-2.757 1.791-.844.328-2.119.722-4.453.825-2.532.112-3.29.14-9.694.14-6.413 0-7.172-.028-9.694-.14-2.343-.103-3.61-.497-4.453-.825-1.115-.431-1.922-.956-2.756-1.79-.844-.844-1.36-1.641-1.79-2.757-.329-.844-.723-2.119-.826-4.453-.112-2.531-.14-3.29-.14-9.694 0-6.412.028-7.172.14-9.694.103-2.343.497-3.609.825-4.453.431-1.115.957-1.921 1.79-2.756.845-.844 1.642-1.36 2.757-1.79.844-.329 2.119-.722 4.453-.825 2.522-.113 3.281-.141 9.694-.141ZM24 0c-6.516 0-7.331.028-9.89.14-2.55.113-4.304.526-5.822 1.116-1.585.619-2.926 1.435-4.257 2.775-1.34 1.332-2.156 2.672-2.775 4.247C.666 9.806.253 11.55.141 14.1.028 16.669 0 17.484 0 24s.028 7.331.14 9.89c.113 2.55.526 4.304 1.116 5.822.619 1.585 1.435 2.925 2.775 4.257a11.732 11.732 0 0 0 4.247 2.765c1.528.591 3.272 1.003 5.822 1.116 2.56.112 3.375.14 9.89.14 6.516 0 7.332-.028 9.891-.14 2.55-.113 4.303-.525 5.822-1.116a11.732 11.732 0 0 0 4.247-2.765 11.732 11.732 0 0 0 2.766-4.247c.59-1.528 1.003-3.272 1.115-5.822.113-2.56.14-3.375.14-9.89 0-6.516-.027-7.332-.14-9.891-.112-2.55-.525-4.303-1.115-5.822-.591-1.594-1.407-2.935-2.747-4.266a11.732 11.732 0 0 0-4.247-2.765C38.194.675 36.45.262 33.9.15 31.331.028 30.516 0 24 0Z"
                          />
                          <path
                            fill="#C73907"
                            d="M24 11.672c-6.806 0-12.328 5.522-12.328 12.328 0 6.806 5.522 12.328 12.328 12.328 6.806 0 12.328-5.522 12.328-12.328 0-6.806-5.522-12.328-12.328-12.328Zm0 20.325a7.998 7.998 0 0 1 0-15.994 7.998 7.998 0 0 1 0 15.994Zm15.694-20.813a2.879 2.879 0 1 1-2.878-2.878 2.885 2.885 0 0 1 2.878 2.878Z"
                          />
                        </g>
                        <defs>
                          <clipPath id="a">
                            <path fill="#C73907" d="M0 0h48v48H0z" />
                          </clipPath>
                        </defs>
                      </svg>

                      <svg
                        className="tablet:w-8 h-8"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <g clipPath="url(#a)">
                          <path
                            fill="#C73907"
                            d="M44.447 0H3.544C1.584 0 0 1.547 0 3.46v41.07C0 46.444 1.584 48 3.544 48h40.903C46.407 48 48 46.444 48 44.54V3.46C48 1.546 46.406 0 44.447 0ZM14.24 40.903H7.116V17.991h7.125v22.912ZM10.678 14.87a4.127 4.127 0 0 1-4.134-4.125 4.127 4.127 0 0 1 4.134-4.125 4.125 4.125 0 0 1 0 8.25Zm30.225 26.034h-7.115V29.766c0-2.653-.047-6.075-3.704-6.075-3.703 0-4.265 2.896-4.265 5.887v11.325h-7.107V17.991h6.826v3.13h.093c.947-1.8 3.272-3.702 6.731-3.702 7.21 0 8.541 4.744 8.541 10.912v12.572Z"
                          />
                        </g>
                        <defs>
                          <clipPath id="a">
                            <path fill="#C73907" d="M0 0h48v48H0z" />
                          </clipPath>
                        </defs>
                      </svg>
                    </section>
                  </div>
                </div>
              </div>
              <div className="swiper-scrollbar"></div>
            </div>

            <div className="tabletCont phone:hidden tablet-m:hidden tablet:flex justify-between items-center">
              <div
                className="relative overflow-hidden shadow-md m-2 w-56 h-72 rounded-3xl cursor-pointer text-2xl font-bold group transition-all duration-500 hover:w-[120rem] aos-init"
                data-aos="flip-left"
                data-aos-delay="200"
              >
                <div
                  className="bg-cover bg-center h-full w-full transition-all duration-500 group-hover:w-1/3"
                  style={{ backgroundImage: `url(${maamT})` }}
                ></div>
                <div className="absolute top-0 left-0 h-full px-3 flex items-center justify-center bg-white transition-all duration-500 opacity-0 group-hover:opacity-100 group-hover:left-1/3">
                  <div className="flex flex-col justify-center  tablet:px-3">
                    <h1 className="text-[0.9rem] tablet:text-[1.5rem] tablet-m:text-[1.7rem] laptop-m:text-[2rem] font-semibold text-orange-700">
                      Dr. Thelma D. Palaoag
                    </h1>
                    <h1 className="text-[0.8rem] tablet:text-lg tablet-m:text-xl laptop-m:text-2xl">
                      Project Leader
                    </h1>
                    <p className="bg-white font-extralight text-[0.7rem] tablet:mt-2 tablet-m:mt-6 tablet:text-[1rem] tablet-m:text-[1.1rem] laptop-m:text-[1.4rem] leading-relaxed">
                      Dr. Thelma brings over 20 years of experience in
                      technology and innovation. Her visionary leadership has
                      been instrumental in shaping TARAKI's strategic direction.
                    </p>
                    <section className="flex space-x-3 tablet:space-x-8 mt-1 tablet:mt-2 tablet-m:mt-6">
                      <svg
                        className="tablet:w-8 h-8"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <g clipPath="url(#a)">
                          <path
                            fill="#C73907"
                            d="M24 0C10.745 0 0 10.745 0 24c0 11.255 7.75 20.7 18.203 23.293V31.334h-4.95V24h4.95v-3.16c0-8.169 3.697-11.955 11.716-11.955 1.521 0 4.145.298 5.218.596v6.648c-.566-.06-1.55-.09-2.773-.09-3.935 0-5.455 1.492-5.455 5.367V24h7.84L33.4 31.334h-6.49v16.49C38.793 46.39 48 36.271 48 24 48 10.745 37.255 0 24 0Z"
                          />
                        </g>
                        <defs>
                          <clipPath id="a">
                            <path fill="#2f2f38" d="M0 0h48v48H0z" />
                          </clipPath>
                        </defs>
                      </svg>

                      <svg
                        className="tablet:w-8 h-8"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <g clipPath="url(#a)">
                          <path
                            fill="#C73907"
                            d="M24 4.322c6.413 0 7.172.028 9.694.14 2.343.104 3.61.497 4.453.825 1.116.432 1.922.957 2.756 1.791.844.844 1.36 1.64 1.79 2.756.329.844.723 2.12.826 4.454.112 2.53.14 3.29.14 9.693 0 6.413-.028 7.172-.14 9.694-.103 2.344-.497 3.61-.825 4.453-.431 1.116-.957 1.922-1.79 2.756-.845.844-1.642 1.36-2.757 1.791-.844.328-2.119.722-4.453.825-2.532.112-3.29.14-9.694.14-6.413 0-7.172-.028-9.694-.14-2.343-.103-3.61-.497-4.453-.825-1.115-.431-1.922-.956-2.756-1.79-.844-.844-1.36-1.641-1.79-2.757-.329-.844-.723-2.119-.826-4.453-.112-2.531-.14-3.29-.14-9.694 0-6.412.028-7.172.14-9.694.103-2.343.497-3.609.825-4.453.431-1.115.957-1.921 1.79-2.756.845-.844 1.642-1.36 2.757-1.79.844-.329 2.119-.722 4.453-.825 2.522-.113 3.281-.141 9.694-.141ZM24 0c-6.516 0-7.331.028-9.89.14-2.55.113-4.304.526-5.822 1.116-1.585.619-2.926 1.435-4.257 2.775-1.34 1.332-2.156 2.672-2.775 4.247C.666 9.806.253 11.55.141 14.1.028 16.669 0 17.484 0 24s.028 7.331.14 9.89c.113 2.55.526 4.304 1.116 5.822.619 1.585 1.435 2.925 2.775 4.257a11.732 11.732 0 0 0 4.247 2.765c1.528.591 3.272 1.003 5.822 1.116 2.56.112 3.375.14 9.89.14 6.516 0 7.332-.028 9.891-.14 2.55-.113 4.303-.525 5.822-1.116a11.732 11.732 0 0 0 4.247-2.765 11.732 11.732 0 0 0 2.766-4.247c.59-1.528 1.003-3.272 1.115-5.822.113-2.56.14-3.375.14-9.89 0-6.516-.027-7.332-.14-9.891-.112-2.55-.525-4.303-1.115-5.822-.591-1.594-1.407-2.935-2.747-4.266a11.732 11.732 0 0 0-4.247-2.765C38.194.675 36.45.262 33.9.15 31.331.028 30.516 0 24 0Z"
                          />
                          <path
                            fill="#C73907"
                            d="M24 11.672c-6.806 0-12.328 5.522-12.328 12.328 0 6.806 5.522 12.328 12.328 12.328 6.806 0 12.328-5.522 12.328-12.328 0-6.806-5.522-12.328-12.328-12.328Zm0 20.325a7.998 7.998 0 0 1 0-15.994 7.998 7.998 0 0 1 0 15.994Zm15.694-20.813a2.879 2.879 0 1 1-2.878-2.878 2.885 2.885 0 0 1 2.878 2.878Z"
                          />
                        </g>
                        <defs>
                          <clipPath id="a">
                            <path fill="#C73907" d="M0 0h48v48H0z" />
                          </clipPath>
                        </defs>
                      </svg>

                      <svg
                        className="tablet:w-8 h-8"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <g clipPath="url(#a)">
                          <path
                            fill="#C73907"
                            d="M44.447 0H3.544C1.584 0 0 1.547 0 3.46v41.07C0 46.444 1.584 48 3.544 48h40.903C46.407 48 48 46.444 48 44.54V3.46C48 1.546 46.406 0 44.447 0ZM14.24 40.903H7.116V17.991h7.125v22.912ZM10.678 14.87a4.127 4.127 0 0 1-4.134-4.125 4.127 4.127 0 0 1 4.134-4.125 4.125 4.125 0 0 1 0 8.25Zm30.225 26.034h-7.115V29.766c0-2.653-.047-6.075-3.704-6.075-3.703 0-4.265 2.896-4.265 5.887v11.325h-7.107V17.991h6.826v3.13h.093c.947-1.8 3.272-3.702 6.731-3.702 7.21 0 8.541 4.744 8.541 10.912v12.572Z"
                          />
                        </g>
                        <defs>
                          <clipPath id="a">
                            <path fill="#C73907" d="M0 0h48v48H0z" />
                          </clipPath>
                        </defs>
                      </svg>
                    </section>
                  </div>
                </div>
              </div>
              <div
                className="relative overflow-hidden w-56 h-72 shadow-md m-2 rounded-3xl cursor-pointer text-2xl font-bold group transition-all duration-500 hover:w-[120rem] aos-init"
                data-aos="flip-left"
                data-aos-delay="400"
              >
                <div
                  className="bg-cover bg-center h-full w-full transition-all duration-500 group-hover:w-1/3"
                  style={{ backgroundImage: `url(${ateJez})` }}
                ></div>
                <div className="absolute top-0 left-0 h-full px-3 flex items-center justify-center bg-white transition-all duration-500 opacity-0 group-hover:opacity-100 group-hover:left-1/3">
                  <div className="flex flex-col justify-center  tablet:px-3">
                    <h1 className="text-[0.9rem] tablet:text-[1.5rem] tablet-m:text-[1.7rem] laptop-m:text-[2rem] font-semibold text-orange-700">
                      Jezelle Q. Oliva
                    </h1>
                    <h1 className="text-[0.8rem] tablet:text-lg tablet-m:text-xl laptop-m:text-2xl">
                      Startup Community Enabler
                    </h1>
                    <p className="bg-white font-extralight text-[0.7rem] tablet:mt-2 tablet-m:mt-6 tablet:text-[1rem] tablet-m:text-[1.1rem] laptop-m:text-[1.4rem] leading-relaxed">
                      Jezelle is an educator, empowering TARAKI startups, and
                      fosters community growth through spearheading innovative
                      initiatives.
                    </p>
                    <section className="flex space-x-3 tablet:space-x-8 mt-1 tablet:mt-2 tablet-m:mt-6">
                      <svg
                        className="tablet:w-8 h-8"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <g clipPath="url(#a)">
                          <path
                            fill="#C73907"
                            d="M24 0C10.745 0 0 10.745 0 24c0 11.255 7.75 20.7 18.203 23.293V31.334h-4.95V24h4.95v-3.16c0-8.169 3.697-11.955 11.716-11.955 1.521 0 4.145.298 5.218.596v6.648c-.566-.06-1.55-.09-2.773-.09-3.935 0-5.455 1.492-5.455 5.367V24h7.84L33.4 31.334h-6.49v16.49C38.793 46.39 48 36.271 48 24 48 10.745 37.255 0 24 0Z"
                          />
                        </g>
                        <defs>
                          <clipPath id="a">
                            <path fill="#2f2f38" d="M0 0h48v48H0z" />
                          </clipPath>
                        </defs>
                      </svg>

                      <svg
                        className="tablet:w-8 h-8"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <g clipPath="url(#a)">
                          <path
                            fill="#C73907"
                            d="M24 4.322c6.413 0 7.172.028 9.694.14 2.343.104 3.61.497 4.453.825 1.116.432 1.922.957 2.756 1.791.844.844 1.36 1.64 1.79 2.756.329.844.723 2.12.826 4.454.112 2.53.14 3.29.14 9.693 0 6.413-.028 7.172-.14 9.694-.103 2.344-.497 3.61-.825 4.453-.431 1.116-.957 1.922-1.79 2.756-.845.844-1.642 1.36-2.757 1.791-.844.328-2.119.722-4.453.825-2.532.112-3.29.14-9.694.14-6.413 0-7.172-.028-9.694-.14-2.343-.103-3.61-.497-4.453-.825-1.115-.431-1.922-.956-2.756-1.79-.844-.844-1.36-1.641-1.79-2.757-.329-.844-.723-2.119-.826-4.453-.112-2.531-.14-3.29-.14-9.694 0-6.412.028-7.172.14-9.694.103-2.343.497-3.609.825-4.453.431-1.115.957-1.921 1.79-2.756.845-.844 1.642-1.36 2.757-1.79.844-.329 2.119-.722 4.453-.825 2.522-.113 3.281-.141 9.694-.141ZM24 0c-6.516 0-7.331.028-9.89.14-2.55.113-4.304.526-5.822 1.116-1.585.619-2.926 1.435-4.257 2.775-1.34 1.332-2.156 2.672-2.775 4.247C.666 9.806.253 11.55.141 14.1.028 16.669 0 17.484 0 24s.028 7.331.14 9.89c.113 2.55.526 4.304 1.116 5.822.619 1.585 1.435 2.925 2.775 4.257a11.732 11.732 0 0 0 4.247 2.765c1.528.591 3.272 1.003 5.822 1.116 2.56.112 3.375.14 9.89.14 6.516 0 7.332-.028 9.891-.14 2.55-.113 4.303-.525 5.822-1.116a11.732 11.732 0 0 0 4.247-2.765 11.732 11.732 0 0 0 2.766-4.247c.59-1.528 1.003-3.272 1.115-5.822.113-2.56.14-3.375.14-9.89 0-6.516-.027-7.332-.14-9.891-.112-2.55-.525-4.303-1.115-5.822-.591-1.594-1.407-2.935-2.747-4.266a11.732 11.732 0 0 0-4.247-2.765C38.194.675 36.45.262 33.9.15 31.331.028 30.516 0 24 0Z"
                          />
                          <path
                            fill="#C73907"
                            d="M24 11.672c-6.806 0-12.328 5.522-12.328 12.328 0 6.806 5.522 12.328 12.328 12.328 6.806 0 12.328-5.522 12.328-12.328 0-6.806-5.522-12.328-12.328-12.328Zm0 20.325a7.998 7.998 0 0 1 0-15.994 7.998 7.998 0 0 1 0 15.994Zm15.694-20.813a2.879 2.879 0 1 1-2.878-2.878 2.885 2.885 0 0 1 2.878 2.878Z"
                          />
                        </g>
                        <defs>
                          <clipPath id="a">
                            <path fill="#C73907" d="M0 0h48v48H0z" />
                          </clipPath>
                        </defs>
                      </svg>

                      <svg
                        className="tablet:w-8 h-8"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <g clipPath="url(#a)">
                          <path
                            fill="#C73907"
                            d="M44.447 0H3.544C1.584 0 0 1.547 0 3.46v41.07C0 46.444 1.584 48 3.544 48h40.903C46.407 48 48 46.444 48 44.54V3.46C48 1.546 46.406 0 44.447 0ZM14.24 40.903H7.116V17.991h7.125v22.912ZM10.678 14.87a4.127 4.127 0 0 1-4.134-4.125 4.127 4.127 0 0 1 4.134-4.125 4.125 4.125 0 0 1 0 8.25Zm30.225 26.034h-7.115V29.766c0-2.653-.047-6.075-3.704-6.075-3.703 0-4.265 2.896-4.265 5.887v11.325h-7.107V17.991h6.826v3.13h.093c.947-1.8 3.272-3.702 6.731-3.702 7.21 0 8.541 4.744 8.541 10.912v12.572Z"
                          />
                        </g>
                        <defs>
                          <clipPath id="a">
                            <path fill="#C73907" d="M0 0h48v48H0z" />
                          </clipPath>
                        </defs>
                      </svg>
                    </section>
                  </div>
                </div>
              </div>
              <div
                className="relative overflow-hidden w-56 h-72 shadow-md m-2 rounded-3xl cursor-pointer text-2xl font-bold group transition-all duration-500 hover:w-[120rem] aos-init"
                data-aos="flip-left"
                data-aos-delay="600"
              >
                <div
                  className="bg-cover bg-center h-full w-full transition-all duration-500 group-hover:w-1/3"
                  style={{ backgroundImage: `url(${sirjade})` }}
                ></div>
                <div className="absolute top-0 left-0 h-full px-3 flex items-center justify-center bg-white transition-all duration-500 opacity-0 group-hover:opacity-100 group-hover:left-1/3">
                  <div className="flex flex-col justify-center  tablet:px-3">
                    <h1 className="text-[0.9rem] tablet:text-[1.5rem] tablet-m:text-[1.7rem] laptop-m:text-[2rem] font-semibold text-orange-700">
                      Jade p. Jimenez
                    </h1>
                    <h1 className="text-[0.8rem] tablet:text-lg tablet-m:text-xl">
                      Project Assistant
                    </h1>
                    <p className="bg-white font-extralight text-[0.7rem] tablet:mt-2 tablet-m:mt-6 tablet:text-[1rem] tablet-m:text-[1.1rem] leading-relaxed">
                      Jade is a seasoned technologist with a passion for
                      cutting-edge innovations. He oversees all technology
                      initiatives, ensuring TARAKI remains at the forefront of
                      industry advancements.
                    </p>
                    <section className="flex space-x-3 tablet:space-x-8 mt-1 tablet:mt-2 tablet-m:mt-6">
                      <svg
                        className="tablet:w-8 h-8"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <g clipPath="url(#a)">
                          <path
                            fill="#C73907"
                            d="M24 0C10.745 0 0 10.745 0 24c0 11.255 7.75 20.7 18.203 23.293V31.334h-4.95V24h4.95v-3.16c0-8.169 3.697-11.955 11.716-11.955 1.521 0 4.145.298 5.218.596v6.648c-.566-.06-1.55-.09-2.773-.09-3.935 0-5.455 1.492-5.455 5.367V24h7.84L33.4 31.334h-6.49v16.49C38.793 46.39 48 36.271 48 24 48 10.745 37.255 0 24 0Z"
                          />
                        </g>
                        <defs>
                          <clipPath id="a">
                            <path fill="#2f2f38" d="M0 0h48v48H0z" />
                          </clipPath>
                        </defs>
                      </svg>

                      <svg
                        className="tablet:w-8 h-8"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <g clipPath="url(#a)">
                          <path
                            fill="#C73907"
                            d="M24 4.322c6.413 0 7.172.028 9.694.14 2.343.104 3.61.497 4.453.825 1.116.432 1.922.957 2.756 1.791.844.844 1.36 1.64 1.79 2.756.329.844.723 2.12.826 4.454.112 2.53.14 3.29.14 9.693 0 6.413-.028 7.172-.14 9.694-.103 2.344-.497 3.61-.825 4.453-.431 1.116-.957 1.922-1.79 2.756-.845.844-1.642 1.36-2.757 1.791-.844.328-2.119.722-4.453.825-2.532.112-3.29.14-9.694.14-6.413 0-7.172-.028-9.694-.14-2.343-.103-3.61-.497-4.453-.825-1.115-.431-1.922-.956-2.756-1.79-.844-.844-1.36-1.641-1.79-2.757-.329-.844-.723-2.119-.826-4.453-.112-2.531-.14-3.29-.14-9.694 0-6.412.028-7.172.14-9.694.103-2.343.497-3.609.825-4.453.431-1.115.957-1.921 1.79-2.756.845-.844 1.642-1.36 2.757-1.79.844-.329 2.119-.722 4.453-.825 2.522-.113 3.281-.141 9.694-.141ZM24 0c-6.516 0-7.331.028-9.89.14-2.55.113-4.304.526-5.822 1.116-1.585.619-2.926 1.435-4.257 2.775-1.34 1.332-2.156 2.672-2.775 4.247C.666 9.806.253 11.55.141 14.1.028 16.669 0 17.484 0 24s.028 7.331.14 9.89c.113 2.55.526 4.304 1.116 5.822.619 1.585 1.435 2.925 2.775 4.257a11.732 11.732 0 0 0 4.247 2.765c1.528.591 3.272 1.003 5.822 1.116 2.56.112 3.375.14 9.89.14 6.516 0 7.332-.028 9.891-.14 2.55-.113 4.303-.525 5.822-1.116a11.732 11.732 0 0 0 4.247-2.765 11.732 11.732 0 0 0 2.766-4.247c.59-1.528 1.003-3.272 1.115-5.822.113-2.56.14-3.375.14-9.89 0-6.516-.027-7.332-.14-9.891-.112-2.55-.525-4.303-1.115-5.822-.591-1.594-1.407-2.935-2.747-4.266a11.732 11.732 0 0 0-4.247-2.765C38.194.675 36.45.262 33.9.15 31.331.028 30.516 0 24 0Z"
                          />
                          <path
                            fill="#C73907"
                            d="M24 11.672c-6.806 0-12.328 5.522-12.328 12.328 0 6.806 5.522 12.328 12.328 12.328 6.806 0 12.328-5.522 12.328-12.328 0-6.806-5.522-12.328-12.328-12.328Zm0 20.325a7.998 7.998 0 0 1 0-15.994 7.998 7.998 0 0 1 0 15.994Zm15.694-20.813a2.879 2.879 0 1 1-2.878-2.878 2.885 2.885 0 0 1 2.878 2.878Z"
                          />
                        </g>
                        <defs>
                          <clipPath id="a">
                            <path fill="#C73907" d="M0 0h48v48H0z" />
                          </clipPath>
                        </defs>
                      </svg>

                      <svg
                        className="tablet:w-8 h-8"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="none"
                        viewBox="0 0 48 48"
                      >
                        <g clipPath="url(#a)">
                          <path
                            fill="#C73907"
                            d="M44.447 0H3.544C1.584 0 0 1.547 0 3.46v41.07C0 46.444 1.584 48 3.544 48h40.903C46.407 48 48 46.444 48 44.54V3.46C48 1.546 46.406 0 44.447 0ZM14.24 40.903H7.116V17.991h7.125v22.912ZM10.678 14.87a4.127 4.127 0 0 1-4.134-4.125 4.127 4.127 0 0 1 4.134-4.125 4.125 4.125 0 0 1 0 8.25Zm30.225 26.034h-7.115V29.766c0-2.653-.047-6.075-3.704-6.075-3.703 0-4.265 2.896-4.265 5.887v11.325h-7.107V17.991h6.826v3.13h.093c.947-1.8 3.272-3.702 6.731-3.702 7.21 0 8.541 4.744 8.541 10.912v12.572Z"
                          />
                        </g>
                        <defs>
                          <clipPath id="a">
                            <path fill="#C73907" d="M0 0h48v48H0z" />
                          </clipPath>
                        </defs>
                      </svg>
                    </section>
                  </div>
                </div>
              </div>
            </div>

            <div className="laptopCont px-0 hidden tablet-m:flex justify-between items-center">
              <div
                className="shadow-lg bg-white flex flex-col justify-center items-center w-[100%] tablet:w-full tablet:my-4  tablet:h-64 tablet-m:w-[30%] tablet-m:h-[36rem] desktop-s:h-[45rem] h-[26rem] my-5 transition-all duration-300 hover:scale-105 hover:z-10 aos-init"
                data-aos="flip-right"
                data-aos-duration="1000"
              >
                <div
                  className="bg-cover bg-center w-[100%] tablet:w-[100%] h-full"
                  style={{ backgroundImage: `url(${maamT})` }}
                ></div>
                <div className="flex flex-col justify-center px-2 py-2 tablet:px-3">
                  <h1 className="laptop-s:text-[1.2rem] laptop-m:text-2xl desktop-m:text-3xl font-semibold text-orange-700">
                    Dr. Thelma D. Palaoag
                  </h1>
                  <h1 className="laptop-s:text-xl desktop-m:text-2xl">
                    Project Leader
                  </h1>
                  <p className="bg-white font-extralight laptop-s:mt-2 laptop-s:text-[1rem] laptop-m:text-lg desktop-m:text-xl leading-relaxed tablet-m:pb-[1.65rem] laptop-s:pb-[1.7rem] desktop-m:pb-[3.2rem]">
                    20 years of experience in technology and innovation. Her
                    visionary leadership has been instrumental in shaping
                    TARAKI's strategic direction.
                  </p>
                  <section className="flex space-x-7 tablet:space-x-8 mt-1 tablet:mt-2 tablet-m:mt-6">
                    <svg
                      className="tablet:w-8 h-8"
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 48 48"
                    >
                      <g clipPath="url(#a)">
                        <path
                          fill="#C73907"
                          d="M24 0C10.745 0 0 10.745 0 24c0 11.255 7.75 20.7 18.203 23.293V31.334h-4.95V24h4.95v-3.16c0-8.169 3.697-11.955 11.716-11.955 1.521 0 4.145.298 5.218.596v6.648c-.566-.06-1.55-.09-2.773-.09-3.935 0-5.455 1.492-5.455 5.367V24h7.84L33.4 31.334h-6.49v16.49C38.793 46.39 48 36.271 48 24 48 10.745 37.255 0 24 0Z"
                        />
                      </g>
                      <defs>
                        <clipPath id="a">
                          <path fill="#2f2f38" d="M0 0h48v48H0z" />
                        </clipPath>
                      </defs>
                    </svg>

                    <svg
                      className="tablet:w-8 h-8"
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 48 48"
                    >
                      <g clipPath="url(#a)">
                        <path
                          fill="#C73907"
                          d="M24 4.322c6.413 0 7.172.028 9.694.14 2.343.104 3.61.497 4.453.825 1.116.432 1.922.957 2.756 1.791.844.844 1.36 1.64 1.79 2.756.329.844.723 2.12.826 4.454.112 2.53.14 3.29.14 9.693 0 6.413-.028 7.172-.14 9.694-.103 2.344-.497 3.61-.825 4.453-.431 1.116-.957 1.922-1.79 2.756-.845.844-1.642 1.36-2.757 1.791-.844.328-2.119.722-4.453.825-2.532.112-3.29.14-9.694.14-6.413 0-7.172-.028-9.694-.14-2.343-.103-3.61-.497-4.453-.825-1.115-.431-1.922-.956-2.756-1.79-.844-.844-1.36-1.641-1.79-2.757-.329-.844-.723-2.119-.826-4.453-.112-2.531-.14-3.29-.14-9.694 0-6.412.028-7.172.14-9.694.103-2.343.497-3.609.825-4.453.431-1.115.957-1.921 1.79-2.756.845-.844 1.642-1.36 2.757-1.79.844-.329 2.119-.722 4.453-.825 2.522-.113 3.281-.141 9.694-.141ZM24 0c-6.516 0-7.331.028-9.89.14-2.55.113-4.304.526-5.822 1.116-1.585.619-2.926 1.435-4.257 2.775-1.34 1.332-2.156 2.672-2.775 4.247C.666 9.806.253 11.55.141 14.1.028 16.669 0 17.484 0 24s.028 7.331.14 9.89c.113 2.55.526 4.304 1.116 5.822.619 1.585 1.435 2.925 2.775 4.257a11.732 11.732 0 0 0 4.247 2.765c1.528.591 3.272 1.003 5.822 1.116 2.56.112 3.375.14 9.89.14 6.516 0 7.332-.028 9.891-.14 2.55-.113 4.303-.525 5.822-1.116a11.732 11.732 0 0 0 4.247-2.765 11.732 11.732 0 0 0 2.766-4.247c.59-1.528 1.003-3.272 1.115-5.822.113-2.56.14-3.375.14-9.89 0-6.516-.027-7.332-.14-9.891-.112-2.55-.525-4.303-1.115-5.822-.591-1.594-1.407-2.935-2.747-4.266a11.732 11.732 0 0 0-4.247-2.765C38.194.675 36.45.262 33.9.15 31.331.028 30.516 0 24 0Z"
                        />
                        <path
                          fill="#C73907"
                          d="M24 11.672c-6.806 0-12.328 5.522-12.328 12.328 0 6.806 5.522 12.328 12.328 12.328 6.806 0 12.328-5.522 12.328-12.328 0-6.806-5.522-12.328-12.328-12.328Zm0 20.325a7.998 7.998 0 0 1 0-15.994 7.998 7.998 0 0 1 0 15.994Zm15.694-20.813a2.879 2.879 0 1 1-2.878-2.878 2.885 2.885 0 0 1 2.878 2.878Z"
                        />
                      </g>
                      <defs>
                        <clipPath id="a">
                          <path fill="#C73907" d="M0 0h48v48H0z" />
                        </clipPath>
                      </defs>
                    </svg>

                    <svg
                      className="tablet:w-8 h-8"
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 48 48"
                    >
                      <g clipPath="url(#a)">
                        <path
                          fill="#C73907"
                          d="M44.447 0H3.544C1.584 0 0 1.547 0 3.46v41.07C0 46.444 1.584 48 3.544 48h40.903C46.407 48 48 46.444 48 44.54V3.46C48 1.546 46.406 0 44.447 0ZM14.24 40.903H7.116V17.991h7.125v22.912ZM10.678 14.87a4.127 4.127 0 0 1-4.134-4.125 4.127 4.127 0 0 1 4.134-4.125 4.125 4.125 0 0 1 0 8.25Zm30.225 26.034h-7.115V29.766c0-2.653-.047-6.075-3.704-6.075-3.703 0-4.265 2.896-4.265 5.887v11.325h-7.107V17.991h6.826v3.13h.093c.947-1.8 3.272-3.702 6.731-3.702 7.21 0 8.541 4.744 8.541 10.912v12.572Z"
                        />
                      </g>
                      <defs>
                        <clipPath id="a">
                          <path fill="#C73907" d="M0 0h48v48H0z" />
                        </clipPath>
                      </defs>
                    </svg>
                  </section>
                </div>
              </div>
              <div
                className="shadow-lg bg-white flex flex-col justify-center items-center w-[100%] tablet:w-full tablet:my-4  tablet:h-64 tablet-m:w-[30%] tablet-m:h-[36rem] desktop-s:h-[45rem] h-[26rem] my-5 transition-all duration-300 hover:scale-105 hover:z-10 aos-init"
                data-aos="flip-right"
                data-aos-duration="2000"
              >
                <div
                  className="bg-cover bg-center w-[100%] tablet:w-[100%] h-full"
                  style={{ backgroundImage: `url(${ateJez})` }}
                ></div>
                <div className="flex flex-col justify-center px-2 py-2 tablet:px-3">
                  <h1 className="laptop-s:text-[1.2rem] laptop-m:text-2xl desktop-m:text-3xl font-semibold text-orange-700">
                    Jezelle Q. Oliva
                  </h1>
                  <h1 className="laptop-s:text-lg laptop-m:text-xl desktop-m:text-2xl">
                    Startup Community Enabler
                  </h1>
                  <p className="bg-white font-extralight laptop-s:mt-2 laptop-s:text-[1rem] laptop-m:text-lg desktop-m:text-xl leading-relaxed tablet-m:pb-[3.2rem] laptop-s:pb-[3.27rem] laptop-m:pb-[3.4rem] desktop-m:pb-[3.19rem]">
                    An educator, empowering TARAKI startups, and fosters
                    community growth through spearheading innovative
                    initiatives.
                  </p>
                  <section className="flex space-x-7 tablet:space-x-8 mt-1 tablet:mt-2 tablet-m:mt-6">
                    <svg
                      className="tablet:w-8 h-8"
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 48 48"
                    >
                      <g clipPath="url(#a)">
                        <path
                          fill="#C73907"
                          d="M24 0C10.745 0 0 10.745 0 24c0 11.255 7.75 20.7 18.203 23.293V31.334h-4.95V24h4.95v-3.16c0-8.169 3.697-11.955 11.716-11.955 1.521 0 4.145.298 5.218.596v6.648c-.566-.06-1.55-.09-2.773-.09-3.935 0-5.455 1.492-5.455 5.367V24h7.84L33.4 31.334h-6.49v16.49C38.793 46.39 48 36.271 48 24 48 10.745 37.255 0 24 0Z"
                        />
                      </g>
                      <defs>
                        <clipPath id="a">
                          <path fill="#2f2f38" d="M0 0h48v48H0z" />
                        </clipPath>
                      </defs>
                    </svg>

                    <svg
                      className="tablet:w-8 h-8"
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 48 48"
                    >
                      <g clipPath="url(#a)">
                        <path
                          fill="#C73907"
                          d="M24 4.322c6.413 0 7.172.028 9.694.14 2.343.104 3.61.497 4.453.825 1.116.432 1.922.957 2.756 1.791.844.844 1.36 1.64 1.79 2.756.329.844.723 2.12.826 4.454.112 2.53.14 3.29.14 9.693 0 6.413-.028 7.172-.14 9.694-.103 2.344-.497 3.61-.825 4.453-.431 1.116-.957 1.922-1.79 2.756-.845.844-1.642 1.36-2.757 1.791-.844.328-2.119.722-4.453.825-2.532.112-3.29.14-9.694.14-6.413 0-7.172-.028-9.694-.14-2.343-.103-3.61-.497-4.453-.825-1.115-.431-1.922-.956-2.756-1.79-.844-.844-1.36-1.641-1.79-2.757-.329-.844-.723-2.119-.826-4.453-.112-2.531-.14-3.29-.14-9.694 0-6.412.028-7.172.14-9.694.103-2.343.497-3.609.825-4.453.431-1.115.957-1.921 1.79-2.756.845-.844 1.642-1.36 2.757-1.79.844-.329 2.119-.722 4.453-.825 2.522-.113 3.281-.141 9.694-.141ZM24 0c-6.516 0-7.331.028-9.89.14-2.55.113-4.304.526-5.822 1.116-1.585.619-2.926 1.435-4.257 2.775-1.34 1.332-2.156 2.672-2.775 4.247C.666 9.806.253 11.55.141 14.1.028 16.669 0 17.484 0 24s.028 7.331.14 9.89c.113 2.55.526 4.304 1.116 5.822.619 1.585 1.435 2.925 2.775 4.257a11.732 11.732 0 0 0 4.247 2.765c1.528.591 3.272 1.003 5.822 1.116 2.56.112 3.375.14 9.89.14 6.516 0 7.332-.028 9.891-.14 2.55-.113 4.303-.525 5.822-1.116a11.732 11.732 0 0 0 4.247-2.765 11.732 11.732 0 0 0 2.766-4.247c.59-1.528 1.003-3.272 1.115-5.822.113-2.56.14-3.375.14-9.89 0-6.516-.027-7.332-.14-9.891-.112-2.55-.525-4.303-1.115-5.822-.591-1.594-1.407-2.935-2.747-4.266a11.732 11.732 0 0 0-4.247-2.765C38.194.675 36.45.262 33.9.15 31.331.028 30.516 0 24 0Z"
                        />
                        <path
                          fill="#C73907"
                          d="M24 11.672c-6.806 0-12.328 5.522-12.328 12.328 0 6.806 5.522 12.328 12.328 12.328 6.806 0 12.328-5.522 12.328-12.328 0-6.806-5.522-12.328-12.328-12.328Zm0 20.325a7.998 7.998 0 0 1 0-15.994 7.998 7.998 0 0 1 0 15.994Zm15.694-20.813a2.879 2.879 0 1 1-2.878-2.878 2.885 2.885 0 0 1 2.878 2.878Z"
                        />
                      </g>
                      <defs>
                        <clipPath id="a">
                          <path fill="#C73907" d="M0 0h48v48H0z" />
                        </clipPath>
                      </defs>
                    </svg>

                    <svg
                      className="tablet:w-8 h-8"
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 48 48"
                    >
                      <g clipPath="url(#a)">
                        <path
                          fill="#C73907"
                          d="M44.447 0H3.544C1.584 0 0 1.547 0 3.46v41.07C0 46.444 1.584 48 3.544 48h40.903C46.407 48 48 46.444 48 44.54V3.46C48 1.546 46.406 0 44.447 0ZM14.24 40.903H7.116V17.991h7.125v22.912ZM10.678 14.87a4.127 4.127 0 0 1-4.134-4.125 4.127 4.127 0 0 1 4.134-4.125 4.125 4.125 0 0 1 0 8.25Zm30.225 26.034h-7.115V29.766c0-2.653-.047-6.075-3.704-6.075-3.703 0-4.265 2.896-4.265 5.887v11.325h-7.107V17.991h6.826v3.13h.093c.947-1.8 3.272-3.702 6.731-3.702 7.21 0 8.541 4.744 8.541 10.912v12.572Z"
                        />
                      </g>
                      <defs>
                        <clipPath id="a">
                          <path fill="#C73907" d="M0 0h48v48H0z" />
                        </clipPath>
                      </defs>
                    </svg>
                  </section>
                </div>
              </div>
              <div
                className="shadow-lg bg-white flex flex-col justify-center items-center w-[100%] tablet:w-full tablet:my-4  tablet:h-64 tablet-m:w-[30%] tablet-m:h-[36rem] desktop-s:h-[45rem] h-[26rem] my-5 transition-all duration-300 hover:scale-105 hover:z-10 aos-init"
                data-aos="flip-right"
                data-aos-duration="3000"
              >
                <div
                  className="bg-cover bg-center w-[100%] tablet:w-[100%] h-full"
                  style={{ backgroundImage: `url(${sirjade})` }}
                ></div>
                <div className="flex flex-col justify-center px-2 py-2 tablet:px-3">
                  <h1 className="laptop-s:text-[1.2rem] laptop-m:text-2xl desktop-m:text-3xl font-semibold text-orange-700">
                    Jade P. Jimenez
                  </h1>
                  <h1 className="laptop-s:text-lg  laptop-m:text-xl desktop-m:text-2xl">
                    Project Assistant
                  </h1>
                  <p className="bg-white font-extralight laptop-s:mt-2 laptop-s:text-[1rem] laptop-m:text-lg desktop-m:text-xl leading-relaxed laptop-l:pb-[1.65rem] desktop-s:pb-[1.6rem] desktop-m:pb-[1.475rem]">
                    A seasoned technologist with a passion for cutting-edge
                    innovations. He oversees all technology initiatives,
                    ensuring TARAKI remains at the forefront of industry
                    advancements.
                  </p>
                  <section className="flex space-x-7 tablet:space-x-8 mt-1 tablet:mt-2 tablet-m:mt-6">
                    <svg
                      className="tablet:w-8 h-8"
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 48 48"
                    >
                      <g clipPath="url(#a)">
                        <path
                          fill="#C73907"
                          d="M24 0C10.745 0 0 10.745 0 24c0 11.255 7.75 20.7 18.203 23.293V31.334h-4.95V24h4.95v-3.16c0-8.169 3.697-11.955 11.716-11.955 1.521 0 4.145.298 5.218.596v6.648c-.566-.06-1.55-.09-2.773-.09-3.935 0-5.455 1.492-5.455 5.367V24h7.84L33.4 31.334h-6.49v16.49C38.793 46.39 48 36.271 48 24 48 10.745 37.255 0 24 0Z"
                        />
                      </g>
                      <defs>
                        <clipPath id="a">
                          <path fill="#2f2f38" d="M0 0h48v48H0z" />
                        </clipPath>
                      </defs>
                    </svg>

                    <svg
                      className="tablet:w-8 h-8"
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 48 48"
                    >
                      <g clipPath="url(#a)">
                        <path
                          fill="#C73907"
                          d="M24 4.322c6.413 0 7.172.028 9.694.14 2.343.104 3.61.497 4.453.825 1.116.432 1.922.957 2.756 1.791.844.844 1.36 1.64 1.79 2.756.329.844.723 2.12.826 4.454.112 2.53.14 3.29.14 9.693 0 6.413-.028 7.172-.14 9.694-.103 2.344-.497 3.61-.825 4.453-.431 1.116-.957 1.922-1.79 2.756-.845.844-1.642 1.36-2.757 1.791-.844.328-2.119.722-4.453.825-2.532.112-3.29.14-9.694.14-6.413 0-7.172-.028-9.694-.14-2.343-.103-3.61-.497-4.453-.825-1.115-.431-1.922-.956-2.756-1.79-.844-.844-1.36-1.641-1.79-2.757-.329-.844-.723-2.119-.826-4.453-.112-2.531-.14-3.29-.14-9.694 0-6.412.028-7.172.14-9.694.103-2.343.497-3.609.825-4.453.431-1.115.957-1.921 1.79-2.756.845-.844 1.642-1.36 2.757-1.79.844-.329 2.119-.722 4.453-.825 2.522-.113 3.281-.141 9.694-.141ZM24 0c-6.516 0-7.331.028-9.89.14-2.55.113-4.304.526-5.822 1.116-1.585.619-2.926 1.435-4.257 2.775-1.34 1.332-2.156 2.672-2.775 4.247C.666 9.806.253 11.55.141 14.1.028 16.669 0 17.484 0 24s.028 7.331.14 9.89c.113 2.55.526 4.304 1.116 5.822.619 1.585 1.435 2.925 2.775 4.257a11.732 11.732 0 0 0 4.247 2.765c1.528.591 3.272 1.003 5.822 1.116 2.56.112 3.375.14 9.89.14 6.516 0 7.332-.028 9.891-.14 2.55-.113 4.303-.525 5.822-1.116a11.732 11.732 0 0 0 4.247-2.765 11.732 11.732 0 0 0 2.766-4.247c.59-1.528 1.003-3.272 1.115-5.822.113-2.56.14-3.375.14-9.89 0-6.516-.027-7.332-.14-9.891-.112-2.55-.525-4.303-1.115-5.822-.591-1.594-1.407-2.935-2.747-4.266a11.732 11.732 0 0 0-4.247-2.765C38.194.675 36.45.262 33.9.15 31.331.028 30.516 0 24 0Z"
                        />
                        <path
                          fill="#C73907"
                          d="M24 11.672c-6.806 0-12.328 5.522-12.328 12.328 0 6.806 5.522 12.328 12.328 12.328 6.806 0 12.328-5.522 12.328-12.328 0-6.806-5.522-12.328-12.328-12.328Zm0 20.325a7.998 7.998 0 0 1 0-15.994 7.998 7.998 0 0 1 0 15.994Zm15.694-20.813a2.879 2.879 0 1 1-2.878-2.878 2.885 2.885 0 0 1 2.878 2.878Z"
                        />
                      </g>
                      <defs>
                        <clipPath id="a">
                          <path fill="#C73907" d="M0 0h48v48H0z" />
                        </clipPath>
                      </defs>
                    </svg>

                    <svg
                      className="tablet:w-8 h-8"
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 48 48"
                    >
                      <g clipPath="url(#a)">
                        <path
                          fill="#C73907"
                          d="M44.447 0H3.544C1.584 0 0 1.547 0 3.46v41.07C0 46.444 1.584 48 3.544 48h40.903C46.407 48 48 46.444 48 44.54V3.46C48 1.546 46.406 0 44.447 0ZM14.24 40.903H7.116V17.991h7.125v22.912ZM10.678 14.87a4.127 4.127 0 0 1-4.134-4.125 4.127 4.127 0 0 1 4.134-4.125 4.125 4.125 0 0 1 0 8.25Zm30.225 26.034h-7.115V29.766c0-2.653-.047-6.075-3.704-6.075-3.703 0-4.265 2.896-4.265 5.887v11.325h-7.107V17.991h6.826v3.13h.093c.947-1.8 3.272-3.702 6.731-3.702 7.21 0 8.541 4.744 8.541 10.912v12.572Z"
                        />
                      </g>
                      <defs>
                        <clipPath id="a">
                          <path fill="#C73907" d="M0 0h48v48H0z" />
                        </clipPath>
                      </defs>
                    </svg>
                  </section>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </section>
  );
}

export default TarakiTeam;
