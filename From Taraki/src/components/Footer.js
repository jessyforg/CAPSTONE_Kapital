function footer() {
  return (
    <div>
      <div>
        <footer className="text-center bg-white p-2 rounded-lg shadow mt-8 laptop-s:text-center">
          <div className="w-full mx-auto max-w-screen-xl p-4 px-14 tablet:px-64 tablet:flex tablet:items-center tablet:justify-between laptop-s:px-[32rem] phone:px-16 tablet-m:px-96">
            <span className="text-xs text-gray-400 text-center laptop-m:text-sm">
              © 2024 | TARAKI | All Rights Reserved Designed by{" "}
              <span className="font-semibold text-gray-500"> TARAKI-CAR</span>
            </span>
            <ul className="flex flex-wrap items-center mt-0 text-sm font-medium phone:hidden laptop:flex text-gray-400">
              <li>
                <a
                  href="#section2"
                  className="hover:underline me-4 tablet:me-6"
                >
                  About
                </a>
              </li>
              <li>
                <a href="#1" className="hover:underline me-4 tablet:me-6">
                  Privacy Policy
                </a>
              </li>
              <li>
                <a href="#2" className="hover:underline me-4 tablet:me-6">
                  Licensing
                </a>
              </li>
            </ul>
          </div>
        </footer>
      </div>
    </div>
  );
}
export default footer;
