function tryParseJSON (jsonString){
    try {
        let r = JSON.parse(jsonString);
        if (r && typeof r === "object") {
            return r;
        }
    }
    catch (e) { }
    return [];
}

function ready_adaptive(){
    // Setup header nav button for adaptive
    document.getElementById("header-nav-button").addEventListener("click", () => {
        let navbar = document.getElementById("header-wrapper-navigation");
        let header_wrapper = document.getElementById("header-wrapper");
        if(navbar.style.display === "block"){
            setTimeout(()=>{
                navbar.style.display = "none";
                header_wrapper.style.bottom = "auto";
            }, 200);
            navbar.style.animationName = "cart-close";
            document.body.style.overflow = "auto";
        } else {
            navbar.style.display = "block";
            header_wrapper.style.bottom = "0px";
            navbar.style.animationName = "cart-open";
            document.body.style.overflow = "hidden";
        }
    });
    // Setup resize
    window.addEventListener("resize", resize_window);
    resize_window();
    // Setup catalog component
    let products = document.querySelectorAll(".catalog-container > .product");
    for(let product of products){
        product.addEventListener("click", click_product);
    }
    // Setup section submenu
    let submenus = document.querySelectorAll("section .section-title .submenu");
    for(let submenu of submenus){
        for(let li of submenu.children){
            li.addEventListener("click", () => {
                submenu.querySelector(".selected").classList.remove("selected");
                li.classList.add("selected");
            });
        }
    }
    // Setup cart and favorite
    cart_update();
    favorite_update();
}

function resize_window(){
    let navbar = document.getElementById("header-wrapper-navigation");
    let header_wrapper = document.getElementById("header-wrapper");
    if(window.innerWidth > 600){
        navbar.style.display = "block";
        header_wrapper.style.bottom = "auto";
        navbar.style.animationName = "cart-open";
    } else {
        navbar.style.display = "none";
        header_wrapper.style.bottom = "auto";
    }
}

function click_product(e){
    e.preventDefault();
    if(e.target !== this.querySelector(".submenu .to_favorite") && e.target !== this.querySelector(".submenu .to_cart")){
        if(window.innerWidth <= 600){
            if(e.target === this.querySelector(".submenu .close")){
                this.classList.remove("showing_adaptive");
            } else {
                this.classList.add("showing_adaptive");
            }
        } else {
            
        }
    } else {
        let product = tryParseJSON(this.dataset.product);
        switch(e.target){
            case this.querySelector(".submenu .to_favorite"):
                catalog_add_favorite(product);
                break;
            case this.querySelector(".submenu .to_cart"):
                catalog_add_cart(product);
                break;
        }
    }
}

function catalog_add_favorite(product){
    let slots = tryParseJSON(localStorage.getItem("catalog_favorite_slots"));
    if(slots.findIndex(slt => slt.id == product.id) === -1) slots.push(product);
    localStorage.setItem("catalog_favorite_slots", JSON.stringify(slots));
    favorite_update();
}

function catalog_add_cart(product){
    let slots = tryParseJSON(localStorage.getItem("catalog_cart_slots"));
    if(slots.findIndex(slt => slt.id == product.id) === -1) slots.push(product);
    localStorage.setItem("catalog_cart_slots", JSON.stringify(slots));
    cart_update();
}

function cart_update(){
    let slots = tryParseJSON(localStorage.getItem("catalog_cart_slots"));
    if(slots != null) document.getElementById("cart-count-text").textContent = slots.length;
    if(slots.length > 0){
        let contentBody = document.getElementById("cart-body").querySelector(".content").children[0];
        let contentPageBody = document.getElementById("cart-page-content");
        document.getElementById("cart-body").querySelector(".plug-box").style.display = "none";
        contentBody.innerHTML = '';
        if(contentPageBody) contentPageBody.innerHTML = '';
        for(let slot of slots){
            contentBody.appendChild(generate_cart_slot(slot, "catalog_cart_slots"));
            if(contentPageBody) contentPageBody.appendChild(generate_cart_slot(slot, "catalog_cart_slots"));
        }
        document.getElementById("cart-body").querySelector(".content").style.display = "block";
    } else {
        document.getElementById("cart-body").querySelector(".plug-box").style.display = "flex";
        document.getElementById("cart-body").querySelector(".content").style.display = "none";
        if(document.getElementById("cart-page-content")) document.getElementById("cart-page-content").innerHTML = "";
    }
}

function favorite_update(){
    let slots = tryParseJSON(localStorage.getItem("catalog_favorite_slots"));
    if(slots != null) document.getElementById("favorite-count-text").textContent = slots.length;

    if(slots.length > 0){
        let contentPageBody = document.getElementById("favorite-page-content");
        if(contentPageBody == null) return;
        contentPageBody.innerHTML = '';
        for(let slot of slots){
            contentPageBody.appendChild(generate_cart_slot(slot, "catalog_favorite_slots"));
        }
    } else {
        if(document.getElementById("favorite-page-content")) document.getElementById("favorite-page-content").innerHTML = "";
    }
}

function generate_cart_slot(slot, slots_name){
    let elementBody = document.createElement("div");
    elementBody.dataset.id = slot.id;
    elementBody.className = "cart-slot";
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
        let slots = tryParseJSON(localStorage.getItem(slots_name));
        console.log(slots)
        if(slots.findIndex(slt => slt.id == slot.id) !== -1) slots = slots.splice(slot.id, 1);
        localStorage.setItem(slots_name, JSON.stringify(slots));
        cart_update();
        favorite_update();
    });

    return elementBody;
}

function popup_account(){
    let form = document.createElement("form");
    form.className = "form-content";
    form.innerHTML = `
    <div class="field">
        <input type="text" placeholder="Логин" required>
    </div>
    <div class="field">
        <input type="password" placeholder="Пароль" required>
    </div>
    <div class="form-actions">
        <button class="btn">Регистрация</button>
        <button class="btn filled">Войти</button>
    </div>
    `;
    popup(form, "Личный кабинет");
}

function submit_form(form, url, customData = false, method = "POST"){
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        
    });
}

document.addEventListener("DOMContentLoaded", ready_adaptive);
window.addEventListener("load", () => {
    // Enable body and off loading
    document.getElementById("loading-page").style.animation = "cart-close 0.5s";
    document.body.style.overflow = "auto";
    setTimeout(() => {
        document.getElementById("loading-page").remove();
    }, 500);
});