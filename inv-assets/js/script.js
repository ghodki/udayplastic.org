$(window).scroll(function () {
    var scroll = $(window).scrollTop();
    if (scroll >= 50) {
        $("header").addClass("headercolor");
    } else {
        $("header").removeClass("headercolor");
    }
});

$(window).scroll(function () {
    var scroll = $(window).scrollTop();
    if (scroll >= 50) {
        $("header").addClass("headercolor");
    } else {
        $("header").removeClass("headercolor");
    }
});

$(function () {
    $(".navbar-toggler").click(function () {
        $("header .navbar").toggleClass("headercolor");
    });
});

$(function () {
    $(".ham-menu input").bind('click', function () {
        $('this').toggleClass('active-btn');
        $('.nav-list').toggleClass('active-btn');
        $('.primary-show').toggleClass('first-title');
        $('.collapse').toggleClass('show');
    });
});

const menu = document.querySelector(".menu");
const menuInner = menu.querySelector(".menu__inner");
const menuArrow = menu.querySelector(".menu__arrow");
const menuTitle = menu.querySelector(".menu__title");
const burger = document.querySelector(".burger");
const overlay = document.querySelector(".overlay");

// Navbar Menu Toggle Function
function toggleMenu() {
    menu.classList.toggle("is-active");
    overlay.classList.toggle("is-active");
}

// Show Mobile Submenu Function
function showSubMenu(children) {
    var subMenu = children.querySelector(".submenu");
    subMenu.classList.add("is-active");
    subMenu.style.animation = "slideLeft 0.35s ease forwards";
    const menuTitle = children.querySelector("i").parentNode.childNodes[0].textContent;
    menu.querySelector(".menu__title").textContent = menuTitle;
    menu.querySelector(".menu__header").classList.add("is-active");
}

// Hide Mobile Submenu Function
function hideSubMenu() {
    subMenu.style.animation = "slideRight 0.35s ease forwards";
    setTimeout(() => {
        subMenu.classList.remove("is-active");
    }, 300);

    menu.querySelector(".menu__title").textContent = "";
    menu.querySelector(".menu__header").classList.remove("is-active");
}

// Toggle Mobile Submenu Function
function toggleSubMenu(e) {
    if (!menu.classList.contains("is-active")) {
        return;
    }
    if (e.target.closest(".menu__dropdown")) {
        const children = e.target.closest(".menu__dropdown");
        showSubMenu(children);
    }
}

// Fixed Navbar Menu on Window Resize
$(document).ready(function() {
    if (window.innerWidth >= 991 || window.innerWidth >= 768 || window.innerWidth >= 360 || window.innerWidth >= 425 || window.innerWidth >= 320) {
        if (menu.classList.contains("is-active")) {
            toggleMenu();
        }
        
        // Toggle dropdown menu when the mobile menu toggle button is clicked
        $(".ham-menu input").on('click', function() {
            // Toggle the visibility of the menu
            $(".menu").toggleClass("is-active");
            // Optionally, toggle other classes or perform additional actions
            $(".overlay").toggleClass("is-active"); // Toggle overlay
            $(".submenu").removeClass("is-active");
        });
        
        $(".submenu__close").on('click', function() {
            $(".submenu").removeClass("is-active");
        });
        
        // Close the dropdown menu when clicking outside the menu
        $(".overlay").on('click', function() {
            $(".menu").removeClass("is-active");
            $(this).removeClass("is-active");
            $(this).next(".submenu").removeClass("is-active");
        });

        // Toggle submenu visibility when submenu headers are clicked
        $(".menu__link").on('click', function() {
            $(this).next(".submenu").toggleClass("is-active");
        });
    }
});