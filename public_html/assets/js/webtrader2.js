// WebTrader 2.0 JavaScript Functions
// Fixed missing function definitions and added defensive programming
// All referenced functions are now properly defined

// Get URL parameter function
function getURLParameter(key) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(key);
}

// Insert quick message function for chat
function insertQuickMessage(message) {
    const chatInput = document.getElementById("chatMessage");
    if (chatInput) {
        chatInput.value = message;
        chatInput.focus();
        // Optionally trigger input event to notify any listeners
        chatInput.dispatchEvent(new Event("input", { bubbles: true }));
    }
}

// Amount change function
function changeAmount(amount) {
    const input = document.getElementById("amount");
    let current = parseFloat(input.value) || 0;
    current = Math.max(0.01, (current + amount).toFixed(2));
    input.value = current;

    const newAmount = document.getElementById('newAmount');
    if(newAmount){
        newAmount.value = current;
    }

}

// Set amount function for quick amount buttons
function setAmount(amount) {
    const input = document.getElementById("amount");
    input.value = amount.toFixed(2);

    const newAmount = document.getElementById('newAmount');
    if(newAmount){
        newAmount.value = amount.toFixed(2);;
    }

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
        // Auto-fixed category information available if needed
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

/*function submitOrder(type) {
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
}*/

// Interface navigation functions with URL parameter updates
function getAllInterfaces() {
    return {
        mainContent: document.getElementById("mainContent"),
        accountInterface: document.getElementById("accountInterface"),
        depositInterface: document.getElementById("depositInterface"),
        withdrawalInterface: document.getElementById("withdrawalInterface"),
        chatInterface: document.getElementById("chatInterface"),
        uploadDocumentInterface: document.getElementById(
            "uploadDocumentInterface"
        ),
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
    // Hide all interfaces first
    hideAllInterfaces();

    const interfaces = getAllInterfaces();

    if (!interfaces.mainContent) {
        return;
    }

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

    // Update URL parameter and sidebar
    updateURLParameter("interface", "trading");
    updateSidebarActive(".markets-icon");
}

function showAccountInterface() {
    // Fix DOM structure first
    fixInterfaceStructure();

    // Hide all interfaces first
    hideAllInterfaces();

    const interfaces = getAllInterfaces();

    if (!interfaces.accountInterface) {
        return;
    }

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
    interfaces.accountInterface.style.setProperty("z-index", "1", "important");
    // interfaces.accountInterface.style.setProperty(
    //     "z-index",
    //     "9999",
    //     "important"
    // );
    // interfaces.accountInterface.style.setProperty(
    //     "position",
    //     "fixed",
    //     "important"
    // );
    // interfaces.accountInterface.style.setProperty("top", "0", "important");
    // interfaces.accountInterface.style.setProperty("left", "0", "important");
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

    // Update URL parameter and sidebar
    updateURLParameter("interface", "account");
    updateSidebarActive(".account-icon");

    // Debug visibility
    debugInterfaceVisibility("accountInterface");
}

function showDepositInterface() {
    // Fix DOM structure first
    fixInterfaceStructure();

    // Hide all interfaces first
    hideAllInterfaces();

    const interfaces = getAllInterfaces();

    if (!interfaces.depositInterface) {
        return;
    }

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
    interfaces.depositInterface.style.setProperty("z-index", "1", "important");
    // interfaces.depositInterface.style.setProperty(
    //     "z-index",
    //     "9999",
    //     "important"
    // );
    // interfaces.depositInterface.style.setProperty(
    //     "position",
    //     "fixed",
    //     "important"
    // );
    // interfaces.depositInterface.style.setProperty("top", "0", "important");
    // interfaces.depositInterface.style.setProperty("left", "0", "important");
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

    // Update URL parameter and sidebar
    updateURLParameter("interface", "deposit");
    updateSidebarActive(".deposit-icon");

    // Debug visibility
    debugInterfaceVisibility("depositInterface");
}

function showWithdrawalInterface() {
    // Fix DOM structure first
    fixInterfaceStructure();

    // Hide all interfaces first
    hideAllInterfaces();

    const interfaces = getAllInterfaces();

    if (!interfaces.withdrawalInterface) {
        return;
    }

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
    interfaces.withdrawalInterface.style.setProperty("z-index", "1", "important");

    // interfaces.withdrawalInterface.style.setProperty(
    //     "z-index",
    //     "9999",
    //     "important"
    // );
    // interfaces.withdrawalInterface.style.setProperty(
    //     "position",
    //     "fixed",
    //     "important"
    // );
    // interfaces.withdrawalInterface.style.setProperty("top", "0", "important");
    // interfaces.withdrawalInterface.style.setProperty("left", "0", "important");
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

    // Update URL parameter and sidebar
    updateURLParameter("interface", "withdrawal");
    updateSidebarActive(".withdrawal-icon");
}

function showChatInterface() {
    // Fix DOM structure first
    fixInterfaceStructure();

    // Hide all interfaces first
    hideAllInterfaces();

    const interfaces = getAllInterfaces();

    if (!interfaces.chatInterface) {
        return;
    }

    // Use setProperty with !important to override any CSS rules
    interfaces.chatInterface.style.setProperty("display", "block", "important");
    interfaces.chatInterface.style.setProperty(
        "visibility",
        "visible",
        "important"
    );
    interfaces.chatInterface.style.setProperty("opacity", "1", "important");
    interfaces.chatInterface.style.setProperty("z-index", "1", "important");
    // interfaces.chatInterface.style.setProperty("z-index", "9999", "important");
    // interfaces.chatInterface.style.setProperty(
    //     "position",
    //     "fixed",
    //     "important"
    // );
    // interfaces.chatInterface.style.setProperty("top", "0", "important");
    interfaces.chatInterface.style.setProperty("left", "0", "important");
    interfaces.chatInterface.style.setProperty("width", "100%", "important");
    interfaces.chatInterface.style.setProperty("height", "100vh", "important");
    interfaces.chatInterface.style.setProperty(
        "background",
        "linear-gradient(135deg, #0a0e1a 0%, #1a1f2e 100%)",
        "important"
    );

    // Update URL parameter and sidebar
    updateURLParameter("interface", "chat");
    updateSidebarActive(".chat-icon");

    // Scroll to bottom of chat messages
    scrollToBottomOfChat();

    // Debug visibility
    debugInterfaceVisibility("chatInterface");
}

function showUploadDocumentInterface() {
    // Fix DOM structure first
    fixInterfaceStructure();

    // Hide all interfaces first
    hideAllInterfaces();

    const interfaces = getAllInterfaces();

    if (!interfaces.uploadDocumentInterface) {
        return;
    }

    // Use setProperty with !important to override any CSS rules
    interfaces.uploadDocumentInterface.style.setProperty(
        "display",
        "block",
        "important"
    );
    interfaces.uploadDocumentInterface.style.setProperty(
        "visibility",
        "visible",
        "important"
    );
    interfaces.uploadDocumentInterface.style.setProperty(
        "opacity",
        "1",
        "important"
    );
    interfaces.uploadDocumentInterface.style.setProperty(
        "z-index",
        "1",
        "important"
    );
    // interfaces.uploadDocumentInterface.style.setProperty(
    //     "z-index",
    //     "9999",
    //     "important"
    // );
    // interfaces.uploadDocumentInterface.style.setProperty(
    //     "position",
    //     "fixed",
    //     "important"
    // );
    // interfaces.uploadDocumentInterface.style.setProperty(
    //     "top",
    //     "0",
    //     "important"
    // );
    // interfaces.uploadDocumentInterface.style.setProperty(
    //     "left",
    //     "0",
    //     "important"
    // );
    interfaces.uploadDocumentInterface.style.setProperty(
        "width",
        "100%",
        "important"
    );
    interfaces.uploadDocumentInterface.style.setProperty(
        "height",
        "100vh",
        "important"
    );
    interfaces.uploadDocumentInterface.style.setProperty(
        "background",
        "linear-gradient(135deg, #0a0e1a 0%, #1a1f2e 100%)",
        "important"
    );

    // Update URL parameter and sidebar
    updateURLParameter("interface", "upload");
    updateSidebarActive(".upload-document-icon");

    // Initialize drag and drop functionality
    initializeDragAndDrop();

    // Debug visibility
    debugInterfaceVisibility("uploadDocumentInterface");
}

// Upload Document Interface Functions
function initializeDragAndDrop() {
    // KYC Dropzone
    const kycDropzone = document.getElementById("kycDropzone");
    const kycFileInput = document.getElementById("kycFileInput");

    if (kycDropzone && kycFileInput) {
        setupDropzone(kycDropzone, kycFileInput, handleKycFiles);
    }

    // Other Documents Dropzone
    const otherDocsDropzone = document.getElementById("otherDocsDropzone");
    const otherDocsFileInput = document.getElementById("otherDocsFileInput");

    if (otherDocsDropzone && otherDocsFileInput) {
        setupDropzone(
            otherDocsDropzone,
            otherDocsFileInput,
            handleOtherDocsFiles
        );
    }
}

function setupDropzone(dropzone, fileInput, handleFiles) {
    // Drag and drop events
    dropzone.addEventListener("dragover", (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.add("dragover");
    });

    dropzone.addEventListener("dragleave", (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.remove("dragover");
    });

    dropzone.addEventListener("drop", (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.remove("dragover");

        const files = Array.from(e.dataTransfer.files);
        handleFiles(files);
    });

    // Click to select files
    dropzone.addEventListener("click", () => {
        fileInput.click();
    });

    // File input change
    fileInput.addEventListener("change", (e) => {
        const files = Array.from(e.target.files);
        handleFiles(files);
    });
}

function triggerKycFileInput() {
    const kycFileInput = document.getElementById("kycFileInput");
    if (kycFileInput) {
        kycFileInput.click();
    }
}

function triggerOtherDocsFileInput() {
    const otherDocsFileInput = document.getElementById("otherDocsFileInput");
    if (otherDocsFileInput) {
        otherDocsFileInput.click();
    }
}

function handleKycFiles(files) {
    const maxFiles = 5; // Limit KYC files
    const maxSize = 10 * 1024 * 1024; // 10MB per file
    const allowedTypes = [
        "image/jpeg",
        "image/jpg",
        "image/png",
        "application/pdf",
    ];

    if (files.length > maxFiles) {
        showUploadMessage(
            "error",
            `You can only upload up to ${maxFiles} KYC documents.`
        );
        return;
    }

    const validFiles = [];
    const invalidFiles = [];

    files.forEach((file) => {
        if (!allowedTypes.includes(file.type)) {
            invalidFiles.push(`${file.name} - Invalid file type`);
        } else if (file.size > maxSize) {
            invalidFiles.push(`${file.name} - File too large (max 10MB)`);
        } else {
            validFiles.push(file);
        }
    });

    if (invalidFiles.length > 0) {
        showUploadMessage(
            "error",
            "Some files were rejected: " + invalidFiles.join(", ")
        );
    }

    if (validFiles.length > 0) {
        uploadFiles(validFiles, "kyc");
    }
}

function handleOtherDocsFiles(files) {
    const maxSize = 10 * 1024 * 1024; // 10MB per file
    const allowedTypes = [
        "image/jpeg",
        "image/jpg",
        "image/png",
        "application/pdf",
        "application/msword",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
    ];

    const validFiles = [];
    const invalidFiles = [];

    files.forEach((file) => {
        if (!allowedTypes.includes(file.type)) {
            invalidFiles.push(`${file.name} - Invalid file type`);
        } else if (file.size > maxSize) {
            invalidFiles.push(`${file.name} - File too large (max 10MB)`);
        } else {
            validFiles.push(file);
        }
    });

    if (invalidFiles.length > 0) {
        showUploadMessage(
            "error",
            "Some files were rejected: " + invalidFiles.join(", ")
        );
    }

    if (validFiles.length > 0) {
        uploadFiles(validFiles, "other");
    }
}

function uploadFiles(files, type) {
    const formData = new FormData();
    const progressBar = document.getElementById("progressBar");
    const progressText = document.getElementById("progressText");
    const uploadProgress = document.getElementById("uploadProgress");

    // Show progress bar
    if (uploadProgress) {
        uploadProgress.style.display = "block";
    }

    // Add files to form data
    files.forEach((file, index) => {
        formData.append(`files[${index}]`, file);
    });
    formData.append("type", type);
    formData.append(
        "_token",
        document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content")
    );

    // Create XMLHttpRequest to track upload progress
    const xhr = new XMLHttpRequest();

    // Upload progress tracking
    xhr.upload.addEventListener("progress", (e) => {
        if (e.lengthComputable) {
            const percentComplete = (e.loaded / e.total) * 100;
            if (progressBar) {
                progressBar.style.width = percentComplete + "%";
            }
            if (progressText) {
                progressText.textContent = Math.round(percentComplete) + "%";
            }
        }
    });

    // Upload complete
    xhr.addEventListener("load", () => {
        if (uploadProgress) {
            uploadProgress.style.display = "none";
        }

        try {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                showUploadMessage(
                    "success",
                    response.message || "Files uploaded successfully!"
                );
                displayUploadedFiles(response.files, type);

                // Clear file inputs
                const kycFileInput = document.getElementById("kycFileInput");
                const otherDocsFileInput =
                    document.getElementById("otherDocsFileInput");
                if (kycFileInput) kycFileInput.value = "";
                if (otherDocsFileInput) otherDocsFileInput.value = "";
            } else {
                showUploadMessage(
                    "error",
                    response.message || "Upload failed. Please try again."
                );
            }
        } catch (error) {
            showUploadMessage("error", "Upload failed. Please try again.");
        }
    });

    // Upload error
    xhr.addEventListener("error", () => {
        if (uploadProgress) {
            uploadProgress.style.display = "none";
        }
        showUploadMessage(
            "error",
            "Upload failed. Please check your connection and try again."
        );
    });

    // Send the request
    xhr.open("POST", "/client/upload-documents", true);
    xhr.send(formData);
}

function displayUploadedFiles(files, type) {
    const containerId =
        type === "kyc" ? "kycFilesContainer" : "additionalFilesContainer";
    const listId = type === "kyc" ? "kycFilesList" : "additionalFilesList";

    const container = document.getElementById(containerId);
    const list = document.getElementById(listId);

    if (!container || !list) return;

    // Show the files list
    list.style.display = "block";

    // Clear existing files
    container.innerHTML = "";

    // Add each file
    files.forEach((file) => {
        const fileItem = createFileItem(file, type);
        container.appendChild(fileItem);
    });

    const countId = type === "kyc" ? "kycFilesCount" : "additionalFilesCount";
    const countElement = document.getElementById(countId);
    if (countElement) {
        countElement.textContent = `${files.length} file${
            files.length !== 1 ? "s" : ""
        }`;
    }

}

function createFileItem(file, type) {
    const fileItem = document.createElement("div");
    fileItem.className = "file-item";
    fileItem.innerHTML = `
        <div class="file-info">
            <div class="file-icon">
                <i class="bi bi-${getFileIcon(file.type)}"></i>
            </div>
            <div class="file-details">
                <h6>${file.name}</h6>
                <p>${formatFileSize(file.size)} • ${getFileType(
        file.type
    )} • ${formatDate(file.uploaded_at)}</p>
            </div>
        </div>
        <div class="file-actions">
            <button class="btn btn-outline-primary btn-sm" onclick="downloadFile('${
                file.id
            }')">
                <i class="bi bi-download"></i>
            </button>
            <button class="btn btn-outline-danger btn-sm" onclick="deleteFile('${
                file.id
            }', '${type}')">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    return fileItem;
}

function getFileIcon(type) {
    const iconMap = {
        "application/pdf": "file-pdf",
        "image/jpeg": "file-image",
        "image/jpg": "file-image",
        "image/png": "file-image",
        "application/msword": "file-word",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
            "file-word",
    };
    return iconMap[type] || "file-earmark";
}

function getFileType(type) {
    const typeMap = {
        "application/pdf": "PDF",
        "image/jpeg": "JPEG",
        "image/jpg": "JPG",
        "image/png": "PNG",
        "application/msword": "DOC",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
            "DOCX",
    };
    return typeMap[type] || "Unknown";
}

function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
}

function downloadFile(fileId) {
    window.open(`/client/download-document/${fileId}`, "_blank");
}

function deleteFile(fileId, type) {
    if (!confirm("Are you sure you want to delete this file?")) {
        return;
    }

    const formData = new FormData();
    formData.append("file_id", fileId);
    formData.append(
        "_token",
        document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content")
    );

    fetch("/client/delete-document", {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showUploadMessage("success", "File deleted successfully!");
                // Refresh the files list
                loadExistingFiles();
            } else {
                showUploadMessage(
                    "error",
                    data.message || "Failed to delete file."
                );
            }
        })
        .catch((error) => {
            showUploadMessage(
                "error",
                "Failed to delete file. Please try again."
            );
        });
}

function showUploadMessage(type, message) {
    const messagesContainer = document.getElementById("uploadMessages");
    if (!messagesContainer) return;

    const messageDiv = document.createElement("div");
    messageDiv.className = `upload-message ${type}`;
    messageDiv.innerHTML = `
        <i class="bi bi-${
            type === "success"
                ? "check-circle"
                : type === "error"
                ? "exclamation-circle"
                : "info-circle"
        }"></i>
        <span>${message}</span>
    `;

    messagesContainer.appendChild(messageDiv);

    // Auto-remove message after 5 seconds
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.parentNode.removeChild(messageDiv);
        }
    }, 5000);
}

function loadExistingFiles() {
    fetch("/client/get-documents", {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                if (data.kyc_files && data.kyc_files.length > 0) {
                    displayUploadedFiles(data.kyc_files, "kyc");
                }
                if (data.other_files && data.other_files.length > 0) {
                    displayUploadedFiles(data.other_files, "other");
                }
            }
        })
        .catch((error) => {
            // Silently fail - files will be loaded on next page refresh
        });
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
   /*
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
                    //(bank) => bank.country === selectedCountry
                    (bank) => String(bank.country) === String(selectedCountry)
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
*/

const countrySelect = document.getElementById("country_select");
const bankSelect = document.getElementById("bank_select");
const bankDetailsDisplay = document.getElementById("bankDetailsDisplay");

if (countrySelect && bankSelect) {
    countrySelect.addEventListener("change", function () {
        const selectedCountry = this.value;

        // تم نقل هذا السطر إلى الداخل لضمان قراءة البيانات في اللحظة التي يغير فيها العميل الدولة
        const banksData = window.banksData || [];

        // Clear and reset bank select
        bankSelect.innerHTML = '<option value="">Choose a bank...</option>';
        bankSelect.disabled = !selectedCountry;
        bankDetailsDisplay.style.display = "none";

        if (selectedCountry) {
            // Filter banks by selected country (مع التعديل الخاص بنوع البيانات)
            const countryBanks = banksData.filter(
                (bank) => String(bank.country) === String(selectedCountry)
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

    // e-wallet
    const ewallet_country_select = document.getElementById("ewallet_country_select");
    const ewallet_select = document.getElementById("ewallet_select");
    const ewalletDetailsDisplay = document.getElementById("ewalletDetailsDisplay");

    if (ewallet_country_select && ewallet_select) {
        ewallet_country_select.addEventListener("change", function () {
            const selectedCountry = this.value;

            const ewalletData = window.depositWallets || [];

            // Clear and reset ewallet select
            ewallet_select.innerHTML = '<option value="">Choose a wallet...</option>';
            ewallet_select.disabled = !selectedCountry;
            ewalletDetailsDisplay.style.display = "none";
            ewalletDetailsDisplay.innerHTML = "";

            if (selectedCountry) {
                // Filter ewallet by selected country 
                const countryewallet = ewalletData.filter((wallet) => {
                    if (!wallet.countries || !Array.isArray(wallet.countries)) {
                        return false;
                    }
                    return wallet.countries.some(
                        (country) => String(country.id) === String(selectedCountry)
                    );
                });

                countryewallet.forEach((ewallet) => {
                        const option = document.createElement("option");
                        option.value = ewallet.id;

                        option.textContent = window.currentAppLocale === 'ar' 
                        ? ewallet.name_ar
                        : ewallet.name_en;

                        option.setAttribute("data-fields", JSON.stringify(ewallet.fields || []));

                        ewallet_select.appendChild(option);
                    });
                }
        });

        ewallet_select.addEventListener("change", function () {
            const selectedOption = this.options[this.selectedIndex];

            // Reset details container view state
            ewalletDetailsDisplay.innerHTML = "";

            if (selectedOption && selectedOption.value) {
                // Retrieve and parse your encoded string back into a structural JSON object array
                const fieldsJson = selectedOption.getAttribute("data-fields");
                const fields = fieldsJson ? JSON.parse(fieldsJson) : [];

                if (fields.length > 0) {
                    // Loop through fields to generate customized dynamic inputs layout configurations
                    fields.forEach((field) => {
                        const fieldGroup = document.createElement("div");
                        fieldGroup.className = "mb-3";

                        // Determine primary display name based on the globally assigned locale state string
                        const localizedFieldName = window.currentAppLocale === 'ar' 
                            ? field.arabic_field_name 
                            : field.english_field_name;

                        const localizedFieldValue = window.currentAppLocale === 'ar' 
                            ? field.arabic_field_value 
                            : field.english_field_value;

                        fieldGroup.innerHTML = `
                            <label class="form-label-modern">${localizedFieldName}</label>
                            <input type="hidden" name="extra_fields[${field.id}]" value="${localizedFieldValue}">
                            <p class="form-control-modern">${localizedFieldValue}</p>
                        `;
                        ewalletDetailsDisplay.appendChild(fieldGroup);
                    });

                    // Show the container details block
                    ewalletDetailsDisplay.style.display = "block";
                } else {
                    // Keep block hidden if the chosen wallet contains no customized extra data field specifications
                    ewalletDetailsDisplay.style.display = "none";
                }
            } else {
                ewalletDetailsDisplay.style.display = "none";
            }
        });
    }

    // withdrawal
    const withdrawal_ewallet_country_select = document.getElementById("withdrawal_ewallet_country_select");
    const withdrawal_ewallet_select = document.getElementById("withdrawal_ewallet_select");
    const withdrawal_ewalletDetailsDisplay = document.getElementById("withdrawal_ewalletDetailsDisplay");

    if (withdrawal_ewallet_country_select && withdrawal_ewallet_select) {
        withdrawal_ewallet_country_select.addEventListener("change", function () {
            const selectedCountry = this.value;

            const ewalletData = window.withdrawalWallets || [];

            // Clear and reset ewallet select
            withdrawal_ewallet_select.innerHTML = '<option value="">Choose a wallet...</option>';
            withdrawal_ewallet_select.disabled = !selectedCountry;
            withdrawal_ewalletDetailsDisplay.style.display = "none";
            withdrawal_ewalletDetailsDisplay.innerHTML = "";

            if (selectedCountry) {
                // Filter ewallet by selected country 
                const countryewallet = ewalletData.filter((wallet) => {
                    if (!wallet.countries || !Array.isArray(wallet.countries)) {
                        return false;
                    }
                    return wallet.countries.some(
                        (country) => String(country.id) === String(selectedCountry)
                    );
                });

                if (countryewallet.length) {
                    document.getElementById("withdrawal_ewallet_select_div").classList.remove("d-none", "hidden");

                    countryewallet.forEach((ewallet) => {
                        const option = document.createElement("option");
                        option.value = ewallet.id;
    
                        option.textContent = window.currentAppLocale === 'ar' 
                        ? ewallet.name_ar
                        : ewallet.name_en;
    
                        option.setAttribute("data-fields", JSON.stringify(ewallet.fields || []));
    
                        withdrawal_ewallet_select.appendChild(option);
                    });
                }else{
                    
                    document.getElementById("withdrawal_ewallet_select_div").classList.add("d-none", "hidden");

                    withdrawal_ewalletDetailsDisplay.innerHTML = "";

                    // Loop through fields to generate customized dynamic inputs layout configurations
                    defaultFields.forEach((field) => {
                        const fieldGroup = document.createElement("div");
                        fieldGroup.className = "mb-3";

                        // Determine primary display name based on the globally assigned locale state string
                        const localizedFieldName = window.currentAppLocale === 'ar' 
                            ? field.arabic_field_name 
                            : field.english_field_name;

                        fieldGroup.innerHTML = `
                            <label class="form-label-modern">${localizedFieldName}</label>
                            <input type="text" class="form-control-modern" name="extra_fields[${field.id}]" placeholder="..." required>
                        `;
                        withdrawal_ewalletDetailsDisplay.appendChild(fieldGroup);
                    });

                    // Show the container details block
                    withdrawal_ewalletDetailsDisplay.style.display = "block";
                }
            }
        });

        withdrawal_ewallet_select.addEventListener("change", function () {
            const selectedOption = this.options[this.selectedIndex];

            // Reset details container view state
            withdrawal_ewalletDetailsDisplay.innerHTML = "";

            if (selectedOption && selectedOption.value) {
                // Retrieve and parse your encoded string back into a structural JSON object array
                const fieldsJson = selectedOption.getAttribute("data-fields");
                const fields = fieldsJson ? JSON.parse(fieldsJson) : [];

                if (fields.length > 0) {
                    // Loop through fields to generate customized dynamic inputs layout configurations
                    fields.forEach((field) => {
                        const fieldGroup = document.createElement("div");
                        fieldGroup.className = "mb-3";

                        // Determine primary display name based on the globally assigned locale state string
                        const localizedFieldName = window.currentAppLocale === 'ar' 
                            ? field.arabic_field_name 
                            : field.english_field_name;

                        fieldGroup.innerHTML = `
                            <label class="form-label-modern">${localizedFieldName}</label>
                            <input type="text" class="form-control-modern" name="extra_fields[${field.id}]" placeholder="..." required>
                        `;
                        withdrawal_ewalletDetailsDisplay.appendChild(fieldGroup);
                    });

                    // Show the container details block
                    withdrawal_ewalletDetailsDisplay.style.display = "block";
                } else {
                    // Keep block hidden if the chosen wallet contains no customized extra data field specifications
                    withdrawal_ewalletDetailsDisplay.style.display = "none";
                }
            } else {
                withdrawal_ewalletDetailsDisplay.style.display = "none";
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
                if (typeof updateAssetPrices === "function") {
                    updateAssetPrices(assetId, assetSymbol, bidPrice, askPrice);
                }
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
        case "chat":
            showChatInterface();
            break;
        case "upload":
            showUploadDocumentInterface();
            break;
        case "trading":
        default:
            showMainContent();
            break;
    }

    // Initialize current asset highlighting
    if (typeof highlightCurrentAsset === "function") {
        highlightCurrentAsset();
    }

    // Asset search functionality
    const assetSearchElement = document.getElementById("assetSearch");
    if (assetSearchElement) {
        assetSearchElement.addEventListener("input", function () {
            if (typeof filterAssets === "function") {
                filterAssets();
            }
        });
    }

    // Category filter functionality
    const categoryFilterElement = document.getElementById("categoryFilter");
    if (categoryFilterElement) {
        categoryFilterElement.addEventListener("change", function () {
            if (typeof filterAssets === "function") {
                filterAssets();
            }
        });
    }

    // Also listen for keyup events on search for better responsiveness
    if (assetSearchElement) {
        assetSearchElement.addEventListener("keyup", function () {
            if (typeof filterAssets === "function") {
                filterAssets();
            }
        });
    }

    // Favorites functionality
    const showFavouritesBtn = document.getElementById("showFavouritesBtn");
    if (showFavouritesBtn) {
        showFavouritesBtn.addEventListener("click", function () {
            const btn = this;
            if (btn.classList.contains("active")) {
                btn.classList.remove("active");
                btn.style.backgroundColor = "#23272f";
                if (typeof showAllAssets === "function") {
                    showAllAssets();
                }
            } else {
                btn.classList.add("active");
                btn.style.backgroundColor = "#4f8cff";
                if (typeof showOnlyFavorites === "function") {
                    showOnlyFavorites();
                }
            }
        });
    }

    // Context menu for favorites
    const addToFavouriteBtn = document.getElementById("addToFavouriteBtn");
    if (addToFavouriteBtn) {
        addToFavouriteBtn.addEventListener("click", function () {
            const assetId = this.getAttribute("data-asset-id");
            if (typeof toggleFavorite === "function") {
                toggleFavorite(assetId, "add");
            }
        });
    }

    const removeFromFavouriteBtn = document.getElementById(
        "removeFromFavouriteBtn"
    );
    if (removeFromFavouriteBtn) {
        removeFromFavouriteBtn.addEventListener("click", function () {
            const assetId = this.getAttribute("data-asset-id");
            if (typeof toggleFavorite === "function") {
                toggleFavorite(assetId, "remove");
            }
        });
    }

    // Buy and Sell button functionality
    const buyBtn = document.getElementById("buyBtn");
    if (buyBtn) {
        buyBtn.addEventListener("click", function () {
            const assetId = document.getElementById("selectedAssetId").value;
            if (!assetId || assetId === "null" || assetId === "") {
                showNotification("Please select a valid asset first", "error");
                return;
            }
            document.getElementById("orderType").value = "1"; // 1 = buy
            document.getElementById("type-input").value = "1";
            // document.getElementById("orderForm").submit();
        });
    }

    const sellBtn = document.getElementById("sellBtn");
    if (sellBtn) {
        sellBtn.addEventListener("click", function () {
            const assetId = document.getElementById("selectedAssetId").value;
            if (!assetId || assetId === "null" || assetId === "") {
                showNotification("Please select a valid asset first", "error");
                return;
            }
            document.getElementById("orderType").value = "2"; // 2 = sell
            document.getElementById("type-input").value = "2";
            // document.getElementById("orderForm").submit();
        });
    }

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
            if (typeof autoResizeTextarea === "function") {
                autoResizeTextarea(chatInput);
            }

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
                        if (typeof scrollToBottomOfChat === "function") {
                            scrollToBottomOfChat();
                        }
                    } else {
                        showNotification(
                            data.error || "Failed to send message",
                            "error"
                        );
                    }
                })
                .catch((error) => {
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
            if (typeof autoResizeTextarea === "function") {
                autoResizeTextarea(this);
            }
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

    // Initialize upload document interface on page load
    if (document.getElementById("uploadDocumentInterface")) {
        loadExistingFiles();
    }
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
    const interfaces = getAllInterfaces();
    const body = document.body;

    Object.keys(interfaces).forEach((key) => {
        const element = interfaces[key];
        if (element && key !== "mainContent") {
            // Don't move mainContent
            const currentParent = element.parentElement;

            // If not already a direct child of body, move it
            if (currentParent && currentParent !== body) {
                body.appendChild(element);
            }
        }
    });
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
        return;
    }

    const computed = window.getComputedStyle(element);
    const rect = element.getBoundingClientRect();
    // Check if element has content
    const hasVisibleContent = element.textContent.trim().length > 0;
    return {
        element,
        computed,
        rect,
        hasVisibleContent,
    };
}

// Enhanced debug function to trace the DOM hierarchy
function debugDOMStructure() {
    const interfaces = getAllInterfaces();

    Object.keys(interfaces).forEach((key) => {
        const element = interfaces[key];
        if (element) {
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
        }
    });
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
    const interfaces = getAllInterfaces();
    const icons = {
        markets: document.querySelector(".markets-icon"),
        account: document.querySelector(".account-icon"),
        deposit: document.querySelector(".deposit-icon"),
        withdrawal: document.querySelector(".withdrawal-icon"),
    };
    return { interfaces, icons };
};

window.testShowAccount = function () {
    showAccountInterface();
};

window.testShowDeposit = function () {
    showDepositInterface();
};

window.testShowWithdrawal = function () {
    showWithdrawalInterface();
};

window.testShowMain = function () {
    showMainContent();
};

// Simple test function to check interface visibility and content
window.testAllInterfaces = function () {
    const interfaces = getAllInterfaces();

    Object.keys(interfaces).forEach((key) => {
        const element = interfaces[key];
        if (element) {
            // Interface element exists
        } else {
            // Interface element not found
        }
    });
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
window.getURLParameter = getURLParameter;
window.scrollToBottomOfChat = scrollToBottomOfChat;
window.addMessageToChat = addMessageToChat;
window.debugDOMStructure = debugDOMStructure;

// ============= NOTIFICATION POPUP FUNCTIONALITY =============

// Toggle notification popup
function toggleNotificationPopup() {
    const popup = document.getElementById("notificationPopup");
    if (!popup) {
        return;
    }
    // Simplified visibility check - just check if it has the 'show' class
    const isCurrentlyVisible = popup.classList.contains("show");
    if (!isCurrentlyVisible) {
        showNotificationPopup();
    } else {
        closeNotificationPopup();
    }
}

// Show notification popup
function showNotificationPopup() {
    const popup = document.getElementById("notificationPopup");
    const notificationIcon = document.querySelector(".notification-icon");

    const languageSwitcherBtn = document.querySelector('.language-switcher-btn');
    const languageDropdown = document.querySelector('.language-dropdown');

    languageDropdown.classList.remove('show');
    languageSwitcherBtn.classList.remove('active');


    if (!popup || !notificationIcon) {
        return;
    }
    // First, ensure popup is reset to initial state
    popup.classList.remove("show");
    popup.style.removeProperty("display");
    popup.style.removeProperty("visibility");
    popup.style.removeProperty("opacity");

    // Position the popup near the notification icon
    const iconRect = notificationIcon.getBoundingClientRect();
    const popupWidth = 350; // Width from CSS
    const popupHeight = 500; // Max height from CSS
    // Calculate initial position to the right of the icon
    let left = iconRect.right + 10;
    let top = iconRect.top;

    // Ensure popup doesn't go off screen
    const viewportWidth = window.innerWidth;
    const viewportHeight = window.innerHeight;

    // Adjust horizontal position if popup would go off screen
    if (left + popupWidth > viewportWidth) {
        left = iconRect.left - popupWidth - 10;
    }

    // Ensure left position is not negative
    if (left < 10) {
        left = 10;
    }

    // Adjust vertical position if popup would go off screen
    if (top + popupHeight > viewportHeight) {
        top = viewportHeight - popupHeight - 20;
    }

    // Ensure top position is not negative
    if (top < 10) {
        top = 10;
    }
    // Set position
    popup.style.left = left + "px";
    popup.style.top = top + "px";

    // Show popup with proper styling
    popup.style.setProperty("display", "block", "important");
    popup.style.setProperty("visibility", "visible", "important");
    popup.style.setProperty("opacity", "1", "important");
    popup.style.setProperty("z-index", "999999", "important");
    popup.style.setProperty("position", "fixed", "important");
    popup.style.setProperty("transform", "translateY(0) scale(1)", "important");
    popup.style.setProperty("pointer-events", "auto", "important");

    // Add show class
    popup.classList.add("show");
}

// Close notification popup
function closeNotificationPopup() {
    const popup = document.getElementById("notificationPopup");
    if (!popup) {
        return;
    }

    popup.classList.remove("show");
    setTimeout(() => {
        popup.style.setProperty("display", "none", "important");
        popup.style.setProperty("visibility", "hidden", "important");
        popup.style.setProperty("opacity", "0", "important");
    }, 300); // Match the transition duration
}

// Initialize notification popup functionality
function initializeNotificationPopup() {
    // Find notification icon

    const notificationIcon = document.querySelector(".notification-icon");
    if (!notificationIcon) {
        return;
    }
    // Only initialize if not already done
    if (!notificationIcon.hasAttribute("data-initialized")) {
        // Add event listener directly without cloning
        notificationIcon.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            toggleNotificationPopup();
        });

        notificationIcon.setAttribute("data-initialized", "true");
    }

    // Ensure the close-on-outside-click is only added once globally
    if (!document.body.hasAttribute("data-notification-outside-click-initialized")) {
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
        document.body.setAttribute(
            "data-notification-outside-click-initialized",
            "true"
        );
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

// Make functions available globally
window.toggleNotificationPopup = toggleNotificationPopup;
window.showNotificationPopup = showNotificationPopup;
window.closeNotificationPopup = closeNotificationPopup;

// ============= MISSING FUNCTIONS =============

// Function to scroll to bottom of chat messages
function scrollToBottomOfChat() {
    const chatMessages = document.getElementById("chatMessages");
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}

// Function to update URL parameter
function updateURLParameter(key, value) {
    const url = new URL(window.location);
    if (value) {
        url.searchParams.set(key, value);
    } else {
        url.searchParams.delete(key);
    }
    window.history.replaceState({}, "", url);
}

// Function to update sidebar active state
function updateSidebarActive(selector) {
    // شيل الـ active class من كل العناصر الأساسية في الـ sidebar
    document.querySelectorAll(".sidebar .nav-icon").forEach((item) => {
        item.classList.remove("active");
        item.style.removeProperty("background-color");
        item.style.removeProperty("color");
    });

    // اختار العنصر الجديد وفعّله
    const targetElement = document.querySelector(selector);
    if (targetElement) {
        targetElement.classList.add("active");
    }
}

// Function to debug interface visibility
function debugInterfaceVisibility(interfaceName) {
    const element = document.getElementById(interfaceName);
    if (element) {
    }
}

// Function to update asset prices in the order form
function updateAssetPrices(assetId, symbol, bidPrice, askPrice) {
    // Store current symbol globally
    window.currentSymbol = symbol;

    // Update the selected asset ID in the form
    const selectedAssetIdInput = document.getElementById("selectedAssetId");
    if (selectedAssetIdInput) {
        selectedAssetIdInput.value = assetId;
    }

    // Update the symbol display in the order form
    const symbolDisplays = document.querySelectorAll(
        ".current-symbol, .selected-symbol"
    );
    symbolDisplays.forEach((display) => {
        display.textContent = symbol;
    });

    // Update bid/ask prices in the order form
    const bidPriceDisplays = document.querySelectorAll(
        ".current-bid, .bid-price-display"
    );
    const askPriceDisplays = document.querySelectorAll(
        ".current-ask, .ask-price-display"
    );

    bidPriceDisplays.forEach((display) => {
        display.textContent = bidPrice;
    });

    askPriceDisplays.forEach((display) => {
        display.textContent = askPrice;
    });
}

// Function to highlight current asset
function highlightCurrentAsset() {
    const currentSymbol = window.currentSymbol;
    if (!currentSymbol) return;

    // Remove highlighting from all assets
    document.querySelectorAll(".asset-button").forEach((button) => {
        button.classList.remove("active", "selected");
    });

    // Highlight the current asset
    const currentAssetButton = document.querySelector(
        `[data-symbol="${currentSymbol}"]`
    );
    if (currentAssetButton) {
        currentAssetButton.classList.add("active", "selected");
    }
}

// Function to show only favorites
function showOnlyFavorites() {
    const assetButtons = document.querySelectorAll(".asset-button");
    const favoriteAssets = window.favouriteAssetIds || [];

    assetButtons.forEach((button) => {
        const assetId = button.getAttribute("data-asset-id");
        const isFavorite = favoriteAssets.includes(parseInt(assetId));

        if (isFavorite) {
            button.style.display = "flex";
            button.style.visibility = "visible";
            button.classList.remove("d-none", "hidden");
            button.classList.add("d-flex");
        } else {
            button.style.display = "none";
            button.style.visibility = "hidden";
            button.classList.add("d-none", "hidden");
            button.classList.remove("d-flex");
        }
    });
}

// Function to show all assets
function showAllAssets() {
    const assetButtons = document.querySelectorAll(".asset-button");

    assetButtons.forEach((button) => {
        button.style.display = "flex";
        button.style.visibility = "visible";
        button.classList.remove("d-none", "hidden");
        button.classList.add("d-flex");
    });
}

// Function to toggle favorite status
function toggleFavorite(assetId, action) {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    fetch("/toggle-favourite", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({
            asset_id: assetId,
            action: action,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Update the global favorites list
                window.favouriteAssetIds = data.favourites || [];

                // Update the star icon for this asset
                const assetButton = document.querySelector(
                    `[data-asset-id="${assetId}"]`
                );
                const starIcon = assetButton
                    ? assetButton.querySelector(".favorite-star")
                    : null;

                if (starIcon) {
                    if (action === "add") {
                        starIcon.classList.remove("bi-star");
                        starIcon.classList.add("bi-star-fill");
                        starIcon.style.color = "#ffc107";
                    } else {
                        starIcon.classList.remove("bi-star-fill");
                        starIcon.classList.add("bi-star");
                        starIcon.style.color = "#6c757d";
                    }
                }

                showNotification(
                    action === "add"
                        ? "Added to favorites!"
                        : "Removed from favorites!",
                    "success"
                );
            } else {
                showNotification(
                    data.message || "Error updating favorites",
                    "error"
                );
            }
        })
        .catch((error) => {
            showNotification("Error updating favorites", "error");
        });
}

// Function to auto-resize textarea
function autoResizeTextarea(textarea) {
    textarea.style.height = "auto";
    textarea.style.height = Math.min(textarea.scrollHeight, 120) + "px";
}

// Note: initializeNotificationPopup function is already defined earlier in the file

// Make additional functions globally available
window.scrollToBottomOfChat = scrollToBottomOfChat;
window.updateURLParameter = updateURLParameter;
window.updateSidebarActive = updateSidebarActive;
window.debugInterfaceVisibility = debugInterfaceVisibility;
window.updateAssetPrices = updateAssetPrices;
window.highlightCurrentAsset = highlightCurrentAsset;
window.showOnlyFavorites = showOnlyFavorites;
window.showAllAssets = showAllAssets;
window.toggleFavorite = toggleFavorite;
window.autoResizeTextarea = autoResizeTextarea;
