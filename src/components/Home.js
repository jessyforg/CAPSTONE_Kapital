function Home() {
  return (
    <div>
      <div className="font-satoshi overflow-x-hidden pt-20">
        <section className="bg-trkblack text-center pt-10">
          <h1 className="text-white text-3xl font-bold px-8">
            Empowering Innovation for a Better{" "}
            <span className="text-orange-600">Future.</span>
          </h1>
          <p className="text-white font-extralight text-[0.9rem] leading-relaxed mt-5 px-8">
            A Technological Consortium for Awareness, Readiness, and Advancement
            of Knowledge in Innovation
          </p>

          <button className=" bg-white py-1 px-4 mt-5 mb-7 text-[0.8rem] border border-white rounded-md hover:bg-trkblack hover:text-white hover:border-orange-600">
            Learn More
          </button>
        </section>
        <div className="py-5 mt-5 bg-white border border-gray-400"></div>
      </div>
    </div>
  );
}

export default Home;
