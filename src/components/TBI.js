import React from "react";
import { scroller } from "react-scroll";
import { Link, useNavigate } from "react-router-dom";
import tarakiLogo from "../components/imgs/taraki-black.svg";
import "aos/dist/aos.css";
import Intto from "./imgs/InTTO.svg";
import UP from "./imgs/SILBI_TBI.svg";
import SLU from "./imgs/SLU.svg";
import BSU from "./imgs/BSU.svg";
import IFSU from "./imgs/IFSU-TBI.svg";
import Henry from "./imgs/investors/Henry.webp";
import Jaydee from "./imgs/investors/Jaydee.webp";
import Wilson from "./imgs/investors/Wilson.webp";
import Elmer from "./imgs/investors/Elmer.webp";
import Angelo from "./imgs/investors/Angelo.webp";
import Benjie from "./imgs/investors/Benjie.webp";

function TBI() {
  const navigate = useNavigate();

  const handleScrollToHome = () => {
    scroller.scrollTo("Home", { smooth: true, duration: 1000, offset: -400 });
    navigate("/");
  };

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
              Return to home
            </button>
          </div>
        </div>
      </nav>

      <div className="font-satoshi mt-24 laptop-s:mt-32 desktop-s:mt-36 desktop-m:mt-40">
        <h1 className="font-bold text-[1rem] laptop-s:text-xl desktop-s:text-2xl text-center">
          Technology Business Incubation - CAR
        </h1>
        <section className="grid grid-cols-1 place-items-center justify-items-center tablet:grid gap-4 tablet-m:gap-1  tablet:grid-cols-2 tablet-m:grid-cols-3 tablet:px-12 laptop-m:px-24 desktop-s:px-28 desktop-m:px-36">
          <a href="/tbi/intto">
            <div className="border flex flex-col justify-center items-center mt-5 border-gray-300 rounded-lg w-72 h-60 laptop-s:w-[23rem] laptop-m:w-[25rem] desktop-s:w-[27rem] desktop-m:w-[32rem] laptop-s:h-72 desktop-m:h-80 transition-all duration-300 hover:scale-110 hover:border-ucgreen hover:border-4">
              <img
                src={Intto}
                alt="1st-ico"
                className="h-12 laptop-s:h-16 desktop-m:h-20 aos-init mb-1"
              />
              <h3 className="py-3 text-center text-[0.9rem] laptop-s:text-xl desktop-m:[1.5rem] text-ucgreen font-bold">
                InTTO
              </h3>
              <p className="text-[0.7rem] laptop-s:text-[0.8rem] desktop-m:text-[0.9rem]  text-center font-regular px-10 mt-1 aos-init">
                The Innovation and Technology Transfer Office (InTTO) fosters
                innovation by offering business and technology transfer
                opportunities to faculty, students, alumni, and the community
                through its two specialized units.
              </p>
            </div>
          </a>
          <a
            href="https://upbsilbi.com/"
            target="_blank"
            rel="noopener noreferrer"
          >
            <div className="border flex flex-col justify-center items-center mt-5 border-gray-300 rounded-lg w-72 h-60 laptop-s:w-[23rem] laptop-m:w-[25rem] desktop-s:w-[27rem] desktop-m:w-[32rem] laptop-s:h-72 desktop-m:h-80 transition-all duration-300 hover:scale-110 hover:border-upred hover:border-4">
              <img
                src={UP}
                alt="1st-ico"
                className="h-12 laptop-s:h-16 desktop-m:h-20 aos-init"
              />
              <h3 className="py-3 text-center text-[0.9rem] laptop-s:text-xl desktop-m:[1.5rem] font-bold text-upred">
                SILBI
              </h3>
              <p className="text-[0.7rem] tablet-m:text-[0.6rem] laptop-s:text-[0.8rem] desktop-m:text-[0.9rem] text-center font-regular px-10 mt-1 aos-init">
                Silbi, meaning "service" in Filipino, reflects UP Baguio's
                dedication to community service. The SILBI Center drives
                transformation in Cordillera and Northern Luzon through research
                and innovation, fostering public service initiatives.
              </p>
            </div>
          </a>
          <a
            href="https://www.facebook.com/slu.edu.ph"
            target="_blank"
            rel="noopener noreferrer"
          >
            <div className="border flex flex-col justify-center items-center mt-5 border-gray-300 rounded-lg w-72 h-60 laptop-s:w-[23rem] laptop-m:w-[25rem] desktop-s:w-[27rem] desktop-m:w-[32rem] laptop-s:h-72 desktop-m:h-80 transition-all duration-300 hover:scale-110 hover:border-slublue hover:border-4">
              <img
                src={SLU}
                alt="1st-ico"
                className="h-12 laptop-s:h-16 desktop-m:h-20 aos-init"
              />
              <h3 className="py-3 text-center text-[0.9rem] laptop-s:text-xl desktop-m:[1.5rem] font-bold text-slublue">
                ConRes
              </h3>
              <p className="text-[0.7rem] tablet-m:text-[0.6rem] laptop-s:text-[0.8rem] desktop-m:text-[0.9rem]  text-center font-regular px-10 mt-1 aos-init">
                Established in 2017 with CHED funding, the SIRIB Center created
                a Technology Hub and Co-Working Space. It launched
                "Technopreneurship 101" to integrate entrepreneurship into
                engineering education, fostering tech-savvy entrepreneurs.
              </p>
            </div>
          </a>
          <a
            href="https://www.facebook.com/BenguetStateUniversity"
            target="_blank"
            rel="noopener noreferrer"
          >
            <div className="border flex flex-col justify-center items-center mt-5 border-gray-300 rounded-lg w-72 h-60 laptop-s:w-[23rem] laptop-m:w-[25rem] desktop-s:w-[27rem] desktop-m:w-[32rem] laptop-s:h-72 desktop-m:h-80 transition-all duration-300 hover:scale-110 hover:border-bsuyellow hover:border-4 tablet-m:hidden">
              <img
                src={BSU}
                alt="1st-ico"
                className="h-12 laptop-s:h-16 desktop-m:h-20 aos-init"
              />
              <h3 className="py-3 text-center text-[0.9rem] laptop-s:text-xl desktop-m:[1.5rem] font-bold text-bsuyellow">
                ATBI / IC
              </h3>
              <p className="text-[0.7rem] tablet-m:text-[0.6rem] laptop-s:text-[0.8rem] desktop-m:text-[0.9rem]  text-center font-regular px-10 mt-1 aos-init">
                Founded under BOR Resolution No. 1939, s. 2010, the Agri-based
                Technology Business Incubator/Innovation Center supports
                start-ups and micro businesses in agricultural technology,
                offering professional services to help them grow.
              </p>
            </div>
          </a>
          <a
            href="https://www.facebook.com/ifugaostateuniversity"
            target="_blank"
            rel="noopener noreferrer"
          >
            <div className="border flex flex-col justify-center items-center mt-5 border-gray-300 rounded-lg w-72 h-60 laptop-s:w-[23rem] laptop-m:w-[25rem] desktop-s:w-[27rem] desktop-m:w-[32rem] laptop-s:h-72 desktop-m:h-80 transition-all duration-300 hover:scale-110 hover:border-ifsugreen hover:border-4 tablet-m:hidden">
              <img
                src={IFSU}
                alt="1st-ico"
                className="h-[4.5rem] laptop-s:h-10 laptop-s:w-10 desktop-m:w-[25rem] aos-init"
              />
              <h3 className="py-3 text-center text-[0.9rem] laptop-s:text-xl desktop-m:[1.5rem] font-bold text-ifsugreen">
                IFSU IPTBM
              </h3>
              <p className="text-[0.7rem] tablet-m:text-[0.6rem] laptop-s:text-[0.8rem] desktop-m:text-[0.9rem]  text-center font-regular px-10 mt-1 aos-init">
                Founded under BOR Resolution No. 1939, s. 2010, the Agri-based
                Technology Business Incubator/Innovation Center supports
                start-ups and micro businesses in agricultural technology,
                offering professional services to help them grow.
              </p>
            </div>
          </a>
        </section>
        <section className="grid grid-cols-1 place-items-center justify-items-center tablet-m:grid gap-4 tablet-m:gap-1  tablet:grid-cols-2 tablet-m:grid-cols-2 tablet-m:px-[12.5rem] laptop-s:px-[15.3rem] laptop-m:px-[18.9rem] desktop-s:px-[21.3rem] desktop-m:px-[26rem] phone:pt-4 phone:hidden tablet:hidden">
          <a
            href="https://www.facebook.com/BenguetStateUniversity"
            target="_blank"
            rel="noopener noreferrer"
          >
            <div className="border flex flex-col justify-center items-center mt-5 border-gray-300 rounded-lg w-72 h-60 laptop-s:w-[23rem] laptop-m:w-[25rem] desktop-s:w-[27rem] desktop-m:w-[32rem] laptop-s:h-72 desktop-m:h-80 transition-all duration-300 hover:scale-110 hover:border-bsuyellow hover:border-4">
              <img
                src={BSU}
                alt="1st-ico"
                className="h-12 laptop-s:h-16 desktop-m:h-20 aos-init"
              />
              <h3 className="py-3 text-center text-xl laptop-s:text-xl desktop-m:[1.5rem] font-bold text-bsuyellow">
                ATBI / IC
              </h3>
              <p className="text-[0.7rem] tablet-m:text-[0.6rem] laptop-s:text-[0.8rem] desktop-m:text-[0.9rem]  text-center font-regular px-10 mt-1 aos-init">
                Founded under BOR Resolution No. 1939, s. 2010, the Agri-based
                Technology Business Incubator/Innovation Center supports
                start-ups and micro businesses in agricultural technology,
                offering professional services to help them grow.
              </p>
            </div>
          </a>
          <a
            href="https://www.facebook.com/ifugaostateuniversity"
            target="_blank"
            rel="noopener noreferrer"
          >
            <div className="border flex flex-col justify-center items-center mt-5 border-gray-300 rounded-lg w-72 h-60 laptop-s:w-[23rem] laptop-m:w-[25rem] desktop-s:w-[27rem] desktop-m:w-[32rem] laptop-s:h-72 desktop-m:h-80 transition-all duration-300 hover:scale-110 hover:border-ifsugreen hover:border-4">
              <img
                src={IFSU}
                alt="1st-ico"
                className="h-14 tablet-m:w-[3.8rem] laptop-s:h-[4.5rem] laptop-s:w-20 desktop-m:w-[5.7rem] desktop-m:h-20 aos-init"
              />
              <h3 className="py-3 text-center text-xl laptop-s:text-xl desktop-m:[1.5rem] font-bold text-ifsugreen">
                IFSU IPTBM
              </h3>
              <p className="text-[0.7rem] tablet-m:text-[0.6rem] laptop-s:text-[0.8rem] desktop-m:text-[0.9rem]  text-center font-regular px-10 mt-1 aos-init">
                Founded under BOR Resolution No. 1939, s. 2010, the Agri-based
                Technology Business Incubator/Innovation Center supports
                start-ups and micro businesses in agricultural technology,
                offering professional services to help them grow.
              </p>
            </div>
          </a>
        </section>

        <section className="my-10">
          <h1 className="font-bold text-[1rem] laptop-s:text-xl desktop-s:text-2xl text-center">
            Potential Investors
          </h1>
          <div className="grid grid-cols-1 gap-4 tablet:grid-cols-2 tablet-m:grid-cols-3 px-10 tablet-m:px-8">
            <div className="flex flex-col justify-center items-center text-center rounded-lg mt-5">
              <img src={Henry} alt="" className="w-36 rounded-full" />
              <div className="">
                <h1 className="font-bold tablet:text-[0.7rem] laptop-s:text-[0.9rem] desktop-s:text-[1rem] desktop-m:text-xl">
                  Henry James Banayat
                </h1>
                <p className="px-10 tablet-m:px-12 text-[0.5rem] laptop-s:text-[0.6rem] desktop-s:text-[0.8rem] desktop-m:text-[1rem] desktop-s:px-10">
                  Director of Business Development at Bitshares Labs, Inc.
                </p>
              </div>
            </div>
            <div className="flex flex-col justify-center items-center text-center rounded-lg mt-5">
              <img src={Jaydee} alt="" className="w-36 rounded-full" />
              <div className="">
                <h1 className="font-bold tablet:text-[0.7rem] laptop-s:text-[0.9rem] desktop-s:text-[1rem] desktop-m:text-xl">
                  Jaydee Rebadulla
                </h1>
                <p className="px-10 tablet-m:px-12 text-[0.5rem] laptop-s:text-[0.6rem] desktop-s:text-[0.8rem] desktop-m:text-[1rem] desktop-s:px-10">
                  Director at Lycaon Creatives; Chairman & CEO at Errand Doers
                  PH; Owner and Founder at El Lote PH
                </p>
              </div>
            </div>
            <div className="flex flex-col justify-center items-center text-center rounded-lg mt-5">
              <img src={Wilson} alt="" className="w-36 rounded-full" />
              <div className="">
                <h1 className="font-bold tablet:text-[0.7rem] laptop-s:text-[0.9rem] desktop-s:text-[1rem] desktop-m:text-xl">
                  Wilson Capuyan
                </h1>
                <p className="px-10 tablet-m:px-12 text-[0.5rem] laptop-s:text-[0.6rem] desktop-s:text-[0.8rem] desktop-m:text-[1rem] desktop-s:px-10">
                  Founder & General Manager at Pixels & Metrics Head of Growth
                  at the Neutral and Space for the startups in the region
                </p>
              </div>
            </div>
            <div className="flex flex-col justify-center items-center text-center rounded-lg mt-5">
              <img src={Elmer} alt="" className="w-36 rounded-full" />
              <div className="">
                <h1 className="font-bold tablet:text-[0.7rem] laptop-s:text-[0.9rem] desktop-s:text-[1rem] desktop-m:text-xl">
                  Elmer Macalingay
                </h1>
                <p className="px-10 tablet-m:px-12 text-[0.5rem] laptop-s:text-[0.6rem] desktop-s:text-[0.8rem] desktop-m:text-[1rem] desktop-s:px-10">
                  Founder of Health 100 Restoreant
                </p>
              </div>
            </div>
            <div className="flex flex-col justify-center items-center text-center rounded-lg mt-5">
              <img src={Benjie} alt="" className="w-36 rounded-full" />
              <div className="">
                <h1 className="font-bold tablet:text-[0.7rem] laptop-s:text-[0.9rem] desktop-s:text-[1rem] desktop-m:text-xl">
                  Hon. Benjamin Magalong
                </h1>
                <p className="px-10 tablet-m:px-12 text-[0.5rem] laptop-s:text-[0.6rem] desktop-s:text-[0.8rem] desktop-m:text-[1rem] desktop-s:px-10">
                  City Mayor of Baguio
                </p>
              </div>
            </div>
            <div className="flex flex-col justify-center items-center text-center rounded-lg mt-5">
              <img src={Angelo} alt="" className="w-36 rounded-full" />
              <div className="">
                <h1 className="font-bold tablet:text-[0.7rem] laptop-s:text-[0.9rem] desktop-s:text-[1rem] desktop-m:text-xl">
                  Angelo Valdez
                </h1>
                <p className="px-10 tablet-m:px-12 text-[0.5rem] laptop-s:text-[0.6rem] desktop-s:text-[0.6rem] desktop-m:text-[0.8rem] desktop-s:px-14 ">
                  CEO of Harper and Hill, Global & International Network
                  Connector, ASEAN HR Business leader & Former Director for
                  South East Asia at Morgan Philips Group
                </p>
              </div>
            </div>
          </div>
        </section>
      </div>

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

export default TBI;
