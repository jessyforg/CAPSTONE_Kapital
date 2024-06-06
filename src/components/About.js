import animation from '../components/imgs/taraki-animation.mp4'
function about() {
  return (
    <div>
      <div className="font-satoshi overflow-x-hidden">
        <div className="cont">
          <section className="text-center mt-16">
            <h1 className="font-semibold text-md">About Us</h1>
            <p className="font-light text-sm px-10 mt-5">
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
              w-72"
            ></video>
          </section>
        </div>
      </div>
    </div>
  );
}

export default about;
