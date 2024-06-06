// eslint-disable-next-line no-unused-vars
import React, { useEffect, useRef } from "react";
import tarakiLogo from '../components/imgs/taraki-black.svg'

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
      <nav className="bg-white border-gray-200 fixed w-full z-50 top-0 start-0">
        <div className="flex flex-wrap items-center justify-between mx-auto p-4">
          <a
            href="#home"
            className="flex items-center space-x-3 rtl:space-x-reverse"
          >
            <img
              src={tarakiLogo}
              className="w-28"
              alt="TARAKI LOGO HERE"
            />
          </a>
          <div className="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
            <button
              data-collapse-toggle="navbar-cta"
              type="button"
              className="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-800 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
              aria-controls="navbar-cta"
              aria-expanded="false"
              id="mobile-menu-button"
              ref={menuButtonRef}
            >
              <span className="sr-only">Open main menu</span>
              <svg
                className="w-5 h-5"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
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
            className="items-center justify-between hidden w-full md:flex md:w-auto md:order-1"
            id="navbar-cta"
            ref={navbarStickyRef}
          >
            <ul className="flex flex-col font-medium p-4 md:p-0 mt-4 border rounded-lg md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0">
              <li>
                <a
                  href="#home"
                  className="block py-2 px-3 md:p-0 text-white bg-orange-600 rounded"
                >
                  Home
                </a>
              </li>
              <li>
                <a
                  href="#about"
                  className="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-orange-600 hover:text-gray-50"
                >
                  About
                </a>
              </li>
              <li>
                <a
                  href="#services"
                  className="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-orange-600 hover:text-gray-50"
                >
                  Services
                </a>
              </li>
              <li>
                <a
                  href="#contact"
                  className="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-orange-600 hover:text-gray-50"
                >
                  Contact
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </header>
  );
}

export default Navbar;
