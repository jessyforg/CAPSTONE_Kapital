import React, { useEffect } from "react";
import { Link } from "react-scroll";
import AOS from "aos";
import "aos/dist/aos.css";
import logo from "../components/imgs/taraki-black.svg";

function Contacts() {
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
          <section
            className="flex justify-center items-center mt-14 tablet:mt-12 aos-init"
            data-aos="fade-down"
          >
            <Link
              to="home"
              spy={true}
              smooth={true}
              duration={2000}
              offset={-50}
              className="flex items-center space-x-3 rtl:space-x-reverse cursor-pointer"
            >
              <img
                src={logo}
                className="h-14 laptop-m:h-20"
                alt="TARAKI LOGO HERE"
              />
            </Link>
          </section>

          <div className="items-center justify-between tablet-m:hidden phone:hidden tablet:hidden laptop-s:block pt-5">
            <ul className="flex flex-col font-medium text-center p-4 tablet-m:p-0 mt-4 rounded-lg tablet-m:space-x-8 rtl:space-x-reverse tablet-m:flex-row tablet-m:mt-0 laptop-m:text-[1.25rem] justify-center">
              <li>
                <Link
                  to="about"
                  spy={true}
                  smooth={true}
                  duration={2000}
                  offset={-200}
                  href="#home"
                  className="block py-2 px-3 tablet-m:p-0 text-gray-900 hover:text-orange-600 rounded-lg aos-init"
                  data-aos="fade-down"
                  data-aos-duration="800"
                >
                  About
                </Link>
              </li>
              <li>
                <Link
                  to="team"
                  spy={true}
                  smooth={true}
                  duration={2000}
                  offset={-200}
                  href="#home"
                  className="block py-2 px-3 tablet-m:p-0 text-gray-900 hover:text-orange-600 rounded-lg aos-init"
                  data-aos="fade-down"
                  data-aos-duration="1000"
                >
                  TARAKIs
                </Link>
              </li>
              <li>
                <Link
                  to="program"
                  spy={true}
                  smooth={true}
                  duration={2000}
                  offset={-100}
                  href="#program"
                  className="block py-2 px-3 tablet-m:p-0 text-gray-900 rounded-lg hover:text-orange-600 aos-init"
                  data-aos="fade-down"
                  data-aos-duration="1200"
                >
                  Programs
                </Link>
              </li>
              <li>
                <Link
                  to="framework"
                  spy={true}
                  smooth={true}
                  duration={2000}
                  offset={-120}
                  href="#services"
                  className="block py-2 px-3 tablet-m:p-0 text-gray-900 rounded-lg hover:text-orange-600 aos-init"
                  data-aos="fade-down"
                  data-aos-duration="1400"
                >
                  Framework
                </Link>
              </li>
              <li>
                <Link
                  to="events"
                  spy={true}
                  smooth={true}
                  duration={2000}
                  offset={-120}
                  href="#contact"
                  className="block py-2 px-3 tablet-m:p-0 text-gray-900 rounded-lg hover:text-orange-600 aos-init"
                  data-aos="fade-down"
                  data-aos-duration="1600"
                >
                  Events
                </Link>
              </li>
              <li>
                <Link
                  to="faq"
                  spy={true}
                  smooth={true}
                  duration={2000}
                  offset={-100}
                  href="#contact"
                  className="block py-2 px-3 tablet-m:p-0 text-gray-900 rounded-lg hover:text-orange-600 aos-init"
                  data-aos="fade-down"
                  data-aos-duration="1800"
                >
                  FAQ
                </Link>
              </li>
            </ul>
          </div>

          <section className="flex justify-evenly items-center px-14 tablet:px-56 tablet-m:px-[22rem] tablet:my-8 my-8 laptop-s:px-[30rem] laptop-m:px-[34rem] desktop-m:px-[48rem]">
            <a
              href="https://www.facebook.com/tarakicar"
              target="_blank"
              rel="noreferrer"
              className=" aos-init"
              data-aos="zoom-in"
            >
              <svg
                className="tablet:w-8 tablet:h-8 laptop-m:w-10 laptop-m:h-10 transition-all duration-300 hover:scale-110"
                xmlns="http://www.w3.org/2000/svg"
                width="28"
                height="28"
                fill="none"
                viewBox="0 0 48 48"
              >
                <g clipPath="url(#a)">
                  <path
                    fill="#F04F06"
                    d="M24 0C10.745 0 0 10.745 0 24c0 11.255 7.75 20.7 18.203 23.293V31.334h-4.95V24h4.95v-3.16c0-8.169 3.697-11.955 11.716-11.955 1.521 0 4.145.298 5.218.596v6.648c-.566-.06-1.55-.09-2.773-.09-3.935 0-5.455 1.492-5.455 5.367V24h7.84L33.4 31.334h-6.49v16.49C38.793 46.39 48 36.271 48 24 48 10.745 37.255 0 24 0Z"
                  />
                </g>
                <defs>
                  <clipPath id="a">
                    <path fill="#2f2f38" d="M0 0h48v48H0z" />
                  </clipPath>
                </defs>
              </svg>
            </a>
            <a
              href="https://www.instagram.com/tarakicar/"
              target="_blank"
              rel="noreferrer"
              className="aos-init"
              data-aos="zoom-in"
            >
              <svg
                className="tablet:w-8 tablet:h-8 laptop-m:w-10 laptop-m:h-10 transition-all duration-300 hover:scale-110"
                xmlns="http://www.w3.org/2000/svg"
                width="28"
                height="28"
                fill="none"
                viewBox="0 0 48 48"
              >
                <g clipPath="url(#a)">
                  <path
                    fill="#F04F06"
                    d="M24 4.322c6.413 0 7.172.028 9.694.14 2.343.104 3.61.497 4.453.825 1.116.432 1.922.957 2.756 1.791.844.844 1.36 1.64 1.79 2.756.329.844.723 2.12.826 4.454.112 2.53.14 3.29.14 9.693 0 6.413-.028 7.172-.14 9.694-.103 2.344-.497 3.61-.825 4.453-.431 1.116-.957 1.922-1.79 2.756-.845.844-1.642 1.36-2.757 1.791-.844.328-2.119.722-4.453.825-2.532.112-3.29.14-9.694.14-6.413 0-7.172-.028-9.694-.14-2.343-.103-3.61-.497-4.453-.825-1.115-.431-1.922-.956-2.756-1.79-.844-.844-1.36-1.641-1.79-2.757-.329-.844-.723-2.119-.826-4.453-.112-2.531-.14-3.29-.14-9.694 0-6.412.028-7.172.14-9.694.103-2.343.497-3.609.825-4.453.431-1.115.957-1.921 1.79-2.756.845-.844 1.642-1.36 2.757-1.79.844-.329 2.119-.722 4.453-.825 2.522-.113 3.281-.141 9.694-.141ZM24 0c-6.516 0-7.331.028-9.89.14-2.55.113-4.304.526-5.822 1.116-1.585.619-2.926 1.435-4.257 2.775-1.34 1.332-2.156 2.672-2.775 4.247C.666 9.806.253 11.55.141 14.1.028 16.669 0 17.484 0 24s.028 7.331.14 9.89c.113 2.55.526 4.304 1.116 5.822.619 1.585 1.435 2.925 2.775 4.257a11.732 11.732 0 0 0 4.247 2.765c1.528.591 3.272 1.003 5.822 1.116 2.56.112 3.375.14 9.89.14 6.516 0 7.332-.028 9.891-.14 2.55-.113 4.303-.525 5.822-1.116a11.732 11.732 0 0 0 4.247-2.765 11.732 11.732 0 0 0 2.766-4.247c.59-1.528 1.003-3.272 1.115-5.822.113-2.56.14-3.375.14-9.89 0-6.516-.027-7.332-.14-9.891-.112-2.55-.525-4.303-1.115-5.822-.591-1.594-1.407-2.935-2.747-4.266a11.732 11.732 0 0 0-4.247-2.765C38.194.675 36.45.262 33.9.15 31.331.028 30.516 0 24 0Z"
                  />
                  <path
                    fill="#F04F06"
                    d="M24 11.672c-6.806 0-12.328 5.522-12.328 12.328 0 6.806 5.522 12.328 12.328 12.328 6.806 0 12.328-5.522 12.328-12.328 0-6.806-5.522-12.328-12.328-12.328Zm0 20.325a7.998 7.998 0 0 1 0-15.994 7.998 7.998 0 0 1 0 15.994Zm15.694-20.813a2.879 2.879 0 1 1-2.878-2.878 2.885 2.885 0 0 1 2.878 2.878Z"
                  />
                </g>
                <defs>
                  <clipPath id="a">
                    <path fill="#F04F06" d="M0 0h48v48H0z" />
                  </clipPath>
                </defs>
              </svg>
            </a>
            <a
              href="https://ph.linkedin.com/company/taraki-car?fbclid=IwZXh0bgNhZW0CMTAAAR0FWX8lV2uhonBQWo7ZcVXDE47WYE7txp3X2VfG_07yJCfhNLU1MudzBM8_aem_ZmFrZWR1bW15MTZieXRlcw"
              target="_blank"
              rel="noreferrer"
              className="aos-init"
              data-aos="zoom-in"
            >
              <svg
                className="tablet:w-8 tablet:h-8  laptop-m:w-10 laptop-m:h-10 transition-all duration-300 hover:scale-110"
                xmlns="http://www.w3.org/2000/svg"
                width="28"
                height="28"
                fill="none"
                viewBox="0 0 48 48"
              >
                <g clipPath="url(#a)">
                  <path
                    fill="#F04F06"
                    d="M44.447 0H3.544C1.584 0 0 1.547 0 3.46v41.07C0 46.444 1.584 48 3.544 48h40.903C46.407 48 48 46.444 48 44.54V3.46C48 1.546 46.406 0 44.447 0ZM14.24 40.903H7.116V17.991h7.125v22.912ZM10.678 14.87a4.127 4.127 0 0 1-4.134-4.125 4.127 4.127 0 0 1 4.134-4.125 4.125 4.125 0 0 1 0 8.25Zm30.225 26.034h-7.115V29.766c0-2.653-.047-6.075-3.704-6.075-3.703 0-4.265 2.896-4.265 5.887v11.325h-7.107V17.991h6.826v3.13h.093c.947-1.8 3.272-3.702 6.731-3.702 7.21 0 8.541 4.744 8.541 10.912v12.572Z"
                  />
                </g>
                <defs>
                  <clipPath id="a">
                    <path fill="#F04F06" d="M0 0h48v48H0z" />
                  </clipPath>
                </defs>
              </svg>
            </a>

            <a
              href="https://heyzine.com/flip-book/be7bd4e55d.html#page/1"
              target="_blank"
              rel="noreferrer"
              className="aos-init"
              data-aos="zoom-in"
            >
              <svg
                className="tablet:w-8 tablet:h-8 laptop-m:w-10 laptop-m:h-10 transition-all duration-300 hover:scale-110"
                xmlns="http://www.w3.org/2000/svg"
                width="28"
                height="28"
                fill="none"
                viewBox="0 0 32 23"
              >
                <path
                  fill="#F04F06"
                  d="M4.038.787h24.666a3.083 3.083 0 0 1 3.084 3.083v15.417a3.083 3.083 0 0 1-3.084 3.083H4.038a3.083 3.083 0 0 1-3.084-3.083V3.87A3.083 3.083 0 0 1 4.038.787ZM8.663 13.12a1.542 1.542 0 1 0 0 3.084h9.25a1.542 1.542 0 1 0 0-3.084h-9.25Zm15.416-7.708v3.083h3.084V5.412h-3.084Z"
                />
              </svg>
            </a>
          </section>
        </div>
      </div>
    </div>
  );
}
export default Contacts;
