const sidebar = document.getElementById("sidebar");
const mobileMenuBtn = document.getElementById("mobileMenuBtn");
const sidebarCloseBtn = document.getElementById("sidebarCloseBtn");
const sidebarOverlay = document.getElementById("sidebarOverlay");

// Toggle functions
function openMobileSidebar() {
  sidebar.classList.add("mobile-open");
  if (sidebarOverlay) sidebarOverlay.classList.add("active");
  // Change burger icon to X
  if (mobileMenuBtn) {
    const icon = mobileMenuBtn.querySelector("i");
    if (icon) {
      icon.classList.remove("fa-bars");
      icon.classList.add("fa-xmark");
    }
    mobileMenuBtn.setAttribute("aria-label", "Close navigation");
    mobileMenuBtn.setAttribute("aria-expanded", "true");
  }
  document.body.style.overflow = "hidden";
}

function closeMobileSidebar() {
  if (sidebar) sidebar.classList.remove("mobile-open");
  if (sidebarOverlay) sidebarOverlay.classList.remove("active");
  // Change X back to burger
  if (mobileMenuBtn) {
    const icon = mobileMenuBtn.querySelector("i");
    if (icon) {
      icon.classList.remove("fa-xmark");
      icon.classList.add("fa-bars");
    }
    mobileMenuBtn.setAttribute("aria-label", "Open navigation");
    mobileMenuBtn.setAttribute("aria-expanded", "false");
  }
  // Restore scrolling if logout modal isn't active
  if (!document.querySelector(".logout-modal.active")) {
    document.body.style.overflow = "";
  }
}

// Event listeners
if (mobileMenuBtn) {
  mobileMenuBtn.addEventListener("click", () => {
    if (sidebar.classList.contains("mobile-open")) {
      closeMobileSidebar();
    } else {
      openMobileSidebar();
    }
  });
}

if (sidebarCloseBtn) {
  sidebarCloseBtn.addEventListener("click", closeMobileSidebar);
}

if (sidebarOverlay) {
  sidebarOverlay.addEventListener("click", closeMobileSidebar);
}


/* =========================================================
   LOGOUT BUTTON
========================================================= */

if (logoutBtn) {

    logoutBtn.addEventListener(
        "click",
        function() {

            /*
             * Close sidebar first if open.
             */

            closeMobileSidebar();


            if (logoutModal) {

                logoutModal.classList.add(
                    "active"
                );

            }


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


    /*
     * Only restore scrolling if
     * the mobile sidebar is not open.
     */

    if (
        !sidebar ||
        !sidebar.classList.contains(
            "mobile-open"
        )
    ) {

        document.body.style.overflow =
            "";

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
                event.target === logoutModal
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

        if (event.key !== "Escape") {

            return;

        }


        /*
         * Close sidebar first.
         */

        if (
            sidebar &&
            sidebar.classList.contains(
                "mobile-open"
            )
        ) {

            closeMobileSidebar();

        }


        /*
         * Close logout modal.
         */

        if (
            logoutModal &&
            logoutModal.classList.contains(
                "active"
            )
        ) {

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

        /*
         * If screen becomes desktop,
         * force the mobile sidebar closed.
         */

        if (
            window.innerWidth >
            MOBILE_BREAKPOINT
        ) {

            closeMobileSidebar();

        }

    }
);


/* =========================================================
   PAYMENT SEARCH + FILTER
========================================================= */

const paymentSearch =
    document.getElementById(
        "paymentSearch"
    );

const paymentStatusFilter =
    document.getElementById(
        "paymentStatusFilter"
    );

const paymentTable =
    document.getElementById(
        "paymentTable"
    );

const paymentEmpty =
    document.getElementById(
        "paymentEmpty"
    );


/* =========================================================
   FILTER PAYMENTS
========================================================= */

function filterPayments() {

    if (!paymentTable) {

        return;

    }


    const searchValue =
        paymentSearch
            ? paymentSearch.value
                .toLowerCase()
                .trim()
            : "";


    const statusValue =
        paymentStatusFilter
            ? paymentStatusFilter.value
                .toLowerCase()
            : "all";


    const rows =
        paymentTable.querySelectorAll(
            "tbody tr"
        );


    let visibleRows = 0;


    rows.forEach(
        function(row) {

            const rowText =
                row.textContent
                    .toLowerCase();


            const rowStatus =
                (
                    row.getAttribute(
                        "data-status"
                    ) || ""
                )
                .toLowerCase();


            const matchesSearch =
                rowText.includes(
                    searchValue
                );


            const matchesStatus =
                statusValue === "all" ||
                rowStatus === statusValue;


            if (
                matchesSearch &&
                matchesStatus
            ) {

                row.style.display = "";

                visibleRows++;

            } else {

                row.style.display =
                    "none";

            }

        }
    );


    /*
     * Show empty message
     * when nothing matches.
     */

    if (paymentEmpty) {

        paymentEmpty.style.display =
            visibleRows === 0
                ? "block"
                : "none";

    }

}


/* =========================================================
   SEARCH EVENT
========================================================= */

if (paymentSearch) {

    paymentSearch.addEventListener(
        "input",
        filterPayments
    );

}


/* =========================================================
   FILTER EVENT
========================================================= */

if (paymentStatusFilter) {

    paymentStatusFilter.addEventListener(
        "change",
        filterPayments
    );

}


/* =========================================================
   CHART FILTER
========================================================= */

const chartFilter =
    document.getElementById(
        "chartFilter"
    );


if (chartFilter) {

    chartFilter.addEventListener(
        "change",
        function() {

            const selectedPeriod =
                this.value;


            console.log(
                "Selected payment period:",
                selectedPeriod
            );

        }
    );

}


/* =========================================================
   INITIALIZE MOBILE MENU
========================================================= */

if (mobileMenuBtn) {

    mobileMenuBtn.setAttribute(
        "aria-expanded",
        "false"
    );

}


/* =========================================================
   PREVENT ACCIDENTAL SCROLL LOCK
========================================================= */

window.addEventListener(
    "pageshow",
    function() {

        /*
         * When the page is loaded again,
         * make sure everything starts clean.
         */

        if (isMobile()) {

            closeMobileSidebar();

        }

    }
);