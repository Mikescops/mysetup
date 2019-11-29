/**
 * @name searchItem
 * @description Search Products over API
 * @param {string} [query] [Keyword to search]
 * @param {string} [action] [Define where the function is called (add or edit)]
 *
 * @const timer
 */
var timer;
const searchItem = (query, action) => {
	clearTimeout(timer);
	timer = setTimeout(() => {
		if (query.length < 2) {
			return;
		}
		$.ajax({
			url: webRootJs + 'thirdParties/searchProducts',
			type: 'get',
			data: {
				'q': query
			},
			success: (response) => {

				$('.search_results').html('');

				const products = response.products;

				if (products[0] == null) {
					$('.search_results').append('No products found...');
				}

				$.each(products, (_key, value) => {
					let list = $('<li class="text-card"></li>');

					let src = value.src;
					let encodedSrc = encodeURIComponent(src);

					let encodedTitle = value.title;
					let title = decodeURIComponent(encodedTitle);

					let url = value.href;
					let encodedUrl = encodeURIComponent(url);

					list.html(`<div class="wrapper"> <div class="card-container"> <div class="top" style="background: url(${src}) no-repeat center center; background-size: contain"></div> <a onclick="addToBasket(\`${encodedTitle}\`, '${encodedUrl}', '${encodedSrc}')" class="bottom"><i class="fas fa-plus"></i></a> </div> <div class="inside"> <div class="icon"><i class="fas fa-info-circle"></i></div> <div class="contents"> ${title} </div> </div> </div>`);

					$('.search_results').append(list);
				});

			},
			error: () => {
				$('.search_results').html('No products found...');
			}
		});

	}, 400);
};

let basket = [];
/**
 * @name addToBasket
 * @description Add to input selected product from search
 * @param {string} [title] [Title of product]
 * @param {string} [url] [Url of product]
 * @param {string} [src] [Source of image]
 */
const addToBasket = (title, url, src) => {
	basket.push({ title, url, src });

	decodedTitle = decodeURIComponent(title);
	decodedSrc = decodeURIComponent(src);

	let list = $('<li class="text-card"></li>');

	list.html(`<div class="wrapper"> <div class="card-container"> <div class="top" style="background: url(${decodedSrc}) no-repeat center center; background-size: contain"></div> <a onclick="deleteFromBasket(\`${title}\`,this)" class="bottom"><i class="far fa-trash-alt"></i></a> </div> <div class="inside"> <div class="icon"><i class="fas fa-info-circle"></i></div> <div class="contents"> ${decodedTitle} </div> </div> </div>`);

	$('.basket_items').append(list);
};

/**
 * @name deleteFromBasket
 * @description Delete from input selected product
 * @param {string} [title] [Title of product to delete]
 * @param {string} [parent] [DOM element who triggered the function]
 */
const deleteFromBasket = (title, parent) => {
	basket.splice(basket.indexOf(basket.find(e => e.title == title)), 1)

	parent.closest('li').remove();
};

var sortable = new Sortable($('.basket_items')[0], {
	onEnd: function (evt) {
		basket.splice(evt.newIndex, 0, basket.splice(evt.oldIndex, 1)[0]);
	}
});

const fillProductForm = () => {
	const list = basket.map((element) => element.title + ';' + element.url + ';' + element.src + ',').toString();
	$('.hiddenInput').val(list);
};
