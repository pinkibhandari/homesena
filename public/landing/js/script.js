/* =========================
   DOCUMENT READY
========================= */

$(document).ready(function () {
    /* =========================
       NAVBAR SCROLL + BACK TO TOP
    ========================= */

    var kawa = $(".top-bar");
    var back = $("#back-to-top");

    function scrollFunction() {
        if ($(window).scrollTop() > 700) {
            kawa.addClass("fixed");
            back.addClass("show-top");
        } else {
            kawa.removeClass("fixed");
            back.removeClass("show-top");
        }
    }

    $(window).on("scroll", scrollFunction);

    /* =========================
       SMOOTH SCROLL
    ========================= */

    $('a[href*="#"]:not([href="#"])').click(function () {
        if (
            location.pathname.replace(/^\//, "") ===
                this.pathname.replace(/^\//, "") &&
            location.hostname === this.hostname
        ) {
            var target = $(this.hash);
            target = target.length
                ? target
                : $("[name=" + this.hash.slice(1) + "]");

            if (target.length) {
                $("html, body").animate(
                    {
                        scrollTop: target.offset().top,
                    },
                    800,
                );

                return false;
            }
        }
    });
});

/* =========================
   FAQ TOGGLE
========================= */

document.querySelectorAll(".faq-question").forEach(function (btn) {
    btn.addEventListener("click", function () {
        let item = this.parentElement;

        document.querySelectorAll(".faq-item").forEach(function (faq) {
            if (faq !== item) {
                faq.classList.remove("active");
            }
        });

        item.classList.toggle("active");
    });
});

/* =========================
   SERVICES SLIDER
========================= */
const slider = document.getElementById("servicesSlider");
const track = slider ? slider.querySelector(".services-track") : null;

if (slider && track) {
    let speed = 1.2;
    let isHover = false;

    /* duplicate items for infinite loop */
    track.innerHTML += track.innerHTML;

    function autoScroll() {
        if (!isHover) {
            slider.scrollLeft += speed;
        }

        /* smooth reset */
        if (slider.scrollLeft >= track.scrollWidth / 2) {
            slider.scrollLeft = 0;
        }

        requestAnimationFrame(autoScroll);
    }

    autoScroll();

    /* hover pause */

    slider.addEventListener("mouseenter", () => {
        isHover = true;
    });

    slider.addEventListener("mouseleave", () => {
        isHover = false;
    });

    /* drag support */

    let isDown = false;
    let startX;
    let scrollLeft;

    slider.addEventListener("mousedown", (e) => {
        isDown = true;
        slider.style.cursor = "grabbing";

        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });

    slider.addEventListener("mouseup", () => {
        isDown = false;
        slider.style.cursor = "grab";
    });

    slider.addEventListener("mouseleave", () => {
        isDown = false;
        slider.style.cursor = "grab";
    });

    slider.addEventListener("mousemove", (e) => {
        if (!isDown) return;

        e.preventDefault();

        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 2;

        slider.scrollLeft = scrollLeft - walk;
    });
    // nav button //
    // const menuBtn = document.getElementById("menuBtn");
    // const mobileMenu = document.getElementById("mobileMenu");
    // const closeMenu = document.getElementById("closeMenu");

    /* open menu */

    // menuBtn.onclick = () => {
    //     mobileMenu.style.left = "0";
    // };

    /* close menu */

    // closeMenu.onclick = () => {
    //     mobileMenu.style.left = "-100%";
    // };

    /* close menu when link clicked */

    // const menuLinks = mobileMenu.querySelectorAll("a");

    // menuLinks.forEach((link) => {
    //     link.addEventListener("click", () => {
    //         mobileMenu.style.left = "-100%";
    //     });
    // });
}


document.addEventListener("DOMContentLoaded", function () {

    const menuBtn = document.getElementById("menuBtn");
    const mobileMenu = document.getElementById("mobileMenu");
    const closeMenu = document.getElementById("closeMenu");

    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener("click", function () {
            console.log("Menu clicked");
            mobileMenu.style.left = "0";
        });
    }

     closeMenu.addEventListener("click", function () {
        mobileMenu.style.left = "-100%";
    });

    const menuLinks = mobileMenu.querySelectorAll("a");

    menuLinks.forEach((link) => {
        link.addEventListener("click", () => {
            mobileMenu.style.left = "-100%";
        });
    });

});