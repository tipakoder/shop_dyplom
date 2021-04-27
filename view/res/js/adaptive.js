function ready_adaptive(){
    // Setup header nav button for adaptive
    document.getElementById("header-nav-button").addEventListener("click", () => {
        let navbar = document.getElementById("header-wrapper-navigation");
        if(navbar.style.display === "block"){
            setTimeout(()=>{navbar.style.display = "none";}, 200);
            navbar.style.animationName = "cart-close";
        } else {
            navbar.style.display = "block";
            navbar.style.animationName = "cart-open";
        }
    });
    // Setup resize
    window.addEventListener("resize", ()=>{
        let navbar = document.getElementById("header-wrapper-navigation");
        if(window.innerWidth > 600){
            navbar.style.display = "block";
            navbar.style.animationName = "cart-open";
        } else {
            navbar.style.animationName = "cart-close";
        }
    });
}

document.addEventListener("DOMContentLoaded", ready_adaptive);