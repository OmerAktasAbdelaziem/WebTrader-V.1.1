function requiredMargin() {
	var selectedOption = $('#posCurrencyId option:selected');
	var currencyName   = selectedOption.data('symbol');
	var contractSize   = selectedOption.data('contract-size');
	var baseCurrency   = selectedOption.data('base');
	var posAmount      = $('#posAmount').val();
	var posPrice       = $('#posPrice').val();
	var leverage       = selectedOption.data('leverage');
	if($('#posCurrencyId').val() != 0){
		if (currencyName.startsWith("USD") || (!currencyName.includes("USD") && baseCurrency !== "USD")) {
			var reqMargin = ((posAmount * posPrice * contractSize) / leverage) * (1/posPrice);
		}else{
			var reqMargin = (posAmount * posPrice * contractSize) / leverage;
		}
	
		console.log(posAmount, posPrice, leverage, contractSize, reqMargin);
		$('#reqMargin').val(parseFloat(reqMargin).toFixed(3));
	}

}

function startAjaxPnl() {
	if ($.active > 0) {
        return;
    }
	if ($('#asset-select').length) {
		const selectedOption = $(this).find(':selected');
		assetId = $('#asset-select').val();
	}
	
	$.ajax({
		url: 'https://new.elitexcrm.com/client/get_pnl/' + client_id + '/' + assetId+'/fromClient',
		method: 'GET',
		dataType: 'json',
		success: function (data) {
			if ($('#asset-select').length) {
				const selectedOption = $(this).find(':selected');
				
				selectedOption.data('bid', data.bid);
				selectedOption.attr('data-bid', data.bid);
				
				selectedOption.data('ask', data.ask);
				selectedOption.attr('data-ask', data.ask);
				
				$('#bid').val(data.bid);
				$('#ask').val(data.ask);
	
				$('#sell-price').text(data.bid);
				$('#buy-price').text(data.ask);
			}

			$('.sellPrice').text(parseFloat(data.bid).toFixed(2));
			$('.bidInput').val(data.bid);
			$('.buyPrice').text(parseFloat(data.ask).toFixed(2));
			$('.askInput').val(data.ask);
			// updatePosPrice();
			// requiredMargin();
			const orderIds = data.orders.map(order => Number(order.id)); // Convert IDs to numbers
			
			$('.active_pnl').each(function () {
				const orderId = Number($(this).attr('data-order-id')); // Convert to number
			
				if (!orderIds.includes(orderId)) {
					$(this).closest('tr').remove();
				}
			});
			
			data.orders.forEach(order => {
				$('.pnl[data-order-id="' + order.id + '"]').find('div')
					.text(parseFloat(order.pnl).toFixed(2))
					.removeClass('text-danger text-success')
					.addClass(parseFloat(order.pnl) < 0 ? 'text-danger' : 'text-success');

				$('.current_price[data-order-id="' + order.id + '"]').text(order.close_price);
			});

			data.assets.forEach(asset => {
				$('.bid_price[data-asset-id="' + asset.id + '"]').text(asset.bid_price);
				$('.bid_price[data-asset-id="' + asset.id + '"]').removeClass('text-danger','text-success');
				$('.ask_price[data-asset-id="' + asset.id + '"]').removeClass('text-danger','text-success');
				$('.ask_price[data-asset-id="' + asset.id + '"]').text(asset.ask_price);

				if (asset.bid_price < asset.last_bid) {
					$('.bid_price[data-asset-id="' + asset.id + '"]').addClass('text-danger');
				}

				if (asset.bid_price > asset.last_bid) {
					$('.bid_price[data-asset-id="' + asset.id + '"]').addClass('text-success');
				}

				if (asset.ask_price < asset.last_ask) {
					$('.ask_price[data-asset-id="' + asset.id + '"]').addClass('text-danger');
				}

				if (asset.ask_price > asset.last_ask) {
					$('.ask_price[data-asset-id="' + asset.id + '"]').addClass('text-success');
				}
			});

			$('.currentPL').text('$ ' + data.pnl)
				.removeClass('text-danger text-success')
				.addClass(parseFloat(data.pnl) < 0 ? 'text-danger' : 'text-success');

			$('.equity').text('$ ' + data.equity)
				.removeClass('text-danger text-success')
				.addClass(parseFloat(data.equity) < 0 ? 'text-danger' : 'text-success');

			$('.online').text(data.online_text)
				.closest('.d-flex')
				.removeClass('text-warning text-success')
				.addClass(data.online ? 'text-success' : 'text-warning');
				
			startAjaxPnl();
		},
		error: function () {
			console.error('Error fetching PnL data.');
			startAjaxPnl();
		}
	});

}

$(document).ready(function(){
	setInterval(startAjaxPnl, 100);
});