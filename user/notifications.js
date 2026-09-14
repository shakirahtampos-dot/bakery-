/* =========================================================
   KATES GOODIES
   NOTIFICATIONS PORTAL JAVASCRIPT
========================================================= */

(function () {

    "use strict";


    /* =========================================================
       STORAGE
    ========================================================= */

    const STORAGE_KEY =
        "katesGoodiesNotifications";


    /* =========================================================
       ELEMENTS
    ========================================================= */

    const notificationsList =
        document.getElementById(
            "notificationsList"
        );

    const notificationsEmpty =
        document.getElementById(
            "notificationsEmpty"
        );

    const notificationTotal =
        document.getElementById(
            "notificationTotal"
        );

    const unreadFilterCount =
        document.getElementById(
            "unreadFilterCount"
        );

    const markAllReadButton =
        document.getElementById(
            "markAllRead"
        );

    const filterButtons =
        document.querySelectorAll(
            ".notification-filter"
        );

    const deleteModal =
        document.getElementById(
            "deleteModal"
        );

    const confirmDeleteButton =
        document.getElementById(
            "confirmDelete"
        );


    /* =========================================================
       STATE
    ========================================================= */

    let currentFilter = "all";

    let notificationToDelete = null;


    /* =========================================================
       GET NOTIFICATIONS
    ========================================================= */

    function getNotifications() {

        try {

            const saved =
                localStorage.getItem(
                    STORAGE_KEY
                );


            if (!saved) {
                return [];
            }


            const parsed =
                JSON.parse(saved);


            if (Array.isArray(parsed)) {

                return parsed;

            }

        } catch (error) {

            console.error(
                "Error loading notifications:",
                error
            );

        }


        return [];
    }


    /* =========================================================
       SAVE NOTIFICATIONS
    ========================================================= */

    function saveNotifications(
        notifications
    ) {

        try {

            localStorage.setItem(
                STORAGE_KEY,
                JSON.stringify(
                    notifications
                )
            );

        } catch (error) {

            console.error(
                "Error saving notifications:",
                error
            );

        }
    }


    /* =========================================================
       ICON
    ========================================================= */

    function getNotificationIcon(
        type
    ) {

        switch (type) {

            case "order":

                return "fa-bag-shopping";


            case "promo":

                return "fa-tag";


            case "welcome":

                return "fa-heart";


            case "system":

                return "fa-circle-info";


            default:

                return "fa-bell";

        }
    }


    /* =========================================================
       FORMAT DATE
    ========================================================= */

    function formatNotificationDate(
        dateString
    ) {

        if (!dateString) {
            return "";
        }


        const date =
            new Date(dateString);


        if (
            Number.isNaN(
                date.getTime()
            )
        ) {

            return "";

        }


        const now =
            new Date();


        const difference =
            now.getTime() -
            date.getTime();


        const minute =
            60 * 1000;

        const hour =
            60 * minute;

        const day =
            24 * hour;


        if (difference < minute) {

            return "Just now";

        }


        if (difference < hour) {

            const minutes =
                Math.floor(
                    difference / minute
                );

            return (
                minutes +
                (
                    minutes === 1
                        ? " minute ago"
                        : " minutes ago"
                )
            );

        }


        if (difference < day) {

            const hours =
                Math.floor(
                    difference / hour
                );

            return (
                hours +
                (
                    hours === 1
                        ? " hour ago"
                        : " hours ago"
                )
            );

        }


        if (difference < 7 * day) {

            const days =
                Math.floor(
                    difference / day
                );

            return (
                days +
                (
                    days === 1
                        ? " day ago"
                        : " days ago"
                )
            );

        }


        return date.toLocaleDateString(
            "en-PH",
            {
                month: "short",
                day: "numeric",
                year: "numeric"
            }
        );
    }


    /* =========================================================
       ESCAPE HTML
    ========================================================= */

    function escapeHtml(
        value
    ) {

        return String(value ?? "")
            .replace(
                /&/g,
                "&amp;"
            )
            .replace(
                /</g,
                "&lt;"
            )
            .replace(
                />/g,
                "&gt;"
            )
            .replace(
                /"/g,
                "&quot;"
            )
            .replace(
                /'/g,
                "&#039;"
            );
    }


    /* =========================================================
       FILTER
    ========================================================= */

    function getFilteredNotifications(
        notifications
    ) {

        switch (currentFilter) {

            case "unread":

                return notifications.filter(
                    function (notification) {

                        return notification.read !== true;

                    }
                );


            case "order":

                return notifications.filter(
                    function (notification) {

                        return notification.type === "order";

                    }
                );


            case "promo":

                return notifications.filter(
                    function (notification) {

                        return notification.type === "promo";

                    }
                );


            default:

                return notifications;
        }
    }


    /* =========================================================
       RENDER
    ========================================================= */

    function renderNotifications() {

        if (!notificationsList) {
            return;
        }


        const notifications =
            getNotifications();


        const filtered =
            getFilteredNotifications(
                notifications
            );


        notificationsList.innerHTML =
            "";


        /*
         * Sort newest first.
         */

        filtered.sort(
            function (a, b) {

                return (
                    new Date(
                        b.date || 0
                    ) -
                    new Date(
                        a.date || 0
                    )
                );

            }
        );


        filtered.forEach(
            function (notification) {

                const item =
                    document.createElement(
                        "article"
                    );


                item.className =
                    "notification-item " +
                    (
                        notification.read
                            ? "read"
                            : "unread"
                    ) +
                    " type-" +
                    escapeHtml(
                        notification.type || "system"
                    );


                const unreadDot =
                    notification.read
                        ? ""
                        : `
                            <span
                                class="unread-dot"
                                aria-label="Unread"
                            ></span>
                        `;


                item.innerHTML = `

                    ${unreadDot}

                    <div class="notification-icon">

                        <i
                            class="fa-solid ${getNotificationIcon(
                                notification.type
                            )}"
                            aria-hidden="true"
                        ></i>

                    </div>


                    <div class="notification-content">

                        <div
                            class="notification-content-top"
                        >

                            <h3>
                                ${escapeHtml(
                                    notification.title
                                )}
                            </h3>

                            <span
                                class="notification-date"
                            >
                                ${formatNotificationDate(
                                    notification.date
                                )}
                            </span>

                        </div>


                        <p>
                            ${escapeHtml(
                                notification.message
                            )}
                        </p>

                    </div>


                    <div class="notification-actions">

                        ${
                            notification.read
                                ? `
                                    <button
                                        type="button"
                                        class="notification-action"
                                        data-action="unread"
                                        data-id="${notification.id}"
                                        aria-label="Mark as unread"
                                        title="Mark as unread"
                                    >
                                        <i
                                            class="fa-regular fa-envelope"
                                        ></i>
                                    </button>
                                `
                                : `
                                    <button
                                        type="button"
                                        class="notification-action"
                                        data-action="read"
                                        data-id="${notification.id}"
                                        aria-label="Mark as read"
                                        title="Mark as read"
                                    >
                                        <i
                                            class="fa-solid fa-check"
                                        ></i>
                                    </button>
                                `
                        }


                        <button
                            type="button"
                            class="notification-action delete"
                            data-action="delete"
                            data-id="${notification.id}"
                            aria-label="Delete notification"
                            title="Delete notification"
                        >

                            <i
                                class="fa-solid fa-trash"
                            ></i>

                        </button>

                    </div>

                `;


                notificationsList.appendChild(
                    item
                );

            }
        );


        updateCounters(
            notifications
        );


        if (filtered.length === 0) {

            notificationsList.style.display =
                "none";

            notificationsEmpty.classList.add(
                "show"
            );

        } else {

            notificationsList.style.display =
                "flex";

            notificationsEmpty.classList.remove(
                "show"
            );

        }

    }


    /* =========================================================
       COUNTERS
    ========================================================= */

    function updateCounters(
        notifications
    ) {

        const unread =
            notifications.filter(
                function (notification) {

                    return notification.read !== true;

                }
            ).length;


        if (notificationTotal) {

            notificationTotal.textContent =
                notifications.length;

        }


        if (unreadFilterCount) {

            unreadFilterCount.textContent =
                unread;

        }


        if (
            markAllReadButton
        ) {

            markAllReadButton.disabled =
                unread === 0;

            markAllReadButton.style.opacity =
                unread === 0
                    ? ".55"
                    : "1";

        }

    }


    /* =========================================================
       MARK ONE AS READ / UNREAD
    ========================================================= */

    function toggleRead(
        id,
        readState
    ) {

        const notifications =
            getNotifications();


        const updated =
            notifications.map(
                function (notification) {

                    if (
                        String(notification.id) ===
                        String(id)
                    ) {

                        return {
                            ...notification,
                            read: readState
                        };

                    }


                    return notification;

                }
            );


        saveNotifications(
            updated
        );


        renderNotifications();

    }


    /* =========================================================
       MARK ALL AS READ
    ========================================================= */

    function markAllAsRead() {

        const notifications =
            getNotifications();


        const updated =
            notifications.map(
                function (notification) {

                    return {
                        ...notification,
                        read: true
                    };

                }
            );


        saveNotifications(
            updated
        );


        renderNotifications();

    }


    /* =========================================================
       DELETE MODAL
    ========================================================= */

    function openDeleteModal(
        id
    ) {

        if (!deleteModal) {
            return;
        }


        notificationToDelete =
            id;


        deleteModal.classList.add(
            "is-open"
        );


        deleteModal.setAttribute(
            "aria-hidden",
            "false"
        );


        document.body.classList.add(
            "modal-open"
        );

    }


    function closeDeleteModal() {

        if (!deleteModal) {
            return;
        }


        deleteModal.classList.remove(
            "is-open"
        );


        deleteModal.setAttribute(
            "aria-hidden",
            "true"
        );


        document.body.classList.remove(
            "modal-open"
        );


        notificationToDelete =
            null;

    }


    /* =========================================================
       DELETE CONFIRMATION
    ========================================================= */

    function deleteNotification() {

        if (
            notificationToDelete === null
        ) {
            return;
        }


        const notifications =
            getNotifications();


        const updated =
            notifications.filter(
                function (notification) {

                    return (
                        String(
                            notification.id
                        ) !==
                        String(
                            notificationToDelete
                        )
                    );

                }
            );


        saveNotifications(
            updated
        );


        closeDeleteModal();

        renderNotifications();

    }


    /* =========================================================
       FILTER BUTTONS
    ========================================================= */

    filterButtons.forEach(
        function (button) {

            button.addEventListener(
                "click",
                function () {

                    filterButtons.forEach(
                        function (item) {

                            item.classList.remove(
                                "active"
                            );

                        }
                    );


                    this.classList.add(
                        "active"
                    );


                    currentFilter =
                        this.dataset.filter ||
                        "all";


                    renderNotifications();

                }
            );

        }
    );


    /* =========================================================
       NOTIFICATION ACTIONS
    ========================================================= */

    if (notificationsList) {

        notificationsList.addEventListener(
            "click",
            function (event) {

                const button =
                    event.target.closest(
                        "[data-action]"
                    );


                if (!button) {
                    return;
                }


                const action =
                    button.dataset.action;


                const id =
                    button.dataset.id;


                if (
                    action === "read"
                ) {

                    toggleRead(
                        id,
                        true
                    );

                }


                else if (
                    action === "unread"
                ) {

                    toggleRead(
                        id,
                        false
                    );

                }


                else if (
                    action === "delete"
                ) {

                    openDeleteModal(
                        id
                    );

                }

            }
        );

    }


    /* =========================================================
       MARK ALL BUTTON
    ========================================================= */

    if (markAllReadButton) {

        markAllReadButton.addEventListener(
            "click",
            markAllAsRead
        );

    }


    /* =========================================================
       CONFIRM DELETE
    ========================================================= */

    if (confirmDeleteButton) {

        confirmDeleteButton.addEventListener(
            "click",
            deleteNotification
        );

    }


    /* =========================================================
       CLOSE DELETE MODAL
    ========================================================= */

    document.querySelectorAll(
        "[data-close-delete]"
    ).forEach(
        function (button) {

            button.addEventListener(
                "click",
                closeDeleteModal
            );

        }
    );


    /* =========================================================
       ESCAPE
    ========================================================= */

    document.addEventListener(
        "keydown",
        function (event) {

            if (
                event.key === "Escape"
            ) {

                closeDeleteModal();

            }

        }
    );


    /* =========================================================
       CROSS-TAB UPDATE
    ========================================================= */

    window.addEventListener(
        "storage",
        function (event) {

            if (
                event.key ===
                STORAGE_KEY
            ) {

                renderNotifications();

            }

        }
    );


    /* =========================================================
       INITIAL RENDER
    ========================================================= */

    renderNotifications();

})();