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

// ============= ADDITIONAL UTILITY FUNCTIONS FROM WEBTRADER.JS =============

// Function to guess category based on asset symbol (for auto-fixing categories)
function guessAssetCategory(symbol) {
    symbol = symbol.toUpperCase();

    // Forex pairs (6 characters, currency pairs)
    if (symbol.length === 6 && /^[A-Z]{6}$/.test(symbol)) {
        const forexPairs = [
            "EUR",
            "USD",
            "GBP",
            "JPY",
            "AUD",
            "CAD",
            "CHF",
            "NZD",
            "SEK",
            "NOK",
            "DKK",
        ];
        const first3 = symbol.substring(0, 3);
        const last3 = symbol.substring(3, 6);
        if (forexPairs.includes(first3) && forexPairs.includes(last3)) {
            return "Forex";
        }
    }

    // Crypto symbols
    const cryptoSymbols = [
        "BTC",
        "ETH",
        "ADA",
        "DOT",
        "LINK",
        "XRP",
        "LTC",
        "BCH",
        "DOGE",
        "MATIC",
        "AVAX",
        "SOL",
    ];
    if (cryptoSymbols.some((crypto) => symbol.includes(crypto))) {
        return "Crypto";
    }

    // Commodities
    const commoditySymbols = [
        "XAU",
        "XAG",
        "OIL",
        "GOLD",
        "SILVER",
        "CRUDE",
        "BRENT",
        "NATGAS",
    ];
    if (commoditySymbols.some((commodity) => symbol.includes(commodity))) {
        return "Commodity";
    }

    // Indices
    const indexSymbols = [
        "SPX",
        "NDX",
        "DOW",
        "FTSE",
        "DAX",
        "NIKKEI",
        "SP500",
        "NASDAQ",
        "DJI",
    ];
    if (indexSymbols.some((index) => symbol.includes(index))) {
        return "Indx";
    }

    // Default fallback
    return "Stocks";
}

// Function to auto-fix asset categories on page load
function autoFixAssetCategories() {
    const assetButtons = document.querySelectorAll(".asset-button");
    let fixedCount = 0;

    assetButtons.forEach((button) => {
        const symbol = button.getAttribute("data-symbol");
        let category = button.getAttribute("data-category") || "";

        // If category is empty, guess it and fix it
        if (!category) {
            category = guessAssetCategory(symbol);
            button.setAttribute("data-category", category);
            fixedCount++;
        }
    });

    // Only log if categories were actually fixed
    if (fixedCount > 0) {
        console.log(`Auto-fixed ${fixedCount} asset categories on page load`);
    }
}

// Function to update order form prices
function updateOrderFormPrices() {
    if (!window.currentSymbol) return;

    const assetButton = document.querySelector(
        `[data-symbol="${window.currentSymbol}"]`
    );
    if (assetButton) {
        const bidPriceSpan = assetButton.querySelector(".bid_price");
        const askPriceSpan = assetButton.querySelector(".ask_price");

        if (bidPriceSpan && askPriceSpan) {
            const bidPrice = bidPriceSpan.textContent.trim();
            const askPrice = askPriceSpan.textContent.trim();
            const assetId = assetButton.getAttribute("data-asset-id");

            updateAssetPrices(
                assetId,
                window.currentSymbol,
                bidPrice,
                askPrice
            );
        }
    }
}

// Initialize price updates (if needed)
function initializePriceUpdates() {
    // Auto-fix categories when page loads
    autoFixAssetCategories();

    // Update order form prices
    updateOrderFormPrices();

    // Set up periodic price updates if needed
    // setInterval(updateOrderFormPrices, 5000); // Update every 5 seconds
}

// Banking and transaction functions
function refreshTransactions() {
    showNotification("Refreshing transactions...", "info");
    setTimeout(() => {
        location.reload();
    }, 500);
}

function refreshWithdrawalHistory() {
    showNotification("Refreshing withdrawal history...", "info");
    setTimeout(() => {
        location.reload();
    }, 500);
}

function refreshDepositTransactions() {
    showNotification("Refreshing deposit transactions...", "info");
    setTimeout(() => {
        location.reload();
    }, 500);
}

// Form submission handlers
function submitWithdrawalForm(event) {
    event.preventDefault();
    const form = event.target;

    // Basic validation
    const amount = form.querySelector('[name="amount"]');
    if (amount && parseFloat(amount.value) <= 0) {
        showNotification("Please enter a valid withdrawal amount", "error");
        return;
    }

    // Additional validation for withdrawal
    const accountHolder = form.querySelector('[name="account_holder"]');
    if (accountHolder && !accountHolder.value.trim()) {
        showNotification("Please enter account holder name", "error");
        return;
    }

    // Submit the form
    form.submit();
}

function submitDepositForm(event) {
    event.preventDefault();
    const form = event.target;

    // Basic validation
    const amount = form.querySelector('[name="amount"]');
    if (amount && parseFloat(amount.value) <= 0) {
        showNotification("Please enter a valid deposit amount", "error");
        return;
    }

    // Additional validation for deposit
    const paymentMethod = form.querySelector('[name="payment_method"]');
    if (paymentMethod && paymentMethod.value === "bank_transfer") {
        const receipt = form.querySelector('[name="receipt"]');
        if (receipt && !receipt.files.length) {
            showNotification(
                "Please upload a receipt for bank transfer",
                "error"
            );
            return;
        }
    }

    // Submit the form
    form.submit();
}

// Enhanced order form functions
function initializeOrderForm() {
    // Amount adjustment buttons
    const minusBtn = document.querySelector(".btnminus");
    const plusBtn = document.querySelector(".btnplus");
    if (minusBtn) minusBtn.addEventListener("click", () => changeAmount(-0.01));
    if (plusBtn) plusBtn.addEventListener("click", () => changeAmount(0.01));
}

function submitOrder(type) {
    const assetId = document.getElementById("selectedAssetId").value;

    if (!assetId || assetId === "null" || assetId === "") {
        showNotification("Please select a valid asset first", "error");
        return;
    }

    // Set order type
    document.getElementById("orderType").value = type;

    // Submit the form
    const orderForm = document.getElementById("orderForm");
    if (orderForm) {
        orderForm.submit();
    }
}

// Interface navigation functions with URL parameter updates
function getAllInterfaces() {
    return {
        mainContent: document.getElementById("mainContent"),
        accountInterface: document.getElementById("accountInterface"),
        depositInterface: document.getElementById("depositInterface"),
        withdrawalInterface: document.getElementById("withdrawalInterface"),
        chatInterface: document.getElementById("chatInterface"),
    };
}

function hideAllInterfaces() {
    const interfaces = getAllInterfaces();
    Object.values(interfaces).forEach((element) => {
        if (element) {
            element.style.display = "none";
            element.style.visibility = "hidden";
            element.style.opacity = "0";
        }
    });

    // Only close notification popup if it's currently open (don't force hide it)
    const notificationPopup = document.getElementById("notificationPopup");
    if (notificationPopup && notificationPopup.classList.contains("show")) {
        closeNotificationPopup();
    }
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

    // Show main content with forceful styling to override any CSS conflicts
    console.log("Main content element:", interfaces.mainContent);
    console.log(
        "Before styling - display:",
        interfaces.mainContent.style.display,
        "visibility:",
        interfaces.mainContent.style.visibility
    );

    // Use setProperty with !important to override any CSS rules
    interfaces.mainContent.style.setProperty("display", "block", "important");
    interfaces.mainContent.style.setProperty(
        "visibility",
        "visible",
        "important"
    );
    interfaces.mainContent.style.setProperty("opacity", "1", "important");
    interfaces.mainContent.style.setProperty("z-index", "1", "important");
    interfaces.mainContent.style.setProperty(
        "position",
        "relative",
        "important"
    );
    interfaces.mainContent.style.setProperty("width", "100%", "important");
    interfaces.mainContent.style.setProperty(
        "min-height",
        "100vh",
        "important"
    );

    console.log(
        "After styling - display:",
        interfaces.mainContent.style.display,
        "visibility:",
        interfaces.mainContent.style.visibility
    );

    // Update URL parameter and sidebar
    updateURLParameter("interface", "trading");
    updateSidebarActive(".markets-icon");

    // Initialize notification popup for this interface
    initializeNotificationPopup();
    setTimeout(() => {
        initializeNotificationPopup();
    }, 250);

    console.log(
        "showMainContent() completed - main content should be visible now"
    );
}

function showAccountInterface() {
    console.log("showAccountInterface() called");

    // Fix DOM structure first
    fixInterfaceStructure();

    // Hide all interfaces first
    hideAllInterfaces();

    const interfaces = getAllInterfaces();

    if (!interfaces.accountInterface) {
        console.error("Account interface element not found");
        return;
    }

    // Show account interface with forceful styling to override any CSS conflicts
    console.log("Account interface element:", interfaces.accountInterface);
    console.log(
        "Before styling - display:",
        interfaces.accountInterface.style.display,
        "visibility:",
        interfaces.accountInterface.style.visibility
    );

    // Use setProperty with !important to override any CSS rules
    interfaces.accountInterface.style.setProperty(
        "display",
        "block",
        "important"
    );
    interfaces.accountInterface.style.setProperty(
        "visibility",
        "visible",
        "important"
    );
    interfaces.accountInterface.style.setProperty("opacity", "1", "important");
    interfaces.accountInterface.style.setProperty(
        "z-index",
        "9999",
        "important"
    );
    interfaces.accountInterface.style.setProperty(
        "position",
        "fixed",
        "important"
    );
    interfaces.accountInterface.style.setProperty("top", "0", "important");
    interfaces.accountInterface.style.setProperty("left", "0", "important");
    interfaces.accountInterface.style.setProperty("width", "100%", "important");
    interfaces.accountInterface.style.setProperty(
        "height",
        "100vh",
        "important"
    );
    interfaces.accountInterface.style.setProperty(
        "background",
        "linear-gradient(135deg, #0a0e1a 0%, #1a1f2e 100%)",
        "important"
    );

    console.log(
        "After styling - display:",
        interfaces.accountInterface.style.display,
        "visibility:",
        interfaces.accountInterface.style.visibility
    );

    // Update URL parameter and sidebar
    updateURLParameter("interface", "account");
    updateSidebarActive(".account-icon");

    // Initialize notification popup for this interface
    initializeNotificationPopup();
    setTimeout(() => {
        initializeNotificationPopup();
    }, 250);

    console.log(
        "showAccountInterface() completed - account interface should be visible now"
    );

    // Debug visibility
    debugInterfaceVisibility("accountInterface");
}

function showDepositInterface() {
    console.log("showDepositInterface() called");

    // Fix DOM structure first
    fixInterfaceStructure();

    // Hide all interfaces first
    hideAllInterfaces();

    const interfaces = getAllInterfaces();

    if (!interfaces.depositInterface) {
        console.error("Deposit interface element not found");
        return;
    }

    // Show deposit interface with forceful styling to override any CSS conflicts
    console.log("Deposit interface element:", interfaces.depositInterface);
    console.log(
        "Before styling - display:",
        interfaces.depositInterface.style.display,
        "visibility:",
        interfaces.depositInterface.style.visibility
    );

    // Use setProperty with !important to override any CSS rules
    interfaces.depositInterface.style.setProperty(
        "display",
        "block",
        "important"
    );
    interfaces.depositInterface.style.setProperty(
        "visibility",
        "visible",
        "important"
    );
    interfaces.depositInterface.style.setProperty("opacity", "1", "important");
    interfaces.depositInterface.style.setProperty(
        "z-index",
        "9999",
        "important"
    );
    interfaces.depositInterface.style.setProperty(
        "position",
        "fixed",
        "important"
    );
    interfaces.depositInterface.style.setProperty("top", "0", "important");
    interfaces.depositInterface.style.setProperty("left", "0", "important");
    interfaces.depositInterface.style.setProperty("width", "100%", "important");
    interfaces.depositInterface.style.setProperty(
        "height",
        "100vh",
        "important"
    );
    interfaces.depositInterface.style.setProperty(
        "background",
        "linear-gradient(135deg, #0a0e1a 0%, #1a1f2e 100%)",
        "important"
    );

    console.log(
        "After styling - display:",
        interfaces.depositInterface.style.display,
        "visibility:",
        interfaces.depositInterface.style.visibility
    );

    // Update URL parameter and sidebar
    updateURLParameter("interface", "deposit");
    updateSidebarActive(".deposit-icon");

    // Initialize notification popup for this interface
    initializeNotificationPopup();
    setTimeout(() => {
        initializeNotificationPopup();
    }, 250);

    console.log(
        "showDepositInterface() completed - deposit interface should be visible now"
    );

    // Debug visibility
    debugInterfaceVisibility("depositInterface");
}

function showWithdrawalInterface() {
    console.log("showWithdrawalInterface() called");

    // Fix DOM structure first
    fixInterfaceStructure();

    // Hide all interfaces first
    hideAllInterfaces();

    const interfaces = getAllInterfaces();

    if (!interfaces.withdrawalInterface) {
        console.error("Withdrawal interface element not found");
        return;
    }

    // Show withdrawal interface with forceful styling to override any CSS conflicts
    console.log(
        "Withdrawal interface element:",
        interfaces.withdrawalInterface
    );
    console.log(
        "Before styling - display:",
        interfaces.withdrawalInterface.style.display,
        "visibility:",
        interfaces.withdrawalInterface.style.visibility
    );

    // Use setProperty with !important to override any CSS rules
    interfaces.withdrawalInterface.style.setProperty(
        "display",
        "block",
        "important"
    );
    interfaces.withdrawalInterface.style.setProperty(
        "visibility",
        "visible",
        "important"
    );
    interfaces.withdrawalInterface.style.setProperty(
        "opacity",
        "1",
        "important"
    );
    interfaces.withdrawalInterface.style.setProperty(
        "z-index",
        "9999",
        "important"
    );
    interfaces.withdrawalInterface.style.setProperty(
        "position",
        "fixed",
        "important"
    );
    interfaces.withdrawalInterface.style.setProperty("top", "0", "important");
    interfaces.withdrawalInterface.style.setProperty("left", "0", "important");
    interfaces.withdrawalInterface.style.setProperty(
        "width",
        "100%",
        "important"
    );
    interfaces.withdrawalInterface.style.setProperty(
        "height",
        "100vh",
        "important"
    );
    interfaces.withdrawalInterface.style.setProperty(
        "background",
        "linear-gradient(135deg, #0a0e1a 0%, #1a1f2e 100%)",
        "important"
    );

    console.log(
        "After styling - display:",
        interfaces.withdrawalInterface.style.display,
        "visibility:",
        interfaces.withdrawalInterface.style.visibility
    );

    // Update URL parameter and sidebar
    updateURLParameter("interface", "withdrawal");
    updateSidebarActive(".withdrawal-icon");

    // Initialize notification popup for this interface
    initializeNotificationPopup();
    setTimeout(() => {
        initializeNotificationPopup();
    }, 250);

    console.log(
        "showWithdrawalInterface() completed - withdrawal interface should be visible now"
    );
}

function showChatInterface() {
    console.log("showChatInterface() called");

    // Fix DOM structure first
    fixInterfaceStructure();

    // Hide all interfaces first
    hideAllInterfaces();

    const interfaces = getAllInterfaces();

    if (!interfaces.chatInterface) {
        console.error("Chat interface element not found");
        return;
    }

    // Show chat interface with forceful styling to override any CSS conflicts
    console.log("Chat interface element:", interfaces.chatInterface);
    console.log(
        "Before styling - display:",
        interfaces.chatInterface.style.display,
        "visibility:",
        interfaces.chatInterface.style.visibility
    );

    // Use setProperty with !important to override any CSS rules
    interfaces.chatInterface.style.setProperty("display", "block", "important");
    interfaces.chatInterface.style.setProperty(
        "visibility",
        "visible",
        "important"
    );
    interfaces.chatInterface.style.setProperty("opacity", "1", "important");
    interfaces.chatInterface.style.setProperty("z-index", "9999", "important");
    interfaces.chatInterface.style.setProperty(
        "position",
        "fixed",
        "important"
    );
    interfaces.chatInterface.style.setProperty("top", "0", "important");
    interfaces.chatInterface.style.setProperty("left", "0", "important");
    interfaces.chatInterface.style.setProperty("width", "100%", "important");
    interfaces.chatInterface.style.setProperty("height", "100vh", "important");
    interfaces.chatInterface.style.setProperty(
        "background",
        "linear-gradient(135deg, #0a0e1a 0%, #1a1f2e 100%)",
        "important"
    );

    console.log(
        "After styling - display:",
        interfaces.chatInterface.style.display,
        "visibility:",
        interfaces.chatInterface.style.visibility
    );

    // Update URL parameter and sidebar
    updateURLParameter("interface", "chat");
    updateSidebarActive(".chat-icon");

    // Initialize notification popup for this interface
    initializeNotificationPopup();
    setTimeout(() => {
        initializeNotificationPopup();
    }, 250);

    console.log(
        "showChatInterface() completed - chat interface should be visible now"
    );

    // Scroll to bottom of chat messages
    scrollToBottomOfChat();

    // Debug visibility
    debugInterfaceVisibility("chatInterface");
}

// Chat-specific utility functions
function scrollToBottomOfChat() {
    const chatMessages = document.getElementById("chatMessages");
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}

function insertQuickMessage(message) {
    const chatInput = document.getElementById("chatMessage");
    if (chatInput) {
        chatInput.value = message;
        chatInput.focus();
        // Auto-resize textarea
        autoResizeTextarea(chatInput);
    }
}

function autoResizeTextarea(textarea) {
    textarea.style.height = "auto";
    textarea.style.height = Math.min(textarea.scrollHeight, 120) + "px";
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
    // Initialize price updates and auto-fix categories
    initializePriceUpdates();

    // Initialize order form
    initializeOrderForm();
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

    // Chat icon navigation - now goes directly to chat interface (no overlay)
    const chatIcon = document.querySelector(".chat-icon");
    if (chatIcon) {
        chatIcon.addEventListener("click", function (e) {
            e.preventDefault();
            showChatInterface();
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

    // Fix interface DOM structure first
    fixInterfaceStructure();

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
        case "chat":
            console.log("URL parameter indicates chat interface");
            showChatInterface();
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

    // Chat functionality
    const chatForm = document.getElementById("chatForm");
    const chatInput = document.getElementById("chatMessage");

    if (chatForm) {
        chatForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const messageText = chatInput.value.trim();
            if (!messageText) {
                showNotification("Please enter a message", "error");
                return;
            }

            // Disable submit button to prevent double submission
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
            }

            // Add message to chat visually before submitting
            addMessageToChat("You", messageText, false);

            // Clear input immediately for better UX
            chatInput.value = "";
            autoResizeTextarea(chatInput);

            // Prepare form data
            const formData = new FormData(this);
            // Make sure the message is in the form data
            formData.set("message", messageText);

            // Submit using fetch API to handle the response
            fetch(this.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((response) => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error("Network response was not ok");
                    }
                })
                .then((data) => {
                    if (data.success) {
                        showNotification(
                            "Message sent successfully",
                            "success"
                        );
                        scrollToBottomOfChat();
                    } else {
                        showNotification(
                            data.error || "Failed to send message",
                            "error"
                        );
                    }
                })
                .catch((error) => {
                    console.error("Error sending message:", error);
                    showNotification("Error sending message", "error");
                })
                .finally(() => {
                    // Re-enable submit button
                    if (submitBtn) {
                        submitBtn.disabled = false;
                    }
                });
        });
    }

    if (chatInput) {
        // Auto-resize textarea as user types
        chatInput.addEventListener("input", function () {
            autoResizeTextarea(this);
        });

        // Submit on Enter (without Shift)
        chatInput.addEventListener("keydown", function (e) {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event("submit"));
            }
        });
    }

    // Initialize notification popup functionality
    initializeNotificationPopup();
});

// Function to add a message to the chat UI
function addMessageToChat(sender, message, isSupport) {
    const chatMessages = document.getElementById("chatMessages");
    if (!chatMessages) return;

    const messageElement = document.createElement("div");
    messageElement.className = `message ${
        isSupport ? "support-message" : "user-message"
    }`;

    const now = new Date();
    const timeString =
        now.toLocaleDateString() +
        " " +
        now.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });

    messageElement.innerHTML = `
        ${
            isSupport
                ? '<div class="message-avatar"><i class="bi bi-headset"></i></div>'
                : ""
        }
        <div class="message-content">
            <div class="message-header">
                <span class="message-sender">${sender}</span>
                <span class="message-time">${timeString}</span>
            </div>
            <div class="message-text">${message}</div>
        </div>
        ${
            !isSupport
                ? '<div class="message-avatar"><i class="bi bi-person-circle"></i></div>'
                : ""
        }
    `;

    chatMessages.appendChild(messageElement);
    scrollToBottomOfChat();
}

// Function to fix interface DOM structure by moving them to body level
function fixInterfaceStructure() {
    console.log("=== FIXING INTERFACE STRUCTURE ===");

    const interfaces = getAllInterfaces();
    const body = document.body;

    Object.keys(interfaces).forEach((key) => {
        const element = interfaces[key];
        if (element && key !== "mainContent") {
            // Don't move mainContent
            const currentParent = element.parentElement;
            console.log(`${key} current parent:`, currentParent?.id || "no ID");

            // If not already a direct child of body, move it
            if (currentParent && currentParent !== body) {
                console.log(`Moving ${key} to body level`);
                body.appendChild(element);
            }
        }
    });

    console.log("=== INTERFACE STRUCTURE FIXED ===");
}

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

// Debug function to check computed styles and identify visibility issues
function debugInterfaceVisibility(interfaceName) {
    const interfaces = getAllInterfaces();
    const element = interfaces[interfaceName];

    if (!element) {
        console.error(`Interface ${interfaceName} not found`);
        return;
    }

    const computed = window.getComputedStyle(element);
    const rect = element.getBoundingClientRect();

    console.log(`=== DEBUG: ${interfaceName.toUpperCase()} INTERFACE ===`);
    console.log("Element:", element);
    console.log("Element ID:", element.id);
    console.log("Element classes:", element.className);
    console.log("Inline styles:", {
        display: element.style.display,
        visibility: element.style.visibility,
        opacity: element.style.opacity,
        zIndex: element.style.zIndex,
        position: element.style.position,
        width: element.style.width,
        height: element.style.height,
    });
    console.log("Computed styles:", {
        display: computed.display,
        visibility: computed.visibility,
        opacity: computed.opacity,
        zIndex: computed.zIndex,
        position: computed.position,
        width: computed.width,
        height: computed.height,
        overflow: computed.overflow,
    });
    console.log("Bounding rect:", {
        width: rect.width,
        height: rect.height,
        top: rect.top,
        left: rect.left,
        bottom: rect.bottom,
        right: rect.right,
    });
    console.log("Parent element:", element.parentElement);
    console.log("Children count:", element.children.length);

    // Check if element has content
    const hasVisibleContent = element.textContent.trim().length > 0;
    console.log("Has text content:", hasVisibleContent);
    console.log("HTML content length:", element.innerHTML.length);

    return {
        element,
        computed,
        rect,
        hasVisibleContent,
    };
}

// Enhanced debug function to trace the DOM hierarchy
function debugDOMStructure() {
    console.log("=== DOM STRUCTURE DEBUG ===");

    const interfaces = getAllInterfaces();

    Object.keys(interfaces).forEach((key) => {
        const element = interfaces[key];
        if (element) {
            console.log(`\n${key.toUpperCase()}:`);
            console.log("- Element:", element);
            console.log("- Parent:", element.parentElement);
            console.log("- Parent ID:", element.parentElement?.id);
            console.log("- Parent classes:", element.parentElement?.className);
            console.log("- Grandparent:", element.parentElement?.parentElement);
            console.log(
                "- Grandparent ID:",
                element.parentElement?.parentElement?.id
            );

            // Check if nested inside another interface
            let parent = element.parentElement;
            let nesting = [];
            while (parent) {
                if (
                    parent.id &&
                    [
                        "mainContent",
                        "accountInterface",
                        "depositInterface",
                        "withdrawalInterface",
                    ].includes(parent.id)
                ) {
                    nesting.push(parent.id);
                }
                parent = parent.parentElement;
            }
            console.log("- Nested inside interfaces:", nesting);
        }
    });

    console.log("\n=== END DOM STRUCTURE DEBUG ===");
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

// Simple test function to check interface visibility and content
window.testAllInterfaces = function () {
    console.log("=== TESTING ALL INTERFACES ===");

    const interfaces = getAllInterfaces();

    Object.keys(interfaces).forEach((key) => {
        const element = interfaces[key];
        if (element) {
            console.log(`\n${key.toUpperCase()}:`);
            console.log("- Element exists:", !!element);
            console.log("- Display:", element.style.display);
            console.log("- Visibility:", element.style.visibility);
            console.log("- Content length:", element.innerHTML.length);
            console.log("- Has children:", element.children.length);
            console.log(
                "- Computed display:",
                window.getComputedStyle(element).display
            );
            console.log(
                "- Computed visibility:",
                window.getComputedStyle(element).visibility
            );
            console.log("- Bounding box:", element.getBoundingClientRect());
        } else {
            console.log(`${key.toUpperCase()}: NOT FOUND`);
        }
    });

    console.log("\n=== END TEST ===");
};

// ============= GLOBAL FUNCTION EXPORTS =============
// Make key functions globally available for inline script calls
window.showMainContent = showMainContent;
window.showAccountInterface = showAccountInterface;
window.showWithdrawalInterface = showWithdrawalInterface;
window.showDepositInterface = showDepositInterface;
window.showChatInterface = showChatInterface;
window.refreshTransactions = refreshTransactions;
window.refreshWithdrawalHistory = refreshWithdrawalHistory;
window.refreshDepositTransactions = refreshDepositTransactions;
window.submitWithdrawalForm = submitWithdrawalForm;
window.submitDepositForm = submitDepositForm;
window.showNotification = showNotification;
window.copyToClipboard = copyToClipboard;
window.changeAmount = changeAmount;
window.updateAssetPrices = updateAssetPrices;
window.toggleFavorite = toggleFavorite;
window.editOrder = editOrder;
window.showContextMenu = showContextMenu;
window.hideContextMenu = hideContextMenu;
window.filterAssets = filterAssets;
window.showAllAssets = showAllAssets;
window.showOnlyFavorites = showOnlyFavorites;
window.highlightCurrentAsset = highlightCurrentAsset;
window.autoFixAssetCategories = autoFixAssetCategories;
window.guessAssetCategory = guessAssetCategory;
window.debugInterfaceVisibility = debugInterfaceVisibility;
window.debugDOMStructure = debugDOMStructure;
window.fixInterfaceStructure = fixInterfaceStructure;
window.insertQuickMessage = insertQuickMessage;
window.scrollToBottomOfChat = scrollToBottomOfChat;
window.addMessageToChat = addMessageToChat;
window.debugDOMStructure = debugDOMStructure;

// ============= NOTIFICATION POPUP FUNCTIONALITY =============

// Comprehensive debug function for notification popup
function debugNotificationPopup() {
    console.log("=== NOTIFICATION POPUP DEBUG ===");

    // Find popup element
    const popup = document.getElementById("notificationPopup");
    const popupByClass = document.querySelector(".notification-popup");
    const notificationIcon = document.querySelector(".notification-icon");

    console.log("Elements found:");
    console.log("- By ID (#notificationPopup):", !!popup, popup);
    console.log(
        "- By class (.notification-popup):",
        !!popupByClass,
        popupByClass
    );
    console.log(
        "- Notification icon (.notification-icon):",
        !!notificationIcon,
        notificationIcon
    );

    if (popup) {
        const computedStyle = window.getComputedStyle(popup);
        console.log("Popup element details:");
        console.log("- innerHTML length:", popup.innerHTML.length);
        console.log("- Current style.display:", popup.style.display);
        console.log("- Computed display:", computedStyle.display);
        console.log("- Computed visibility:", computedStyle.visibility);
        console.log("- Computed opacity:", computedStyle.opacity);
        console.log("- Computed z-index:", computedStyle.zIndex);
        console.log("- Computed position:", computedStyle.position);
        console.log("- Computed transform:", computedStyle.transform);
        console.log("- getBoundingClientRect:", popup.getBoundingClientRect());
        console.log("- classList:", popup.classList.toString());

        // Check if popup is actually in viewport
        const rect = popup.getBoundingClientRect();
        const inViewport =
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= window.innerHeight &&
            rect.right <= window.innerWidth;
        console.log("- In viewport:", inViewport);

        // Check for overlapping elements
        const elementsAtCenter = document.elementsFromPoint(
            rect.left + rect.width / 2,
            rect.top + rect.height / 2
        );
        console.log(
            "- Elements at popup center:",
            elementsAtCenter.map(
                (el) =>
                    `${el.tagName}${el.id ? "#" + el.id : ""}${
                        el.className
                            ? "." + el.className.split(" ").join(".")
                            : ""
                    }`
            )
        );
    }

    if (notificationIcon) {
        console.log("Notification icon details:");
        console.log(
            "- Initialized:",
            notificationIcon.hasAttribute("data-initialized")
        );
        console.log(
            "- getBoundingClientRect:",
            notificationIcon.getBoundingClientRect()
        );
        console.log(
            "- Event listeners count:",
            getEventListeners
                ? getEventListeners(notificationIcon)
                : "getEventListeners not available"
        );
    }

    console.log("=== END DEBUG ===");

    return {
        popup,
        popupByClass,
        notificationIcon,
        popupRect: popup ? popup.getBoundingClientRect() : null,
        iconRect: notificationIcon
            ? notificationIcon.getBoundingClientRect()
            : null,
    };
}

// Force show notification popup for testing
function forceShowNotificationPopup() {
    console.log("=== FORCE SHOWING NOTIFICATION POPUP ===");
    const popup = document.getElementById("notificationPopup");
    if (!popup) {
        console.error("Popup not found!");
        return;
    }

    // Reset all styles and force visibility
    popup.style.cssText = `
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: fixed !important;
        z-index: 999999 !important;
        top: 100px !important;
        left: 100px !important;
        width: 350px !important;
        background: red !important;
        border: 5px solid yellow !important;
        transform: none !important;
        transition: none !important;
    `;

    popup.classList.add("show");
    popup.innerHTML =
        "<div style='padding: 20px; color: white; font-size: 20px;'>TEST POPUP VISIBLE!</div>";

    console.log("Forced popup to show with test content and red background");
    return popup;
}

// Toggle notification popup
function toggleNotificationPopup() {
    console.log("toggleNotificationPopup called");
    const popup = document.getElementById("notificationPopup");
    if (!popup) {
        console.error("Notification popup element not found!");
        console.log(
            "Available elements with 'notification' in ID:",
            Array.from(document.querySelectorAll('[id*="notification"]')).map(
                (el) => el.id
            )
        );
        debugNotificationPopup(); // Run debug if popup not found
        return;
    }

    console.log("Notification popup element found:", popup);
    console.log("Current popup display:", popup.style.display);
    console.log("Current popup class list:", popup.classList.toString());
    console.log(
        "Current popup computed display:",
        window.getComputedStyle(popup).display
    );

    // Check if popup is currently visible (either by style or class)
    const computedStyle = window.getComputedStyle(popup);
    const isCurrentlyVisible =
        (popup.style.display !== "none" &&
            computedStyle.display !== "none" &&
            popup.classList.contains("show")) ||
        (computedStyle.opacity === "1" &&
            computedStyle.visibility === "visible");

    if (!isCurrentlyVisible) {
        console.log("Showing notification popup");
        showNotificationPopup();
    } else {
        console.log("Closing notification popup");
        closeNotificationPopup();
    }
}

// Show notification popup
function showNotificationPopup() {
    console.log("showNotificationPopup called");
    const popup = document.getElementById("notificationPopup");
    const notificationIcon = document.querySelector(".notification-icon");

    if (!popup || !notificationIcon) {
        console.error("Popup or notification icon not found!", {
            popup: !!popup,
            notificationIcon: !!notificationIcon,
        });
        if (!popup) debugNotificationPopup();
        return;
    }

    console.log("Positioning and showing popup");

    // First, ensure popup is reset to initial state
    popup.classList.remove("show");
    popup.style.removeProperty("display");
    popup.style.removeProperty("visibility");
    popup.style.removeProperty("opacity");

    // Position the popup near the notification icon
    const iconRect = notificationIcon.getBoundingClientRect();
    const popupWidth = 350; // Width from CSS
    const popupHeight = 500; // Max height from CSS

    console.log("Icon position:", iconRect);
    console.log("Viewport size:", window.innerWidth, "x", window.innerHeight);

    // Calculate initial position to the right of the icon
    let left = iconRect.right + 10;
    let top = iconRect.top;

    // Ensure popup doesn't go off screen
    const viewportWidth = window.innerWidth;
    const viewportHeight = window.innerHeight;

    // Adjust horizontal position if popup would go off screen
    if (left + popupWidth > viewportWidth) {
        left = iconRect.left - popupWidth - 10;
        console.log("Adjusted left position to avoid going off screen:", left);
    }

    // Ensure left position is not negative
    if (left < 10) {
        left = 10;
        console.log("Adjusted left position to avoid negative position:", left);
    }

    // Adjust vertical position if popup would go off screen
    if (top + popupHeight > viewportHeight) {
        top = viewportHeight - popupHeight - 20;
        console.log("Adjusted top position to avoid going off screen:", top);
    }

    // Ensure top position is not negative
    if (top < 10) {
        top = 10;
        console.log("Adjusted top position to avoid negative position:", top);
    }

    // Set position
    popup.style.left = left + "px";
    popup.style.top = top + "px";

    console.log("Final position:", left, top);

    // Force styling with !important to override any conflicting CSS
    popup.style.setProperty("display", "block", "important");
    popup.style.setProperty("visibility", "visible", "important");
    popup.style.setProperty("opacity", "1", "important");
    popup.style.setProperty("z-index", "999999", "important");
    popup.style.setProperty("position", "fixed", "important");
    popup.style.setProperty("transform", "translateY(0) scale(1)", "important");
    popup.style.setProperty("pointer-events", "auto", "important");
    // Debug: add a visible border and force dimensions
    popup.style.setProperty("border", "3px solid red", "important");
    popup.style.setProperty("width", "350px", "important");
    popup.style.setProperty("height", "auto", "important");
    popup.style.setProperty("min-width", "350px", "important");
    popup.style.setProperty("min-height", "200px", "important");
    popup.style.setProperty("max-height", "500px", "important");
    popup.style.setProperty("overflow", "visible", "important");
    popup.style.setProperty(
        "background",
        "linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%)",
        "important"
    );

    // Also force all child elements to be visible
    const allChildren = popup.querySelectorAll("*");
    allChildren.forEach((child) => {
        child.style.setProperty("display", "block", "important");
        child.style.setProperty("visibility", "visible", "important");
        child.style.setProperty("opacity", "1", "important");
    });

    // Fix specific flex containers that might be collapsing
    const popupContent = popup.querySelector(".notification-popup-content");
    if (popupContent) {
        popupContent.style.setProperty("display", "flex", "important");
        popupContent.style.setProperty("flex-direction", "column", "important");
        popupContent.style.setProperty("height", "auto", "important");
        popupContent.style.setProperty("min-height", "200px", "important");
        popupContent.style.setProperty("width", "100%", "important");
    }

    const popupHeader = popup.querySelector(".notification-popup-header");
    if (popupHeader) {
        popupHeader.style.setProperty("display", "flex", "important");
        popupHeader.style.setProperty("padding", "15px 20px", "important");
        popupHeader.style.setProperty("min-height", "50px", "important");
    }

    const popupMessages = popup.querySelector(".notification-popup-messages");
    if (popupMessages) {
        popupMessages.style.setProperty("display", "block", "important");
        popupMessages.style.setProperty("padding", "10px", "important");
        popupMessages.style.setProperty("min-height", "100px", "important");
        popupMessages.style.setProperty("flex", "1", "important");
    }

    // Log the content for debugging
    console.log("Popup innerHTML length:", popup.innerHTML.length);
    console.log(
        "Popup content preview:",
        popup.innerHTML.substring(0, 200) + "..."
    );
    console.log("Full popup innerHTML:", popup.innerHTML);

    // ULTRA-AGGRESSIVE FIX: Replace all content with a simple visible div
    popup.innerHTML = `
        <div style="
            width: 340px !important;
            height: 180px !important;
            padding: 20px !important;
            color: white !important;
            background: #1a1a1a !important;
            border: 2px solid #4f8cff !important;
            border-radius: 8px !important;
            font-family: Arial, sans-serif !important;
            font-size: 14px !important;
            line-height: 1.4 !important;
            box-sizing: border-box !important;
            display: block !important;
            position: relative !important;
        ">
            <div style="
                display: flex !important;
                align-items: center !important;
                margin-bottom: 15px !important;
                color: #4f8cff !important;
                font-weight: bold !important;
                font-size: 16px !important;
            ">
                <i class="bi bi-bell" style="margin-right: 8px !important;"></i>
                Notifications
                <button onclick="closeNotificationPopup()" style="
                    margin-left: auto !important;
                    background: transparent !important;
                    border: none !important;
                    color: #888 !important;
                    font-size: 18px !important;
                    cursor: pointer !important;
                    padding: 4px !important;
                ">×</button>
            </div>
            <div style="
                color: #ccc !important;
                text-align: center !important;
                margin-top: 20px !important;
            ">
                <div style="font-size: 40px !important; margin-bottom: 10px !important;">🔔</div>
                <h4 style="margin: 10px 0 !important; color: #fff !important;">No Notifications</h4>
                <p style="margin: 0 !important; font-size: 13px !important;">You don't have any notifications at this time.</p>
            </div>
        </div>
    `;

    console.log("Replaced popup content with ultra-simple structure");

    console.log(
        "Popup display set to block with forced styling, adding show class"
    );

    // Add show class
    popup.classList.add("show");

    // Double-check after a small delay
    setTimeout(() => {
        popup.classList.add("show");
        popup.style.setProperty("opacity", "1", "important");
        popup.style.setProperty(
            "transform",
            "translateY(0) scale(1)",
            "important"
        );
        console.log("Show class added with delayed styling confirmation");

        // Final verification
        const computedStyle = window.getComputedStyle(popup);
        const rect = popup.getBoundingClientRect();
        console.log("Final popup verification:", {
            display: computedStyle.display,
            visibility: computedStyle.visibility,
            opacity: computedStyle.opacity,
            zIndex: computedStyle.zIndex,
            position: computedStyle.position,
            transform: computedStyle.transform,
            left: popup.style.left,
            top: popup.style.top,
            rect: rect,
            inViewport: rect.width > 0 && rect.height > 0,
        });

        // If still not visible, run debug
        if (
            rect.width === 0 ||
            rect.height === 0 ||
            computedStyle.display === "none"
        ) {
            console.warn(
                "Popup appears to still not be visible after setup. Running debug..."
            );
            debugNotificationPopup();
        }
    }, 50);
}

// Close notification popup
function closeNotificationPopup() {
    console.log("closeNotificationPopup called");
    const popup = document.getElementById("notificationPopup");
    if (!popup) {
        console.error("Notification popup not found for closing");
        return;
    }

    console.log("Removing show class and hiding popup");
    popup.classList.remove("show");
    setTimeout(() => {
        popup.style.setProperty("display", "none", "important");
        popup.style.setProperty("visibility", "hidden", "important");
        popup.style.setProperty("opacity", "0", "important");
        console.log("Popup hidden");
    }, 300); // Match the transition duration
}

// Initialize notification popup functionality
function initializeNotificationPopup() {
    console.log("Initializing notification popup...");

    // Find notification icon
    const notificationIcon = document.querySelector(".notification-icon");
    if (!notificationIcon) {
        console.error("Notification icon not found!");
        return;
    }

    console.log("Notification icon found:", notificationIcon);

    // Only initialize if not already done
    if (!notificationIcon.hasAttribute("data-initialized")) {
        // Add event listener directly without cloning
        notificationIcon.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            console.log("Notification icon clicked");
            toggleNotificationPopup();
        });

        notificationIcon.setAttribute("data-initialized", "true");
        console.log("Notification icon event listener added");
    } else {
        console.log("Notification icon already initialized");
    }

    // Ensure the close-on-outside-click is only added once globally
    if (!document.hasAttribute("data-notification-outside-click-initialized")) {
        document.addEventListener("click", function (event) {
            const popup = document.getElementById("notificationPopup");
            const notificationIcon =
                document.querySelector(".notification-icon");

            if (
                popup &&
                (popup.style.display === "block" ||
                    popup.classList.contains("show")) &&
                !popup.contains(event.target) &&
                notificationIcon &&
                !notificationIcon.contains(event.target)
            ) {
                closeNotificationPopup();
            }
        });
        document.setAttribute(
            "data-notification-outside-click-initialized",
            "true"
        );
        console.log("Outside click listener added");
    }

    // Handle notification item clicks
    const notificationItems = document.querySelectorAll(".notification-item");
    notificationItems.forEach((item) => {
        if (!item.hasAttribute("data-click-initialized")) {
            item.addEventListener("click", function () {
                const notificationId = this.dataset.id;
                markNotificationAsRead(notificationId);
            });
            item.setAttribute("data-click-initialized", "true");
        }
    });

    console.log("Notification popup initialization completed");
}

// Mark notification as read
function markNotificationAsRead(notificationId) {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    fetch("/notification/read", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({
            notification_id: notificationId,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Remove the notification item from the popup
                const notificationItem = document.querySelector(
                    `[data-id="${notificationId}"]`
                );
                if (notificationItem) {
                    notificationItem.remove();
                }

                // Update the notification badge count
                updateNotificationBadge(data.remaining_count);

                // If no notifications left, show the "no notifications" message
                const notificationMessages = document.getElementById(
                    "notificationPopupMessages"
                );
                if (data.remaining_count === 0 && notificationMessages) {
                    notificationMessages.innerHTML = `
                    <div class="no-notifications-message">
                        <div class="no-notifications-icon">
                            <i class="bi bi-bell-slash"></i>
                        </div>
                        <h4>No Notifications</h4>
                        <p>You don't have any notifications at this time.</p>
                    </div>
                `;
                }

                showNotification("Notification marked as read", "success");
            } else {
                showNotification(
                    data.message || "Error marking notification as read",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showNotification("Error marking notification as read", "error");
        });
}

// Update notification badge count
function updateNotificationBadge(count) {
    const notificationIcon = document.querySelector(".notification-icon");
    const existingBadge = notificationIcon.querySelector(".notification-badge");

    if (count > 0) {
        if (existingBadge) {
            existingBadge.textContent = count;
        } else {
            // Create new badge if it doesn't exist
            const badge = document.createElement("span");
            badge.className = "notification-badge";
            badge.textContent = count;
            notificationIcon.appendChild(badge);
        }
    } else {
        // Remove badge if count is 0
        if (existingBadge) {
            existingBadge.remove();
        }
    }
}

// Debug function to check notification popup visibility
function debugNotificationPopup() {
    const popup = document.getElementById("notificationPopup");
    if (!popup) {
        console.error("Notification popup not found!");
        return;
    }

    const rect = popup.getBoundingClientRect();
    const computedStyle = window.getComputedStyle(popup);

    console.log("=== NOTIFICATION POPUP DEBUG ===");
    console.log("Element:", popup);
    console.log("BoundingClientRect:", rect);
    console.log("Computed styles:", {
        display: computedStyle.display,
        visibility: computedStyle.visibility,
        opacity: computedStyle.opacity,
        zIndex: computedStyle.zIndex,
        position: computedStyle.position,
        transform: computedStyle.transform,
        background: computedStyle.background,
        border: computedStyle.border,
        width: computedStyle.width,
        height: computedStyle.height,
        minWidth: computedStyle.minWidth,
        minHeight: computedStyle.minHeight,
        maxWidth: computedStyle.maxWidth,
        maxHeight: computedStyle.maxHeight,
        overflow: computedStyle.overflow,
        fontSize: computedStyle.fontSize,
        lineHeight: computedStyle.lineHeight,
    });
    console.log("Inline styles:", popup.style.cssText);
    console.log("Classes:", popup.className);
    console.log("Is visible:", rect.width > 0 && rect.height > 0);

    // Check parent elements
    console.log("=== PARENT ELEMENTS CHECK ===");
    let parent = popup.parentElement;
    let level = 0;
    while (parent && level < 10) {
        const parentRect = parent.getBoundingClientRect();
        const parentStyle = window.getComputedStyle(parent);
        console.log(`Parent ${level}:`, {
            element: parent,
            tagName: parent.tagName,
            id: parent.id,
            className: parent.className,
            display: parentStyle.display,
            visibility: parentStyle.visibility,
            opacity: parentStyle.opacity,
            overflow: parentStyle.overflow,
            rect: parentRect,
            isVisible: parentRect.width > 0 && parentRect.height > 0,
        });
        parent = parent.parentElement;
        level++;
    }

    // Check if the element is actually connected to the DOM
    console.log("Is connected to DOM:", popup.isConnected);
    console.log("=== END DEBUG ===");
}

// Export debug function globally
window.debugNotificationPopup = debugNotificationPopup;

// Make debug functions available globally for console testing
window.forceShowNotificationPopup = forceShowNotificationPopup;
window.toggleNotificationPopup = toggleNotificationPopup;
window.showNotificationPopup = showNotificationPopup;
window.closeNotificationPopup = closeNotificationPopup;

console.log("Notification popup debug functions available globally:");
console.log("- debugNotificationPopup() - comprehensive debug info");
console.log("- forceShowNotificationPopup() - force show with test styling");
console.log("- toggleNotificationPopup() - normal toggle function");
console.log("- showNotificationPopup() - normal show function");
console.log("- closeNotificationPopup() - normal close function");

// ============= INTERFACE SWITCHING AND CHAT FUNCTIONALITY =============

// Function to force popup visibility by neutralizing all possible CSS hiding rules
function forcePopupVisibilityAggressively() {
    const popup = document.getElementById("notificationPopup");
    if (!popup) {
        console.error("Notification popup not found!");
        return;
    }

    // Remove all classes that might hide the element
    const hidingClasses = [
        "d-none",
        "hidden",
        "hide",
        "invisible",
        "sr-only",
        "visually-hidden",
        "collapse",
        "collapsed",
    ];
    hidingClasses.forEach((cls) => {
        popup.classList.remove(cls);
        // Also remove from all child elements
        popup.querySelectorAll("*").forEach((child) => {
            child.classList.remove(cls);
        });
    });

    // Remove hidden attribute if present
    popup.removeAttribute("hidden");
    popup.querySelectorAll("*").forEach((child) => {
        child.removeAttribute("hidden");
    });

    // Create and inject critical CSS to override any aggressive hiding rules
    const criticalStyles = `
        #notificationPopup {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: fixed !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            z-index: 999999 !important;
            width: 400px !important;
            min-width: 400px !important;
            height: auto !important;
            min-height: 200px !important;
            max-height: 80vh !important;
            background: white !important;
            border: 3px solid red !important;
            box-shadow: 0 0 20px rgba(79, 140, 255, 0.5) !important;
            pointer-events: auto !important;
            overflow: visible !important;
            font-size: 14px !important;
            line-height: 1.4 !important;
            color: black !important;
            padding: 20px !important;
            box-sizing: border-box !important;
        }
        
        #notificationPopup * {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            color: inherit !important;
            background: transparent !important;
            border: none !important;
            margin: 0 !important;
            padding: 5px !important;
            font-size: inherit !important;
            line-height: inherit !important;
            overflow: visible !important;
            pointer-events: auto !important;
        }
        
        #notificationPopup .notification-popup-content {
            display: block !important;
            width: 100% !important;
            height: auto !important;
            min-height: 150px !important;
        }
        
        #notificationPopup .notification-popup-header {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            margin-bottom: 10px !important;
            background: #f0f0f0 !important;
            padding: 10px !important;
        }
        
        #notificationPopup .notification-popup-close {
            display: inline-block !important;
            cursor: pointer !important;
            background: red !important;
            color: white !important;
            padding: 5px 10px !important;
            border: none !important;
        }
    `;

    // Remove any existing critical styles and add new ones
    let existingCriticalStyle = document.getElementById(
        "notificationPopupCriticalStyles"
    );
    if (existingCriticalStyle) {
        existingCriticalStyle.remove();
    }

    const styleElement = document.createElement("style");
    styleElement.id = "notificationPopupCriticalStyles";
    styleElement.textContent = criticalStyles;
    document.head.appendChild(styleElement);

    // Force a reflow
    popup.offsetHeight;

    console.log("Applied aggressive visibility styles to notification popup");

    // Debug after applying styles
    setTimeout(() => {
        debugNotificationPopup();
    }, 100);
}

// Last resort function to create a completely new notification popup
function createFallbackNotificationPopup() {
    console.log("Creating fallback notification popup as last resort");

    // Remove the old popup if it exists
    const oldPopup = document.getElementById("notificationPopup");
    if (oldPopup) {
        oldPopup.remove();
    }

    // Create a completely new popup element
    const newPopup = document.createElement("div");
    newPopup.id = "notificationPopup";
    newPopup.className = "notification-popup show";

    // Set inline styles with maximum specificity
    newPopup.style.cssText = `
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        z-index: 2147483647 !important;
        width: 400px !important;
        min-width: 400px !important;
        height: auto !important;
        min-height: 300px !important;
        max-height: 80vh !important;
        background: #1a1a1a !important;
        border: 3px solid #4f8cff !important;
        border-radius: 12px !important;
        box-shadow: 0 20px 40px rgba(79, 140, 255, 0.5) !important;
        padding: 0 !important;
        margin: 0 !important;
        box-sizing: border-box !important;
        pointer-events: auto !important;
        overflow: visible !important;
        font-family: Arial, sans-serif !important;
        font-size: 14px !important;
        line-height: 1.4 !important;
        color: #ffffff !important;
    `;

    // Set the content
    newPopup.innerHTML = `
        <div class="notification-popup-content" style="
            display: block !important;
            width: 100% !important;
            height: auto !important;
            min-height: 250px !important;
            padding: 20px !important;
            background: transparent !important;
        ">
            <div class="notification-popup-header" style="
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                margin-bottom: 15px !important;
                padding: 15px !important;
                background: rgba(45, 45, 45, 0.8) !important;
                border-radius: 8px !important;
                border: 1px solid #333 !important;
            ">
                <div class="notification-popup-title" style="
                    display: flex !important;
                    align-items: center !important;
                    color: #4f8cff !important;
                    font-weight: bold !important;
                    font-size: 16px !important;
                ">
                    <i class="bi bi-bell" style="margin-right: 8px !important;"></i>
                    <span>Notifications</span>
                </div>
                <button class="notification-popup-close" onclick="closeNotificationPopup()" style="
                    display: inline-block !important;
                    cursor: pointer !important;
                    background: #dc3545 !important;
                    color: white !important;
                    padding: 8px 12px !important;
                    border: none !important;
                    border-radius: 4px !important;
                    font-size: 14px !important;
                ">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <div class="notification-popup-messages" id="notificationPopupMessages" style="
                display: block !important;
                width: 100% !important;
                height: auto !important;
                min-height: 150px !important;
                padding: 15px !important;
                background: rgba(30, 30, 30, 0.6) !important;
                border-radius: 8px !important;
                overflow-y: auto !important;
                max-height: 300px !important;
            ">
                <div class="no-notifications-message" style="
                    text-align: center !important;
                    padding: 20px !important;
                    color: #ccc !important;
                ">
                    <div class="no-notifications-icon" style="
                        font-size: 40px !important;
                        margin-bottom: 10px !important;
                        color: #666 !important;
                    ">
                        <i class="bi bi-bell-slash"></i>
                    </div>
                    <h4 style="margin: 10px 0 !important; color: #fff !important;">No Notifications</h4>
                    <p style="margin: 0 !important; color: #ccc !important;">You don't have any notifications at this time.</p>
                </div>
            </div>
        </div>
    `;

    // Append to body
    document.body.appendChild(newPopup);

    console.log("Fallback notification popup created and added to DOM");

    // Verify the new popup
    setTimeout(() => {
        const rect = newPopup.getBoundingClientRect();
        console.log("Fallback popup verification:", {
            rect: rect,
            isVisible: rect.width > 0 && rect.height > 0,
        });

        if (rect.width > 0 && rect.height > 0) {
            console.log("SUCCESS! Fallback popup is visible!");
        } else {
            console.error(
                "CRITICAL FAILURE: Even the fallback popup is not visible. This indicates a serious DOM or browser issue."
            );
        }
    }, 100);
}

// Export globally
window.createFallbackNotificationPopup = createFallbackNotificationPopup;
