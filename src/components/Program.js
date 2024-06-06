import aware from '../components/imgs/aware.png'
import ready from '../components/imgs/ready.jpg'
import know from '../components/imgs/knowledge.JPG'
import inno from '../components/imgs/inno.png'

function program() {
  return (
    <div>
      <div>
        <div className="cont">
          <section className="mt-16 text-center">
            <h1 className="font-semibold text-md px-20">
              We&apos;re covering a lot of developments in our news updates.
            </h1>
          </section>{" "}
          <div className="mx-auto mt-5">
            <img
              src={aware}
              alt="awareness"
              className="w-72 mx-auto rounded-lg"
            />
          </div>
          <h1 className="text-center text-sm font-bold mt-5 px-8">
            AWARENESS PROGRAM
          </h1>
          <p className="text-sm text-center font-light mt-5 px-8">
            Our Awareness Programs are designed to educate and inform
            participants about the latest trends, challenges, and opportunities
            in technology and innovation. These programs aim to build a strong
            foundation of knowledge and inspire participants to engage with
            emerging technologies.
          </p>
          <div className="mx-auto mt-5">
            <img
              src={ready}
              alt="readiness"
              className="mx-auto w-72 rounded-lg"
            />
          </div>
          <h1 className="text-center text-sm font-bold mt-5 px-8">
            READINESS PROGRAM
          </h1>
          <p className="text-sm text-center font-light mt-1 px-8">
            The Readiness Programs focus on preparing individuals and
            organizations for the future of work and technological advancements.
            These programs provide the necessary skills and knowledge to thrive
            in a technology-driven world.
          </p>
          <div className="mx-auto mt-5">
            <img
              src={know}
              alt="knowledge"
              className="mx-auto w-72 rounded-lg"
            />
          </div>
          <h1 className="text-center text-sm font-bold mt-5 px-8">
            KNOWLEDGE ADVANCEMENT INITIVATIVES
          </h1>
          <p className="text-sm text-center font-light mt-1 px-8">
            Our Knowledge Advancement Initiatives aim to push the boundaries of
            what is known in the field of technology and innovation. We support
            research and development projects that contribute to the advancement
            of knowledge and create new opportunities for innovation.
          </p>
          <div className="mx-auto mt-5">
            <img
              src={inno}
              alt="innovation"
              className="mx-auto w-72 rounded-lg"
            />
          </div>
          <h1 className="text-center text-sm font-bold mt-5 px-8">
            INNOVATION PROJECT
          </h1>
          <p className="text-sm text-center font-light mt-1 px-8">
            TARAKI&apos;s Innovation Projects are practical applications of our
            research and knowledge. These projects are designed to address
            real-world challenges and demonstrate the potential of new
            technologies.
          </p>
        </div>
      </div>
    </div>
  );
}
export default program;
