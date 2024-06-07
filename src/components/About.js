import animation from "../components/imgs/taraki-animation.mp4";
function about() {
  return (
    <div>
      <div className="font-satoshi overflow-x-hidden">
        <div className="cont tablet:flex tablet:justify-between tablet:items-center tablet:mt-8 tablet:px-10">
          <section className="text-center tablet:text-left mt-16 tablet:mt-0">
            <h1 className="font-semibold text-md tablet:text-lg tablet:px-0">
              About Us
            </h1>
            <p className="font-light text-sm tablet:text-md px-10 tablet:px-0 mt-5 tablet:mt-0 tablet:leading-6">
              <span className="font-semibold text-orange-600">TARAKI</span>{" "}
              envisions to be the country’s leading technological consortium in
              building and transforming the Cordilleran Startup Ecosystem. We
              cultivate ingenuity for innovators by fostering collaboration and
              community-driven initiatives, aiming to become globally renowned.
            </p>
          </section>
          <section className="mt-5">
            <video
              src={animation}
              autoPlay
              className="m-auto
              w-72 tablet:w-[95%]"
            ></video>
          </section>
        </div>
      </div>
    </div>
  );
}

export default about;
