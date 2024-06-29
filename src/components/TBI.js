import React from "react";
import "aos/dist/aos.css";
import Intto from "./imgs/InTTO.svg";
import UP from "./imgs/SILBI_TBI.svg";
import SLU from "./imgs/SLU.svg";
import BSU from "./imgs/BSU.svg";
import IFSU from "./imgs/IFSU.svg";
import profile from "./imgs/profile-investors.svg";

function TBI() {
  return (
    <div className="font-satoshi mt-24 laptop-s:mt-32 desktop-s:mt-36 desktop-m:mt-40">
      <h1 className="font-bold text-[1rem] laptop-s:text-xl desktop-s:text-2xl text-center">
        Technology Business Incubation - CAR
      </h1>
      <section className="grid grid-cols-1 place-items-center justify-items-center tablet:grid gap-4 tablet-m:gap-1  tablet:grid-cols-2 tablet-m:grid-cols-3 tablet:px-12 laptop-m:px-24 desktop-s:px-28 desktop-m:px-36">
        <a
          href="https://www.facebook.com/UCInTTO"
          target="_blank"
          rel="noopener noreferrer"
        >
          <div className="border flex flex-col justify-center items-center mt-5 border-gray-300 rounded-lg w-72 h-52 laptop-s:w-[23rem] laptop-m:w-[25rem] desktop-s:w-[27rem] desktop-m:w-[32rem] laptop-s:h-60 desktop-m:h-72 transition-all duration-300 hover:scale-110 hover:border-ucgreen hover:border-4">
            <img
              src={Intto}
              alt="1st-ico"
              className="h-12 laptop-s:h-16 desktop-m:h-20 aos-init"
            />
            <p className="text-[0.6rem] laptop-s:text-[0.8rem] desktop-m:text-[0.9rem]  text-center font-regular px-10 mt-1 aos-init">
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
          <div className="border flex flex-col justify-center items-center mt-5 border-gray-300 rounded-lg w-72 h-52 laptop-s:w-[23rem] laptop-m:w-[25rem] desktop-s:w-[27rem] desktop-m:w-[32rem] laptop-s:h-60 desktop-m:h-72 transition-all duration-300 hover:scale-110 hover:border-upred hover:border-4">
            <img
              src={UP}
              alt="1st-ico"
              className="h-12 laptop-s:h-16 desktop-m:h-20 aos-init"
            />
            <p className="text-[0.6rem] tablet-m:text-[0.6rem] laptop-s:text-[0.8rem] desktop-m:text-[0.9rem] text-center font-regular px-10 mt-1 h-10 aos-init">
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
          <div className="border flex flex-col justify-center items-center mt-5 border-gray-300 rounded-lg w-72 h-52 laptop-s:w-[23rem] laptop-m:w-[25rem] desktop-s:w-[27rem] desktop-m:w-[32rem] laptop-s:h-60 desktop-m:h-72 transition-transform duration-300 hover:scale-110 hover:border-slublue hover:border-4 box-border">
            <img
              src={SLU}
              alt="1st-ico"
              className="h-12 laptop-s:h-16 desktop-m:h-20 aos-init"
            />
            <p className="text-[0.6rem] tablet-m:text-[0.6rem] laptop-s:text-[0.8rem] desktop-m:text-[0.9rem] text-left font-regular px-10 mt-1 h-24 laptop-s:h-28 desktop-m:h-32 box-border">
              Established in 2017 with CHED funding, the SIRIB Center created a
              Technology Hub and Co-Working Space. It launched
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
          <div className="border flex flex-col justify-center items-center mt-5 border-gray-300 rounded-lg w-72 h-52 laptop-s:w-[23rem] laptop-m:w-[25rem] desktop-s:w-[27rem] desktop-m:w-[32rem] laptop-s:h-60 desktop-m:h-72 transition-all duration-300 hover:scale-110 hover:border-bsuyellow hover:border-4">
            <img
              src={BSU}
              alt="1st-ico"
              className="h-12 laptop-s:h-16 desktop-m:h-20 aos-init"
            />
            <p className="text-[0.6rem] tablet-m:text-[0.6rem] laptop-s:text-[0.8rem] desktop-m:text-[0.9rem]  text-center font-regular px-10 mt-1 aos-init">
              Founded under BOR Resolution No. 1939, s. 2010, the Agri-based
              Technology Business Incubator/Innovation Center supports start-ups
              and micro businesses in agricultural technology, offering
              professional services to help them grow.
            </p>
          </div>
        </a>
        <a
          href="https://www.facebook.com/ifugaostateuniversity"
          target="_blank"
          rel="noopener noreferrer"
        >
          <div className="border flex flex-col justify-center items-center mt-5 border-gray-300 rounded-lg w-72 h-52 laptop-s:w-[23rem] laptop-m:w-[25rem] desktop-s:w-[27rem] desktop-m:w-[32rem] laptop-s:h-60 desktop-m:h-72 transition-all duration-300 hover:scale-110 hover:border-ifsugreen hover:border-4">
            <img
              src={IFSU}
              alt="1st-ico"
              className="h-7 w-64 laptop-s:h-20 laptop-s:w-80 desktop-m:w-[25rem] aos-init"
            />
            <p className="text-[0.6rem] tablet-m:text-[0.6rem] laptop-s:text-[0.8rem] desktop-m:text-[0.9rem]  text-center font-regular px-10 mt-1 aos-init">
              Founded under BOR Resolution No. 1939, s. 2010, the Agri-based
              Technology Business Incubator/Innovation Center supports start-ups
              and micro businesses in agricultural technology, offering
              professional services to help them grow.
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
            <img src={profile} alt="" className="w-36 rounded-full" />
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
            <img src={profile} alt="" className="w-36 rounded-full" />
            <div className="">
              <h1 className="font-bold tablet:text-[0.7rem] laptop-s:text-[0.9rem] desktop-s:text-[1rem] desktop-m:text-xl">
                Jaydee Rebadulla
              </h1>
              <p className="px-10 tablet-m:px-12 text-[0.5rem] laptop-s:text-[0.6rem] desktop-s:text-[0.8rem] desktop-m:text-[1rem] desktop-s:px-10">
                Director at Lycaon Creatives; Chairman & CEO at Errand Doers PH;
                Owner and Founder at El Lote PH
              </p>
            </div>
          </div>
          <div className="flex flex-col justify-center items-center text-center rounded-lg mt-5">
            <img src={profile} alt="" className="w-36 rounded-full" />
            <div className="">
              <h1 className="font-bold tablet:text-[0.7rem] laptop-s:text-[0.9rem] desktop-s:text-[1rem] desktop-m:text-xl">
                Wilson Capuyan
              </h1>
              <p className="px-10 tablet-m:px-12 text-[0.5rem] laptop-s:text-[0.6rem] desktop-s:text-[0.8rem] desktop-m:text-[1rem] desktop-s:px-10">
                Founder & General Manager at Pixels & Metrics Head of Growth at
                the Neutral and Space for the startups in the region
              </p>
            </div>
          </div>
          <div className="flex flex-col justify-center items-center text-center rounded-lg mt-5">
            <img src={profile} alt="" className="w-36 rounded-full" />
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
            <img src={profile} alt="" className="w-36 rounded-full" />
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
            <img src={profile} alt="" className="w-36 rounded-full" />
            <div className="">
              <h1 className="font-bold tablet:text-[0.7rem] laptop-s:text-[0.9rem] desktop-s:text-[1rem] desktop-m:text-xl">
                Angelo Valdez
              </h1>
              <p className="px-10 tablet-m:px-12 text-[0.5rem] laptop-s:text-[0.6rem] desktop-s:text-[0.6rem] desktop-m:text-[0.8rem] desktop-s:px-14 ">
                CEO of Harper and Hill, Global & International Network
                Connector, ASEAN HR Business leader & Former Director for South
                East Asia at Morgan Philips Group
              </p>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}

export default TBI;
