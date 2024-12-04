$(window).on('load', function () {
    setTimeout(function () {
        $('.loader_bg').fadeOut('slow');
    }, 1000);
});

// Navigation menu animation
function animateMenu() {
    var menu = $('.menuBg');
    var nav = $('.mobileNav');

    if (menu.hasClass('showMenu')) {
        menu.removeClass('showMenu').addClass('hideMenu');
        nav.removeClass('fadeIn');
    } else if (menu.hasClass('hideMenu')) {
        menu.removeClass('hideMenu').addClass('showMenu');
        nav.addClass('fadeIn');
    } else {
        menu.addClass('showMenu');
        nav.addClass('fadeIn');
    }
}

// Close navigation menu
function closeMenu() {
    $('.menuBg').removeClass('showMenu').addClass('hideMenu');
    $('.mobileNav').removeClass('fadeIn');
    $('.hamburger').removeClass('open');
}

// Document ready handler
$(document).ready(function() {
    // Handle hamburger menu toggle
    $('.hamburger').on('click', function() {
        $(this).toggleClass('open');
        animateMenu();
    });

    // Handle link click in mobile navigation
    $('.mobileNav a').on('click', function(){
        $('.hamburger').toggleClass('open');
        animateMenu();
    });
});

// Intersection Observer for active link styling and modal display
document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const sections = document.querySelectorAll('section');

    const changePage = (index) => {
        if (index >= 0 && index < sections.length) {
            sections[index].scrollIntoView({ behavior: 'smooth', block: 'start' });

            // Update active link styling
            navLinks.forEach((link, i) => {
                if (i === index) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }
    };

    const closeMenu = () => {
        $('.menuBg').removeClass('showMenu').addClass('hideMenu');
        $('.mobileNav').removeClass('fadeIn');
        $('.hamburger').removeClass('open');
    };

    // Update active link based on scroll position
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const index = Array.from(sections).indexOf(entry.target);
            if (entry.isIntersecting) {
                navLinks.forEach((link, i) => {
                    if (i === index) {
                        link.classList.add('active');
                    } else {
                        link.classList.remove('active');
                    }
                });
            }
        });
    }, { threshold: 0.5 });

    sections.forEach(section => {
        observer.observe(section);
    });

    navLinks.forEach((link, index) => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            changePage(index);
            closeMenu(); // Close menu when navbar item is clicked
        });
    });
});

// Modal appearing on mobile screen 
document.addEventListener('DOMContentLoaded', () => {
    const modalElement = document.getElementById('myModal');
    const modal = new bootstrap.Modal(modalElement);
    const blogSection = document.getElementById('blog');
    let modalTimeoutId;
    let isModalShown = false;
    let isDirectNavigation = false;
    let lastScrollPosition = 0;

    // Function to show the modal with a delay
    const showModal = () => {
        modalTimeoutId = setTimeout(() => {
            if (!isModalShown && !isDirectNavigation) {
                modal.show();
                isModalShown = true;
            }
        }, 1000); // 1 second delay
    };

    // Function to check and show the modal
    const checkAndShowModal = () => {
        const isLandscape = window.matchMedia("(orientation: landscape)").matches;

        if (window.innerWidth <= 767 && !isLandscape) {
            clearTimeout(modalTimeoutId);

            const blogObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && entry.target === blogSection) {
                        showModal();
                    } else {
                        clearTimeout(modalTimeoutId);
                    }
                });
            }, { threshold: 0.5 });

            blogObserver.observe(blogSection);
        } else {
            modal.hide();
            clearTimeout(modalTimeoutId);
            isModalShown = false;
        }
    };

    // Function to handle orientation changes
    const handleOrientationChange = () => {
        if (window.matchMedia("(orientation: landscape)").matches) {
            modal.hide();
            isModalShown = false;
        }
    };

    // Event listener for orientation changes
    window.addEventListener('orientationchange', handleOrientationChange);

    // Event listener for window resize
    window.addEventListener('resize', checkAndShowModal);

    // Event listener for page load
    window.addEventListener('load', checkAndShowModal);

    // Ensure modal hides immediately if orientation changes
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'hidden') {
            modal.hide();
            isModalShown = false;
        }
    });

    // Event listener for navigation link clicks
    document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
        link.addEventListener('click', (event) => {
            // Check if the clicked link is pointing to the blog section
            const targetId = new URL(event.currentTarget.href).hash.substring(1);
            if (targetId === 'blog') {
                isDirectNavigation = true;
                setTimeout(() => {
                    isDirectNavigation = false;
                }, 1000); // Reset the flag after 1 second to handle quick navigation
            } else {
                // If not the blog section, hide the modal
                modal.hide();
                isModalShown = false;
            }
        });
    });
});



// Script for dots 
document.addEventListener('DOMContentLoaded', function () {
    const dots = document.querySelectorAll('.dot');
    const sections = document.querySelectorAll('section');

    // Function to handle scroll and update dots
    function handleScroll() {
        let index = sections.length;
        while (--index && window.scrollY + 50 < sections[index].offsetTop) {}
        dots.forEach((dot) => dot.classList.remove('active'));
        if (dots[index]) {
            dots[index].classList.add('active');
        }
    }

    // Function to initialize or remove dots functionality based on screen width
    function initializeDots() {
        if (window.innerWidth > 767) {
            handleScroll();
            window.addEventListener('scroll', handleScroll);

            dots.forEach(dot => {
                dot.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        window.scrollTo({
                            top: target.offsetTop,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        } else {
            window.removeEventListener('scroll', handleScroll);
            dots.forEach(dot => {
                dot.removeEventListener('click', function (e) {
                    e.preventDefault();
                });
            });
        }
    }

    // Initialize dots functionality based on the current screen width
    initializeDots();

    // Add resize event listener to adjust functionality when resizing the window
    window.addEventListener('resize', initializeDots);
});


