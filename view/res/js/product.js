// ------------- PRODUCT

function get_product_db(id){
	let body = new FormData();
	body.append("id", id);
	fetch("/productGet/", {
        body: body,
        method: "POST"
    }).then(async(res) => {
        return await res.json();
    }).then((data) => {
        if(data.type != "error"){
            if(data.data.product) return data.data.product;
        }
        return false;
    }).catch((error) => {
        console.log(error);
        return false;
    });
}

// ------------- CART

function render_cart(){

}

function get_cart(id){
    let findedIndex = window.productCartSlots.findIndex(slt => slt.id == id);
    return (findedIndex !== -1) ? findedIndex : false;
}

function save_cart(){
	localStorage.setItem("catalog_favorite_slots", JSON.stringify(slots));
	render_cart();
}

function add_cart(id){
	if((index = get_cart(id)) === false){
		let product = get_product_db(id);
		product.count = 1;
		window.productCartSlots.push(product);
	} else {
		window.productCartSlots[index].count++;
	}
	save_cart();
}

function remove_cart(id){
	if((index = get_cart(id)) !== false){
		delete window.productCartSlots[index];
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
			delete window.productCartSlots[index];
		} else {
			window.productCartSlots[index].count--;
		}
		save_cart();
	}
}

// ------------- FAVORITE

function add_favorite(id){

}

function remove_favorite(id){

}

// ------------- GLOBAL EVENT CLICK

document.addEventListener("click", function(e){
	// FOR CART
	if(e.target.classList.contains("event-add-cart")){
		add_cart(e.target.dataset.id);
	}
	if(e.target.classList.contains("event-remove-cart")){
		remove_cart(e.target.dataset.id);
	}
	if(e.target.classList.contains("event-more-cart")){
		more_cart(e.target.dataset.id);
	}
	if(e.target.classList.contains("event-less-cart")){
		less_cart(e.target.dataset.id);
	}
	// FOR FAVORITE
	if(e.target.classList.contains("event-add-favorite")){
		add_favorite(e.target.dataset.id);
	}
	if(e.target.classList.contains("event-remove-favorite")){
		remove_favorite(e.target.dataset.id);
	}
});

// ------------- GLOBAL EVENT LOADED

document.addEventListener("DOMContentLoaded", function(){
	// FOR CART
	window.productCartSlots = tryParseJSON(localStorage.getItem("catalog_cart_slots"));
	// FOR FAVORITE
	window.productFavoriteSlots = tryParseJSON(localStorage.getItem("catalog_favorite_slots"));
});