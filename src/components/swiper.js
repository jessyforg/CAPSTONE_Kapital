import Swiper from 'swiper';

const initSwiper = () => {
  return new Swiper(".multiple-slide-carousel", {
    loop: true,
    slidesPerView: 3,
    spaceBetween: 20,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      1920: {
        slidesPerView: 3,
        spaceBetween: 30,
      },
      1028: {
        slidesPerView: 2,
        spaceBetween: 30,
      },
      990: {
        slidesPerView: 1,
        spaceBetween: 0,
      },
    },
  });
};

export default initSwiper;
