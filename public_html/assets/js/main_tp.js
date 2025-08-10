function requiredMargin() {
    var selectedOption = $("#posCurrencyId option:selected");
    var currencyName = selectedOption.data("symbol");
    var contractSize = selectedOption.data("contract-size");
    var baseCurrency = selectedOption.data("base");
    var posAmount = $("#posAmount").val();
    var posPrice = $("#posPrice").val();
    var leverage = selectedOption.data("leverage");
    if ($("#posCurrencyId").val() != 0) {
        if (
            currencyName.startsWith("USD") ||
            (!currencyName.includes("USD") && baseCurrency !== "USD")
        ) {
            var reqMargin =
                ((posAmount * posPrice * contractSize) / leverage) *
                (1 / posPrice);
        } else {
            var reqMargin = (posAmount * posPrice * contractSize) / leverage;
        }

        $("#reqMargin").val(parseFloat(reqMargin).toFixed(3));
    }
}

function startAjaxPnl() {
    if ($.active > 0) {
        return;
    }
    if ($("#asset-select").length) {
        const selectedOption = $(this).find(":selected");
        assetId = $("#asset-select").val();
    }

    $.ajax({
        url:
            "https://crm.avaxtrades.com/client/get_pnl/" +
            client_id +
            "/" +
            assetId +
            "/fromClient",
        method: "GET",
        dataType: "json",
        success: function (data) {
            if (!data || typeof data !== "object") {
                console.error(
                    "Invalid response from server - not an object:",
                    data
                );
                setTimeout(startAjaxPnl, 5000);
                return;
            }

            if (!data.orders) {
                console.warn(
                    "No orders array in response, setting empty array"
                );
                data.orders = [];
            }

            if (!data.assets) {
                console.warn(
                    "No assets array in response, setting empty array"
                );
                data.assets = [];
            }

            if ($("#asset-select").length) {
                const selectedOption = $("#asset-select").find(":selected");

                selectedOption.data("bid", data.bid);
                selectedOption.attr("data-bid", data.bid);

                selectedOption.data("ask", data.ask);
                selectedOption.attr("data-ask", data.ask);

                $("#bid").val(data.bid);
                $("#ask").val(data.ask);

                $("#sell-price").text(data.bid);
                $("#buy-price").text(data.ask);
            }

            $(".sellPrice").text(parseFloat(data.bid).toFixed(2));
            $(".bidInput").val(data.bid);
            $(".buyPrice").text(parseFloat(data.ask).toFixed(2));
            $(".askInput").val(data.ask);

            const orderIds = data.orders.map((order) => Number(order.id));

            // Only remove orders if we're in the trading tab and have a valid response
            if (
                $("#trading-tab").hasClass("active") &&
                data.orders &&
                data.orders.length >= 0
            ) {
                $(".active_pnl").each(function () {
                    const orderId = Number($(this).attr("data-order-id"));
                    const row = $(this).closest("tr");

                    // Don't remove if order ID is missing or invalid
                    if (!orderId || isNaN(orderId)) {
                        return;
                    }

                    // Don't remove if row has our protection class
                    if (row.hasClass("debug-protected")) {
                        return;
                    }

                    if (!orderIds.includes(orderId)) {
                        row.remove();
                    }
                });
            } else {
                // Skip order removal - not in trading tab or invalid response
            }

            data.orders.forEach((order) => {
                const pnlElement = $('.pnl[data-order-id="' + order.id + '"]');
                if (pnlElement.length) {
                    pnlElement
                        .find("div")
                        .text(parseFloat(order.pnl).toFixed(2))
                        .removeClass("text-danger text-success")
                        .addClass(
                            parseFloat(order.pnl) < 0
                                ? "text-danger"
                                : "text-success"
                        );
                }

                $('.current_price[data-order-id="' + order.id + '"]').text(
                    order.close_price
                );
            });

            data.assets.forEach((asset) => {
                const bidPriceElement = $(
                    '.bid_price[data-asset-id="' + asset.id + '"]'
                );
                const askPriceElement = $(
                    '.ask_price[data-asset-id="' + asset.id + '"]'
                );

                if (bidPriceElement.length) {
                    bidPriceElement
                        .text(asset.bid_price)
                        .removeClass("text-danger text-success");
                    if (asset.bid_price < asset.last_bid) {
                        bidPriceElement.addClass("text-danger");
                    } else if (asset.bid_price > asset.last_bid) {
                        bidPriceElement.addClass("text-success");
                    }
                }

                if (askPriceElement.length) {
                    askPriceElement
                        .text(asset.ask_price)
                        .removeClass("text-danger text-success");
                    if (asset.ask_price < asset.last_ask) {
                        askPriceElement.addClass("text-danger");
                    } else if (asset.ask_price > asset.last_ask) {
                        askPriceElement.addClass("text-success");
                    }
                }
            });

            $(".currentPL")
                .text("$ " + parseFloat(data.pnl).toFixed(2))
                .removeClass("text-danger text-success")
                .addClass(
                    parseFloat(data.pnl) < 0 ? "text-danger" : "text-success"
                );

            $(".equity")
                .text("$ " + parseFloat(data.equity).toFixed(2))
                .removeClass("text-danger text-success")
                .addClass(
                    parseFloat(data.equity) < 0 ? "text-danger" : "text-success"
                );

            $(".online")
                .text(data.online_text)
                .closest(".d-flex")
                .removeClass("text-warning text-success")
                .addClass(data.online ? "text-success" : "text-warning");

            setTimeout(startAjaxPnl, 5000);
        },
        error: function () {
            console.error("Error fetching PnL data.");
            setTimeout(startAjaxPnl, 5000);
        },
    });
}

$(document).ready(function () {
    // Reduced polling frequency to prevent aggressive order removal
    setInterval(startAjaxPnl, 2000); // Changed from 100ms to 2000ms (2 seconds)
});
