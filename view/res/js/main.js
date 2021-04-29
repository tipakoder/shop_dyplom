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
        } else {
            navbar.style.display = "block";
            header_wrapper.style.bottom = "0";
            navbar.style.animationName = "cart-open";
        }
    });
    // Setup resize
    window.addEventListener("resize", resize_window);
    window.addEventListener("load", resize_window);
    // Setup catalog component
    let products = document.querySelectorAll(".catalog-container > .product");
    for(let product of products){
        product.addEventListener("click", open_adaptive_product);
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
}

function resize_window(){
    let navbar = document.getElementById("header-wrapper-navigation");
    if(window.innerWidth > 600){
        navbar.style.display = "block";
        navbar.style.animationName = "cart-open";
    } else {
        navbar.style.display = "none";
        navbar.style.bottom = "auto";
    }
}

function open_adaptive_product(e){
    e.preventDefault();
    console.log(e.target);
    if(window.innerWidth <= 600){
        if(e.target === this.querySelector(".submenu .close")){
            this.classList.remove("showing_adaptive");
        } else {
            this.classList.add("showing_adaptive");
        }
    }
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