// ------------- PRODUCT

function get_product_db(id){
	let body = new FormData();
	body.append("id", id);
	let fetchResponse = fetch("/productGet/", {
        body: body,
        method: "POST"
    }).then(async(res) => {
        return await res.json();
    }).then((data) => {
        if(data.type != "error"){
            if(data.data.product != false) return data.data.product;
        }
        return false;
    }).catch((error) => {
        console.log(error);
        return false;
    });
    return fetchResponse;
}

// ------------- CART

function render_cart(){
	// Изменяем число в header
	if(window.productCartSlots != null && document.getElementById("cart-count-text")) 
		document.getElementById("cart-count-text").textContent = window.productCartSlots.length;

	if(window.productCartSlots.length > 0 && document.getElementById("cart-body")){
		// Получить список корзины header
		let contentBody = document.getElementById("cart-body").querySelector(".content ul");
		// Получить список корзины на отдельной странице
	    let contentPageBody = document.getElementById("cart-page-content");
	    // Выключаем заглушку
	    document.getElementById("cart-body").querySelector(".plug-box").style.display = "none";
	    // Отчищаем список корзины в header
	    contentBody.innerHTML = '';
	    // Отчищаем список корзины на отдельной странице
	    if(contentPageBody) {
	    	document.getElementById("cart-page-content-plug").style.display = "none";
	    	contentPageBody.innerHTML = '';
	    }
	    // Общий прайс
	    let common_price = 0;
	    // Перебираем список корзины и воссоздаём
		for(let slot of window.productCartSlots){
			common_price += parseInt(slot.price) * parseInt(slot.count);
			contentBody.appendChild(generate_cart_slot(slot, "catalog_cart_slots"));
	        if(contentPageBody) contentPageBody.appendChild(generate_cart_slot(slot, "catalog_cart_slots"));
		}
		// Создаём блок с итоговыми данными
		if(contentPageBody){
			let total_block = document.createElement("div");
			total_block.className = "total-block";
			total_block.innerHTML = `
			<h3 class="price">Итог: ${common_price} руб.</h3>
			<button class="btn filled" onclick="popup_new_order()">Оформить заказ</button>
			`;
			contentPageBody.appendChild(total_block);
		}
		document.getElementById("cart-body").querySelector(".content").style.display = "block";
	} else {
		// Отчистить корзину
		if(document.getElementById("cart-body")) {
			document.getElementById("cart-body").querySelector(".content ul").innerHTML = "";
			document.getElementById("cart-body").querySelector(".plug-box").style.display = "flex";
			document.getElementById("cart-body").querySelector(".content").style.display = "none";
		}
		if(document.getElementById("cart-page-content")) {
			document.getElementById("cart-page-content").innerHTML = "";
			document.getElementById("cart-page-content-plug").style.display = "flex";
		}
	}
}

function generate_cart_slot(slot){
	let elementBody = document.createElement("div");
    elementBody.dataset.id = slot.id;
    elementBody.className = "cart-slot";
    elementBody.setAttribute("href", `/product/${slot.id}/`);
    elementBody.innerHTML = `
    <img class="image" src="${slot['photo']}" alt="">
    <div class="body-wrapper">
        <h5 class="product-name">${slot['name']}</h5>
        <p class="product-price">${slot['price']} руб./шт</p>
    </div>
    <div class="controls-wrapper">
        <div class="control btn gray filled event-less-cart" data-id="${slot['id']}">-</div>
        <div class="control count_number">${slot['count']}</div>
        <div class="control btn gray filled event-more-cart" data-id="${slot['id']}">+</div>
    </div>
    <i class="slot-remove fas fa-times"></i>
    `;

    elementBody.querySelector(".slot-remove").addEventListener("click", (e) => {
        e.preventDefault();
        remove_cart(slot.id);
    });

    return elementBody;
}

function get_cart(id){
    let findedIndex = window.productCartSlots.findIndex(slt => slt.id == id);
    return (findedIndex !== -1) ? findedIndex : false;
}

function clear_cart(){
	window.productCartSlots = [];
	save_cart();
}

function save_cart(){
	localStorage.setItem("catalog_cart_slots", JSON.stringify(window.productCartSlots));
	render_cart();
}

function add_cart(id, count = 1){
	if((index = get_cart(id)) === false){
		get_product_db(id).then((product) => {
			product.count = count;
			window.productCartSlots.push(product);
			save_cart();
		});
	} else {
		window.productCartSlots[index].count++;
	}
	save_cart();
}

function remove_cart(id){
	if((index = get_cart(id)) !== false){
		window.productCartSlots.splice(index, 1);
		save_cart();
	}
}

function more_cart(id){
	if((index = get_cart(id)) !== false){
		window.productCartSlots[index].count++;
		save_cart();
	}
}

function less_cart(id){
	if((index = get_cart(id)) !== false){
		if(window.productCartSlots[index].count-1 == 0){
			window.productCartSlots.splice(index, 1);
		} else {
			window.productCartSlots[index].count--;
		}
		save_cart();
	}
}

function product_page_events(e){
	let product_count = document.getElementById("product-count");
	if(e.target.classList.contains("event-product-more")){
		product_count.textContent = parseInt(product_count.textContent)+1;
	}
	if(e.target.classList.contains("event-product-less")){
		if(parseInt(product_count.textContent)-1 > 0)
			product_count.textContent = parseInt(product_count.textContent)-1;
	}
	if(e.target.classList.contains("event-product-add-cart")){
		e.preventDefault();
		add_cart(e.target.dataset.id, parseInt(product_count.textContent));
	}
}

// ------------- FAVORITE

function render_favorite(){
	// Изменяем число в header
	if(window.productFavoriteSlots != null && document.getElementById("favorite-count-text")) 
		document.getElementById("favorite-count-text").textContent = window.productFavoriteSlots.length;

	if(window.productFavoriteSlots.length > 0 && document.getElementById("favorite-page-content")){
		// Получить список избранного на отдельной странице
	    let contentPageBody = document.getElementById("favorite-page-content");
	    // Отчищаем список избранного на отдельной странице
	    contentPageBody.innerHTML = '';
	    // Выключаем заглушку
	    document.getElementById("favorite-page-content-plug").style.display = "none";
	    // Перебираем список избранного и воссоздаём
		for(let slot of window.productFavoriteSlots){
	        if(contentPageBody) contentPageBody.appendChild(generate_favorite_slot(slot, "catalog_favorite_slots"), false);
		}
	} else {
		// Отчистить список избранного
		if(document.getElementById("favorite-page-content")) {
			document.getElementById("favorite-page-content").innerHTML = "";
			document.getElementById("favorite-page-content-plug").style.display = "flex";
		}
	}
}

function generate_favorite_slot(slot){
	let elementBody = document.createElement("a");
    elementBody.dataset.id = slot.id;
    elementBody.className = "cart-slot";
    elementBody.setAttribute("href", `/product/${slot.id}/`);
    elementBody.innerHTML = `
    <img class="image" src="${slot['photo']}" alt="">
    <div class="body-wrapper">
        <h5 class="product-name">${slot['name']}</h5>
        <p class="product-price">${slot['price']} руб./шт</p>
    </div>
    <i class="slot-remove fas fa-times"></i>
    `;

    elementBody.querySelector(".slot-remove").addEventListener("click", (e) => {
        e.preventDefault();
        remove_favorite(slot.id);
    });

    return elementBody;
}

function get_favorite(id){
    let findedIndex = window.productFavoriteSlots.findIndex(slt => slt.id == id);
    return (findedIndex !== -1) ? findedIndex : false;
}

function save_favorite(){
	localStorage.setItem("catalog_favorite_slots", JSON.stringify(window.productFavoriteSlots));
	render_favorite();
}

function add_favorite(id, count = 1){
	if((index = get_favorite(id)) === false){
		get_product_db(id).then((product) => {
			product.count = count;
			window.productFavoriteSlots.push(product);
			save_favorite();
		});
	} else {
		window.productFavoriteSlots[index].count++;
	}
	save_favorite();
}

function remove_favorite(id){
	if((index = get_favorite(id)) !== false){
		window.productFavoriteSlots.splice(index, 1);
		save_favorite();
	}
}

// ------------- GLOBAL EVENT CLICK

document.addEventListener("click", function(e){
	// FOR PRODUCT
	if(document.getElementById("product-page")){
		product_page_events(e);
	}
	// FOR CART
	if(e.target.classList.contains("event-add-cart")){
		e.preventDefault();
		add_cart(e.target.dataset.id);
	}
	if(e.target.classList.contains("event-remove-cart")){
		e.preventDefault();
		remove_cart(e.target.dataset.id);
	}
	if(e.target.classList.contains("event-more-cart")){
		e.preventDefault();
		more_cart(e.target.dataset.id);
	}
	if(e.target.classList.contains("event-less-cart")){
		e.preventDefault();
		less_cart(e.target.dataset.id);
	}
	// FOR FAVORITE
	if(e.target.classList.contains("event-add-favorite")){
		e.preventDefault();
		add_favorite(e.target.dataset.id);
	}
	if(e.target.classList.contains("event-remove-favorite")){
		e.preventDefault();
		remove_favorite(e.target.dataset.id);
	}
});

// ------------- GLOBAL EVENT LOADED

document.addEventListener("DOMContentLoaded", function(){
	// FOR CART
	window.productCartSlots = tryParseJSON(localStorage.getItem("catalog_cart_slots"));
	render_cart();
	// FOR FAVORITE
	window.productFavoriteSlots = tryParseJSON(localStorage.getItem("catalog_favorite_slots"));
	render_favorite();
});