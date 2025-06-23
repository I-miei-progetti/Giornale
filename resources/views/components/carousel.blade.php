 <!-- Swiper -->
  <div class="swiper mySwiper" >
    <div class="swiper-wrapper">
      <div class="swiper-slide">
         <div class="title" data-swiper-parallax="-300">Slide 1</div>
        <img src="https://swiperjs.com/demos/images/nature-1.jpg" />
      </div>
      <div class="swiper-slide">
         <div class="title" data-swiper-parallax="-300">Slide 1</div>
        <img src="https://swiperjs.com/demos/images/nature-2.jpg" />
      </div>
      <div class="swiper-slide">
         <div class="title" data-swiper-parallax="-300">Slide 1</div>
        <img src="https://swiperjs.com/demos/images/nature-3.jpg" />
      </div>
      <div class="swiper-slide">
         <div class="title" data-swiper-parallax="-300">Slide 1</div>
        <img src="https://swiperjs.com/demos/images/nature-4.jpg" />
      </div>
    </div>
    {{-- <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div> --}}
    <div class="swiper-pagination"></div>
  </div>

  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <!-- Initialize Swiper -->
  <script>
     var swiper = new Swiper(".mySwiper", {
      spaceBetween: 30,
      centeredSlides: true,
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
        
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
  </script>