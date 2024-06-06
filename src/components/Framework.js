import frame from '../components/imgs/framework.png'

function framework() {
  return (
    <div>
      <div>
        <div className="cont">
          <section className="mt-16 text-center">
            <h1 className="font-semibold text-md">Framework</h1>
          </section>
          <div className="mx-auto mt-5">
            <img
              src={frame}
              alt="awareness"
              className="w-72 mx-auto rounded-lg"
            />
          </div>
        </div>
      </div>
    </div>
  );
}
export default framework;
