// Amount change function
function changeAmount(amount) {
    const input = document.getElementById("amount");
    let current = parseFloat(input.value) || 0;
    current = Math.max(0.01, (current + amount).toFixed(2));
    input.value = current;
}

// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard
        .writeText(text)
        .then(function () {
            showNotification("Copied to clipboard!", "success");
        })
        .catch(function () {
            showNotification("Failed to copy to clipboard", "error");
        });
}

// Show notification function
function showNotification(message, type = "info") {
    const notification = document.createElement("div");
    notification.className = `alert alert-${
        type === "error" ? "danger" : type
    } alert-dismissible fade show notification-toast`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    `;

    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Interface navigation functions with URL parameter updates
function showMainContent() {
    const mainContent = document.getElementById("mainContent");
    const accountInterface = document.getElementById("accountInterface");
    const depositInterface = document.getElementById("depositInterface");
    const withdrawalInterface = document.getElementById("withdrawalInterface");

    if (!mainContent) {
        console.error("Main content element not found");
        return;
    }

    mainContent.style.display = "block";
    if (accountInterface) accountInterface.style.display = "none";
    if (depositInterface) depositInterface.style.display = "none";
    if (withdrawalInterface) withdrawalInterface.style.display = "none";

    // Update URL parameter
    updateURLParameter("interface", "trading");
    updateSidebarActive(".markets-icon");
}

function showAccountInterface() {
    const mainContent = document.getElementById("mainContent");
    const accountInterface = document.getElementById("accountInterface");
    const depositInterface = document.getElementById("depositInterface");
    const withdrawalInterface = document.getElementById("withdrawalInterface");

    if (!accountInterface) {
        console.error("Account interface element not found");
        return;
    }

    if (mainContent) mainContent.style.display = "none";
    accountInterface.style.display = "block";
    if (depositInterface) depositInterface.style.display = "none";
    if (withdrawalInterface) withdrawalInterface.style.display = "none";

    // Update URL parameter
    updateURLParameter("interface", "account");
    updateSidebarActive(".account-icon");
}

function showDepositInterface() {
    const mainContent = document.getElementById("mainContent");
    const accountInterface = document.getElementById("accountInterface");
    const depositInterface = document.getElementById("depositInterface");
    const withdrawalInterface = document.getElementById("withdrawalInterface");

    if (!depositInterface) {
        console.error("Deposit interface element not found");
        return;
    }

    if (mainContent) mainContent.style.display = "none";
    if (accountInterface) accountInterface.style.display = "none";
    depositInterface.style.display = "block";
    if (withdrawalInterface) withdrawalInterface.style.display = "none";

    // Update URL parameter
    updateURLParameter("interface", "deposit");
    updateSidebarActive(".deposit-icon");
}

function showWithdrawalInterface() {
    const mainContent = document.getElementById("mainContent");
    const accountInterface = document.getElementById("accountInterface");
    const depositInterface = document.getElementById("depositInterface");
    const withdrawalInterface = document.getElementById("withdrawalInterface");

    if (!withdrawalInterface) {
        console.error("Withdrawal interface element not found");
        return;
    }

    if (mainContent) mainContent.style.display = "none";
    if (accountInterface) accountInterface.style.display = "none";
    if (depositInterface) depositInterface.style.display = "none";
    withdrawalInterface.style.display = "block";

    // Update URL parameter
    updateURLParameter("interface", "withdrawal");
    updateSidebarActive(".withdrawal-icon");
}

// Function to update URL parameters without page reload
function updateURLParameter(key, value) {
    const url = new URL(window.location.href);
    url.searchParams.set(key, value);
    window.history.pushState({}, "", url);
}

// Function to get URL parameter
function getURLParameter(key) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(key);
}

// Update sidebar active states
function updateSidebarActive(activeClass) {
    document.querySelectorAll(".nav-icon").forEach((icon) => {
        icon.classList.remove("active");
    });
    const activeIcon = document.querySelector(activeClass);
    if (activeIcon) {
        activeIcon.classList.add("active");
    }
}

// Single consolidated DOM initialization
document.addEventListener("DOMContentLoaded", function () {
    // Handle trading tabs specifically (Orders/Pending/History)
    const tradingTabsContainer = document.getElementById("tradeTabs");
    if (tradingTabsContainer) {
        const tradingTabLinks =
            tradingTabsContainer.querySelectorAll(".nav-link");
        const tradingTabPanes = document.querySelectorAll(
            "#openOrders, #history, #summary"
        );

        // Initialize Bootstrap tabs for trading section
        tradingTabLinks.forEach((link) => {
            // Add click event to handle tab switching
            link.addEventListener("click", function (e) {
                e.preventDefault();

                // Hide all trading tab panes first
                tradingTabPanes.forEach((pane) => {
                    pane.classList.remove("show", "active");
                });

                // Remove active class from all trading tab links
                tradingTabLinks.forEach((tabLink) => {
                    tabLink.classList.remove("active");
                });

                // Activate clicked tab link
                this.classList.add("active");

                // Show target pane
                const targetId = this.getAttribute("href").substring(1);
                const targetPane = document.getElementById(targetId);
                if (targetPane) {
                    targetPane.classList.add("show", "active");
                }
            });
        });

        // Ensure only one trading tab is active initially
        let activeFound = false;
        tradingTabPanes.forEach((pane) => {
            if (
                pane.classList.contains("show") &&
                pane.classList.contains("active")
            ) {
                if (activeFound) {
                    // Remove extra active panes
                    pane.classList.remove("show", "active");
                } else {
                    activeFound = true;
                }
            }
        });

        // If no active tab found, activate the first one
        if (
            !activeFound &&
            tradingTabLinks.length > 0 &&
            tradingTabPanes.length > 0
        ) {
            tradingTabLinks[0].classList.add("active");
            tradingTabPanes[0].classList.add("show", "active");
        }
    }

    // Sidebar navigation
    const marketsIcon = document.querySelector(".markets-icon");
    const accountIcon = document.querySelector(".account-icon");
    const depositIcon = document.querySelector(".deposit-icon");
    const withdrawalIcon = document.querySelector(".withdrawal-icon");
    const logoutIcon = document.querySelector(".logout-icon");

    if (marketsIcon) {
        marketsIcon.addEventListener("click", function (e) {
            e.preventDefault();
            showMainContent();
        });
    }

    if (accountIcon) {
        accountIcon.addEventListener("click", function (e) {
            e.preventDefault();
            showAccountInterface();
        });
    }

    if (depositIcon) {
        depositIcon.addEventListener("click", function (e) {
            e.preventDefault();
            showDepositInterface();
        });
    }

    if (withdrawalIcon) {
        withdrawalIcon.addEventListener("click", function (e) {
            e.preventDefault();
            showWithdrawalInterface();
        });
    }

    if (logoutIcon) {
        logoutIcon.addEventListener("click", function (e) {
            e.preventDefault();
            if (confirm("Are you sure you want to logout?")) {
                const logoutForm = document.getElementById("logoutForm");
                if (logoutForm) {
                    logoutForm.submit();
                } else {
                    console.error("Logout form not found!");
                }
            }
        });
    }

    // Back to trading buttons
    document.querySelectorAll(".back-to-trading-btn").forEach((btn) => {
        btn.addEventListener("click", showMainContent);
    });

    // Initialize interface based on URL parameter
    const interfaceParam = getURLParameter("interface");

    // Ensure all interface elements exist
    const interfaces = {
        mainContent: document.getElementById("mainContent"),
        accountInterface: document.getElementById("accountInterface"),
        depositInterface: document.getElementById("depositInterface"),
        withdrawalInterface: document.getElementById("withdrawalInterface"),
    };

    // Ensure interfaces have minimum styling
    Object.values(interfaces).forEach((element) => {
        if (element) {
            element.style.minHeight = "100vh";
            element.style.width = "100%";
        }
    });

    switch (interfaceParam) {
        case "account":
            showAccountInterface();
            break;
        case "deposit":
            showDepositInterface();
            break;
        case "withdrawal":
            showWithdrawalInterface();
            break;
        case "trading":
        default:
            showMainContent();
            break;
    }

    // Initialize current asset highlighting
    highlightCurrentAsset();
});

// Function to highlight the current asset and ensure proper ordering
function highlightCurrentAsset() {
    const currentSymbol = window.currentSymbol || "";
    if (!currentSymbol) return;

    const assetButtons = document.querySelectorAll(".asset-button");
    let currentAssetButton = null;

    assetButtons.forEach((button) => {
        const buttonSymbol = button.getAttribute("data-symbol");
        if (buttonSymbol === currentSymbol) {
            button.classList.add("current-asset");
            currentAssetButton = button;
        } else {
            button.classList.remove("current-asset");
        }
    });

    // Ensure current asset is visible when filters are applied
    if (currentAssetButton && currentAssetButton.style.display === "none") {
        currentAssetButton.style.display = "flex";
        currentAssetButton.style.visibility = "visible";
        currentAssetButton.classList.remove("d-none", "hidden");
        currentAssetButton.classList.add("d-flex");
    }
}
