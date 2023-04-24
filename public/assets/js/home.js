/*==================== SWIPER JS ====================*/
let galleryThumbs = new Swiper('.gallery-thumbs', {
    spaceBetween: 0,
    slidesPerView: 0,
})

let galleryTop = new Swiper('.gallery-top', {
    effect: 'fade',
    loop: true,

    thumbs: {
        swiper: galleryThumbs
    }
})

/*==================== POPUP ====================*/
const btnOpenVideo = document.querySelectorAll('.home__video-content')
const islandsPopup = document.getElementById('popup')

function poPup(){
    islandsPopup.classList.add('show-popup')
}
btnOpenVideo.forEach(b => b.addEventListener('click', poPup))

const btnCloseVideo = document.getElementById('popup-close')

btnCloseVideo.addEventListener('click', ()=> {
    islandsPopup.classList.remove('show-popup')
})

/*==================== GSAP ANIMATION ====================*/
const controlImg = document.querySelectorAll('.home__img')

function scrollAnimation(){
    gsap.from('.home__subtitle', {opacity: 0, duration: .2, delay: .2, y: -20})
    gsap.from('.home__title', {opacity: 0, duration: .3, delay: .3, y: -20})
    gsap.from('.home__description', {opacity: 0, duration: .4, delay: .4, y: -20})
    gsap.from('.home__button', {opacity: 0, duration: .5, delay: .5, y: -20})
    gsap.from('.home__video-content', {opacity: 0, duration: .6, delay: .6, y: -20})

    islandsPopup.classList.remove('show-popup')
}

controlImg.forEach(c => c.addEventListener('click', scrollAnimation))
