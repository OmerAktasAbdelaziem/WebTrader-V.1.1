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
function getAllInterfaces() {
    return {
        mainContent: document.getElementById("mainContent"),
        accountInterface: document.getElementById("accountInterface"),
        depositInterface: document.getElementById("depositInterface"),
        withdrawalInterface: document.getElementById("withdrawalInterface"),
    };
}

function hideAllInterfaces() {
    const interfaces = getAllInterfaces();
    Object.values(interfaces).forEach((element) => {
        if (element) {
            element.style.display = "none";
            element.style.visibility = "hidden";
        }
    });
}

function showMainContent() {
    console.log("showMainContent() called");

    // Hide all interfaces first
    hideAllInterfaces();

    const interfaces = getAllInterfaces();

    if (!interfaces.mainContent) {
        console.error("Main content element not found");
        return;
    }

    // Show main content
    interfaces.mainContent.style.display = "block";
    interfaces.mainContent.style.visibility = "visible";

    // Update URL parameter and sidebar
    updateURLParameter("interface", "trading");
    updateSidebarActive(".markets-icon");

    console.log("showMainContent() completed - main content shown");
}

function showAccountInterface() {
    console.log("showAccountInterface() called");

    // Hide all interfaces first
    hideAllInterfaces();

    const interfaces = getAllInterfaces();

    if (!interfaces.accountInterface) {
        console.error("Account interface element not found");
        return;
    }

    // Show account interface
    interfaces.accountInterface.style.display = "block";
    interfaces.accountInterface.style.visibility = "visible";

    // Update URL parameter and sidebar
    updateURLParameter("interface", "account");
    updateSidebarActive(".account-icon");

    console.log("showAccountInterface() completed - account interface shown");
}

function showDepositInterface() {
    console.log("showDepositInterface() called");

    // Hide all interfaces first
    hideAllInterfaces();

    const interfaces = getAllInterfaces();

    if (!interfaces.depositInterface) {
        console.error("Deposit interface element not found");
        return;
    }

    // Show deposit interface
    interfaces.depositInterface.style.display = "block";
    interfaces.depositInterface.style.visibility = "visible";

    // Update URL parameter and sidebar
    updateURLParameter("interface", "deposit");
    updateSidebarActive(".deposit-icon");

    console.log("showDepositInterface() completed - deposit interface shown");
}

function showWithdrawalInterface() {
    console.log("showWithdrawalInterface() called");

    // Hide all interfaces first
    hideAllInterfaces();

    const interfaces = getAllInterfaces();

    if (!interfaces.withdrawalInterface) {
        console.error("Withdrawal interface element not found");
        return;
    }

    // Show withdrawal interface
    interfaces.withdrawalInterface.style.display = "block";
    interfaces.withdrawalInterface.style.visibility = "visible";

    // Update URL parameter and sidebar
    updateURLParameter("interface", "withdrawal");
    updateSidebarActive(".withdrawal-icon");

    console.log(
        "showWithdrawalInterface() completed - withdrawal interface shown"
    );
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
    console.log("updateSidebarActive() called with class:", activeClass);

    // Remove active class from all nav icons
    document.querySelectorAll(".nav-icon").forEach((icon) => {
        icon.classList.remove("active");
    });

    // Add active class to the specified icon
    const activeIcon = document.querySelector(activeClass);
    if (activeIcon) {
        activeIcon.classList.add("active");
        console.log("Active class added to:", activeClass);
    } else {
        console.error("Active icon not found for class:", activeClass);
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

    // Country and Bank selection functionality
    const countrySelect = document.getElementById("country_select");
    const bankSelect = document.getElementById("bank_select");
    const bankDetailsDisplay = document.getElementById("bankDetailsDisplay");

    // Banks data for filtering - get from global variable set in HTML
    const banksData = window.banksData || [];

    if (countrySelect && bankSelect) {
        countrySelect.addEventListener("change", function () {
            const selectedCountry = this.value;

            // Clear and reset bank select
            bankSelect.innerHTML = '<option value="">Choose a bank...</option>';
            bankSelect.disabled = !selectedCountry;
            bankDetailsDisplay.style.display = "none";

            if (selectedCountry) {
                // Filter banks by selected country
                const countryBanks = banksData.filter(
                    (bank) => bank.country === selectedCountry
                );

                countryBanks.forEach((bank) => {
                    const option = document.createElement("option");
                    option.value = bank.id;
                    option.textContent = bank.bank_name || bank.name;

                    // Set all data attributes for bank details
                    option.setAttribute(
                        "data-bank-name",
                        bank.bank_name || bank.name || ""
                    );
                    option.setAttribute(
                        "data-account-name",
                        bank.account_name || bank.beneficiary_name || ""
                    );
                    option.setAttribute(
                        "data-account-number",
                        bank.account_number || ""
                    );
                    option.setAttribute("data-iban", bank.iban || "");
                    option.setAttribute(
                        "data-swift-code",
                        bank.swift_code || ""
                    );
                    option.setAttribute(
                        "data-aba-routing",
                        bank.aba_routing_number || bank.routing_number || ""
                    );
                    option.setAttribute(
                        "data-beneficiary-address",
                        bank.beneficiary_address || bank.address || ""
                    );
                    option.setAttribute(
                        "data-beneficiary-country",
                        bank.beneficiary_country || bank.country || ""
                    );
                    option.setAttribute(
                        "data-bank-address",
                        bank.bank_address || bank.address || ""
                    );

                    bankSelect.appendChild(option);
                });
            }
        });

        bankSelect.addEventListener("change", function () {
            const selectedOption = this.options[this.selectedIndex];

            if (selectedOption.value) {
                // Get bank details from data attributes
                const bankName = selectedOption.getAttribute("data-bank-name");
                const accountName =
                    selectedOption.getAttribute("data-account-name");
                const accountNumber = selectedOption.getAttribute(
                    "data-account-number"
                );
                const iban = selectedOption.getAttribute("data-iban");
                const swiftCode =
                    selectedOption.getAttribute("data-swift-code");
                const abaRouting =
                    selectedOption.getAttribute("data-aba-routing");
                const beneficiaryAddress = selectedOption.getAttribute(
                    "data-beneficiary-address"
                );
                const beneficiaryCountry = selectedOption.getAttribute(
                    "data-beneficiary-country"
                );
                const bankAddress =
                    selectedOption.getAttribute("data-bank-address");

                // Update display elements
                document.getElementById("displayBankName").textContent =
                    bankName || "N/A";
                document.getElementById("displayAccountName").textContent =
                    accountName || "N/A";
                document.getElementById("displayAccountNumber").textContent =
                    accountNumber || "N/A";
                document.getElementById("displayIban").textContent =
                    iban || "N/A";
                document.getElementById("displaySwiftCode").textContent =
                    swiftCode || "N/A";
                document.getElementById("displayAbaRouting").textContent =
                    abaRouting || "N/A";
                document.getElementById(
                    "displayBeneficiaryAddress"
                ).textContent = beneficiaryAddress || "N/A";
                document.getElementById(
                    "displayBeneficiaryCountry"
                ).textContent = beneficiaryCountry || "N/A";
                document.getElementById("displayBankAddress").textContent =
                    bankAddress || "N/A";

                // Show/hide rows based on data availability
                const toggleRow = (rowId, value) => {
                    const row = document.getElementById(rowId);
                    if (row) {
                        row.style.display =
                            value && value.trim() !== "" && value !== "N/A"
                                ? "flex"
                                : "none";
                    }
                };

                toggleRow("ibanRow", iban);
                toggleRow("swiftCodeRow", swiftCode);
                toggleRow("abaRoutingRow", abaRouting);
                toggleRow("beneficiaryAddressRow", beneficiaryAddress);
                toggleRow("beneficiaryCountryRow", beneficiaryCountry);
                toggleRow("bankAddressRow", bankAddress);

                // Show the bank details card
                bankDetailsDisplay.style.display = "block";
            } else {
                // Hide the bank details card
                bankDetailsDisplay.style.display = "none";
            }
        });
    }

    // Crypto selection functionality
    const cryptoSelect = document.getElementById("crypto_type_select");
    const usdtAddressDisplay = document.getElementById("usdtAddressDisplay");

    if (cryptoSelect) {
        cryptoSelect.addEventListener("change", function () {
            if (this.value === "USDT") {
                usdtAddressDisplay.style.display = "block";
            } else {
                usdtAddressDisplay.style.display = "none";
            }
        });
    }

    // File upload preview functionality
    const fileInputs = document.querySelectorAll(".file-input");
    fileInputs.forEach((input) => {
        input.addEventListener("change", function () {
            const file = this.files[0];
            const uploadContent = this.nextElementSibling;

            if (file) {
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB

                uploadContent.innerHTML =
                    '<i class="bi bi-file-earmark-check" style="color: #28a745;"></i>' +
                    '<p style="color: #28a745;">File Selected: ' +
                    fileName +
                    "</p>" +
                    "<small>Size: " +
                    fileSize +
                    " MB</small>";
            } else {
                // Reset to original content
                uploadContent.innerHTML =
                    '<i class="bi bi-cloud-upload"></i>' +
                    "<p>Click to upload or drag and drop</p>" +
                    "<small>PDF, PNG, JPG, JPEG (Max 5MB)</small>";
            }
        });
    });

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

    // Asset buttons initialization
    const assetButtons = document.querySelectorAll(".asset-button");
    assetButtons.forEach((button) => {
        // Add click event that updates prices before navigation
        button.addEventListener("click", function (e) {
            const assetId = this.getAttribute("data-asset-id");
            const assetSymbol = this.getAttribute("data-symbol");

            // Find the bid and ask price spans within this button
            const bidPriceSpan = this.querySelector(".bid_price");
            const askPriceSpan = this.querySelector(".ask_price");

            if (bidPriceSpan && askPriceSpan) {
                const bidPrice = bidPriceSpan.textContent.trim();
                const askPrice = askPriceSpan.textContent.trim();

                // Update prices immediately for better UX
                updateAssetPrices(assetId, assetSymbol, bidPrice, askPrice);
            }
        });
    });

    // Profile editing functionality
    const editBtn = document.getElementById("editProfileBtn");
    const profileForm = document.getElementById("profileForm");
    const profileDisplay = document.getElementById("profileDisplay");
    const cancelBtn = document.querySelector(".cancel-edit-btn");

    if (editBtn) {
        editBtn.addEventListener("click", function () {
            profileDisplay.style.display = "none";
            profileForm.style.display = "block";
            editBtn.style.display = "none";
        });
    }

    if (cancelBtn) {
        cancelBtn.addEventListener("click", function () {
            profileForm.style.display = "none";
            profileDisplay.style.display = "block";
            editBtn.style.display = "inline-block";
        });
    }

    // Handle form submission
    if (profileForm) {
        profileForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            fetch(this.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        showNotification(
                            "Profile updated successfully!",
                            "success"
                        );
                        // Refresh the page to show updated data
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        showNotification(
                            "Error updating profile: " +
                                (data.message || "Unknown error"),
                            "error"
                        );
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    showNotification("Error updating profile", "error");
                });
        });
    }

    // Refresh functionality
    const refreshBtn = document.getElementById("refreshBtn");
    if (refreshBtn) {
        refreshBtn.addEventListener("click", function () {
            // Add loading state
            const icon = this.querySelector("i");
            icon.classList.add("fa-spin");

            // Simulate refresh - in a real app, this would reload the data
            setTimeout(() => {
                location.reload();
            }, 500);
        });
    }

    // Tab switching functionality for deposit tabs
    const depositTabs = document.querySelectorAll("#depositTabs .nav-link");
    depositTabs.forEach((tab) => {
        tab.addEventListener("click", function () {
            // Remove active class from all tabs
            depositTabs.forEach((t) => t.classList.remove("active"));
            // Add active class to clicked tab
            this.classList.add("active");
        });
    });

    // Initialize interface based on URL parameter
    const interfaceParam = getURLParameter("interface");

    // Ensure all interface elements exist and log their status
    const interfaces = getAllInterfaces();

    console.log("Interface elements found:", {
        mainContent: !!interfaces.mainContent,
        accountInterface: !!interfaces.accountInterface,
        depositInterface: !!interfaces.depositInterface,
        withdrawalInterface: !!interfaces.withdrawalInterface,
    });

    // Ensure interfaces have proper styling and are initially hidden
    Object.values(interfaces).forEach((element) => {
        if (element) {
            element.style.minHeight = "100vh";
            element.style.width = "100%";
            element.style.position = "relative";
            // Initially hide all interfaces
            element.style.display = "none";
            element.style.visibility = "hidden";
        }
    });

    // Show the correct interface based on URL parameter
    console.log(
        "Initializing interface based on URL parameter:",
        interfaceParam
    );

    switch (interfaceParam) {
        case "account":
            console.log("URL parameter indicates account interface");
            showAccountInterface();
            break;
        case "deposit":
            console.log("URL parameter indicates deposit interface");
            showDepositInterface();
            break;
        case "withdrawal":
            console.log("URL parameter indicates withdrawal interface");
            showWithdrawalInterface();
            break;
        case "trading":
        default:
            console.log(
                "URL parameter indicates trading interface (or default)"
            );
            showMainContent();
            break;
    }

    // Initialize current asset highlighting
    highlightCurrentAsset();

    // Asset search functionality
    document
        .getElementById("assetSearch")
        .addEventListener("input", function () {
            filterAssets();
        });

    // Category filter functionality
    document
        .getElementById("categoryFilter")
        .addEventListener("change", function () {
            filterAssets();
        });

    // Also listen for keyup events on search for better responsiveness
    document
        .getElementById("assetSearch")
        .addEventListener("keyup", function () {
            filterAssets();
        });

    // Favorites functionality
    document
        .getElementById("showFavouritesBtn")
        .addEventListener("click", function () {
            const btn = this;
            if (btn.classList.contains("active")) {
                btn.classList.remove("active");
                btn.style.backgroundColor = "#23272f";
                showAllAssets();
            } else {
                btn.classList.add("active");
                btn.style.backgroundColor = "#4f8cff";
                showOnlyFavorites();
            }
        });

    // Context menu for favorites
    document
        .getElementById("addToFavouriteBtn")
        .addEventListener("click", function () {
            const assetId = this.getAttribute("data-asset-id");
            toggleFavorite(assetId, "add");
        });

    document
        .getElementById("removeFromFavouriteBtn")
        .addEventListener("click", function () {
            const assetId = this.getAttribute("data-asset-id");
            toggleFavorite(assetId, "remove");
        });

    // Buy and Sell button functionality
    document.getElementById("buyBtn").addEventListener("click", function () {
        const assetId = document.getElementById("selectedAssetId").value;
        if (!assetId || assetId === "null" || assetId === "") {
            showNotification("Please select a valid asset first", "error");
            return;
        }
        document.getElementById("orderType").value = "1"; // 1 = buy
        document.getElementById("orderForm").submit();
    });

    document.getElementById("sellBtn").addEventListener("click", function () {
        const assetId = document.getElementById("selectedAssetId").value;
        if (!assetId || assetId === "null" || assetId === "") {
            showNotification("Please select a valid asset first", "error");
            return;
        }
        document.getElementById("orderType").value = "2"; // 2 = sell
        document.getElementById("orderForm").submit();
    });
});

// Filter assets function
function filterAssets() {
    const searchTerm = document
        .getElementById("assetSearch")
        .value.toLowerCase()
        .trim();
    const selectedCategory = document
        .getElementById("categoryFilter")
        .value.trim();
    const assetButtons = document.querySelectorAll(".asset-button");
    const currentSymbol = window.currentSymbol || "";

    let visibleCount = 0;
    let hiddenCount = 0;

    assetButtons.forEach((button, index) => {
        const assetName = button.getAttribute("data-name") || "";
        const assetSymbol = button.getAttribute("data-symbol") || "";
        const assetCategory = button.getAttribute("data-category") || "";
        const isCurrentAsset = assetSymbol === currentSymbol;

        // Convert to lowercase for comparison
        const nameMatch = assetName.toLowerCase().includes(searchTerm);
        const symbolMatch = assetSymbol.toLowerCase().includes(searchTerm);
        const categoryMatch =
            selectedCategory === "" || assetCategory === selectedCategory;

        // Search matches if search term is empty OR name/symbol contains search term
        const matchesSearch = searchTerm === "" || nameMatch || symbolMatch;
        const shouldShow = matchesSearch && categoryMatch;

        // Always show the current asset, even if it doesn't match filters
        const forceShow = isCurrentAsset && currentSymbol !== "";

        // Use multiple methods to ensure visibility changes work
        if (shouldShow || forceShow) {
            // Show the asset - restore Bootstrap row display
            button.style.display = "flex";
            button.style.visibility = "visible";
            button.classList.remove("d-none", "hidden");
            button.classList.add("d-flex");
            visibleCount++;
        } else {
            // Hide the asset
            button.style.display = "none";
            button.style.visibility = "hidden";
            button.classList.add("d-none", "hidden");
            button.classList.remove("d-flex");
            hiddenCount++;
        }
    });

    // Ensure current asset highlighting is maintained
    highlightCurrentAsset();
}

// Show all assets
function showAllAssets() {
    const assetButtons = document.querySelectorAll(".asset-button");

    assetButtons.forEach((button) => {
        // Use multiple methods to ensure visibility - restore Bootstrap row display
        button.style.display = "flex";
        button.style.visibility = "visible";
        button.classList.remove("d-none", "hidden");
        button.classList.add("d-flex");
    });

    // Reset filters
    document.getElementById("assetSearch").value = "";
    document.getElementById("categoryFilter").value = "";

    // Ensure current asset highlighting is maintained
    highlightCurrentAsset();
}

// Show only favorite assets
function showOnlyFavorites() {
    const assetButtons = document.querySelectorAll(".asset-button");
    const currentSymbol = window.currentSymbol || "";
    let favoriteCount = 0;

    assetButtons.forEach((button) => {
        const hasStar = button.querySelector(".star-icon");
        const assetSymbol = button.getAttribute("data-symbol") || "";
        const isCurrentAsset = assetSymbol === currentSymbol;

        // Show if it's a favorite OR if it's the current asset
        if (hasStar || isCurrentAsset) {
            // Show the asset - restore Bootstrap row display
            button.style.display = "flex";
            button.style.visibility = "visible";
            button.classList.remove("d-none", "hidden");
            button.classList.add("d-flex");
            favoriteCount++;
        } else {
            // Hide the asset
            button.style.display = "none";
            button.style.visibility = "hidden";
            button.classList.add("d-none", "hidden");
            button.classList.remove("d-flex");
        }
    });

    // Clear search and category filters
    document.getElementById("assetSearch").value = "";
    document.getElementById("categoryFilter").value = "";

    // Ensure current asset highlighting is maintained
    highlightCurrentAsset();
}

// Toggle favorite function
function toggleFavorite(assetId, action) {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    const toggleRoute = document.body.getAttribute(
        "data-toggle-favourite-route"
    );

    fetch(toggleRoute, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({
            asset_id: assetId,
            action: action,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Update the UI
                const assetButton = document.querySelector(
                    `[data-asset-id="${assetId}"]`
                );
                if (assetButton) {
                    const starIcon = assetButton.querySelector(".star-icon");
                    if (action === "add" && !starIcon) {
                        // Add star icon
                        const nameSpan = assetButton.querySelector(".name");
                        nameSpan.innerHTML +=
                            '<span class="star-icon" style="color: gold; margin-left: 6px;">★</span>';
                    } else if (action === "remove" && starIcon) {
                        // Remove star icon
                        starIcon.remove();
                    }
                }
                showNotification(data.message, "success");
            } else {
                showNotification(
                    data.message || "Error updating favorites",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showNotification("Error updating favorites", "error");
        });

    // Hide context menu
    document.getElementById("customContextMenu").style.display = "none";
}

// Edit order function
function editOrder(orderId, stopLoss, takeProfit) {
    document.getElementById("editOrderId").value = orderId;
    document.getElementById("edit_stop_loss").value = stopLoss || "";
    document.getElementById("edit_take_profit").value = takeProfit || "";

    // Set the form action with the correct order ID
    const form = document.getElementById("editOrderForm");
    const updateOrderRoute =
        window.updateOrderRoute || "/order/update/__ORDER_ID__";
    form.action = updateOrderRoute.replace("__ORDER_ID__", orderId);
}

// Context menu functionality
function showContextMenu(event, assetId) {
    event.preventDefault();
    event.stopPropagation();

    const menu = document.getElementById("customContextMenu");
    const addBtn = document.getElementById("addToFavouriteBtn");
    const removeBtn = document.getElementById("removeFromFavouriteBtn");

    // Update asset ID for both buttons
    addBtn.setAttribute("data-asset-id", assetId);
    removeBtn.setAttribute("data-asset-id", assetId);

    // Check if asset is already in favorites
    const assetButton = document.querySelector(`[data-asset-id="${assetId}"]`);
    const isFavorite = assetButton.querySelector(".star-icon") !== null;

    // Show/hide appropriate buttons
    if (isFavorite) {
        addBtn.style.display = "none";
        removeBtn.style.display = "block";
    } else {
        addBtn.style.display = "block";
        removeBtn.style.display = "none";
    }

    // Position and show menu
    menu.style.left = event.pageX + "px";
    menu.style.top = event.pageY + "px";
    menu.style.display = "block";

    // Hide menu when clicking elsewhere
    document.addEventListener("click", hideContextMenu);
}

function hideContextMenu() {
    document.getElementById("customContextMenu").style.display = "none";
    document.removeEventListener("click", hideContextMenu);
}

// Function to update form fields and displayed prices when asset is selected
function updateAssetPrices(assetId, assetSymbol, bidPrice, askPrice) {
    // Update hidden form fields
    document.getElementById("selectedAssetId").value = assetId;
    document.getElementById("selectedAssetSymbol").value = assetSymbol;
    document.getElementById("currentBidPrice").value = bidPrice;
    document.getElementById("currentAskPrice").value = askPrice;
    document.getElementById("currentChartSymbol").value = assetSymbol;

    // Update displayed prices on buy/sell buttons
    document.getElementById("displayBidPrice").textContent =
        parseFloat(bidPrice).toFixed(4);
    document.getElementById("displayAskPrice").textContent =
        parseFloat(askPrice).toFixed(4);

    // Update global asset ID for JavaScript
    window.assetId = assetId;
}

// Note: Asset button initialization is now handled in the main DOMContentLoaded listener above

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

// Note: All functionality has been consolidated into the main DOMContentLoaded listener above

// Global test functions for debugging navigation
window.testNavigation = function () {
    console.log("=== NAVIGATION TEST ===");

    const interfaces = getAllInterfaces();
    console.log("Interface elements:", {
        mainContent: !!interfaces.mainContent,
        accountInterface: !!interfaces.accountInterface,
        depositInterface: !!interfaces.depositInterface,
        withdrawalInterface: !!interfaces.withdrawalInterface,
    });

    const icons = {
        markets: document.querySelector(".markets-icon"),
        account: document.querySelector(".account-icon"),
        deposit: document.querySelector(".deposit-icon"),
        withdrawal: document.querySelector(".withdrawal-icon"),
    };
    console.log("Navigation icons:", {
        markets: !!icons.markets,
        account: !!icons.account,
        deposit: !!icons.deposit,
        withdrawal: !!icons.withdrawal,
    });

    return { interfaces, icons };
};

window.testShowAccount = function () {
    console.log("=== MANUAL TEST: Showing Account Interface ===");
    showAccountInterface();
};

window.testShowDeposit = function () {
    console.log("=== MANUAL TEST: Showing Deposit Interface ===");
    showDepositInterface();
};

window.testShowWithdrawal = function () {
    console.log("=== MANUAL TEST: Showing Withdrawal Interface ===");
    showWithdrawalInterface();
};

window.testShowMain = function () {
    console.log("=== MANUAL TEST: Showing Main Content ===");
    showMainContent();
};
