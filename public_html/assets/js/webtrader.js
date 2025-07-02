/**
 * WebTrader JavaScript - Complete functionality for the trading interface
 * This file contains all JavaScript logic for the WebTrader application
 */

// Global variables and initialization
let currentChartSymbol = null;
let currentAssetData = null;
let priceUpdateInterval = null;

// Initialize everything when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    initializeWebTrader();
});

function initializeWebTrader() {
    initializeNavigation();
    initializeContextMenu();
    initializeOrderForm();
    initializeAssetButtons();
    initializeFiltering();
    initializePriceUpdates();
    initializeFormValidation();
    initializeBankingFunctions();
    initializeWithdrawalModal(); // Add this line

    // Get initial chart symbol from PHP
    const chartSymbolElement = document.getElementById("currentChartSymbol");
    if (chartSymbolElement) {
        currentChartSymbol = chartSymbolElement.value;
        updateOrderFormPrices();
    }
}

// ============= NAVIGATION FUNCTIONS =============

function initializeNavigation() {
    const marketsIcon = document.querySelector(".markets-icon");
    const accountIcon = document.querySelector(".account-icon");
    const withdrawalIcon = document.querySelector(".withdrawal-icon");
    const depositIcon = document.querySelector(".deposit-icon");
    const logoutIcon = document.querySelector(".logout-icon");

    // Navigation button handlers
    const backButtons = document.querySelectorAll(".back-to-trading-btn");
    backButtons.forEach((btn) => {
        btn.addEventListener("click", showMainContent);
    });

    if (marketsIcon) marketsIcon.addEventListener("click", showMainContent);
    if (accountIcon)
        accountIcon.addEventListener("click", showAccountInterface);
    if (withdrawalIcon)
        withdrawalIcon.addEventListener("click", showWithdrawalInterface);
    if (depositIcon)
        depositIcon.addEventListener("click", showDepositInterface);
    if (logoutIcon) logoutIcon.addEventListener("click", handleLogout);
}

function showMainContent() {
    // Hide all other interfaces
    const interfaces = [
        "accountInterface",
        "withdrawalInterface",
        "depositInterface",
    ];
    interfaces.forEach((interfaceId) => {
        const element = document.getElementById(interfaceId);
        if (element) element.style.display = "none";
    });

    // Show main content
    const mainContent = document.getElementById("mainContent");
    if (mainContent) mainContent.style.display = "block";

    // Update active navigation
    updateActiveNavigation("markets");
}

function showAccountInterface() {
    // Hide main content and other interfaces
    const interfaces = [
        "mainContent",
        "withdrawalInterface",
        "depositInterface",
    ];
    interfaces.forEach((interfaceId) => {
        const element = document.getElementById(interfaceId);
        if (element) element.style.display = "none";
    });

    // Show account interface
    const accountInterface = document.getElementById("accountInterface");
    if (accountInterface) accountInterface.style.display = "block";

    updateActiveNavigation("account");
}

function showWithdrawalInterface() {
    // Hide main content and other interfaces
    const interfaces = ["mainContent", "accountInterface", "depositInterface"];
    interfaces.forEach((interfaceId) => {
        const element = document.getElementById(interfaceId);
        if (element) element.style.display = "none";
    });

    // Show withdrawal interface
    const withdrawalInterface = document.getElementById("withdrawalInterface");
    if (withdrawalInterface) withdrawalInterface.style.display = "block";

    updateActiveNavigation("withdrawal");

    // Refresh withdrawal history when showing the interface
    setTimeout(() => {
        refreshWithdrawalHistory();
    }, 100);
}

function showDepositInterface() {
    // Hide main content and other interfaces
    const interfaces = [
        "mainContent",
        "accountInterface",
        "withdrawalInterface",
    ];
    interfaces.forEach((interfaceId) => {
        const element = document.getElementById(interfaceId);
        if (element) element.style.display = "none";
    });

    // Show deposit interface
    const depositInterface = document.getElementById("depositInterface");
    if (depositInterface) depositInterface.style.display = "block";

    updateActiveNavigation("deposit");
}

function updateActiveNavigation(activeSection) {
    // Remove active class from all navigation icons
    document.querySelectorAll(".nav-icon").forEach((icon) => {
        icon.classList.remove("active");
    });

    // Add active class to current section
    const iconMap = {
        markets: ".markets-icon",
        account: ".account-icon",
        withdrawal: ".withdrawal-icon",
        deposit: ".deposit-icon",
    };

    const activeIcon = document.querySelector(iconMap[activeSection]);
    if (activeIcon) {
        activeIcon.classList.add("active");
    }
}

function handleLogout(event) {
    event.preventDefault();
    const logoutForm = document.getElementById("logoutForm");
    if (logoutForm) {
        logoutForm.submit();
    }
}

// ============= ASSET AND TRADING FUNCTIONS =============

function initializeAssetButtons() {
    const assetButtons = document.querySelectorAll(".asset-button");
    assetButtons.forEach((button) => {
        button.addEventListener("click", handleAssetClick);
        button.addEventListener("contextmenu", handleAssetRightClick);
    });
}

function handleAssetClick(event) {
    event.preventDefault();
    const button = event.currentTarget;
    const symbol = button.getAttribute("data-symbol");
    const url = button.getAttribute("data-url");

    // Navigate to chart if URL is provided
    if (url && symbol) {
        // Update current chart symbol
        currentChartSymbol = symbol;

        // Update the hidden input for current chart symbol
        const chartSymbolInput = document.getElementById("currentChartSymbol");
        if (chartSymbolInput) {
            chartSymbolInput.value = symbol;
        }

        // Update order form with current asset data
        updateOrderFormPrices();

        // Actually navigate to the chart
        window.location.href = url;
    }
}

function handleAssetRightClick(event) {
    event.preventDefault();
    const button = event.currentTarget;

    // Store current asset data for context menu actions
    currentAssetData = {
        id: button.getAttribute("data-asset-id"),
        symbol: button.getAttribute("data-symbol"),
        name: button.getAttribute("data-name"),
        url: button.getAttribute("data-url"),
        isFavourite: button.querySelector(".star-icon") !== null,
    };

    showContextMenu(event.clientX, event.clientY);
}

// ============= CONTEXT MENU FUNCTIONS =============

function initializeContextMenu() {
    const contextMenu = document.getElementById("customContextMenu");
    const goToAssetBtn = document.getElementById("goToAssetBtn");
    const addToFavouriteBtn = document.getElementById("addToFavouriteBtn");
    const removeFromFavouriteBtn = document.getElementById(
        "removeFromFavouriteBtn"
    );

    // Hide context menu when clicking elsewhere
    document.addEventListener("click", function (event) {
        if (!contextMenu.contains(event.target)) {
            hideContextMenu();
        }
    });

    // Context menu button handlers
    if (goToAssetBtn) {
        goToAssetBtn.addEventListener("click", goToAsset);
    }

    if (addToFavouriteBtn) {
        addToFavouriteBtn.addEventListener("click", addToFavourites);
    }

    if (removeFromFavouriteBtn) {
        removeFromFavouriteBtn.addEventListener("click", removeFromFavourites);
    }
}

function showContextMenu(x, y) {
    const contextMenu = document.getElementById("customContextMenu");
    const addBtn = document.getElementById("addToFavouriteBtn");
    const removeBtn = document.getElementById("removeFromFavouriteBtn");

    if (!contextMenu || !currentAssetData) return;

    // Show appropriate favourite button
    if (currentAssetData.isFavourite) {
        addBtn.style.display = "none";
        removeBtn.style.display = "block";
    } else {
        addBtn.style.display = "block";
        removeBtn.style.display = "none";
    }

    // Position and show context menu
    contextMenu.style.left = x + "px";
    contextMenu.style.top = y + "px";
    contextMenu.style.display = "block";
}

function hideContextMenu() {
    const contextMenu = document.getElementById("customContextMenu");
    if (contextMenu) {
        contextMenu.style.display = "none";
    }
}

function goToAsset() {
    if (currentAssetData && currentAssetData.url) {
        window.location.href = currentAssetData.url;
    }
    hideContextMenu();
}

function addToFavourites() {
    if (currentAssetData) {
        toggleFavourite(currentAssetData.id, true);
    }
    hideContextMenu();
}

function removeFromFavourites() {
    if (currentAssetData) {
        toggleFavourite(currentAssetData.id, false);
    }
    hideContextMenu();
}

function toggleFavourite(assetId, isAdding) {
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
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Update the star icon in the asset button
                updateAssetFavouriteDisplay(assetId, data.is_favourite);
                showNotification(data.message, "success");
            } else {
                showNotification(
                    data.message || "Error updating favourite",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showNotification("Network error occurred", "error");
        });
}

function updateAssetFavouriteDisplay(assetId, isFavourite) {
    const assetButton = document.querySelector(`[data-asset-id="${assetId}"]`);
    if (assetButton) {
        const starIcon = assetButton.querySelector(".star-icon");
        const nameSpan = assetButton.querySelector(".name");

        if (isFavourite && !starIcon) {
            // Add star icon
            const star = document.createElement("span");
            star.className = "star-icon";
            star.textContent = "★";
            nameSpan.appendChild(star);
        } else if (!isFavourite && starIcon) {
            // Remove star icon
            starIcon.remove();
        }
    }
}

// ============= ORDER FORM FUNCTIONS =============

function initializeOrderForm() {
    const orderForm = document.getElementById("orderForm");
    const sellBtn = document.getElementById("sellBtn");
    const buyBtn = document.getElementById("buyBtn");
    const marketOrderRadio = document.getElementById("market_order");
    const pendingOrderRadio = document.getElementById("pending_order");
    const pendingOrderFields = document.getElementById("pendingOrderFields");

    // Order type toggle
    if (marketOrderRadio && pendingOrderRadio && pendingOrderFields) {
        marketOrderRadio.addEventListener("change", toggleOrderFields);
        pendingOrderRadio.addEventListener("change", toggleOrderFields);
    }

    // Order buttons
    if (sellBtn) sellBtn.addEventListener("click", () => submitOrder(2)); // Sell
    if (buyBtn) buyBtn.addEventListener("click", () => submitOrder(1)); // Buy

    // Amount adjustment buttons
    const minusBtn = document.querySelector(".btnminus");
    const plusBtn = document.querySelector(".btnplus");
    if (minusBtn) minusBtn.addEventListener("click", () => changeAmount(-0.01));
    if (plusBtn) plusBtn.addEventListener("click", () => changeAmount(0.01));
}

function toggleOrderFields() {
    const pendingOrderFields = document.getElementById("pendingOrderFields");
    const isPendingOrder = document.getElementById("pending_order").checked;

    if (pendingOrderFields) {
        pendingOrderFields.style.display = isPendingOrder ? "block" : "none";
    }
}

function changeAmount(delta) {
    const amountInput = document.getElementById("amount");
    if (amountInput) {
        const currentValue = parseFloat(amountInput.value) || 0;
        const newValue = Math.max(0.01, currentValue + delta);
        amountInput.value = newValue.toFixed(2);
    }
}

function submitOrder(type) {
    const orderForm = document.getElementById("orderForm");
    const orderTypeInput = document.getElementById("orderType");

    if (orderForm && orderTypeInput) {
        orderTypeInput.value = type;

        // Ensure we have current asset data
        updateOrderFormPrices();

        orderForm.submit();
    }
}

function updateOrderFormPrices() {
    if (!currentChartSymbol) return;

    // Find the asset button for the current chart symbol
    const assetButton = document.querySelector(
        `[data-symbol="${currentChartSymbol}"]`
    );
    if (!assetButton) return;

    const assetId = assetButton.getAttribute("data-asset-id");
    const bidElement = assetButton.querySelector(".bid_price");
    const askElement = assetButton.querySelector(".ask_price");

    if (assetId && bidElement && askElement) {
        const bidPrice = bidElement.textContent.trim();
        const askPrice = askElement.textContent.trim();

        // Update hidden form inputs
        const assetIdInput = document.getElementById("selectedAssetId");
        const bidPriceInput = document.getElementById("currentBidPrice");
        const askPriceInput = document.getElementById("currentAskPrice");
        const assetSymbolInput = document.getElementById("selectedAssetSymbol");

        if (assetIdInput) assetIdInput.value = assetId;
        if (bidPriceInput) bidPriceInput.value = bidPrice;
        if (askPriceInput) askPriceInput.value = askPrice;
        if (assetSymbolInput) assetSymbolInput.value = currentChartSymbol;

        // Update displayed prices in order form
        const displayBidPrice = document.getElementById("displayBidPrice");
        const displayAskPrice = document.getElementById("displayAskPrice");

        if (displayBidPrice) displayBidPrice.textContent = bidPrice;
        if (displayAskPrice) displayAskPrice.textContent = askPrice;
    }
}

// ============= FILTERING FUNCTIONS =============

function initializeFiltering() {
    const searchInput = document.getElementById("assetSearch");
    const categoryFilter = document.getElementById("categoryFilter");

    if (searchInput) {
        searchInput.addEventListener("input", filterAssets);
    }

    if (categoryFilter) {
        categoryFilter.addEventListener("change", filterAssets);
    }
}

function filterAssets() {
    const searchTerm = document
        .getElementById("assetSearch")
        .value.toLowerCase();
    const selectedCategory = document.getElementById("categoryFilter").value;
    const assetButtons = document.querySelectorAll(".asset-button");

    assetButtons.forEach((button) => {
        const assetName = button.getAttribute("data-name").toLowerCase();
        const assetCategory = button.getAttribute("data-category");

        const matchesSearch = assetName.includes(searchTerm);
        const matchesCategory =
            selectedCategory === "" || assetCategory === selectedCategory;

        button.style.display =
            matchesSearch && matchesCategory ? "flex" : "none";
    });
}

// ============= PRICE UPDATE FUNCTIONS =============

function initializePriceUpdates() {
    // Start price updates every 5 seconds
    priceUpdateInterval = setInterval(updatePrices, 5000);
}

function updatePrices() {
    // This would typically fetch new prices from an API
    // For now, we'll just update the order form prices if chart symbol is set
    if (currentChartSymbol) {
        updateOrderFormPrices();
    }
}

// ============= BANKING AND TRANSACTION FUNCTIONS =============

function initializeBankingFunctions() {
    // Initialize bank filtering
    const countrySelect = document.getElementById("new_deposit_country");
    const bankSelect = document.getElementById("new_deposit_bank_select");
    const cryptoSelect = document.getElementById("new_crypto_type");

    if (countrySelect) {
        countrySelect.addEventListener("change", filterBanksByCountry);
    }

    if (bankSelect) {
        bankSelect.addEventListener("change", showBankDetails);
    }

    if (cryptoSelect) {
        cryptoSelect.addEventListener("change", updateWalletAddress);
    }

    // Initialize refresh buttons
    const refreshTransactionsBtn = document.querySelector(
        ".refresh-transactions-btn"
    );
    const refreshDepositBtn = document.querySelector(
        '[onclick="refreshDepositTransactions()"]'
    );

    if (refreshTransactionsBtn) {
        refreshTransactionsBtn.addEventListener("click", function () {
            // Check which interface is currently active and refresh accordingly
            const withdrawalInterface = document.getElementById(
                "withdrawalInterface"
            );
            if (
                withdrawalInterface &&
                withdrawalInterface.style.display !== "none"
            ) {
                refreshWithdrawalHistory();
            } else {
                refreshTransactions();
            }
        });
    }

    if (refreshDepositBtn) {
        refreshDepositBtn.addEventListener("click", refreshDepositTransactions);
    }

    // Initialize form submissions
    initializeFormSubmissions();
}

function initializeFormSubmissions() {
    // Find forms with onsubmit handlers and replace them
    const withdrawalForms = document.querySelectorAll(
        'form[onsubmit="submitWithdrawalForm(event)"]'
    );
    const depositForms = document.querySelectorAll(
        'form[onsubmit="submitDepositForm(event)"]'
    );

    withdrawalForms.forEach((form) => {
        form.removeAttribute("onsubmit");
        form.addEventListener("submit", submitWithdrawalForm);
    });

    depositForms.forEach((form) => {
        form.removeAttribute("onsubmit");
        form.addEventListener("submit", submitDepositForm);
    });
}

function filterBanksByCountry() {
    const countrySelect = document.getElementById("new_deposit_country");
    const bankSelect = document.getElementById("new_deposit_bank_select");

    if (!countrySelect || !bankSelect) return;

    const selectedCountry = countrySelect.value;
    const bankOptions = bankSelect.querySelectorAll("option");

    bankSelect.innerHTML = '<option value="">Select Bank</option>';
    bankSelect.disabled = !selectedCountry;

    if (selectedCountry && window.banksData) {
        const countryBanks = window.banksData.filter(
            (bank) => bank.country === selectedCountry
        );

        countryBanks.forEach((bank) => {
            const option = document.createElement("option");
            option.value = bank.id;
            option.textContent = bank.name;
            option.setAttribute("data-bank-data", JSON.stringify(bank));
            bankSelect.appendChild(option);
        });
    }

    // Hide bank details when country changes
    const bankDetailsDiv = document.getElementById("bankDetails");
    if (bankDetailsDiv) {
        bankDetailsDiv.style.display = "none";
    }
}

function showBankDetails() {
    const bankSelect = document.getElementById("new_deposit_bank_select");
    const bankDetailsDiv = document.getElementById("bankDetails");
    const bankInfoDiv = document.getElementById("bankInfo");

    if (!bankSelect || !bankDetailsDiv || !bankInfoDiv) return;

    const selectedOption = bankSelect.options[bankSelect.selectedIndex];

    if (selectedOption && selectedOption.value) {
        const bankDataAttr = selectedOption.getAttribute("data-bank-data");

        if (bankDataAttr) {
            const bankData = JSON.parse(bankDataAttr);

            const bankDetailsHTML = `
                <div class="bank-details-content">
                    <h6 class="text-primary mb-3">${bankData.name}</h6>
                    ${
                        bankData.accountNumber
                            ? `<p><strong>Account Number:</strong> ${bankData.accountNumber}</p>`
                            : ""
                    }
                    ${
                        bankData.routingNumber
                            ? `<p><strong>Routing Number:</strong> ${bankData.routingNumber}</p>`
                            : ""
                    }
                    ${
                        bankData.swiftCode
                            ? `<p><strong>SWIFT Code:</strong> ${bankData.swiftCode}</p>`
                            : ""
                    }
                    ${
                        bankData.bankAddress
                            ? `<p><strong>Bank Address:</strong> ${bankData.bankAddress}</p>`
                            : ""
                    }
                    ${
                        bankData.beneficiaryName
                            ? `<p><strong>Beneficiary Name:</strong> ${bankData.beneficiaryName}</p>`
                            : ""
                    }
                    ${
                        bankData.beneficiaryAddress
                            ? `<p><strong>Beneficiary Address:</strong> ${bankData.beneficiaryAddress}</p>`
                            : ""
                    }
                    ${
                        bankData.beneficiaryCountry
                            ? `<p><strong>Beneficiary Country:</strong> ${bankData.beneficiaryCountry}</p>`
                            : ""
                    }
                </div>
            `;

            bankInfoDiv.innerHTML = bankDetailsHTML;
            bankDetailsDiv.style.display = "block";
        }
    } else {
        bankDetailsDiv.style.display = "none";
    }
}

function updateWalletAddress() {
    const cryptoSelect = document.getElementById("new_crypto_type");
    const walletAddressDiv = document.getElementById("walletAddress");
    const walletAddressSpan = document.getElementById("walletAddressValue");

    if (!cryptoSelect || !walletAddressDiv || !walletAddressSpan) return;

    const selectedCrypto = cryptoSelect.value;

    if (
        selectedCrypto &&
        window.cryptoWallets &&
        window.cryptoWallets[selectedCrypto]
    ) {
        walletAddressSpan.textContent = window.cryptoWallets[selectedCrypto];
        walletAddressDiv.style.display = "block";
    } else {
        walletAddressDiv.style.display = "none";
    }
}

function refreshTransactions() {
    showNotification("Refreshing transactions...", "info");

    // Make AJAX call to refresh transactions
    fetch("/client/transactions/refresh", {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                updateTransactionsTable(data.transactions);
                showNotification(
                    "Transactions updated successfully",
                    "success"
                );
            } else {
                showNotification("Failed to refresh transactions", "error");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showNotification("Network error occurred", "error");
        });
}

function refreshDepositTransactions() {
    showNotification("Refreshing deposit transactions...", "info");

    fetch("/client/deposits/refresh", {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                updateDepositTransactionsTable(data.transactions);
                showNotification(
                    "Deposit transactions updated successfully",
                    "success"
                );
            } else {
                showNotification(
                    "Failed to refresh deposit transactions",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showNotification("Network error occurred", "error");
        });
}

function updateTransactionsTable(transactions) {
    const tableBody = document.querySelector("#transactionsTable tbody");
    if (!tableBody) return;

    tableBody.innerHTML = "";

    if (transactions.length === 0) {
        tableBody.innerHTML =
            '<tr><td colspan="6" class="text-center">No transactions found</td></tr>';
        return;
    }

    transactions.forEach((transaction) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${transaction.id}</td>
            <td>${transaction.type}</td>
            <td>$${parseFloat(transaction.amount).toFixed(2)}</td>
            <td><span class="badge bg-${
                transaction.status === "completed" ? "success" : "warning"
            }">${transaction.status}</span></td>
            <td>${new Date(transaction.created_at).toLocaleDateString()}</td>
            <td>${transaction.description || "-"}</td>
        `;
        tableBody.appendChild(row);
    });
}

function updateDepositTransactionsTable(transactions) {
    const tableBody = document.querySelector("#depositTransactionsTable tbody");
    if (!tableBody) return;

    tableBody.innerHTML = "";

    if (transactions.length === 0) {
        tableBody.innerHTML =
            '<tr><td colspan="6" class="text-center">No deposit transactions found</td></tr>';
        return;
    }

    transactions.forEach((transaction) => {
        const row = document.createElement("tr");

        // Format payment method properly
        let paymentMethod = transaction.payment_method;

        if (
            transaction.credit_card_details ||
            (typeof transaction.credit_card_details === "string" &&
                transaction.credit_card_details.trim() !== "")
        ) {
            paymentMethod = "Credit Card";
        } else if (transaction.usdt) {
            paymentMethod = "USDT";
        } else if (!paymentMethod) {
            paymentMethod = "Bank Transfer";
        }

        row.innerHTML = `
            <td>${transaction.id}</td>
            <td>$${parseFloat(transaction.amount).toFixed(2)}</td>
            <td>${paymentMethod}</td>
            <td><span class="badge bg-${
                transaction.status === "completed" ? "success" : "warning"
            }">${transaction.status}</span></td>
            <td>${new Date(transaction.created_at).toLocaleDateString()}</td>
        `;
        tableBody.appendChild(row);
    });
}

// ============= WITHDRAWAL MODAL FUNCTIONS =============

function initializeWithdrawalModal() {
    // Handle new withdrawal button
    const newWithdrawalBtn = document.querySelector(".new-withdrawal-btn");
    if (newWithdrawalBtn) {
        newWithdrawalBtn.addEventListener("click", function () {
            // Reset forms when modal opens
            resetWithdrawalForms();
        });
    }

    // Handle withdrawal form submissions
    const bankWithdrawalForm = document.getElementById("bankWithdrawalForm");
    const cryptoWithdrawalForm = document.getElementById(
        "cryptoWithdrawalForm"
    );

    if (bankWithdrawalForm) {
        bankWithdrawalForm.addEventListener(
            "submit",
            handleWithdrawalSubmission
        );
    }

    if (cryptoWithdrawalForm) {
        cryptoWithdrawalForm.addEventListener(
            "submit",
            handleWithdrawalSubmission
        );
    }

    // Handle tab switching to clear validation errors
    const withdrawalTabs = document.querySelectorAll(
        '#withdrawalMethodTabs button[data-bs-toggle="pill"]'
    );
    withdrawalTabs.forEach((tab) => {
        tab.addEventListener("shown.bs.tab", function () {
            clearFormValidation();
        });
    });
}

function resetWithdrawalForms() {
    const bankForm = document.getElementById("bankWithdrawalForm");
    const cryptoForm = document.getElementById("cryptoWithdrawalForm");

    if (bankForm) bankForm.reset();
    if (cryptoForm) cryptoForm.reset();

    clearFormValidation();
}

function clearFormValidation() {
    // Remove any existing validation classes
    const invalidInputs = document.querySelectorAll(
        "#newWithdrawalModal .is-invalid"
    );
    invalidInputs.forEach((input) => {
        input.classList.remove("is-invalid");
    });

    const errorMessages = document.querySelectorAll(
        "#newWithdrawalModal .invalid-feedback"
    );
    errorMessages.forEach((msg) => {
        msg.remove();
    });
}

function handleWithdrawalSubmission(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);

    // Add loading state to submit button
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML =
        '<i class="bi bi-hourglass-split me-2"></i>Processing...';
    submitBtn.disabled = true;

    fetch(form.action, {
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
                // Close modal
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("newWithdrawalModal")
                );
                modal.hide();

                // Show success message
                showNotification(
                    "Withdrawal request submitted successfully!",
                    "success"
                );

                // Refresh withdrawal history
                refreshWithdrawalHistory();

                // Reset form
                form.reset();
            } else {
                // Show error message
                showNotification(
                    data.message || "Failed to submit withdrawal request",
                    "error"
                );

                // Display field-specific errors if available
                if (data.errors) {
                    displayFormErrors(form, data.errors);
                }
            }
        })
        .catch((error) => {
            console.error("Withdrawal submission error:", error);
            showNotification(
                "An error occurred while submitting the withdrawal request",
                "error"
            );
        })
        .finally(() => {
            // Restore submit button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
}

function displayFormErrors(form, errors) {
    // Clear existing errors first
    clearFormValidation();

    Object.keys(errors).forEach((fieldName) => {
        const field = form.querySelector(`[name="${fieldName}"]`);
        if (field) {
            field.classList.add("is-invalid");

            const errorDiv = document.createElement("div");
            errorDiv.className = "invalid-feedback";
            errorDiv.textContent = errors[fieldName][0];

            field.parentNode.appendChild(errorDiv);
        }
    });
}

function refreshWithdrawalHistory() {
    const tableBody = document.querySelector("#withdrawalHistoryTable tbody");
    if (!tableBody) return;

    // Show loading state
    tableBody.innerHTML = `
        <tr>
            <td colspan="6" class="text-center text-muted py-5">
                <i class="bi bi-hourglass-split display-4 d-block mb-3 opacity-50"></i>
                Loading withdrawal history...
            </td>
        </tr>
    `;

    fetch("/client/refresh-transactions", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success && data.withdrawals) {
                updateWithdrawalHistoryTable(data.withdrawals);
                updateWithdrawalSummary(data.summary);
            } else {
                showNotification(
                    "Failed to refresh withdrawal history",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error refreshing withdrawal history:", error);
            showNotification("Error refreshing withdrawal history", "error");

            // Restore empty state
            tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted py-5">
                    <i class="bi bi-exclamation-triangle display-4 d-block mb-3 opacity-50"></i>
                    Failed to load withdrawal history
                </td>
            </tr>
        `;
        });
}

function updateWithdrawalHistoryTable(withdrawals) {
    const tableBody = document.querySelector("#withdrawalHistoryTable tbody");
    if (!tableBody) return;

    if (withdrawals.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted py-5">
                    <i class="bi bi-inbox display-4 d-block mb-3 opacity-50"></i>
                    No withdrawal transactions found
                </td>
            </tr>
        `;
        return;
    }

    const rows = withdrawals
        .map((withdrawal) => {
            const statusClass = getStatusClass(withdrawal.status);
            const formattedDate = new Date(
                withdrawal.created_at
            ).toLocaleDateString();

            return `
            <tr>
                <td><code>#${withdrawal.id}</code></td>
                <td class="fw-bold text-warning">$${parseFloat(
                    withdrawal.amount
                ).toFixed(2)}</td>
                <td>
                    <i class="bi ${getMethodIcon(
                        withdrawal.payment_method
                    )} me-2"></i>
                    ${formatPaymentMethod(withdrawal.payment_method)}
                </td>
                <td>
                    <span class="status-badge ${statusClass}">
                        ${withdrawal.status}
                    </span>
                </td>
                <td class="text-muted">${formattedDate}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="viewWithdrawalDetails('${
                        withdrawal.id
                    }')">
                        <i class="bi bi-eye"></i>
                    </button>
                </td>
            </tr>
        `;
        })
        .join("");

    tableBody.innerHTML = rows;
}

function updateWithdrawalSummary(summary) {
    if (!summary) return;

    // Update summary cards if they exist
    const pendingElement = document
        .querySelector(".pending-icon")
        .parentElement.querySelector("h4");
    const completedElement = document
        .querySelector(".completed-icon")
        .parentElement.querySelector("h4");

    if (pendingElement) {
        pendingElement.textContent = `$${parseFloat(
            summary.pending || 0
        ).toFixed(2)}`;
    }

    if (completedElement) {
        completedElement.textContent = `$${parseFloat(
            summary.completed || 0
        ).toFixed(2)}`;
    }
}

function getStatusClass(status) {
    switch (status.toLowerCase()) {
        case "pending":
            return "status-pending";
        case "completed":
            return "status-completed";
        case "rejected":
            return "status-rejected";
        case "processing":
            return "status-processing";
        default:
            return "status-pending";
    }
}

function getMethodIcon(method) {
    switch (method) {
        case "bank_transfer":
            return "bi-bank";
        case "cryptocurrency":
            return "bi-currency-bitcoin";
        default:
            return "bi-credit-card";
    }
}

function formatPaymentMethod(method) {
    switch (method) {
        case "bank_transfer":
            return "Bank Transfer";
        case "cryptocurrency":
            return "Cryptocurrency";
        default:
            return method
                .replace("_", " ")
                .replace(/\b\w/g, (l) => l.toUpperCase());
    }
}

function viewWithdrawalDetails(withdrawalId) {
    // Implement withdrawal details view
    showNotification(`Viewing details for withdrawal #${withdrawalId}`, "info");
}

// ============= FORM SUBMISSION FUNCTIONS =============

function submitWithdrawalForm(event) {
    event.preventDefault();
    const form = event.target;

    // Basic validation
    const amountInput = form.querySelector('input[name="amount"]');
    if (amountInput && parseFloat(amountInput.value) <= 0) {
        showNotification("Please enter a valid amount", "error");
        return;
    }

    showNotification("Processing withdrawal request...", "info");

    // Submit the form normally for now
    form.submit();
}

function submitDepositForm(event) {
    event.preventDefault();
    const form = event.target;

    // Basic validation
    const amountInput = form.querySelector('input[name="amount"]');
    if (amountInput && parseFloat(amountInput.value) < 10) {
        showNotification("Minimum deposit amount is $10", "error");
        return;
    }

    // Credit card specific validation
    const paymentMethod = form.querySelector(
        'input[name="payment_method"]'
    )?.value;
    if (paymentMethod === "credit_card") {
        if (!validateCreditCardForm(form)) {
            return;
        }
    }

    showNotification("Processing deposit request...", "info");

    // Submit the form normally for now
    form.submit();
}

function validateCreditCardForm(form) {
    const cardNumber =
        form.querySelector('input[name="card_number"]')?.value || "";
    const cardExpiry =
        form.querySelector('input[name="card_expiry"]')?.value || "";
    const cardCvv = form.querySelector('input[name="card_cvv"]')?.value || "";
    const cardHolderName =
        form.querySelector('input[name="card_holder_name"]')?.value || "";
    const billingAddress =
        form.querySelector('textarea[name="billing_address"]')?.value || "";

    // Validate card number (remove spaces and check if numeric)
    const cleanCardNumber = cardNumber.replace(/\s+/g, "");
    if (
        !cleanCardNumber ||
        cleanCardNumber.length < 13 ||
        cleanCardNumber.length > 19 ||
        !/^\d+$/.test(cleanCardNumber)
    ) {
        showNotification("Please enter a valid card number", "error");
        return false;
    }

    // Validate expiry date (MM/YY format)
    if (!cardExpiry || !/^\d{2}\/\d{2}$/.test(cardExpiry)) {
        showNotification("Please enter expiry date in MM/YY format", "error");
        return false;
    }

    // Check if expiry date is not in the past
    const [month, year] = cardExpiry.split("/");
    const expiryDate = new Date(2000 + parseInt(year), parseInt(month) - 1, 1);
    const currentDate = new Date();
    currentDate.setDate(1); // Set to first day of current month for comparison

    if (expiryDate < currentDate) {
        showNotification("Card has expired", "error");
        return false;
    }

    // Validate CVV
    if (
        !cardCvv ||
        cardCvv.length < 3 ||
        cardCvv.length > 4 ||
        !/^\d+$/.test(cardCvv)
    ) {
        showNotification("Please enter a valid CVV", "error");
        return false;
    }

    // Validate cardholder name
    if (!cardHolderName.trim()) {
        showNotification("Please enter cardholder name", "error");
        return false;
    }

    // Validate billing address
    if (!billingAddress.trim()) {
        showNotification("Please enter billing address", "error");
        return false;
    }

    return true;
}

// ============= FORM VALIDATION =============

function initializeFormValidation() {
    // Validate deposit amounts
    const amountInputs = document.querySelectorAll(
        "#new_deposit_amount_bank, #new_deposit_amount_crypto, #credit_card_deposit_amount"
    );
    amountInputs.forEach((input) => {
        input.addEventListener("input", function () {
            const enteredAmount = parseFloat(this.value);
            if (enteredAmount < 10) {
                this.setCustomValidity("Minimum deposit amount is $10");
                this.classList.add("is-invalid");
            } else {
                this.setCustomValidity("");
                this.classList.remove("is-invalid");
            }
        });
    });

    // Credit card number formatting
    const cardNumberInput = document.querySelector("#card_number");
    if (cardNumberInput) {
        cardNumberInput.addEventListener("input", function () {
            // Remove all non-digit characters
            let value = this.value.replace(/\D/g, "");

            // Add spaces every 4 digits
            value = value.replace(/(\d{4})(?=\d)/g, "$1 ");

            // Limit to 19 characters (16 digits + 3 spaces)
            if (value.length > 19) {
                value = value.substring(0, 19);
            }

            this.value = value;
        });
    }

    // Credit card expiry formatting
    const cardExpiryInput = document.querySelector("#card_expiry");
    if (cardExpiryInput) {
        cardExpiryInput.addEventListener("input", function () {
            // Remove all non-digit characters
            let value = this.value.replace(/\D/g, "");

            // Add slash after 2 digits
            if (value.length >= 2) {
                value = value.substring(0, 2) + "/" + value.substring(2, 4);
            }

            this.value = value;
        });
    }

    // Credit card CVV validation
    const cardCvvInput = document.querySelector("#card_cvv");
    if (cardCvvInput) {
        cardCvvInput.addEventListener("input", function () {
            // Only allow digits
            this.value = this.value.replace(/\D/g, "");
        });
    }

    // Validate withdrawal amounts
    const withdrawalAmountInputs = document.querySelectorAll(
        "#new_amount, #new_amount_usdt"
    );
    withdrawalAmountInputs.forEach((input) => {
        input.addEventListener("input", function () {
            const maxAmount = parseFloat(this.getAttribute("max"));
            const enteredAmount = parseFloat(this.value);
            if (enteredAmount > maxAmount) {
                this.setCustomValidity(
                    "Amount cannot exceed available balance"
                );
                this.classList.add("is-invalid");
            } else if (enteredAmount <= 0) {
                this.setCustomValidity("Amount must be greater than 0");
                this.classList.add("is-invalid");
            } else {
                this.setCustomValidity("");
                this.classList.remove("is-invalid");
            }
        });
    });
}

// ============= NOTIFICATION SYSTEM =============

function showNotification(message, type = "info") {
    // Create notification element
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

    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// ============= STEPPER FUNCTIONS =============

// Stepper navigation functions (for account/verification forms)
window.stepper1 = {
    next: function () {
        // Implementation for stepper navigation
        console.log("Stepper next called");
    },
    previous: function () {
        // Implementation for stepper navigation
        console.log("Stepper previous called");
    },
};

// ============= UTILITY FUNCTIONS =============

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

// Make key functions globally available
window.showMainContent = showMainContent;
window.showAccountInterface = showAccountInterface;
window.showWithdrawalInterface = showWithdrawalInterface;
window.showDepositInterface = showDepositInterface;
window.refreshTransactions = refreshTransactions;
window.refreshWithdrawalHistory = refreshWithdrawalHistory;
window.refreshDepositTransactions = refreshDepositTransactions;
window.submitWithdrawalForm = submitWithdrawalForm;
window.submitDepositForm = submitDepositForm;
window.showNotification = showNotification;
window.updateWalletAddress = updateWalletAddress;
window.filterBanksByCountry = filterBanksByCountry;
window.showBankDetails = showBankDetails;
