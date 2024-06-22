import React, { useState, useEffect, useRef } from "react";
import { Link } from "react-scroll";
import tarakiLogo from "../components/imgs/taraki-black.svg";

function Navbar() {
  const [isModalOpen, setIsModalOpen] = useState(false);
  const modalRef = useRef(null);

  const modalClose = () => {
    if (modalRef.current) {
      modalRef.current.classList.remove("fadeIn");
      modalRef.current.classList.add("fadeOut");
      setTimeout(() => {
        setIsModalOpen(false);
      }, 500);
    }
  };

  const openModal = () => {
    setIsModalOpen(true);
    if (modalRef.current) {
      modalRef.current.classList.remove("fadeOut");
      modalRef.current.classList.add("fadeIn");
    }
  };

  useEffect(() => {
    if (modalRef.current) {
      modalRef.current.style.display = isModalOpen ? "flex" : "none";
    }

    const handleWindowClick = (event) => {
      if (event.target === modalRef.current) {
        modalClose();
      }
    };

    window.addEventListener("click", handleWindowClick);

    return () => {
      window.removeEventListener("click", handleWindowClick);
    };
  }, [isModalOpen]);

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
            <ul className="flex flex-col font-medium text-center p-4 tablet-m:p-0 mt-4 rounded-lg tablet-m:space-x-8 rtl:space-x-reverse tablet-m:flex-row tablet-m:mt-0 laptop-m:text-[1rem]">
              <li>
                <Link
                  to="about"
                  spy={true}
                  smooth={true}
                  duration={1600}
                  offset={-410}
                  className="block py-2 px-3 tablet-m:p-0 text-gray-900 hover:text-orange-600 rounded-lg cursor-pointer"
                >
                  About
                </Link>
              </li>
              <li>
                <Link
                  to="team"
                  spy={true}
                  smooth={true}
                  duration={1600}
                  offset={-280}
                  className="block py-2 px-3 tablet-m:p-0 text-gray-900 hover:text-orange-600 rounded-lg cursor-pointer"
                >
                  TARAKIs
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
          <button
            onClick={openModal}
            className="phone:hidden tablet-m:block bg-white tablet-m:px-3 tablet-m:py-2 laptop-s:px-8 laptop-s:py-3 text-[0.8rem] laptop-s:text-sm border border-trkblack rounded-md hover:bg-trkblack hover:text-white hover:border-orange-600 laptop-m:text-lg"
          >
            Contact
          </button>
          {isModalOpen && (
            <div
              ref={modalRef}
              className="main-modal fixed w-full h-100 inset-0 z-50 overflow-hidden flex justify-center items-center animated fadeIn faster"
            >
              <div className="border border-orange-600 modal-container bg-white w-[60rem] mx-auto rounded-lg shadow-lg z-50 overflow-y-auto">
                <div className="modal-content py-4 text-left px-6">
                  <div className="flex justify-between items-center">
                    <p className="text-2xl font-bold">Connect with us today!</p>
                    <div
                      className="modal-close cursor-pointer z-50"
                      onClick={modalClose}
                    >
                      <div className="bg-none p-2 rounded-lg hover:bg-gray-200">
                        <svg
                          className="fill-current text-black"
                          xmlns="http://www.w3.org/2000/svg"
                          width="18"
                          height="18"
                          viewBox="0 0 18 18"
                        >
                          <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                        </svg>
                      </div>
                    </div>
                  </div>
                  <p className=" font-extralight text-sm text-gray-500 pb-3 ">
                    Let us know what you think about
                  </p>
                  <hr></hr>
                  <div className="flex justify-evenly items-center">
                    <div className="my-3">
                      <label
                        for="name"
                        class="block mb-2 text-sm font-medium text-gray-900"
                      >
                        Name
                      </label>
                      <div class="flex">
                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md">
                          <svg
                            class="w-6 h-5 text-gray-500"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                          >
                            <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                          </svg>
                        </span>
                        <input
                          type="text"
                          id="name"
                          class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-[30rem] text-sm border-gray-300 p-4 "
                          placeholder="John Hee Hee"
                        />
                      </div>

                      <label
                        for="email"
                        class="block my-2 text-sm font-medium text-gray-900"
                      >
                        Email
                      </label>
                      <div class="flex">
                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md">
                          <svg
                            className="w-6 h-5 text-gray-500"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                          >
                            <path d="M2.038 5.61A2.01 2.01 0 0 0 2 6v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6c0-.12-.01-.238-.03-.352l-.866.65-7.89 6.032a2 2 0 0 1-2.429 0L2.884 6.288l-.846-.677Z" />
                            <path d="M20.677 4.117A1.996 1.996 0 0 0 20 4H4c-.225 0-.44.037-.642.105l.758.607L12 10.742 19.9 4.7l.777-.583Z" />
                          </svg>
                        </span>
                        <input
                          type="email"
                          id="email"
                          class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-[30rem] text-sm border-gray-300 p-4 "
                          placeholder="example@gmail.com"
                        />
                      </div>
                      <label
                        for="number"
                        class="block my-2 text-sm font-medium text-gray-900"
                      >
                        Mobile Number
                      </label>
                      <div class="flex">
                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md">
                          <svg
                            className="w-6 h-8 text-gray-500"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path
                              fill=""
                              d="M7.978 4a2.553 2.553 0 0 0-1.926.877C4.233 6.7 3.699 8.751 4.153 10.814c.44 1.995 1.778 3.893 3.456 5.572 1.68 1.679 3.577 3.018 5.57 3.459 2.062.456 4.115-.073 5.94-1.885a2.556 2.556 0 0 0 .001-3.861l-1.21-1.21a2.689 2.689 0 0 0-3.802 0l-.617.618a.806.806 0 0 1-1.14 0l-1.854-1.855a.807.807 0 0 1 0-1.14l.618-.62a2.692 2.692 0 0 0 0-3.803l-1.21-1.211A2.555 2.555 0 0 0 7.978 4Z"
                            />
                          </svg>
                        </span>
                        <input
                          type="text"
                          id="number"
                          class="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-[30rem] text-sm border-gray-300 p-4 "
                          placeholder="0999*********"
                        />
                      </div>

                      <label
                        for="message"
                        class="block my-2 text-sm font-medium text-gray-900"
                      >
                        Your message
                      </label>
                      <textarea
                        id="message"
                        rows="4"
                        class="block p-4 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Write your thoughts here..."
                      ></textarea>
                      <div className="flex justify-end pt-2">
                        <button className="focus:outline-none px-4 w-full bg-orange-500 p-3 rounded-lg text-white hover:bg-orange-400">
                          Submit
                        </button>
                      </div>
                    </div>
                    <div className="bg-gray-400 w-96 h-[28rem] ml-8"></div>
                  </div>
                </div>
              </div>
            </div>
          )}
        </div>
      </nav>
    </header>
  );
}

export default Navbar;
