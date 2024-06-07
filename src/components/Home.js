import dost from "./imgs/dost.png";
import uc from "./imgs/uc.png";
import dict from "./imgs/dict.png";
import neda from "./imgs/neda.png";
import dti from "./imgs/dti.png";

function Home() {
  return (
    <div>
      <div className="font-satoshi overflow-x-hidden pt-20">
        <section className="bg-trkblack text-center pt-10">
          <h1 className="text-white text-3xl tablet:text-4xl font-bold px-8">
            Wherever we <span className="text-orange-600">GO,</span>
          </h1>
          <h1 className="text-white text-3xl tablet:text-4xl font-bold px-8">
            we <span className="text-orange-600">EXCEED!</span>
          </h1>
          <p className="text-white font-extralight text-[0.9rem] leading-relaxed mt-5 px-8 tablet:px-52">
            A Technological Consortium for Awareness, Readiness, and Advancement
            of Knowledge in Innovation
          </p>

          <button className=" bg-white py-1 px-4 mt-5 mb-7 tablet:mb-8 text-[0.8rem] border border-white rounded-md hover:bg-trkblack hover:text-white hover:border-orange-600">
            Learn More
          </button>
        </section>
        <div class="relative flex flex-col justify-center overflow-hidden bg-gray-50 border border-b-gray-400">
          <div class="pointer-events-none flex overflow-hidden">
            <div class="animate-marquee flex min-w-full shrink-0 items-center gap-10 tablet:gap-24 p-3">
              <img
                class="aspect-square max-w-12 rounded-md object-cover shadow-md"
                src={dost}
                alt=""
              />
              <img
                class="aspect-square max-w-12 rounded-md object-cover shadow-md"
                src={uc}
                alt=""
              />
              <img
                class="aspect-square max-w-12 rounded-md object-cover shadow-md"
                src={dict}
                alt=""
              />
              <img
                class="aspect-square max-w-12 rounded-md object-cover shadow-md"
                src={neda}
                alt=""
              />
              <img
                class="aspect-square max-w-12 rounded-md object-cover shadow-md mr-3"
                src={dti}
                alt=""
              />
            </div>
            <div class="animate-marquee flex min-w-full shrink-0 items-center gap-10 tablet:gap-24 p-3">
              <img
                class="aspect-square max-w-12 rounded-md object-cover shadow-md"
                src={dost}
                alt=""
              />
              <img
                class="aspect-square max-w-12 rounded-md object-cover shadow-md"
                src={uc}
                alt=""
              />
              <img
                class="aspect-square max-w-12 rounded-md object-cover shadow-md"
                src={dict}
                alt=""
              />
              <img
                class="aspect-square max-w-12 rounded-md object-cover shadow-md"
                src={neda}
                alt=""
              />
              <img
                class="aspect-square max-w-12 rounded-md object-cover shadow-md"
                src={dti}
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
