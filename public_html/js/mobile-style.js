// Mobile WebTrader JavaScript

// Initialize mobile functionality when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    initializeMobileSidebar();
    initializeInterfaceTabs();
    initializeFormHandlers();
    initializeFileUpload();
    initializeDepositForm();
    initializeWithdrawForm();
    initializeClipboardFunctions();
});

// Mobile Sidebar Functionality
function initializeMobileSidebar() {
    const hamburgerBtn = document.querySelector(".hamburger-btn");
    const sidebar = document.querySelector(".mobile-sidebar");
    const overlay = document.querySelector(".sidebar-overlay");
    const closeBtn = document.querySelector(".sidebar-close-btn");

    if (hamburgerBtn && sidebar && overlay) {
        // Open sidebar
        hamburgerBtn.addEventListener("click", function () {
            sidebar.classList.add("active");
            overlay.classList.add("active");
            document.body.style.overflow = "hidden";
        });

        // Close sidebar
        function closeSidebar() {
            sidebar.classList.remove("active");
            overlay.classList.remove("active");
            document.body.style.overflow = "";
        }

        if (closeBtn) {
            closeBtn.addEventListener("click", closeSidebar);
        }

        overlay.addEventListener("click", closeSidebar);

        // Close on escape key
        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape" && sidebar.classList.contains("active")) {
                closeSidebar();
            }
        });
    }
}

// Interface Tab Switching
function initializeInterfaceTabs() {
    const tabButtons = document.querySelectorAll(".interface-tabs .nav-link");
    const tabContents = document.querySelectorAll(".tab-pane");

    tabButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
            e.preventDefault();

            // Remove active from all tabs and contents
            tabButtons.forEach((btn) => btn.classList.remove("active"));
            tabContents.forEach((content) =>
                content.classList.remove("active")
            );

            // Add active to clicked tab
            this.classList.add("active");

            // Show corresponding content
            const targetId = this.getAttribute("data-bs-target");
            if (targetId) {
                const targetContent = document.querySelector(targetId);
                if (targetContent) {
                    targetContent.classList.add("active");
                    targetContent.style.display = "block";
                }
            }
        });
    });
}

// Form Handlers
function initializeFormHandlers() {
    // Add loading states to forms
    const forms = document.querySelectorAll("form");
    forms.forEach((form) => {
        form.addEventListener("submit", function () {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<i class="fas fa-spinner fa-spin"></i> Processing...';
            }
        });
    });

    // Enhanced form validation
    const inputs = document.querySelectorAll(".form-control, .form-select");
    inputs.forEach((input) => {
        input.addEventListener("blur", validateField);
        input.addEventListener("input", clearValidationErrors);
    });
}

// File Upload Enhancement
function initializeFileUpload() {
    const fileInputs = document.querySelectorAll('input[type="file"]');

    fileInputs.forEach((input) => {
        const label =
            input.closest(".file-upload-area") || input.nextElementSibling;

        if (label) {
            // Drag and drop
            ["dragenter", "dragover", "dragleave", "drop"].forEach(
                (eventName) => {
                    label.addEventListener(eventName, preventDefaults, false);
                }
            );

            ["dragenter", "dragover"].forEach((eventName) => {
                label.addEventListener(eventName, highlight, false);
            });

            ["dragleave", "drop"].forEach((eventName) => {
                label.addEventListener(eventName, unhighlight, false);
            });

            label.addEventListener(
                "drop",
                function (e) {
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        input.files = files;
                        updateFileLabel(input, files[0].name);
                    }
                },
                false
            );
        }

        // File selection
        input.addEventListener("change", function () {
            if (this.files.length > 0) {
                updateFileLabel(this, this.files[0].name);
            }
        });
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        e.target.closest(".file-upload-area")?.classList.add("dragover");
    }

    function unhighlight(e) {
        e.target.closest(".file-upload-area")?.classList.remove("dragover");
    }

    function updateFileLabel(input, fileName) {
        const label =
            input.closest(".file-upload-area")?.querySelector(".file-name") ||
            input.nextElementSibling?.querySelector(".file-name");
        if (label) {
            label.textContent = fileName;
        }
    }
}

// Deposit Form Functionality
function initializeDepositForm() {
    const countrySelect = document.getElementById("country");
    const bankSelect = document.getElementById("bank_id");
    const bankDetails = document.getElementById("bankDetails");

    if (countrySelect && bankSelect) {
        countrySelect.addEventListener("change", function () {
            const countryCode = this.value;
            if (countryCode) {
                loadBanksByCountry(countryCode);
            } else {
                bankSelect.innerHTML = '<option value="">Select Bank</option>';
                bankDetails.style.display = "none";
            }
        });

        bankSelect.addEventListener("change", function () {
            const bankId = this.value;
            if (bankId) {
                loadBankDetails(bankId);
                bankDetails.style.display = "block";
            } else {
                bankDetails.style.display = "none";
            }
        });
    }
}

// Withdrawal Form Functionality
function initializeWithdrawForm() {
    const paymentMethodSelect = document.getElementById("payment_method");

    if (paymentMethodSelect) {
        paymentMethodSelect.addEventListener("change", function () {
            const method = this.value;

            // Hide all method details
            document.querySelectorAll('[id$="Details"]').forEach((el) => {
                el.style.display = "none";
            });

            // Show selected method details
            if (method) {
                const detailsElement =
                    document.getElementById(
                        method.replace("_", "") + "Details"
                    ) || document.getElementById(method + "Details");
                if (detailsElement) {
                    detailsElement.style.display = "block";
                }
            }
        });
    }
}

// Copy to clipboard functionality
function initializeClipboardFunctions() {
    // Global copy functions for bank details
    window.copyToClipboard = function (fieldId, button) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        const value = field.value || field.textContent;
        if (!value || value === "N/A") {
            showToast("No data to copy", "warning");
            return;
        }

        if (navigator.clipboard && window.isSecureContext) {
            // Use modern clipboard API
            navigator.clipboard
                .writeText(value)
                .then(function () {
                    showCopySuccess(button);
                })
                .catch(function () {
                    fallbackCopyToClipboard(value, button);
                });
        } else {
            // Fallback for older browsers
            fallbackCopyToClipboard(value, button);
        }
    };

    window.copyAllBankDetails = function () {
        if (!window.currentBankData) {
            showToast("No bank details to copy", "warning");
            return;
        }

        const data = window.currentBankData;
        let allDetails = `Bank Details:\n`;
        allDetails += `Bank Name: ${data.name}\n`;
        if (data.account_number)
            allDetails += `Account Number: ${data.account_number}\n`;
        if (data.beneficiary_name)
            allDetails += `Beneficiary Name: ${data.beneficiary_name}\n`;
        if (data.iban) allDetails += `IBAN: ${data.iban}\n`;
        if (data.swift_code) allDetails += `SWIFT Code: ${data.swift_code}\n`;
        if (data.bic) allDetails += `BIC: ${data.bic}\n`;
        if (data.address) allDetails += `Address: ${data.address}\n`;

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard
                .writeText(allDetails)
                .then(function () {
                    showToast(
                        "All bank details copied to clipboard!",
                        "success"
                    );
                })
                .catch(function () {
                    fallbackCopyToClipboard(allDetails);
                });
        } else {
            fallbackCopyToClipboard(allDetails);
        }
    };
}

function fallbackCopyToClipboard(text, button) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    textArea.style.top = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        const successful = document.execCommand("copy");
        if (successful) {
            if (button) {
                showCopySuccess(button);
            } else {
                showToast("Copied to clipboard!", "success");
            }
        } else {
            showToast("Failed to copy to clipboard", "error");
        }
    } catch (err) {
        console.error("Copy failed:", err);
        showToast("Failed to copy to clipboard", "error");
    } finally {
        document.body.removeChild(textArea);
    }
}

function showCopySuccess(button) {
    const originalHtml = button.innerHTML;
    button.innerHTML =
        '<i class="iconify" data-icon="material-symbols:check"></i>';
    button.classList.add("btn-success");
    button.classList.remove("btn-outline-secondary");

    setTimeout(function () {
        button.innerHTML = originalHtml;
        button.classList.remove("btn-success");
        button.classList.add("btn-outline-secondary");
    }, 2000);

    showToast("Copied!", "success");
}

// Load banks by country (AJAX)
async function loadBanksByCountry(countryCode) {
    const bankSelect = document.getElementById("bank_id");
    if (!bankSelect) return;

    try {
        bankSelect.innerHTML = '<option value="">Loading...</option>';

        const response = await fetch("/client/get-banks-by-country", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content"),
            },
            body: JSON.stringify({ country_code: countryCode }),
        });

        const data = await response.json();

        if (data.success) {
            bankSelect.innerHTML = '<option value="">Select Bank</option>';
            data.banks.forEach((bank) => {
                const option = document.createElement("option");
                option.value = bank.id;
                option.textContent = bank.name;
                bankSelect.appendChild(option);
            });
        } else {
            bankSelect.innerHTML = '<option value="">No banks found</option>';
        }
    } catch (error) {
        console.error("Error loading banks:", error);
        bankSelect.innerHTML = '<option value="">Error loading banks</option>';
    }
}

// Load bank details (AJAX)
async function loadBankDetails(bankId) {
    const bankDetails = document.getElementById("bankDetails");
    if (!bankDetails) return;

    try {
        const response = await fetch("/client/get-bank-details", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content"),
            },
            body: JSON.stringify({ bank_id: bankId }),
        });

        const data = await response.json();

        if (data.success) {
            // Update bank details display
            updateBankDetailsDisplay(data.bank);
        }
    } catch (error) {
        console.error("Error loading bank details:", error);
    }
}

// Update bank details display
function updateBankDetailsDisplay(bank) {
    const bankDetails = document.getElementById("bankDetails");
    if (!bankDetails || !bank) return;

    const detailsHTML = `
        <div class="bank-info">
            <h6 class="mb-2">Bank Details</h6>
            <div class="row g-2">
                <div class="col-12"><strong>Bank Name:</strong> ${
                    bank.name
                }</div>
                <div class="col-12"><strong>Account Name:</strong> ${
                    bank.account_name || "N/A"
                }</div>
                <div class="col-12"><strong>Account Number:</strong> ${
                    bank.account_number || "N/A"
                }</div>
                <div class="col-12"><strong>SWIFT Code:</strong> ${
                    bank.swift_code || "N/A"
                }</div>
                ${
                    bank.additional_info
                        ? `<div class="col-12"><strong>Additional Info:</strong> ${bank.additional_info}</div>`
                        : ""
                }
            </div>
        </div>
    `;

    bankDetails.innerHTML = detailsHTML;
}

// Form validation
function validateField(e) {
    const field = e.target;
    const value = field.value.trim();
    const fieldName = field.getAttribute("name");

    // Clear previous errors
    clearFieldErrors(field);

    // Validation rules
    let isValid = true;
    let errorMessage = "";

    if (field.hasAttribute("required") && !value) {
        isValid = false;
        errorMessage = "This field is required";
    } else if (fieldName === "email" && value && !isValidEmail(value)) {
        isValid = false;
        errorMessage = "Please enter a valid email address";
    } else if (fieldName === "amount" && value) {
        const amount = parseFloat(value);
        const min = parseFloat(field.getAttribute("min") || 0);
        const max = parseFloat(field.getAttribute("max") || Infinity);

        if (amount < min) {
            isValid = false;
            errorMessage = `Minimum amount is ${min}`;
        } else if (amount > max) {
            isValid = false;
            errorMessage = `Maximum amount is ${max}`;
        }
    }

    if (!isValid) {
        showFieldError(field, errorMessage);
    }

    return isValid;
}

// Show field error
function showFieldError(field, message) {
    field.classList.add("is-invalid");

    let errorDiv = field.parentNode.querySelector(".invalid-feedback");
    if (!errorDiv) {
        errorDiv = document.createElement("div");
        errorDiv.className = "invalid-feedback";
        field.parentNode.appendChild(errorDiv);
    }
    errorDiv.textContent = message;
}

// Clear field errors
function clearFieldErrors(field) {
    field.classList.remove("is-invalid");
    const errorDiv = field.parentNode.querySelector(".invalid-feedback");
    if (errorDiv) {
        errorDiv.remove();
    }
}

// Clear validation errors on input
function clearValidationErrors(e) {
    const field = e.target;
    if (field.classList.contains("is-invalid")) {
        clearFieldErrors(field);
    }
}

// Utility functions
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Show toast notification
function showToast(message, type = "success") {
    // Create toast element
    const toast = document.createElement("div");
    toast.className = `alert alert-${type} toast-notification`;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1060;
        min-width: 250px;
        animation: slideInRight 0.3s ease-out;
    `;
    toast.textContent = message;

    // Add close button
    const closeBtn = document.createElement("button");
    closeBtn.type = "button";
    closeBtn.className = "btn-close";
    closeBtn.style.cssText = "float: right; margin-left: 10px;";
    closeBtn.addEventListener("click", () => toast.remove());

    toast.appendChild(closeBtn);
    document.body.appendChild(toast);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 5000);
}

// Refresh data functions
async function refreshDeposits() {
    try {
        const response = await fetch("/deposits/refresh");
        const data = await response.json();

        if (data.success) {
            location.reload(); // Simple reload for now
        }
    } catch (error) {
        console.error("Error refreshing deposits:", error);
    }
}

async function refreshWithdrawals() {
    try {
        const response = await fetch("/withdrawals/refresh");
        const data = await response.json();

        if (data.success) {
            location.reload(); // Simple reload for now
        }
    } catch (error) {
        console.error("Error refreshing withdrawals:", error);
    }
}

// Add smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute("href"));
        if (target) {
            target.scrollIntoView({
                behavior: "smooth",
                block: "start",
            });
        }
    });
});

// Handle window resize
let resizeTimeout;
window.addEventListener("resize", function () {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function () {
        // Close sidebar on resize to desktop
        if (window.innerWidth > 768) {
            const sidebar = document.querySelector(".mobile-sidebar");
            const overlay = document.querySelector(".sidebar-overlay");
            if (sidebar && sidebar.classList.contains("active")) {
                sidebar.classList.remove("active");
                overlay.classList.remove("active");
                document.body.style.overflow = "";
            }
        }
    }, 250);
});

// Add touch gesture support for sidebar
let touchStartX = 0;
let touchEndX = 0;

document.addEventListener("touchstart", function (e) {
    touchStartX = e.changedTouches[0].screenX;
});

document.addEventListener("touchend", function (e) {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipeGesture();
});

function handleSwipeGesture() {
    const swipeThreshold = 50;
    const sidebar = document.querySelector(".mobile-sidebar");
    const overlay = document.querySelector(".sidebar-overlay");

    // Swipe right to open sidebar (from left edge)
    if (touchStartX < 20 && touchEndX - touchStartX > swipeThreshold) {
        if (sidebar && !sidebar.classList.contains("active")) {
            sidebar.classList.add("active");
            overlay.classList.add("active");
            document.body.style.overflow = "hidden";
        }
    }

    // Swipe left to close sidebar
    if (touchStartX - touchEndX > swipeThreshold) {
        if (sidebar && sidebar.classList.contains("active")) {
            sidebar.classList.remove("active");
            overlay.classList.remove("active");
            document.body.style.overflow = "";
        }
    }
}
