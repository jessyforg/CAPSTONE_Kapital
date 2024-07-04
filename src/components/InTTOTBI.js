import React from "react";
import { scroller } from "react-scroll";
import { Link, useNavigate } from "react-router-dom";
import Intto from "./imgs/InTTO.svg";
import tarakiLogo from "../components/imgs/taraki-black.svg"
import DentaSync from "../components/imgs/DentaSync.svg"
import ParaPo from "../components/imgs/ParaPo.svg"
import QrX from "../components/imgs/QrX.svg"

export default function InTTOTBI() {

    const navigate = useNavigate();

    const handleScrollToHome = () => {
        scroller.scrollTo('Home', { smooth: true, duration: 1000, offset: -400 });
        navigate('/tbi');
      };

  return (
    <>
        <nav className="bg-white border-gray-200 shadow-md fixed w-full z-50 top-0 start-0">
            <div className="flex flex-wrap items-center justify-between mx-auto p-4 tablet-m:px-8 laptop-s:p-7 desktop-m:p-10">
                <Link
                to="/"
                onClick={(e) => {
                    scroller.scrollTo('home', { smooth: true, duration: 1000, offset: -50 });
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

        <h1 className="font-bold text-[1rem] laptop-s:text-xl desktop-s:text-2xl text-center py-3">
         InTTO Startups
        </h1>
        </section>
        
        <section className="grid grid-cols-1 place-items-center justify-items-center tablet:grid gap-4 tablet-m:gap-1  tablet:grid-cols-2 tablet-m:grid-cols-3 tablet:px-12 laptop-m:px-24 desktop-s:px-28 desktop-m:px-36">
          <div
            class="w-[300px] h-[420px] bg-transparent cursor-pointer group perspective rounded-lg"
          >
            <div
              class="relative preserve-3d group-hover:my-rotate-y-180 w-full h-full duration-1000"
            >
              <div class="absolute backface-hidden border-2 w-full h-full">
              <img src={DentaSync} class="w-full h-full" alt=""/>
              </div>
              <div
                class="absolute my-rotate-y-180 backface-hidden w-full h-full bg-gray-100 overflow-hidden"
              >
                <div
                  class="text-center flex flex-col items-center justify-center h-full text-gray-800 px-2 pb-24"
                >
                  <h1 class="text-3xl font-semibold">Lorem ipsum</h1>
                  <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis
                    itaque assumenda saepe animi maxime libero non quasi, odit natus
                    veritatis enim culpa nam inventore doloribus quidem temporibus
                    amet velit accusamus.
                  </p>
                  <button
                    class="bg-teal-500 px-6 py-2 font-semibold text-white rounded-full absolute -bottom-20 delay-500 duration-1000 group-hover:bottom-20 scale-0 group-hover:scale-125"
                  >
                    Contact Us!
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div
            class="w-[300px] h-[420px] bg-transparent cursor-pointer group perspective"
          >
            <div
              class="relative preserve-3d group-hover:my-rotate-y-180 w-full h-full duration-1000"
            >
              <div class="absolute backface-hidden border-2 w-full h-full">
              <img src={ParaPo} class="w-full h-full" alt=""/>
              </div>
              <div
                class="absolute my-rotate-y-180 backface-hidden w-full h-full bg-gray-100 overflow-hidden"
              >
                <div
                  class="text-center flex flex-col items-center justify-center h-full text-gray-800 px-2 pb-24"
                >
                  <h1 class="text-3xl font-semibold">Lorem ipsum</h1>
                  <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis
                    itaque assumenda saepe animi maxime libero non quasi, odit natus
                    veritatis enim culpa nam inventore doloribus quidem temporibus
                    amet velit accusamus.
                  </p>
                  <button
                    class="bg-teal-500 px-6 py-2 font-semibold text-white rounded-full absolute -bottom-20 delay-500 duration-1000 group-hover:bottom-20 scale-0 group-hover:scale-125"
                  >
                    Contact Us!
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div
            class="w-[300px] h-[420px] bg-transparent cursor-pointer group perspective"
          >
            <div
              class="relative preserve-3d group-hover:my-rotate-y-180 w-full h-full duration-1000"
            >
              <div class="absolute backface-hidden border-2 w-full h-full">
              <img src={QrX} class="w-full h-full" alt=""/>
              </div>
              <div
                class="absolute my-rotate-y-180 backface-hidden w-full h-full bg-gray-100 overflow-hidden"
              >
                <div
                  class="text-center flex flex-col items-center justify-center h-full text-gray-800 px-2 pb-24"
                >
                  <h1 class="text-3xl font-semibold">Lorem ipsum</h1>
                  <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis
                    itaque assumenda saepe animi maxime libero non quasi, odit natus
                    veritatis enim culpa nam inventore doloribus quidem temporibus
                    amet velit accusamus.
                  </p>
                  <button
                    class="bg-teal-500 px-6 py-2 font-semibold text-white rounded-full absolute -bottom-20 delay-500 duration-1000 group-hover:bottom-20 scale-0 group-hover:scale-125"
                  >
                    Contact Us!
                  </button>
                </div>
              </div>
            </div>
          </div>
    </section>
    </>
  )
}
