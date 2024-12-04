document.addEventListener('DOMContentLoaded', () => {
    const slider = document.querySelector('.slider');
    const sliderItems = document.querySelectorAll('.slider-item');
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const dots = document.querySelectorAll('.dots-item');
    let currentPage = 0;
    let lastScrollTime = 0;
    let scrollDirection = null; // Track scroll direction

    // Helper functions
    const changePage = (index) => {
        if (index >= 0 && index < sliderItems.length) {
            sliderItems[index].scrollIntoView({ behavior: 'smooth' });
            currentPage = index;
            toggleActiveDot(index);
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
        let now = Date.now();
        let isAtBottom = isScrolledToBottom(item);
        let isAtTop = isScrolledToTop(item);

        if (now - lastScrollTime > 1000) {
            lastScrollTime = now;
            if (isAtBottom && e.deltaY > 0) {
                // Scrolled to bottom and user scrolls down
                changePage(getNextPage('next'));
            } else if (isAtTop && e.deltaY < 0) {
                // Scrolled to top and user scrolls up
                changePage(getNextPage('prev'));
            }
        }
    };

    // Event listener for scrolling within a slide
    sliderItems.forEach((item, index) => {
        item.style.overflowY = 'auto';
        item.addEventListener('wheel', (e) => {
            if (index === currentPage) {
                handleScroll(e, item);
            }
        });

        // Track drag events for touch devices
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
                    if (isScrolledToBottom(item) && deltaY > 0) {
                        changePage(getNextPage('next'));
                    } else if (isScrolledToTop(item) && deltaY < 0) {
                        changePage(getNextPage('prev'));
                    }
                    isDragging = false; // Stop drag after transition
                }
            }
        });

        item.addEventListener('touchend', () => {
            isDragging = false;
        });
    });

    // Arrow keys and dot navigation
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
        });
    });

    // Resize event to ensure proper alignment
    window.addEventListener('resize', () => {
        changePage(currentPage);
    });
});
