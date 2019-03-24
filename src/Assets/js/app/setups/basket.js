/**
 * @name searchItem
 * @description Search Products over API
 * @param {string} [query] [Keyword to search]
 * @param {string} [action] [Define where the function is called (add or edit)]
 *
 * @const timer
 */
var timer;
function searchItem(query, action) {
	clearTimeout(timer);
	timer = setTimeout(function validate() {
		if (query.length < 2) {
			return;
		}
		$.ajax({
			url: webRootJs + 'thirdParties/searchProducts',
			type: 'get',
			data: {
				"q": query
			},
			success: function (response) {

				$(".search_results." + action).html("");

				var products = response.products;

				if (products[0] == null) {
					$(".search_results." + action).append("No products found...");
				}

				$.each(products, function (key, value) {
					let list = $('<li class="text-card"></li>');

					let src = value.src;
					let encodedSrc = encodeURIComponent(src);

					let encodedTitle = value.title;
					let title = decodeURIComponent(encodedTitle);

					let url = value.href;
					let encodedUrl = encodeURIComponent(url);

					list.html(` <div class="wrapper"> <div class="card-container"> <div class="top" style="background: url(${src}) no-repeat center center; background-size: contain"></div> <a onclick="addToBasket(\`${encodedTitle}\`, '${encodedUrl}', '${encodedSrc}', '${action}')" class="bottom"><i class="fas fa-plus"></i></a> </div> <div class="inside"> <div class="icon"><i class="fas fa-info-circle"></i></div> <div class="contents"> ${title} </div> </div> </div>`);

					$(".search_results." + action).append(list);
				});

				var image = $('mediumimage');

			},
			error: function (error) {
				$(".search_results." + action).html("No products found...");
			}
		});

	}, 400);
}

/**
 * @name addToBasket
 * @description Add to input selected product from search
 * @param {string} [title] [Title of product]
 * @param {string} [url] [Url of product]
 * @param {string} [src] [Source of image]
 * @param {string} [action] [Define where the function is called (add or edit)]
 */
function addToBasket(title, url, src, action) {
	$('.hiddenInput.' + action).val($('.hiddenInput.' + action).val() + title + ';' + url + ';' + src + ',');

	$(".search_results." + action).html("");
	$(".liveInput." + action).val("");

	decodedTitle = decodeURIComponent(title);
	decodedSrc = decodeURIComponent(src);

	let list = $('<li class="text-card"></li>');

	list.html(` <div class="wrapper"> <div class="card-container"> <div class="top" style="background: url(${decodedSrc}) no-repeat center center; background-size: contain"></div> <a onclick="deleteFromBasket(\`${title}\`,this,'${action}')" class="bottom"><i class="far fa-trash-alt"></i></a> </div> <div class="inside"> <div class="icon"><i class="fas fa-info-circle"></i></div> <div class="contents"> ${decodedTitle} </div> </div> </div>`);

	$(".basket_items." + action).append(list);
}

/**
 * @name deleteFromBasket
 * @description Delete from input selected product
 * @param {string} [title] [Title of product to delete]
 * @param {string} [parent] [DOM element who triggered the function]
 * @param {string} [action] [Define where the function is called (add or edit)]
 */
function deleteFromBasket(title, parent, action) {
	var ResearchArea = $('.hiddenInput.' + action).val();
	var splitTextInput = ResearchArea.split(",");

	new_arr = $.grep(splitTextInput, function (n, i) { // just use arr
		return n.split(";")[0] != title;
	});

	$('.hiddenInput.' + action).val(new_arr);

	parent.closest('li').remove();
}
