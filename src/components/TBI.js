import React from 'react';
import Intto from './imgs/InTTO.svg';
import UP from './imgs/SILBI_TBI.svg';
import Slu from './imgs/SLU.svg';
import Bsu from './imgs/BSU.svg';
import Ifsu from './imgs/IFSU.svg';

function TBI() {
  return (
    <section className="tablet:grid tablet:grid-cols-2 tablet-m:grid-cols-3 laptop-s:py-10">
            <div className="mt-5 transition-all duration-300 hover:scale-110">
              <img
                src={Intto}
                alt="1st-ico"
                className="w-44 tablet-m:w-56 mx-auto desktop-m:w-72"
                
              />
              <p
                className="text-sm tablet-m:text-[0.8rem] laptop-s:text-[1rem] laptop-m:text-[1.3rem] desktop-m:text-[1.6rem] text-center font-regular mt-1 px-16"
                
              >
                Establishment of a 5-yr Regional Startup Development Plan and
                Roadmaps
              </p>
            </div>
            <div className="mt-5 transition-all duration-300 hover:scale-110">
              <img
                src={UP}
                alt="2nd-ico"
                className="w-44 tablet-m:w-56 mx-auto desktop-m:w-72"
                
              />
              <p
                className="text-sm tablet-m:text-[0.8rem] laptop-s:text-[1rem] laptop-m:text-[1.3rem] desktop-m:text-[1.6rem] text-center font-regular mt-1 px-16"
                
              >
                Increasing Awareness about the Consortium
              </p>
            </div>
            <div className="mt-5 transition-all duration-300 hover:scale-110">
              <img
                src={Slu}
                alt="3rd-ico"
                className="w-44 tablet-m:w-56 mx-auto desktop-m:w-72"
                
              />
              <p
                className="text-sm tablet-m:text-[0.8rem] laptop-s:text-[1rem] laptop-m:text-[1.3rem] desktop-m:text-[1.6rem] text-center font-regular mt-1 px-16"
                
              >
                Upskilling and Upscaling Activities
              </p>
            </div>
            <div className="mt-5 transition-all duration-300 hover:scale-110">
              <img
                src={Bsu}
                alt="4th-ico"
                className="w-44 tablet-m:w-56 mx-auto desktop-m:w-72"
                
              />
              <p
                className="text-sm tablet-m:text-[0.8rem] laptop-s:text-[1rem] laptop-m:text-[1.3rem] desktop-m:text-[1.6rem] text-center font-regular mt-1 px-16"
                
              >
                Establishment of Local Investor Network
              </p>
            </div>
            <div className="mt-5 transition-all duration-300 hover:scale-110">
              <img
                src={Ifsu}
                alt="5th-ico"
                className="w-44 tablet-m:w-56 mx-auto desktop-m:w-72"
                
              />
              <p
                className="text-sm tablet-m:text-[0.8rem] laptop-s:text-[1rem] laptop-m:text-[1.3rem] desktop-m:text-[1.6rem] text-center font-regular mt-1 px-16"
                
              >
                Cross Pollination Undertakings Among Stakeholders
              </p>
            </div>
          </section>

  );
}

export default TBI;