// Trading dashboard JavaScript functionality
var client_id;
var assetId = 1;

// Enhanced dynamic functionality
$(document).ready(function () {
    // Initialize real-time clock
    updateMarketTime();
    setInterval(updateMarketTime, 1000);

    // Initialize loading states
    showLoadingStates();

    // Enhanced search with debouncing and visual feedback
    let searchTimeout;
    $(".search").on("input", function () {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val().trim().toLowerCase();
        const tabContainer = $(this).closest(".tab-pane");
        const searchInput = $(this);

        // Add loading state to search
        searchInput.addClass("loading");

        searchTimeout = setTimeout(() => {
            filterAssets(tabContainer, searchTerm);
            searchInput.removeClass("loading");

            // Show results count
            const visibleRows = tabContainer.find(".asset-row:visible").length;
            showSearchResults(visibleRows, searchTerm);
        }, 300);
    });

    // Enhanced asset row interactions with click effects
    $(".asset-row").hover(function () {
        $(this)
            .addClass("loading")
            .delay(200)
            .queue(function (next) {
                $(this).removeClass("loading");
                next();
            });
    });

    // Initialize asset row dropdown functionality
    initializeAssetRowClicks();

    // Enhanced price change animations with sound effect simulation
    function animatePriceChange(element, isIncrease) {
        element.addClass(isIncrease ? "price-up" : "price-down");

        // Add particle effect
        createPriceParticle(element, isIncrease);

        setTimeout(() => {
            element.removeClass("price-up price-down");
        }, 1000);
    }

    // Create price change particle effect
    function createPriceParticle(element, isIncrease) {
        const particle = $('<div class="price-particle"></div>');
        const rect = element[0].getBoundingClientRect();

        particle.css({
            position: "fixed",
            left: rect.left + rect.width / 2,
            top: rect.top,
            width: "4px",
            height: "4px",
            background: isIncrease ? "#10b981" : "#ef4444",
            borderRadius: "50%",
            pointerEvents: "none",
            zIndex: 9999,
            animation: `priceParticle${
                isIncrease ? "Up" : "Down"
            } 1s ease-out forwards`,
        });

        $("body").append(particle);
        setTimeout(() => particle.remove(), 1000);
    }

    // Simulate real-time price updates (replace with actual WebSocket)
    function simulatePriceUpdates() {
        $(".bid_price, .ask_price").each(function () {
            if (Math.random() > 0.7) {
                // 30% chance of price change
                const currentPrice = parseFloat($(this).text());
                const change = (Math.random() - 0.5) * 0.001 * currentPrice;
                const newPrice = (currentPrice + change).toFixed(5);

                $(this).text(newPrice);
                animatePriceChange($(this), change > 0);
            }
        });
    }

    // Start price simulation (remove in production)
    // setInterval(simulatePriceUpdates, 3000);

    // Enhanced modal interactions
    $("#asset-select").on("change", function () {
        const selectedOption = $(this).find(":selected");
        const bidPrice = selectedOption.data("bid");
        const askPrice = selectedOption.data("ask");

        $("#bid").val(bidPrice);
        $("#ask").val(askPrice);
        $("#sell-price").text(bidPrice);
        $("#buy-price").text(askPrice);

        // Add selection animation
        $(this)
            .addClass("loading")
            .delay(300)
            .queue(function (next) {
                $(this).removeClass("loading");
                next();
            });
    });

    // Toggle switches for stop loss and take profit
    $("#stopLossSwitch").on("change", function () {
        const container = $("#stopLossContainer");
        if (this.checked) {
            container.slideDown(300);
        } else {
            container.slideUp(300);
        }
    });

    $("#takeProfitSwitch").on("change", function () {
        const container = $("#takeProfitContainer");
        if (this.checked) {
            container.slideDown(300);
        } else {
            container.slideUp(300);
        }
    });

    $("#stopLossSwitchPending").on("change", function () {
        const container = $("#stopLossContainerPending");
        if (this.checked) {
            container.slideDown(300);
        } else {
            container.slideUp(300);
        }
    });

    $("#takeProfitSwitchPending").on("change", function () {
        const container = $("#takeProfitContainerPending");
        if (this.checked) {
            container.slideDown(300);
        } else {
            container.slideUp(300);
        }
    });

    // Enhanced order button interactions
    $(".new_order, .pending_order").on("click", function () {
        const assetId = this.getAttribute("data-asset");
        const tab = this.getAttribute("data-tab");

        // Add click animation
        $(this).css("transform", "scale(0.95)");
        setTimeout(() => {
            $(this).css("transform", "");
        }, 150);

        if ($(this).hasClass("new_order")) {
            $("#newTab").val(tab);
            $("#asset-select").val(assetId).trigger("change");
        } else {
            $("#pendingTab").val(tab);
            $("#currency").val(assetId);
        }
    });

    // Enhanced star favorites
    $(".fa-star").on("click", function (e) {
        e.preventDefault();
        const star = $(this);

        // Add click animation
        star.css("transform", "scale(1.5) rotate(360deg)");
        setTimeout(() => {
            star.css("transform", "");
        }, 300);

        // Simulate toggle (replace with actual AJAX call)
        if (star.hasClass("text-warning")) {
            star.removeClass("text-warning").addClass("text-secondary");
        } else {
            star.removeClass("text-secondary").addClass("text-warning");
        }
    });

    // Tab switching enhancements
    $(".nav-link").on("click", function () {
        // Add loading state to target tab content
        const targetId = $(this).attr("data-bs-target");
        $(targetId)
            .addClass("loading")
            .delay(500)
            .queue(function (next) {
                $(this).removeClass("loading");
                next();
            });
    });

    // Form validation enhancements
    $("form").on("submit", function (e) {
        const submitBtn = $(this).find('button[type="submit"]:focus');
        submitBtn.addClass("loading");

        // Basic validation
        const amount = $(this).find('input[name="amount"]').val();
        if (!amount || parseFloat(amount) < 0.01) {
            e.preventDefault();
            alert("Please enter a valid amount (minimum 0.01)");
            submitBtn.removeClass("loading");
            return false;
        }
    });

    // Initialize tooltips (if Bootstrap tooltips are available)
    if (typeof bootstrap !== "undefined" && bootstrap.Tooltip) {
        $('[data-bs-toggle="tooltip"]').each(function () {
            new bootstrap.Tooltip(this);
        });
    }
});

// Utility functions
function filterAssets(tabContainer, searchTerm) {
    tabContainer.find(".asset-row").each(function () {
        const assetName = $(this).find(".name").text().trim().toLowerCase();
        if (assetName.includes(searchTerm)) {
            $(this).show().addClass("filtered-in");
        } else {
            $(this).hide().removeClass("filtered-in");
        }
    });
}

function showLoadingStates() {
    // Add initial loading animation to tables
    $(".table-responsive")
        .addClass("loading")
        .delay(1000)
        .queue(function (next) {
            $(this).removeClass("loading");
            next();
        });
}

function updateMarketTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString("en-US", {
        hour12: true,
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    });
    $("#currentTime").text(timeString);

    // Update market status based on time (simplified logic)
    const hour = now.getHours();
    const isWeekend = now.getDay() === 0 || now.getDay() === 6;
    const isMarketOpen = !isWeekend && hour >= 9 && hour < 17;

    const statusIndicator = $(".status-indicator");
    const statusText = $(".market-status span");

    if (isMarketOpen) {
        statusIndicator.removeClass("closed").addClass("live");
        statusText.text("Market Open");
    } else {
        statusIndicator.removeClass("live").addClass("closed");
        statusText.text("Market Closed");
    }
}

function showSearchResults(count, term) {
    // Remove existing notification
    $(".search-results").remove();

    if (term && count >= 0) {
        const notification = $(`
            <div class="search-results" style="
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: rgba(255,255,255,0.9);
                backdrop-filter: blur(10px);
                border-radius: 8px;
                padding: 8px 12px;
                font-size: 12px;
                color: var(--text-secondary);
                z-index: 10;
                animation: slideDown 0.3s ease;
            ">
                ${count} results found for "${term}"
            </div>
        `);

        $(".search")
            .first()
            .parent()
            .css("position", "relative")
            .append(notification);

        // Auto-remove after 3 seconds
        setTimeout(() => notification.fadeOut(), 3000);
    }
}

// WebSocket connection (placeholder for real implementation)
function initializeWebSocket() {
    // Replace with actual WebSocket implementation
    console.log("WebSocket initialization placeholder");
    // const ws = new WebSocket('wss://your-websocket-endpoint');
    // ws.onmessage = function(event) {
    //     const data = JSON.parse(event.data);
    //     updatePrices(data);
    // };
}

// Keyboard shortcuts
document.addEventListener("keydown", function (e) {
    // Escape key to close modals
    if (e.key === "Escape") {
        $(".modal.show").modal("hide");
    }

    // Quick search focus (Ctrl/Cmd + K)
    if ((e.ctrlKey || e.metaKey) && e.key === "k") {
        e.preventDefault();
        $(".search:visible").first().focus();
    }
});

// Asset row dropdown functionality
function initializeAssetRowClicks() {
    // Remove any existing event listeners
    document.querySelectorAll(".asset-row").forEach((row) => {
        row.replaceWith(row.cloneNode(true));
    });

    document.querySelectorAll(".asset-row").forEach((row) => {
        row.addEventListener("click", function (e) {
            // Don't trigger if clicking on star icon or other interactive elements
            if (
                e.target.closest(".fa-star") ||
                e.target.closest('a[href*="toggle.favourite"]')
            ) {
                return;
            }

            e.preventDefault();
            e.stopPropagation();

            // Get asset ID from data attribute
            const assetId = this.dataset.assetId;

            if (!assetId) return;

            // Close any other open dropdowns/details
            document.querySelectorAll(".asset-details").forEach((dropdown) => {
                if (dropdown.id !== `assetDetails${assetId}`) {
                    dropdown.classList.remove("show");
                    dropdown.style.display = "none";

                    // Remove active class from corresponding row
                    const correspondingRow = dropdown.previousElementSibling;
                    if (
                        correspondingRow &&
                        correspondingRow.classList.contains("asset-row")
                    ) {
                        correspondingRow.classList.remove("active");
                    }
                }
            });

            // Find and toggle current dropdown
            let dropdown = document.getElementById(`assetDetails${assetId}`);

            if (dropdown) {
                const isVisible = dropdown.classList.contains("show");

                if (isVisible) {
                    dropdown.classList.remove("show");
                    dropdown.style.display = "none";
                    this.classList.remove("active");
                } else {
                    dropdown.classList.add("show");
                    dropdown.style.display = "table-row";
                    this.classList.add("active");
                }
            }
        });
    });
}

// Toggle favorite function with gold star
function toggleFavorite(event, assetId, tab = "fav") {
    event.stopPropagation(); // Prevent row click

    const starIcon = event.target;

    // Add loading state animation
    starIcon.classList.add("fa-spin");

    // Navigate to toggle route
    setTimeout(() => {
        window.location.href = `/toggle-favourite/${assetId}?tab=${tab}`;
    }, 300);
}

// Open new order modal
function openNewOrderModal(assetId, tab) {
    const modal = document.getElementById("newOrderModal");
    if (modal) {
        // Set asset data
        const assetSelect = modal.querySelector("#asset-select");
        if (assetSelect) {
            assetSelect.value = assetId;
            $(assetSelect).trigger("change");
        }

        // Store tab info
        modal.querySelector(".new_order")?.setAttribute("data-asset", assetId);
        modal.querySelector(".new_order")?.setAttribute("data-tab", tab);

        // Show modal
        $(modal).modal("show");
    }
}

// Open pending order modal
function openPendingOrderModal(assetId, tab) {
    const modal = document.getElementById("newPendingOrderModal");
    if (modal) {
        // Set asset data
        const assetSelect = modal.querySelector("#asset-select-pending");
        if (assetSelect) {
            assetSelect.value = assetId;
            $(assetSelect).trigger("change");
        }

        // Store tab info
        modal
            .querySelector(".pending_order")
            ?.setAttribute("data-asset", assetId);
        modal.querySelector(".pending_order")?.setAttribute("data-tab", tab);

        // Show modal
        $(modal).modal("show");
    }
}

// Show trading hours function
function showTradingHours(symbol) {
    // Determine which modal to show based on symbol type
    let modalId = "tradeHoursModal"; // Default to forex

    if (
        symbol.includes("BTC") ||
        symbol.includes("ETH") ||
        symbol.includes("LTC")
    ) {
        modalId = "CryptoHoursModal";
    } else if (
        symbol.includes("USD") ||
        symbol.includes("EUR") ||
        symbol.includes("GBP")
    ) {
        modalId = "tradeHoursModal";
    } else if (
        symbol.includes("AAPL") ||
        symbol.includes("GOOGL") ||
        symbol.includes("MSFT")
    ) {
        modalId = "StocksHoursModal";
    } else if (
        symbol.includes("DOW") ||
        symbol.includes("NASDAQ") ||
        symbol.includes("SP500")
    ) {
        modalId = "IndicesHoursModal";
    } else if (
        symbol.includes("GOLD") ||
        symbol.includes("OIL") ||
        symbol.includes("SILVER")
    ) {
        modalId = "CommodityHoursModal";
    }

    const modal = document.getElementById(modalId);
    if (modal) {
        $(modal).modal("show");
    }
}

// Show asset details function for favorites
function showAssetDetails(symbol, assetId) {
    showTradingHours(symbol);
}

// Show forex trading hours
function showForexDetails(symbol, assetId) {
    const modal = document.getElementById("tradeHoursModal");
    if (modal) {
        $(modal).modal("show");
    }
}

// Show crypto trading hours
function showCryptoDetails(symbol, assetId) {
    const modal = document.getElementById("CryptoHoursModal");
    if (modal) {
        $(modal).modal("show");
    }
}

// Show stocks trading hours
function showStocksDetails(symbol, assetId) {
    const modal = document.getElementById("StocksHoursModal");
    if (modal) {
        $(modal).modal("show");
    }
}

// Show indices trading hours
function showIndicesDetails(symbol, assetId) {
    const modal = document.getElementById("IndicesHoursModal");
    if (modal) {
        $(modal).modal("show");
    }
}

// Show commodity trading hours
function showCommodityDetails(symbol, assetId) {
    const modal = document.getElementById("CommodityHoursModal");
    if (modal) {
        $(modal).modal("show");
    }
}

// Initialize on page load
document.addEventListener("DOMContentLoaded", function () {
    initializeAssetRowClicks();

    // Re-initialize after any dynamic content updates
    if (typeof MutationObserver !== "undefined") {
        const observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                if (
                    mutation.type === "childList" &&
                    mutation.addedNodes.length > 0
                ) {
                    initializeAssetRowClicks();
                }
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
        });
    }
});

// Intersection Observer for performance optimization
if ("IntersectionObserver" in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
            }
        });
    });

    // Observe asset rows for lazy loading effects
    document.querySelectorAll(".asset-row").forEach((row) => {
        observer.observe(row);
    });
}
