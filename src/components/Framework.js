import frame from "../components/imgs/framework.png";

function framework() {
  return (
    <div>
      <div>
        <div className="cont">
          <section className="mt-16 tablet:mt-12 text-center">
            <h1 className="font-semibold text-md tablet:text-lg tablet-m:text-xl laptop-s:text-2xl laptop-m:text-[2.3rem] desktop-m:text-[2.9rem]">
              Framework
            </h1>
          </section>
          <div className="mx-auto mt-5">
            <img
              src={frame}
              alt="awareness"
              className="w-72 tablet:w-[92%] tablet-m:w-[94%] mx-auto rounded-lg desktop-m:w-[85%] desktop-m:h-[70%]"
            />
          </div>
        </div>
      </div>
    </div>
  );
}
export default framework;
