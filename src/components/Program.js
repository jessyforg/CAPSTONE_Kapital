import React, { useEffect } from "react";
import AOS from "aos";
import "aos/dist/aos.css";
import aware from "../components/imgs/aware.webp";
import ready from "../components/imgs/ready.webp";
import know from "../components/imgs/knowledge.webp";
import inno from "../components/imgs/inno.webp";

function Program() {
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
        <div className="">
          <section id="program" className="mt-16 tablet:my-12 text-center">
            <h1
              className="font-semibold text-md tablet:text-lg tablet-m:text-2xl px-20 tablet:px-52 tablet-m:px-72 laptop-m:px-96 laptop-s:text-3xl laptop-m:text-[2.3rem] desktop-m:text-[2.9rem] laptop-s:py-6 laptop-m:py-8 desktop-s:py-10 laptop-s:bg-trkblack laptop-s:text-white aos-init"
              data-aos="fade-down"
            >
              We&apos;re covering a lot of developments in our news updates.
            </h1>
          </section>{" "}
          <div
            id="sectionCont"
            className="tablet:flex tablet:justify-between tablet:items-center tablet:px-14 tablet-m:px-[4rem] aos-init"
            data-aos="fade-right"
          >
            <div
              className="mx-auto mt-5 rounded-lg bg-cover bg-center w-72 h-52 tablet:w-[115rem] tablet-m:w-[130rem] tablet:h-[14rem] laptop-s:w-[160rem] laptop-m:w-[180rem] desktop-m:w-[210rem] laptop-s:h-[20rem] desktop-m:h-[30rem]"
              style={{ backgroundImage: `url(${aware})` }}
            ></div>

            <div
              id="sectionTextCont"
              className="tablet:flex tablet:flex-col tablet:px-5 laptop-s:rounded-lg"
            >
              <h1
                className="text-center tablet:text-left text-sm tablet:text-[0.9rem] tablet-m:text-xl laptop-s:text-2xl laptop-m:text-[2rem] desktop-m:text-[2.5rem] font-semibold mt-5 px-8 tablet:px-0 aos-init"
                data-aos="fade-right"
              >
                AWARENESS PROGRAM
              </h1>
              <p
                className="text-sm tablet:text-[0.9rem] tablet-m:text-lg laptop-s:text-[1.2rem] laptop-m:text-[1.4rem] desktop-m:text-[1.9rem] tablet:font-normal tablet:leading-6 laptop-s:leading-loose desktop-m:leading-loose text-center tablet:text-left font-light mt-2 tablet:mt-4 laptop-s:mt-7 px-8 tablet:px-0 aos-init"
                data-aos="fade-right"
              >
                Our Awareness Programs are designed to educate and inform
                participants about the latest trends, challenges, and
                opportunities in technology and innovation. These programs aim
                to build a strong foundation of knowledge and inspire
                participants to engage with emerging technologies.
              </p>
            </div>
          </div>
          <div
            id="sectionCont"
            className="tablet:flex tablet:justify-between tablet:items-center tablet:px-14 tablet:mt-5 tablet-m:px-[4rem] aos-init"
            data-aos="fade-right"
          >
            <div
              className="mx-auto mt-5 rounded-lg bg-cover bg-center w-72 h-52 tablet:w-[100rem] tablet-m:w-[130rem] tablet:h-[14rem] tablet:order-2 laptop-s:w-[130rem] laptop-m:w-[140rem] desktop-m:w-[200rem] laptop-s:h-[20rem] desktop-m:h-[30rem]"
              style={{ backgroundImage: `url(${ready})` }}
            ></div>
            <div
              id="sectionTextCont"
              className="tablet:flex tablet:flex-col tablet:px-0 tablet:bg-gradient-to-r from-orange-100 tablet:rounded-lg
              tablet:py-[0.9rem] tablet:pl-4 tablet:mr-4 tablet:mt-5
              tablet-m:py-[0.8rem] tablet-m:pl-4 tablet-m:mr-4 tablet-m:mt-5
              laptop-s:py-[4.5rem] laptop-s:pl-4 laptop-s:mr-4 laptop-s:mt-5 
              desktop-s:py-[5.5rem]
              desktop-m:py-[7.8rem] desktop-m:pl-4 desktop-m:mr-4 desktop-m:mt-5"
            >
              <h1
                className="text-center tablet:text-left text-sm tablet:text-[0.9rem] tablet-m:text-xl laptop-s:text-2xl laptop-m:text-[2rem] desktop-m:text-[2.5rem] font-semibold mt-5 px-8 tablet:px-0 aos-init"
                data-aos="fade-right"
              >
                READINESS PROGRAM
              </h1>
              <p
                className="text-sm tablet:text-[0.9rem] tablet-m:text-lg laptop-s:text-[1.3rem] laptop-m:text-[1.4rem] desktop-m:text-[1.9rem] tablet:font-normal tablet:leading-6 laptop-s:leading-loose text-center tablet:text-left font-light mt-2 tablet:mt-4 px-8 tablet:pl-0 tablet:pr-5 aos-init"
                data-aos="fade-right"
              >
                The Readiness Programs focus on preparing individuals and
                organizations for the future of work and technological
                advancements. These programs provide the necessary skills and
                knowledge to thrive in a technology-driven world.
              </p>
            </div>
          </div>
          <div
            id="sectionCont"
            className="tablet:flex tablet:justify-between tablet:items-center tablet:mt-5 tablet:px-14 tablet-m:px-[4rem] aos-init"
            data-aos="fade-right"
          >
            <div
              className="mx-auto mt-5 rounded-lg bg-cover bg-center w-72 h-52 tablet:w-[110rem] tablet-m:w-[125rem] tablet:h-[14rem] laptop-s:w-[170rem] laptop-m:w-[180rem] desktop-m:w-[200rem] laptop-s:h-[20rem] desktop-m:h-[30rem]"
              style={{ backgroundImage: `url(${know})` }}
            ></div>
            <div
              id="sectionTextCont"
              className="tablet:flex tablet:flex-col tablet:px-5"
            >
              <h1
                className="text-center tablet:text-left text-sm tablet:text-[0.9rem] tablet-m:text-xl laptop-s:text-2xl laptop-m:text-[2rem] desktop-m:text-[2.5rem] font-semibold mt-5 px-8 tablet:px-0 aos-init"
                data-aos="fade-right"
              >
                KNOWLEDGE ADVANCEMENT INITIATIVES
              </h1>
              <p
                className="text-sm tablet:text-[0.9rem] tablet-m:text-lg laptop-s:text-[1.3rem] laptop-m:text-[1.4rem] desktop-m:text-[1.9rem] tablet:font-normal tablet:leading-6 laptop-s:leading-loose text-center tablet:text-left font-light mt-2 tablet:mt-4 px-8 tablet:px-0 aos-init"
                data-aos="fade-right"
              >
                Our Knowledge Advancement Initiatives aim to push the boundaries
                of what is known in the field of technology and innovation. We
                support research and development projects that contribute to the
                advancement of knowledge and create new opportunities for
                innovation.
              </p>
            </div>
          </div>
          <div
            id="sectionCont"
            className="tablet:flex tablet:justify-between tablet:items-center tablet:px-14 tablet:mt-5 tablet-m:px-[4rem] aos-init"
            data-aos="fade-right"
          >
            <div
              className="mx-auto mt-5 rounded-lg bg-cover bg-center w-72 h-52 tablet:w-[85rem] tablet-m:w-[110rem] tablet:h-[14rem] tablet:order-2 laptop-s:w-[114rem] laptop-m:w-[124rem] desktop-m:w-[180rem] laptop-s:h-[20rem] desktop-m:h-[30rem]"
              style={{ backgroundImage: `url(${inno})` }}
            ></div>
            <div
              id="sectionTextCont"
              className="tablet:flex tablet:flex-col tablet:px-0 tablet:bg-gradient-to-r from-orange-100 tablet:rounded-lg
              tablet:py-[1.5rem] tablet:pl-4 tablet:mr-4 tablet:mt-5
              tablet-m:py-[1rem] tablet-m:pl-4 tablet-m:mr-4 tablet-m:mt-5
              laptop-s:py-[4.5rem] laptop-s:pl-4 laptop-s:mr-4 laptop-s:mt-5 
              desktop-s:py-[5.5rem]
              desktop-m:py-[7.8rem] desktop-m:pl-4 desktop-m:mr-4 desktop-m:mt-5"
            >
              <h1
                className="text-center tablet:text-left text-sm tablet:text-[0.9rem] tablet-m:text-xl laptop-s:text-2xl laptop-m:text-[2rem] desktop-m:text-[2.5rem] font-semibold mt-5 px-8 tablet:px-0 aos-init"
                data-aos="fade-right"
              >
                INNOVATION PROJECT
              </h1>
              <p
                className="text-sm tablet:text-[0.9rem] tablet-m:text-lg laptop-s:text-[1.3rem] laptop-m:text-[1.4rem] desktop-m:text-[1.9rem] tablet:font-normal tablet:leading-6 laptop-s:leading-loose text-center tablet:text-left font-light mt-2 tablet:mt-4 px-8 tablet:pl-0 tablet:pr-5 aos-init"
                data-aos="fade-right"
              >
                TARAKI&apos;s Innovation Projects are practical applications of
                our research and knowledge. These projects are designed to
                address real-world challenges and demonstrate the potential of
                new technologies.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
export default Program;
