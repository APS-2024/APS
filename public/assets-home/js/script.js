document.addEventListener('DOMContentLoaded', () => {
    // Initialize AOS
    const adjustAOSForMobile = () => {
        const isMobile = window.innerWidth <= 768;
        const offset = isMobile ? 50 : 200;

        AOS.init({
            duration: 1200,
            once: false,
            offset: offset,
            easing: 'ease-in-out',
        });
    };

    adjustAOSForMobile();

    window.addEventListener('resize', () => {
        adjustAOSForMobile();
    });

    const slider = document.querySelector('.slider');
    const sliderItems = document.querySelectorAll('.slider-item');
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const dots = document.querySelectorAll('.dots-item');
    let currentPage = 0;
    let isScrolling = false;

    const changePage = (index) => {
        if (index >= 0 && index < sliderItems.length) {
            sliderItems[index].scrollIntoView({ behavior: 'smooth', block: 'start' });
            currentPage = index;
            toggleActiveDot(index);

            setTimeout(() => {
                AOS.refresh();
            }, 600);
        }
    };

    const getNextPage = (direction) => {
        if (direction === 'next' && currentPage < sliderItems.length - 1) {
            return currentPage + 1;
        } else if (direction === 'prev' && currentPage > 0) {
            return currentPage - 1;
        } else {
            return currentPage;
        }
    };

    const toggleActiveDot = (index) => {
        dots.forEach((dot) => dot.classList.remove('active'));
        if (dots[index]) {
            dots[index].classList.add('active');
        }
    };

    const isScrolledToBottom = (item) => {
        return item.scrollTop + item.clientHeight >= item.scrollHeight;
    };

    const isScrolledToTop = (item) => {
        return item.scrollTop === 0;
    };

    const handleScroll = (e, item) => {
        if (!isScrolling) {
            isScrolling = true;

            let isAtBottom = isScrolledToBottom(item);
            let isAtTop = isScrolledToTop(item);

            if (isAtBottom && e.deltaY > 0) {
                changePage(getNextPage('next'));
            } else if (isAtTop && e.deltaY < 0) {
                changePage(getNextPage('prev'));
            }

            setTimeout(() => {
                isScrolling = false;
            }, 500);
        }
    };

    sliderItems.forEach((item, index) => {
        item.style.overflowY = 'auto';
        item.addEventListener('wheel', (e) => {
            if (index === currentPage) {
                handleScroll(e, item);
            }
        });

        let startY = 0;
        let isDragging = false;

        item.addEventListener('touchstart', (e) => {
            startY = e.touches[0].clientY;
            isDragging = true;
        });

        item.addEventListener('touchmove', (e) => {
            if (isDragging) {
                let currentY = e.touches[0].clientY;
                let deltaY = startY - currentY;
                startY = currentY;

                if (Math.abs(deltaY) > 10) {
                    if ((isScrolledToBottom(item) && deltaY > 0) || (isScrolledToTop(item) && deltaY < 0)) {
                        changePage(getNextPage(deltaY > 0 ? 'next' : 'prev'));
                    }
                    isDragging = false;
                }
            }
        });

        item.addEventListener('touchend', () => {
            isDragging = false;
        });
    });

    window.addEventListener('keyup', (e) => {
        let nextPage = e.key === 'ArrowUp' ? getNextPage('prev') :
                       e.key === 'ArrowDown' ? getNextPage('next') :
                       null;
        if (nextPage !== null) changePage(nextPage);
    });

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            changePage(index);
        });
    });

    navLinks.forEach((link, index) => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            changePage(index);
            closeMenu(); // Close menu when navbar item is clicked
        });
    });

    window.addEventListener('resize', () => {
        changePage(currentPage);
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('aos-animate');
            } else {
                entry.target.classList.remove('aos-animate');
            }
        });
    }, { threshold: 0.5 });

    sliderItems.forEach(item => {
        observer.observe(item);
    });

    window.addEventListener('load', () => {
        sliderItems[currentPage].scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    $(window).on('load', function () {
        setTimeout(function () {
            $('.loader_bg').fadeOut('slow');
        }, 1000);
    });

    // setTimeout(() => {
    //     if (window.innerWidth <= 768) {
    //         $('#myModal').addClass('zoom-inn').modal('show');
    //     }
    // }, 2000);

    
// navbar animation 
    function animateMenu() {
        var menu = $('.menuBg');
        var nav = $('.mobileNav');

        if (menu.hasClass('showMenu')) { // Hide menu if it's open
            menu.removeClass('showMenu').addClass('hideMenu');
            nav.removeClass('fadeIn');
        } else if (menu.hasClass('hideMenu')) { // Show menu and remove hideMenu
            menu.removeClass('hideMenu').addClass('showMenu');
            nav.addClass('fadeIn');
        } else {
            menu.addClass('showMenu'); // Initial show menu
            nav.addClass('fadeIn');
        }
    };

    function closeMenu() {
        $('.menuBg').removeClass('showMenu').addClass('hideMenu');
        $('.mobileNav').removeClass('fadeIn');
        $('.hamburger').removeClass('open');
    }

    $(document).ready(function() {
        $('.hamburger').on('click', function() {
            $(this).toggleClass('open');
            animateMenu();
        });

        $('.mobileNav a').on('click', function(){
            $('.hamburger').toggleClass('open');
            animateMenu();
        });
    });
});
