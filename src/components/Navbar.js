import React, { useEffect, useRef } from "react";
import { Link } from "react-scroll";
import tarakiLogo from "../components/imgs/taraki-black.svg";

function Navbar() {
  const menuButtonRef = useRef(null);
  const navbarStickyRef = useRef(null);

  useEffect(() => {
    const handleMenuButtonClick = () => {
      if (navbarStickyRef.current.classList.contains("hidden")) {
        navbarStickyRef.current.classList.remove("hidden");
      } else {
        navbarStickyRef.current.classList.add("hidden");
      }
    };

    const menuButton = menuButtonRef.current;
    menuButton.addEventListener("click", handleMenuButtonClick);

    return () => {
      menuButton.removeEventListener("click", handleMenuButtonClick);
    };
  }, []);

  return (
    <header className="font-satoshi overflow-x-hidden">
      <nav className="bg-white border-gray-200 shadow-md fixed w-full z-50 top-0 start-0">
        <div className="flex flex-wrap items-center justify-between mx-auto p-4 tablet-m:px-8 laptop-s:p-7 desktop-m:p-10">
          <Link
            to="home"
            spy={true}
            smooth={true}
            duration={1600}
            offset={-50}
            className="flex items-center space-x-3 rtl:space-x-reverse cursor-pointer"
          >
            <img
              src={tarakiLogo}
              className="w-28 laptop-s:absolute laptop-s:left-2/4 laptop-s:-translate-x-1/2 laptop-m:w-32 desktop-m:w-40"
              alt="TARAKI LOGO HERE"
            />
          </Link>
          <div className="flex space-x-3 tablet-m:space-x-0 rtl:space-x-reverse">
            <button
              data-collapse-toggle="navbar-cta"
              type="button"
              className="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-800 rounded-lg tablet-m:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
              aria-controls="navbar-cta"
              aria-expanded="false"
              id="mobile-menu-button"
              ref={menuButtonRef}
            >
              <span className="sr-only">Open main menu</span>
              <svg
                className="w-5 h-5"
                aria-hidden="true"
                xmlns="http://www.w3.org/1600/svg"
                fill="none"
                viewBox="0 0 17 14"
              >
                <path
                  stroke="currentColor"
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth="2"
                  d="M1 1h15M1 7h15M1 13h15"
                />
              </svg>
            </button>
          </div>
          <div
            className="items-center justify-between hidden w-full tablet-m:flex tablet-m:w-auto mx-auto laptop-s:flex-1"
            id="navbar-cta"
            ref={navbarStickyRef}
          >
            <ul className="flex flex-col font-medium text-center p-4 tablet-m:p-0 mt-4 rounded-lg tablet-m:space-x-8 rtl:space-x-reverse tablet-m:flex-row tablet-m:mt-0 laptop-m:text-[1.26rem]">
              <li>
                <Link
                  to="about"
                  spy={true}
                  smooth={true}
                  duration={1600}
                  offset={-220}
                  className="block py-2 px-3 tablet-m:p-0 text-gray-900 hover:text-orange-600 rounded-lg cursor-pointer"
                >
                  About
                </Link>
              </li>
              <li>
                <Link
                  to="program"
                  spy={true}
                  smooth={true}
                  duration={1600}
                  offset={-100}
                  className="block py-2 px-3 tablet-m:p-0 text-gray-900 hover:text-orange-600 rounded-lg cursor-pointer"
                >
                  Programs
                </Link>
              </li>
              <li>
                <Link
                  to="framework"
                  spy={true}
                  smooth={true}
                  duration={1600}
                  offset={-120}
                  className="block py-2 px-3 tablet-m:p-0 text-gray-900 hover:text-orange-600 rounded-lg cursor-pointer"
                >
                  Framework
                </Link>
              </li>
              <li>
                <Link
                  to="events"
                  spy={true}
                  smooth={true}
                  duration={1600}
                  offset={-120}
                  className="block py-2 px-3 tablet-m:p-0 text-gray-900 hover:text-orange-600 rounded-lg cursor-pointer"
                >
                  Events
                </Link>
              </li>
              <li>
                <Link
                  to="faq"
                  spy={true}
                  smooth={true}
                  duration={1600}
                  offset={-100}
                  className="block py-2 px-3 tablet-m:p-0 text-gray-900 hover:text-orange-600 rounded-lg cursor-pointer"
                >
                  FAQ
                </Link>
              </li>
            </ul>
          </div>
          <button className="phone:hidden tablet-m:block bg-white tablet-m:px-3 tablet-m:py-2 laptop-s:px-8 laptop-s:py-3 text-[0.8rem] laptop-s:text-sm border border-trkblack rounded-md hover:bg-trkblack hover:text-white hover:border-orange-600 laptop-m:text-lg">
            Contact
          </button>
        </div>
      </nav>
    </header>
  );
}

export default Navbar;
