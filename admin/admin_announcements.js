/* =========================================================
   AMOR FASHION
   ADMIN ANNOUNCEMENTS JAVASCRIPT
   SIDEBAR + MOBILE + LOGOUT + FORM
========================================================= */


/* =========================================================
   ELEMENTS
========================================================= */

const sidebar =
    document.getElementById("sidebar");

const mobileMenuBtn =
    document.getElementById("mobileMenuBtn");

const mobileMenuIcon =
    mobileMenuBtn
        ? mobileMenuBtn.querySelector("i")
        : null;

const sidebarOverlay =
    document.getElementById("sidebarOverlay");

const sidebarLinks =
    document.querySelectorAll(".sidebar-link");

const logoutBtn =
    document.getElementById("logoutBtn");

const logoutModal =
    document.getElementById("logoutModal");

const confirmLogout =
    document.getElementById("confirmLogout");

const cancelLogout =
    document.getElementById("cancelLogout");

const announcementForm =
    document.getElementById("announcementForm");

const announcementMessage =
    document.getElementById("announcement_message");

const characterCount =
    document.getElementById("characterCount");

const messageClose =
    document.getElementById("messageClose");

const systemMessage =
    document.getElementById("systemMessage");


/* =========================================================
   CURRENT PAGE
========================================================= */

function setActivePage() {

    const currentPage =
        window.location.pathname
        .split("/")
        .pop();


    sidebarLinks.forEach(function(link) {

        const page =
            link.getAttribute("data-page");


        if (page === currentPage) {

            link.classList.add("active");

        } else {

            link.classList.remove("active");

        }

    });

}


/* =========================================================
   INITIALIZE ACTIVE PAGE
========================================================= */

setActivePage();


/* =========================================================
   SIDEBAR OPEN / CLOSE STATE
   (single source of truth so the sidebar, the dark overlay,
   the hamburger <-> close icon, and body scroll-lock all
   move together instead of drifting out of sync)
========================================================= */

function openMobileSidebar() {

    if (!sidebar) {
        return;
    }


    sidebar.classList.add("mobile-open");


    if (sidebarOverlay) {

        sidebarOverlay.classList.add("active");

    }


    if (mobileMenuBtn) {

        mobileMenuBtn.classList.add("is-open");

        mobileMenuBtn.setAttribute(
            "aria-label",
            "Close navigation"
        );

    }


    if (mobileMenuIcon) {

        mobileMenuIcon.classList.remove("fa-bars");

        mobileMenuIcon.classList.add("fa-xmark");

    }


    document.body.style.overflow = "hidden";

}


function closeMobileSidebar() {

    if (sidebar) {

        sidebar.classList.remove("mobile-open");

    }


    if (sidebarOverlay) {

        sidebarOverlay.classList.remove("active");

    }


    if (mobileMenuBtn) {

        mobileMenuBtn.classList.remove("is-open");

        mobileMenuBtn.setAttribute(
            "aria-label",
            "Open navigation"
        );

    }


    if (mobileMenuIcon) {

        mobileMenuIcon.classList.remove("fa-xmark");

        mobileMenuIcon.classList.add("fa-bars");

    }


    document.body.style.overflow = "";

}


function toggleMobileSidebar() {

    if (!sidebar) {
        return;
    }


    if (sidebar.classList.contains("mobile-open")) {

        closeMobileSidebar();

    } else {

        openMobileSidebar();

    }

}


/* =========================================================
   MOBILE MENU BUTTON (open + close, same button)
========================================================= */

if (mobileMenuBtn) {

    mobileMenuBtn.addEventListener(
        "click",
        function() {

            toggleMobileSidebar();

        }
    );

}


/* =========================================================
   OVERLAY CLICK
========================================================= */

if (sidebarOverlay) {

    sidebarOverlay.addEventListener(
        "click",
        function() {

            closeMobileSidebar();

        }
    );

}


/* =========================================================
   SIDEBAR LINK CLICK
========================================================= */

sidebarLinks.forEach(function(link) {

    link.addEventListener(
        "click",
        function() {


            /* Remove active from all */

            sidebarLinks.forEach(
                function(item) {

                    item.classList.remove(
                        "active"
                    );

                }
            );


            /* Set active */

            this.classList.add(
                "active"
            );


            /* Close mobile sidebar */

            if (
                window.innerWidth <= 768
            ) {

                closeMobileSidebar();

            }

        }
    );

});


/* =========================================================
   LOGOUT BUTTON
========================================================= */

if (logoutBtn) {

    logoutBtn.addEventListener(
        "click",
        function() {

            if (!logoutModal) {
                return;
            }


            logoutModal.classList.add(
                "active"
            );


            document.body.style.overflow =
                "hidden";

        }
    );

}


/* =========================================================
   CLOSE LOGOUT MODAL
========================================================= */

function closeLogoutModal() {

    if (logoutModal) {

        logoutModal.classList.remove(
            "active"
        );

    }


    /* Only clear the scroll lock if the mobile sidebar
       isn't also open and holding it. */

    if (
        !sidebar ||
        !sidebar.classList.contains("mobile-open")
    ) {

        document.body.style.overflow = "";

    }

}


/* =========================================================
   CANCEL LOGOUT
========================================================= */

if (cancelLogout) {

    cancelLogout.addEventListener(
        "click",
        function() {

            closeLogoutModal();

        }
    );

}


/* =========================================================
   CONFIRM LOGOUT
========================================================= */

if (confirmLogout) {

    confirmLogout.addEventListener(
        "click",
        function() {

            window.location.href =
                "logout.php";

        }
    );

}


/* =========================================================
   CLICK OUTSIDE LOGOUT MODAL
========================================================= */

if (logoutModal) {

    logoutModal.addEventListener(
        "click",
        function(event) {

            if (
                event.target ===
                logoutModal
            ) {

                closeLogoutModal();

            }

        }
    );

}


/* =========================================================
   ESCAPE KEY
========================================================= */

document.addEventListener(
    "keydown",
    function(event) {

        if (event.key === "Escape") {

            closeMobileSidebar();

            closeLogoutModal();

        }

    }
);


/* =========================================================
   WINDOW RESIZE
========================================================= */

window.addEventListener(
    "resize",
    function() {

        if (
            window.innerWidth > 768
        ) {

            closeMobileSidebar();

        }

    }
);


/* =========================================================
   CHARACTER COUNTER
========================================================= */

function updateCharacterCount() {

    if (
        !announcementMessage ||
        !characterCount
    ) {
        return;
    }


    characterCount.textContent =
        announcementMessage.value.length;

}


if (announcementMessage) {

    announcementMessage.addEventListener(
        "input",
        updateCharacterCount
    );


    updateCharacterCount();

}


/* =========================================================
   CLEAR FORM
========================================================= */

if (announcementForm) {

    announcementForm.addEventListener(
        "reset",
        function() {

            setTimeout(
                function() {

                    updateCharacterCount();

                },
                0
            );

        }
    );

}


/* =========================================================
   CLOSE SYSTEM MESSAGE
========================================================= */

if (messageClose && systemMessage) {

    messageClose.addEventListener(
        "click",
        function() {

            systemMessage.style.opacity =
                "0";

            systemMessage.style.transform =
                "translateY(-5px)";

            systemMessage.style.transition =
                "0.2s ease";


            setTimeout(
                function() {

                    systemMessage.remove();

                },
                200
            );

        }
    );

}


/* =========================================================
   AUTO HIDE SUCCESS MESSAGE
========================================================= */

if (
    systemMessage &&
    systemMessage.classList.contains("success")
) {

    setTimeout(
        function() {

            if (
                document.body.contains(
                    systemMessage
                )
            ) {

                systemMessage.style.opacity =
                    "0";

                systemMessage.style.transform =
                    "translateY(-5px)";

                systemMessage.style.transition =
                    "0.2s ease";


                setTimeout(
                    function() {

                        if (
                            document.body.contains(
                                systemMessage
                            )
                        ) {

                            systemMessage.remove();

                        }

                    },
                    200
                );

            }

        },
        5000
    );

}