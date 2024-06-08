import frame from "../components/imgs/framework.png";

function framework() {
  return (
    <div>
      <div>
        <div className="cont">
          <section className="mt-16 tablet:mt-12 text-center">
            <h1 className="font-semibold text-md tablet:text-lg tablet-m:text-xl">
              Framework
            </h1>
          </section>
          <div className="mx-auto mt-5">
            <img
              src={frame}
              alt="awareness"
              className="w-72 tablet:w-[92%] tablet-m:w-[94%] mx-auto rounded-lg"
            />
          </div>
        </div>
      </div>
    </div>
  );
}
export default framework;
