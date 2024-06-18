import dost from "./imgs/dost.png";
import uc from "./imgs/uc.png";
import dict from "./imgs/dict.png";
import neda from "./imgs/neda.png";
import dti from "./imgs/dti.png";
import da from "./imgs/da.svg";
import peza from "./imgs/peza.svg";
import video from "../components/imgs/taraki-home-video.mp4";

function Home() {
  return (
    <div>
      <div className="phone:bg-trkblack font-satoshi overflow-x-hidden pt-12">
        <section className="text-center pt-16 tablet-m:py-28 laptop-s:py-32 laptop-m:py-32 desktop-m:py-[18rem]">
          <div className="w-full h-full overflow-hidden">
            <video
              autoPlay
              loop
              muted
              playsInline
              className=" phone:hidden tablet:block absolute top-0 left-0 m-auto w-full"
            >
              <source src={video} type="video/mp4" />
              Your browser does not support the video tag.
            </video>
            <div className="relative z-10 laptop-s:pt-32 laptop-m:pt-48 desktop-m:pt-24">
              <h1 className="text-white text-3xl tablet:text-4xl font-bold px-8 tablet:px-52">
                Wherever we <span className="text-orange-600">GO, </span>
                we <span className="text-orange-600">EXCEED!</span>
              </h1>
              <p className="text-white font-extralight text-[0.9rem] leading-relaxed mt-5 px-8 tablet:px-52 tablet-m:px-[23rem]">
                A Technological Consortium for Awareness, Readiness, and
                Advancement of Knowledge in Innovation
              </p>
              <button className=" bg-white py-1 px-4 mt-5 mb-7 tablet-m:mt-5 tablet:mb-12 tablet-m:mb-0 text-[0.8rem] border border-white rounded-md hover:bg-trkblack hover:text-white hover:border-orange-600">
                Learn More
              </button>
            </div>
          </div>
        </section>
        <div className="relative flex flex-col justify-center overflow-hidden bg-gray-50 border border-b-gray-400 tablet:mt-10 laptop-s:mt-32 laptop-m:mt-44 desktop-m:pt-0">
          <div className="pointer-events-none flex overflow-hidden">
            <div className="animate-marquee flex min-w-full shrink-0 items-center gap-10 tablet:gap-14 tablet-m:gap-24 laptop-s:gap-32 laptop-m:gap-32 desktop-s:gap-36 desktop-m:gap-48 p-3">
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20"
                src={dost}
                alt=""
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20"
                src={uc}
                alt=""
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20"
                src={dict}
                alt=""
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20"
                src={neda}
                alt=""
              />
              <img
                className=" w-12 rounded-md object-cover shadow-md mr-3 laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 "
                src={dti}
                alt=""
              />
              <img
                className=" w-12 rounded-md object-cover shadow-md mr-3 laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 "
                src={da}
                alt=""
              />
              <img
                className=" w-12 rounded-md object-cover shadow-md mr-3 laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 "
                src={peza}
                alt=""
              />
            </div>
            <div className="animate-marquee flex min-w-full shrink-0 items-center gap-10 tablet:gap-14 tablet-m:gap-24 laptop-s:gap-32 laptop-m:gap-32 desktop-s:gap-36 desktop-m:gap-48 p-3">
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20"
                src={dost}
                alt=""
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20"
                src={uc}
                alt=""
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20"
                src={dict}
                alt=""
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20"
                src={neda}
                alt=""
              />
              <img
                className="w-12 rounded-md object-cover shadow-md laptop-m:w-16 desktop-s:w-20 desktop-m:w-20"
                src={dti}
                alt=""
              />
              <img
                className=" w-12 rounded-md object-cover shadow-md mr-3 laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 "
                src={da}
                alt=""
              />
              <img
                className=" w-12 rounded-md object-cover shadow-md mr-3 laptop-m:w-16 desktop-s:w-20 desktop-m:w-20 "
                src={peza}
                alt=""
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default Home;
