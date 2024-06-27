import React from 'react';

function TBI() {
  return (
    <div className='bg-trkblack py-[22rem] flex flex-col justify-center items-center min-h-screen space-y-2'>
    <h1 className='text-center text-white text-5xl font-bold'>
      COMING SOON!
    </h1>
    <h2 className='text-center text-orange-600 text-2xl font-semibold'>This page is still under development. Thank you!</h2>
    <a href="/" className="text-center bg-white py-1 px-4 mt-5 mb-7 tablet-m:mt-5 tablet:mb-12 tablet-m:mb-0 text-[0.8rem] laptop-s:text-sm laptop-s:px-8 laptop-s:py-3 desktop-m:px-10 desktop-m:py-5 laptop-m:text-lg desktop-s:text-[1.4rem] desktop-m:text-[1.7rem] border border-white rounded-md hover:bg-trkblack hover:text-white hover:border-orange-600 aos-init">
        Return to home
    </a>
</div>

  );
}

export default TBI;