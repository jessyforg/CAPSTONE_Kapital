import React, { useEffect } from "react";
import { scroller } from "react-scroll";
import { Link, useNavigate } from "react-router-dom";
import Intto from "./imgs/InTTO.svg";
import tarakiLogo from "../components/imgs/taraki-black.svg";
import DentaSync from "../components/imgs/DentaSync.svg";
import gabe from "../components/imgs/tbi-team/Gabe.png";
import les from "../components/imgs/tbi-team/Les.png";
import echo from "../components/imgs/tbi-team/Echo.png";
import ParaPo from "../components/imgs/ParaPo.svg";
import carl from "../components/imgs/tbi-team/Carl.png";
import Ryzel from "../components/imgs/tbi-team/Ryzel.png";
import Colston from "../components/imgs/tbi-team/Colston.png";
import QrX from "../components/imgs/QrX.svg";
import excel from "../components/imgs/tbi-team/Lhorexcel.png";
import earl from "../components/imgs/tbi-team/Earl.png";
import agnes from "../components/imgs/tbi-team/Agnes.png";

export default function InTTOTBI() {
  const navigate = useNavigate();

  const handleScrollToHome = () => {
    scroller.scrollTo("Home", { smooth: true, duration: 1000, offset: -400 });
    navigate("/tbi");
  };

  useEffect(() => {
    document.title = "InTTO Incubatees";
  }, []);

  return (
    <>
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
              onClick={handleScrollToHome}
              className="bg-white phone:py-3 phone:px-3 tablet-m:px-3 tablet-m:py-2 laptop-s:px-5 laptop-s:py-3 text-[0.8rem] laptop-s:text-sm border border-trkblack rounded-md hover:bg-trkblack hover:text-white hover:border-orange-600 laptop-m:text-lg"
            >
              Return to Engagement
            </button>
          </div>
        </div>
      </nav>

      <section className="font-satoshi mt-24 laptop-s:mt-32 desktop-s:mt-36 desktop-m:mt-40">
        <img
          src={Intto}
          alt="1st-ico"
          className="laptop-s:w-52 phone:w-24 relative left-2/4 -translate-x-1/2 laptop-m:w-32 desktop-m:w-40"
        />

        <h1 className="font-bold text-[1rem] laptop-s:text-xl desktop-s:text-2xl text-center py-10">
          InTTO Startups
        </h1>
      </section>

      <section className="grid grid-cols-1 place-items-center justify-items-center tablet:grid gap-4 tablet-m:gap-1 tablet:grid-cols-2 tablet-m:grid-cols-3 tablet:px-12 laptop-m:px-24 desktop-s:px-28 desktop-m:px-36">
        <div className="w-[300px] h-[400px] bg-transparent cursor-pointer group perspective border-gray-300 rounded-lg">
          <div className="relative preserve-3d group-hover:my-rotate-y-180 w-full h-full duration-1000">
            <div className="absolute backface-hidden border-2 rounded-md w-full h-full">
              <img src={DentaSync} className="w-full h-full px-5" alt="" />
            </div>
            <div className="absolute my-rotate-y-180 backface-hidden w-full h-full bg-gray-100 overflow-hidden rounded-lg">
              <div className="text-center flex flex-col items-center justify-center h-full text-gray-800 px-5">
                <p>
                  An innovative web-based system that revolutionize dental
                  clinic management by streamlining appointment scheduling and
                  dental records management, integrating digitalized dental
                  charts and visual gallery.
                </p>
                <div className="grid-container phone:hidden tablet:hidden tablet-m:hidden laptop-s:block">
                  <div className="flex flex-col justify-center items-center text-center rounded-lg mt-5">
                    <img src={gabe} alt="" className="w-20 rounded-full" />
                    <div className="">
                      <h1 className=" tablet:text-[0.7rem] laptop-s:text-[0.9rem] desktop-s:text-[1rem] desktop-m:text-xl">
                        Gabriel Ollero
                      </h1>
                    </div>
                  </div>
                  <div className="flex flex-col justify-center items-center text-center rounded-lg mt-5">
                    <img src={les} alt="" className="w-20 rounded-full" />
                    <div className="">
                      <h1 className=" tablet:text-[0.7rem] laptop-s:text-[0.9rem] desktop-s:text-[1rem] desktop-m:text-xl">
                        Leslie Fuentes
                      </h1>
                    </div>
                  </div>
                  <div className="flex flex-col justify-center items-center text-center mt-5">
                    <img src={echo} alt="" className="w-20 rounded-full" />
                    <div className="">
                      <h1 className=" tablet:text-[0.7rem] laptop-s:text-[0.9rem] desktop-s:text-[1rem] desktop-m:text-xl">
                        Jericho Tamondong
                      </h1>
                    </div>
                  </div>
                </div>
                <button className="bg-dentasync px-6 py-2 font-semibold text-white rounded-full mt-5">
                  Contact Us!
                </button>
              </div>
            </div>
          </div>
        </div>

        <div className="w-[300px] h-[400px] bg-transparent cursor-pointer group perspective border-gray-300 rounded-lg">
          <div className="relative preserve-3d group-hover:my-rotate-y-180 w-full h-full duration-1000">
            <div className="absolute backface-hidden border-2 rounded-md w-full h-full">
              <img src={ParaPo} className="w-full h-full px-5" alt="" />
            </div>
            <div className="absolute my-rotate-y-180 backface-hidden w-full h-full bg-gray-100 overflow-hidden rounded-lg">
              <div className="text-center flex flex-col items-center justify-center h-full text-gray-800 px-5">
                <p>
                  A jeepney navigation system that provides user with commuting
                  information such a jeepney routes, jeepney stations, and
                  jeepney options and allows them to customize their own jeepney
                  avatar inspired by Filipino culture.
                </p>
                <div className="grid-container phone:hidden tablet:hidden tablet-m:hidden laptop-s:block">
                  <div className="flex flex-col justify-center items-center text-center rounded-lg mt-5">
                    <img src={Ryzel} alt="" className="w-20 rounded-full" />
                    <div className="">
                      <h1 className=" tablet:text-[0.7rem] laptop-s:text-[0.9rem] desktop-s:text-[1rem] desktop-m:text-xl">
                        Ryzel Felizco
                      </h1>
                    </div>
                  </div>
                  <div className="flex flex-col justify-center items-center text-center rounded-lg mt-5">
                    <img src={carl} alt="" className="w-20 rounded-full" />
                    <div className="">
                      <h1 className=" tablet:text-[0.7rem] laptop-s:text-[0.9rem] desktop-s:text-[1rem] desktop-m:text-xl">
                        Carl Masedman
                      </h1>
                    </div>
                  </div>
                  <div className="flex flex-col justify-center items-center text-center mt-5">
                    <img src={Colston} alt="" className="w-20 rounded-full" />
                    <div className="">
                      <h1 className=" tablet:text-[0.7rem] laptop-s:text-[0.9rem] desktop-s:text-[1rem] desktop-m:text-xl">
                        Colston Bod-oy
                      </h1>
                    </div>
                  </div>
                </div>
                <button className="bg-parapo px-6 py-2 font-semibold text-white rounded-full mt-5">
                  Contact Us!
                </button>
              </div>
            </div>
          </div>
        </div>

        <div className="w-[300px] h-[400px] bg-transparent cursor-pointer group perspective border-gray-300 rounded-lg">
          <div className="relative preserve-3d group-hover:my-rotate-y-180 w-full h-full duration-1000">
            <div className="absolute backface-hidden border-2 rounded-md w-full h-full">
              <img src={QrX} className="w-full h-full px-5" alt="" />
            </div>
            <div className="absolute my-rotate-y-180 backface-hidden w-full h-full bg-gray-100 overflow-hidden rounded-lg">
              <div className="text-center flex flex-col items-center justify-center h-full text-gray-800 px-5">
                <p>
                  A modernized prescription software that generates unique QR
                  codes for medical prescriptions, aiming to improve efficiency,
                  reduce errors, and streamline healthcare processes.
                </p>
                <div className="grid-container phone:hidden tablet:hidden tablet-m:hidden laptop-s:block">
                  <div className="flex flex-col justify-center items-center text-center rounded-lg mt-5">
                    <img src={excel} alt="" className="w-20 rounded-full" />
                    <div className="">
                      <h1 className=" tablet:text-[0.7rem] laptop-s:text-[0.9rem] desktop-s:text-[1rem] desktop-m:text-xl">
                        Lhorexcel Bombarda
                      </h1>
                    </div>
                  </div>
                  <div className="flex flex-col justify-center items-center text-center rounded-lg mt-5">
                    <img src={agnes} alt="" className="w-20 rounded-full" />
                    <div className="">
                      <h1 className=" tablet:text-[0.7rem] laptop-s:text-[0.9rem] desktop-s:text-[1rem] desktop-m:text-xl">
                        Agnes Nazarro
                      </h1>
                    </div>
                  </div>
                  <div className="flex flex-col justify-center items-center text-center mt-5">
                    <img src={earl} alt="" className="w-20 rounded-full" />
                    <div className="">
                      <h1 className=" tablet:text-[0.7rem] laptop-s:text-[0.9rem] desktop-s:text-[1rem] desktop-m:text-xl">
                        Earl Alexus Serafica
                      </h1>
                    </div>
                  </div>
                </div>
                <button className="bg-qrx px-6 py-2 font-semibold text-white rounded-full mt-5">
                  Contact Us!
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <footer className="text-center bg-white p-2 rounded-lg shadow mt-8 laptop-s:text-center">
        <div className="w-full mx-auto max-w-screen-xl p-4 px-14 tablet:px-64 tablet:flex tablet:items-center tablet:justify-between laptop-s:px-[32rem] phone:px-16 tablet-m:px-96">
          <span className="text-xs text-gray-400 text-center laptop-m:text-sm">
            © 2024 | TARAKI | All Rights Reserved Designed by{" "}
            <span className="font-bold text-gray-500"> TARAKI-CAR</span>
          </span>
          <ul className="flex flex-wrap items-center mt-0 text-sm font-medium phone:hidden laptop:flex text-gray-400">
            <li>
              <a href="#section2" className="hover:underline me-4 tablet:me-6">
                About
              </a>
            </li>
            <li>
              <a href="#1" className="hover:underline me-4 tablet:me-6">
                Privacy Policy
              </a>
            </li>
            <li>
              <a href="#2" className="hover:underline me-4 tablet:me-6">
                Licensing
              </a>
            </li>
          </ul>
        </div>
      </footer>
    </>
  );
}
