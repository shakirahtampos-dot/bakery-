document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    /* =====================================================
       KATES GOODIES
       ABOUT US PAGE JAVASCRIPT
    ===================================================== */

    /* =====================================================
       MOBILE BURGER MENU
    ===================================================== */

    const navToggle = document.getElementById("navToggle");
    const mainNav = document.getElementById("mainNav");
    const navClose = document.getElementById("navClose");
    const navOverlay = document.getElementById("navOverlay");

    function openNav() {
        if (!mainNav || !navToggle) {
            return;
        }

        mainNav.classList.add("nav-open");
        mainNav.classList.add("is-open");

        navToggle.classList.add("active");

        navToggle.setAttribute("aria-expanded", "true");
        navToggle.setAttribute("aria-label", "Close navigation");

        if (navOverlay) {
            navOverlay.classList.add("nav-overlay-active");
            navOverlay.classList.add("show");
        }

        document.body.classList.add("nav-no-scroll");
        document.body.classList.add("no-scroll");
    }

    function closeNav() {
        if (!mainNav || !navToggle) {
            return;
        }

        mainNav.classList.remove("nav-open");
        mainNav.classList.remove("is-open");

        navToggle.classList.remove("active");

        navToggle.setAttribute("aria-expanded", "false");
        navToggle.setAttribute("aria-label", "Open navigation");

        if (navOverlay) {
            navOverlay.classList.remove("nav-overlay-active");
            navOverlay.classList.remove("show");
        }

        document.body.classList.remove("nav-no-scroll");
        document.body.classList.remove("no-scroll");
    }

    function toggleNav() {
        if (!mainNav) {
            return;
        }

        const navigationIsOpen =
            mainNav.classList.contains("nav-open") ||
            mainNav.classList.contains("is-open");

        if (navigationIsOpen) {
            closeNav();
        } else {
            openNav();
        }
    }

    if (navToggle && mainNav) {
        navToggle.addEventListener("click", function (event) {
            event.preventDefault();
            event.stopPropagation();

            toggleNav();
        });
    }

    if (navClose) {
        navClose.addEventListener("click", function (event) {
            event.preventDefault();

            closeNav();
        });
    }

    if (navOverlay) {
        navOverlay.addEventListener("click", function () {
            closeNav();
        });
    }

    if (mainNav) {
        const navLinks = mainNav.querySelectorAll("a");

        navLinks.forEach(function (link) {
            link.addEventListener("click", function () {
                closeNav();
            });
        });
    }

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeNav();
        }
    });

    window.addEventListener("resize", function () {
        if (window.innerWidth > 900) {
            closeNav();
        }
    });

    /* =====================================================
       ACCOUNT DROPDOWN
       (This was previously missing entirely — the button
       had no click handler, so nothing happened on click,
       on both desktop and mobile.)
    ===================================================== */

    const accountButton = document.getElementById("accountButton");
    const accountDropdown = document.getElementById("accountDropdown");

    function openAccountDropdown() {
        if (!accountButton || !accountDropdown) {
            return;
        }

        accountDropdown.classList.add("show");
        accountButton.classList.add("active");
        accountButton.setAttribute("aria-expanded", "true");
    }

    function closeAccountDropdown() {
        if (!accountButton || !accountDropdown) {
            return;
        }

        accountDropdown.classList.remove("show");
        accountButton.classList.remove("active");
        accountButton.setAttribute("aria-expanded", "false");
    }

    function toggleAccountDropdown() {
        if (!accountDropdown) {
            return;
        }

        if (accountDropdown.classList.contains("show")) {
            closeAccountDropdown();
        } else {
            openAccountDropdown();
        }
    }

    if (accountButton && accountDropdown) {

        accountButton.addEventListener("click", function (event) {
            event.preventDefault();
            event.stopPropagation();

            toggleAccountDropdown();
        });

        // Prevent clicks inside the dropdown from closing it
        accountDropdown.addEventListener("click", function (event) {
            event.stopPropagation();
        });

        // Close it when clicking anywhere else on the page
        document.addEventListener("click", function () {
            closeAccountDropdown();
        });

        // Close it on Escape
        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") {
                closeAccountDropdown();
            }
        });

        // Close it if the mobile nav gets opened, to avoid overlap
        if (navToggle) {
            navToggle.addEventListener("click", function () {
                closeAccountDropdown();
            });
        }

        // Close it whenever the mobile nav closes (e.g. tapping a link)
        if (navClose) {
            navClose.addEventListener("click", function () {
                closeAccountDropdown();
            });
        }
    }

    /* =====================================================
       SMOOTH SCROLL
    ===================================================== */

    const smoothLinks =
        document.querySelectorAll('a[href^="#"]');

    smoothLinks.forEach(function (link) {
        link.addEventListener("click", function (event) {
            const targetId = this.getAttribute("href");

            if (!targetId || targetId === "#") {
                return;
            }

            let target = null;

            try {
                target = document.querySelector(targetId);
            } catch (error) {
                return;
            }

            if (target) {
                event.preventDefault();

                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            }
        });
    });

    /* =====================================================
       SCROLL REVEAL
    ===================================================== */

    const revealElements = document.querySelectorAll(
        ".about-overview-card, " +
        ".why-card, " +
        ".testimonial, " +
        ".about-text, " +
        ".about-images, " +
        ".about-visit-content"
    );

    revealElements.forEach(function (element) {
        element.classList.add("scroll-reveal");
    });

    if ("IntersectionObserver" in window) {
        const revealObserver = new IntersectionObserver(
            function (entries, observer) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("reveal-active");

                        observer.unobserve(entry.target);
                    }
                });
            },
            {
                threshold: 0.15
            }
        );

        revealElements.forEach(function (element) {
            revealObserver.observe(element);
        });
    } else {
        revealElements.forEach(function (element) {
            element.classList.add("reveal-active");
        });
    }

    /* =====================================================
       OVERVIEW CARD STAGGER
    ===================================================== */

    const overviewCards =
        document.querySelectorAll(".about-overview-card");

    overviewCards.forEach(function (card, index) {
        card.style.transitionDelay =
            (index * 0.08) + "s";
    });

    /* =====================================================
       WHY CARD STAGGER
    ===================================================== */

    const whyCards =
        document.querySelectorAll(".why-card");

    whyCards.forEach(function (card, index) {
        card.style.transitionDelay =
            (index * 0.08) + "s";
    });

    /* =====================================================
       TESTIMONIAL STAGGER
    ===================================================== */

    const testimonialCards =
        document.querySelectorAll(".testimonial");

    testimonialCards.forEach(function (testimonial, index) {
        testimonial.style.transitionDelay =
            (index * 0.12) + "s";
    });

    /* =====================================================
       PAGE LOADED
    ===================================================== */

    document.body.classList.add("about-page-loaded");
});