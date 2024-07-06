import React, { useState, useEffect, useRef } from "react";
import emailjs from "@emailjs/browser";
import { scroller } from "react-scroll";
import { Link } from "react-router-dom";
import tarakiLogo from "../components/imgs/taraki-black.svg";
import "./styles.css";

function Navbar() {
  const form = useRef();
  const [showAlert, setShowAlert] = useState(false);

  const sendEmail = (e) => {
    e.preventDefault();

    emailjs
      .sendForm("service_mb7zq1l", "template_l38puqp", form.current, {
        publicKey: "",
      })
      .then(
        () => {
          console.log("SUCCESS!");
          e.target.reset();
        },
        (error) => {
          console.log("FAILED...", error.text);
          setShowAlert(true);
          setTimeout(() => {
            setShowAlert(false);
          }, 3000);
        }
      );
  };

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

  const closeNavbar = () => {
    navbarStickyRef.current.classList.add("hidden");
  };

  return (
    <header className="font-satoshi overflow-x-hidden">
      <nav className="bg-white border-gray-200 shadow-md fixed w-full z-50 top-0 start-0">
        <div className="flex flex-wrap items-center justify-between mx-auto p-4 tablet-m:px-8 laptop-s:p-7 desktop-m:p-10">
          <Link
            to="/"
            onClick={(e) => {
              scroller.scrollTo("home", {
                smooth: true,
                duration: 1000,
                offset: -50,
              });
              closeNavbar();
            }}
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
            <ul className="flex flex-col font-medium p-4 tablet-m:p-0 mt-4 rounded-lg tablet-m:space-x-8 rtl:space-x-reverse tablet-m:flex-row tablet-m:mt-0 laptop-m:text-[1rem]">
              <li>
                <Link
                  to="/"
                  onClick={(e) => {
                    scroller.scrollTo("about", {
                      smooth: true,
                      duration: 1000,
                      offset: -400,
                    });
                    closeNavbar();
                  }}
                  className="block py-2 px-3 tablet-m:p-0 text-gray-900 hover:text-orange-600 rounded-lg cursor-pointer"
                >
                  About
                </Link>
              </li>
              <li>
                <Link
                  to="/"
                  onClick={(e) => {
                    scroller.scrollTo("team", {
                      smooth: true,
                      duration: 1000,
                      offset: -280,
                    });
                    closeNavbar();
                  }}
                  className="block py-2 px-3 tablet-m:p-0 text-gray-900 hover:text-orange-600 rounded-lg cursor-pointer"
                >
                  TARAKIs
                </Link>
              </li>

              <li>
                <div className="dropdown">
                  <span className="rounded-md">
                    <button
                      className="inline-flex phone:py-2 tablet-m:py-0 px-3 w-full leading-5 transition duration-150 ease-in-out bg-white  rounded-md hover:text-orange-600 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800"
                      type="button"
                      aria-haspopup="true"
                      aria-expanded="true"
                      aria-controls="headlessui-menu-items-117"
                    >
                      <span>Explore</span>
                      <svg
                        className="w-5 h-5 ml-2 -mr-1"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                          clip-rule="evenodd"
                        ></path>
                      </svg>
                    </button>
                  </span>
                  <div className="opacity-0 invisible dropdown-menu transition-all duration-300 transform origin-top-right -translate-y-2 scale-100">
                    <div
                      className="absolute laptop-s:w-40 mt-2 origin-top-right bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-lg outline-none"
                      aria-labelledby="headlessui-menu-button-1"
                      id="headlessui-menu-items-117"
                      role="menu"
                    >
                      <div className="py-1">
                        <li>
                          <Link
                            to="/"
                            onClick={(e) => {
                              scroller.scrollTo("program", {
                                smooth: true,
                                duration: 1000,
                                offset: -120,
                              });
                              closeNavbar();
                            }}
                            className="text-gray-700 flex justify-between w-full px-4 py-2 text-sm leading-5 cursor-pointer hover:bg-orange-100"
                          >
                            Programs
                          </Link>
                        </li>
                        <li>
                          <Link
                            to="/"
                            onClick={(e) => {
                              scroller.scrollTo("framework", {
                                smooth: true,
                                duration: 1000,
                                offset: -120,
                              });
                              closeNavbar();
                            }}
                            className="text-gray-700 flex justify-between w-full px-4 py-2 text-sm leading-5 cursor-pointer hover:bg-orange-100"
                          >
                            Framework
                          </Link>
                        </li>
                        <li>
                          <Link
                            to="/"
                            onClick={(e) => {
                              scroller.scrollTo("events", {
                                smooth: true,
                                duration: 1000,
                                offset: -120,
                              });
                              closeNavbar();
                            }}
                            className="text-gray-700 flex justify-between w-full px-4 py-2 text-sm leading-5 cursor-pointer hover:bg-orange-100"
                          >
                            Events
                          </Link>
                        </li>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <Link
                  to="/"
                  onClick={(e) => {
                    scroller.scrollTo("faq", {
                      smooth: true,
                      duration: 1000,
                      offset: -100,
                    });
                    closeNavbar();
                  }}
                  className="block py-2 px-3 tablet-m:p-0 text-gray-900 hover:text-orange-600 rounded-lg cursor-pointer"
                >
                  FAQ
                </Link>
              </li>
              <li>
                <Link
                  to="/tbi"
                  onClick={(e) => {
                    closeNavbar();
                  }}
                  activeClassName="text-orange-600"
                  className="block py-2 px-3 tablet-m:p-0 text-gray-900 hover:text-orange-600 rounded-lg cursor-pointer"
                >
                  Engagement
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
              <div className="border border-orange-600 modal-container bg-white w-[60rem] laptop-s:w-[65rem] mx-auto rounded-lg shadow-lg z-50 overflow-y-auto">
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
                  <form ref={form} onSubmit={sendEmail}>
                    <div className="flex justify-evenly items-center">
                      <div className="my-3">
                        <label
                          htmlFor="name"
                          className="block mb-2 text-sm font-medium text-gray-900"
                        >
                          Your name
                        </label>
                        <div className="flex">
                          <span className="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md">
                            <svg
                              className="w-6 h-5 text-gray-500"
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
                            id="user_name"
                            name="user_name"
                            className="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-0 laptop-s:flex-1 min-w-0 w-[27rem] laptop-s:w-[30rem] text-sm border-gray-300 p-4"
                            placeholder="Enter your name"
                            required
                          />
                        </div>

                        <label
                          htmlFor="email"
                          className="block my-2 text-sm font-medium text-gray-900"
                        >
                          Your email
                        </label>
                        <div className="flex">
                          <span className="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md">
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
                            name="email"
                            className="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-0 laptop-s:flex-1 min-w-0 w-[27rem] laptop-s:w-[30rem] text-sm border-gray-300 p-4"
                            placeholder="example@email.com"
                            required
                          />
                        </div>
                        <label
                          htmlFor="number"
                          className="block my-2 text-sm font-medium text-gray-900"
                        >
                          Your mobile number
                        </label>
                        <div className="flex">
                          <span className="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md">
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
                            name="number"
                            className="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-0 laptop-s:flex-1 min-w-0 w-[27rem] laptop-s:w-[30rem] text-sm border-gray-300 p-4"
                            placeholder="Enter your contact number"
                            required
                          />
                        </div>

                        <label
                          htmlFor="message"
                          className="block my-2 text-sm font-medium text-gray-900"
                        >
                          Your message
                        </label>
                        <textarea
                          id="message"
                          name="message"
                          rows="4"
                          className="block p-2.5 flex-0 laptop-s:flex-1 min-w-0 w-[30rem] laptop-s:w-[33rem] text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Leave a message..."
                          required
                        ></textarea>
                        <div className="flex justify-end pt-2">
                          <button
                            type="submit"
                            value="send"
                            className="focus:outline-none px-4 w-full bg-orange-500 p-3 rounded-lg text-white hover:bg-orange-400"
                          >
                            Submit
                          </button>
                        </div>
                      </div>
                      <div className="shadow-lg w-96 h-[28rem] ml-8">
                        <iframe
                          title="University of Cordilleras Location"
                          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3853.9072788242074!2d120.5901796201217!3d16.410273498776423!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3391a1685e7b7073%3A0xe6bda988e8558d2!2sUniversity%20of%20Cordilleras%20Legarda!5e0!3m2!1sen!2sph!4v1719022546794!5m2!1sen!2sph"
                          width="384"
                          height="448"
                          style={{ border: 0 }}
                          allowFullScreen=""
                          loading="lazy"
                          referrerPolicy="no-referrer-when-downgrade"
                          className="rounded-md shadow-md"
                        ></iframe>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              {showAlert && (
                <>
                  <div className="fixed inset-0 z-50 bg-black bg-opacity-70 animate__animated animate__fadeIn animate__faster"></div>
                  <div className="bg-gray-100 absolute z-50 rounded-md p-8 laptop-s:px-20 flex flex-col top-[28rem] laptop-s:top-[15rem] items-center animate__animated animate__fadeInDown animate__faster">
                    <svg
                      className="stroke-2 stroke-current text-green-600 h-28 w-28 mr-2 flex-shrink-0"
                      viewBox="0 0 24 24"
                      fill="none"
                      strokeLinecap="round"
                      strokeLinejoin="round"
                    >
                      <path d="M0 0h24v24H0z" stroke="none" />
                      <circle cx="12" cy="12" r="9" />
                      <path d="M9 12l2 2 4-4" />
                    </svg>

                    <div className="text-green-700">
                      <div className="font-bold text-2xl">
                        Email has been sent!
                      </div>

                      <div>Please wait for TARAKI team to get back to you.</div>
                    </div>
                  </div>
                </>
              )}
            </div>
          )}
        </div>
      </nav>
    </header>
  );
}

export default Navbar;
