import React, { useState, useEffect } from "react";
import AOS from "aos";
import "aos/dist/aos.css";
import arrow from "./imgs/arrow-down.svg";

function FAQs() {
  useEffect(() => {
    AOS.init({
      duration: 1000, // Animation duration
      easing: "ease-in-out", // Easing function
      once: false,
    });
  }, []);
  const [activeIndex, setActiveIndex] = useState(null);

  const toggleFAQ = (index) => {
    if (activeIndex === index) {
      setActiveIndex(null);
    } else {
      setActiveIndex(index);
    }
  };

  const faqItems = [
    {
      question: "How can I get involved with TARAKI?",
      answer:
        "Stay connected with us through our vibrant community on Facebook and Instagram. Explore tailored events and initiatives designed just for you.",
    },
    {
      question: "Who can join TARAKI's programs and initiatives?",
      answer:
        "Everyone with a spark of innovation is invited! Whether you're a startup founder, an enthusiast, or simply curious about the startup ecosystem, TARAKI welcomes you with open arms.",
    },
    {
      question: "Does TARAKI offer resources for startups?",
      answer:
        "Absolutely! Dive into a wealth of resources tailored for startups: from personalized mentorship sessions to enlightening seminars, workshops, and engaging talks by industry experts at our innovation-driven events.",
    },
    {
      question: "How can TARAKI support my startup?",
      answer:
        "Let TARAKI fuel your startup journey with our acceleration program and a plethora of events specially curated for Cordilleran startups. Stay informed by following our dynamic updates on our Facebook Page.",
    },
  ];

  return (
    <div>
      <div className="cont">
        <section
          id="faq"
          className="flex flex-col justify-start mt-5 tablet:mt-12 bg-trkblack px-8 pt-5 pb-8 tablet:px-8 aos-init"
          data-aos="fade-down"
        >
          <section className="text-center">
            <h1
              className="tablet-m:hidden font-semibold text-md tablet-m:text-xl text-white aos-init"
              data-aos="fade-down"
            >
              FAQs
            </h1>
            <h1
              className="phone:hidden tablet-m:block font-semibold text-md tablet-m:text-2xl text-white laptop-m:text-3xl desktop-m:text-4xl desktop-s:text-[2rem] aos-init"
              data-aos="fade-down"
            >
              Frequently Asked Questions
            </h1>
          </section>
          <div
            className="tablet:mx-[1.8rem] laptop-s:mx-40 laptop-m:mx-40 desktop-s:mx-40 desktop-m:mx-40 aos-init"
            data-aos="fade-right"
          >
            <div className="m-2 space-y-2 laptop-s:my-5">
              {faqItems.map((item, index) => (
                <div
                  key={index}
                  className="group flex flex-col gap-2 rounded-lg bg-white p-3 text-black"
                >
                  <div
                    className="flex cursor-pointer items-center justify-between laptop-s:text-xl laptop-m:text-2xl desktop-m:text-3xl font-regular"
                    onClick={() => toggleFAQ(index)}
                  >
                    <span>{item.question}</span>
                    <img
                      src={arrow}
                      className={`h-5 w-5 transition-all duration-200 ${
                        activeIndex === index ? "-rotate-180" : ""
                      }`}
                      alt=""
                    />
                  </div>
                  <div
                    className={`overflow-hidden transition-all duration-500 ${
                      activeIndex === index
                        ? "max-h-screen opacity-100 phone:text-[1rem] laptop-s:text-lg desktop-s:text-xl text-gray-400"
                        : "max-h-0 opacity-0"
                    }`}
                  >
                    <hr className="h-px my-3 bg-gray-300 border-0" />
                    {item.answer}
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>
      </div>
    </div>
  );
}

export default FAQs;
