import arrow from "./imgs/arrow-down.svg"

function FAQs() {

  return (
    <div>
      <div>
        <div className="cont">
          <section className="flex flex-col justify-start mt-5 tablet:mt-12 bg-trkblack pt-5 pb-8 tablet:px-20">
            <section className="text-center">
              <h1 className="tablet-m:hidden font-semibold text-md tablet-m:text-xl text-white">
                FAQs
              </h1>
              <h1 className="phone:hidden tablet-m:block font-semibold text-md tablet-m:text-2xl text-white laptop-m:text-3xl desktop-m:text-4xl desktop-s:text-[2rem]">
                Frequently Asked Questions
              </h1>
            </section>
            <div className="laptop-s:mx-40 laptop-m:mx-44 desktop-s:mx-48 desktop-m:px-72">
              <div className="m-2 space-y-2 laptop-s:my-5">
                <div
                  className="group flex flex-col gap-2 rounded-lg bg-white p-3 text-black"
                  tabIndex="1"
                >
                  <div className="flex cursor-pointer items-center justify-between laptop-s:text-xl laptop-m:text-2xl desktop-m:text-3xl font-bold">
                    <span> How can I get involved with TARAKI? </span>
                    <img
                      src={arrow}
                      className="h-5 w-5 transition-all duration-500 group-focus:-rotate-180"
                      alt=""
                    />
                  </div>
                  
                  <div
                    className="invisible h-auto max-h-0 items-center opacity-0 transition-all group-focus:visible group-focus:max-h-screen group-focus:opacity-100 group-focus:duration-1000 laptop-s:text-[1.3rem] desktop-m:text-[1.5rem]"
                  ><hr className="h-px my-3 bg-gray-300 border-0"></hr>
                    Stay connected with us through our vibrant community on 
                    Facebook and Instagram. Explore tailored events and initiatives 
                    designed just for you.
                  </div>
                </div>

                <div
                  className="group flex flex-col gap-2 rounded-lg bg-white p-3 text-black"
                  tabIndex="2"
                >
                  <div className="flex cursor-pointer items-center justify-between laptop-s:text-xl laptop-m:text-2xl desktop-m:text-3xl font-bold">
                    <span> Who can join TARAKI's programs and initiatives? </span>
                    <img
                      src={arrow}
                      className="h-5 w-5 transition-all duration-500 group-focus:-rotate-180"
                      alt=""
                    />
                  </div>
                  <div
                    className="invisible h-auto max-h-0 items-center opacity-0 transition-all group-focus:visible group-focus:max-h-screen group-focus:opacity-100 group-focus:duration-1000 laptop-s:text-[1.3rem] desktop-m:text-[1.5rem]"
                  ><hr className="h-px my-3 bg-gray-300 border-0"></hr>
                    Everyone with a spark of innovation is invited! Whether 
                    you're a startup founder, an enthusiast, or simply curious 
                    about the startup ecosystem, TARAKI welcomes you with open 
                    arms.
                  </div>
                </div>

                <div
                  className="group flex flex-col gap-2 rounded-lg bg-white p-3 text-black"
                  tabIndex="3"
                >
                  <div className="flex cursor-pointer items-center justify-between laptop-s:text-xl laptop-m:text-2xl desktop-m:text-3xl font-bold">
                    <span> Does TARAKI offer resources for startups? </span>
                    <img
                      src={arrow}
                      className="h-5 w-5 transition-all duration-500 group-focus:-rotate-180"
                      alt=""
                    />
                  </div>
                  <div
                    className="invisible h-auto max-h-0 items-center opacity-0 transition-all group-focus:visible group-focus:max-h-screen group-focus:opacity-100 group-focus:duration-1000 laptop-s:text-[1.3rem] desktop-m:text-[1.5rem]"
                  ><hr className="h-px my-3 bg-gray-300 border-0"></hr>
                    Absolutely! Dive into a wealth of resources tailored for startups: 
                    from personalized mentorship sessions to enlightening seminars, 
                    workshops, and engaging talks by industry experts at our 
                    innovation-driven events.
                  </div>
                </div>

                <div
                  className="group flex flex-col gap-2 rounded-lg bg-white p-3 text-black"
                  tabIndex="3"
                >
                  <div className="flex cursor-pointer items-center justify-between laptop-s:text-xl laptop-m:text-2xl desktop-m:text-3xl font-bold">
                    <span> How can TARAKI support my startup? </span>
                    <img
                      src={arrow}
                      className="h-5 w-5 transition-all duration-500 group-focus:-rotate-180"
                      alt=""
                    />
                  </div>
                  <div
                    className="invisible h-auto max-h-0 items-center opacity-0 transition-all group-focus:visible group-focus:max-h-screen group-focus:opacity-100 group-focus:duration-1000 laptop-s:text-[1.3rem] desktop-m:text-[1.5rem]"
                  ><hr className="h-px my-3 bg-gray-300 border-0"></hr>
                    Let TARAKI fuel your startup journey with our acceleration program 
                    and a plethora of events specially curated for Cordilleran startups. 
                    Stay informed by following our dynamic updates on our Facebook Page.
                  </div>
                </div>
              </div>
          </div>
          </section>
        </div>
      </div>
    </div>
  );
}

export default FAQs;