function ready_search(){
    if(document.getElementById("filter_name")){
        document.getElementById("filter_name").addEventListener("", function(){

        });
    }

    if(document.getElementById("filter_category")){
        document.getElementById("filter_category").addEventListener("change", search_page_load_subcategory);
    }
}

function search_show(){
    let element = document.createElement("div");
    element.id = "search-wrapper";
    element.innerHTML = `
    <div class="search-content">
        <div class="container-fluid block query">
            <div class="icon search">
                <i class="fas fa-search"></i>
            </div>
            <div class="field">
                <input name="query" type="text" placeholder="Что Вас интересует?">
            </div>
            <button name="do" class="btn gray filled"><i class="fas fa-search"></i> Найти</button>
            <div class="icon close">
                <i class="fas fa-times"></i>
            </div>
        </div>

        <div class="search-result">
            <ul></ul>
            <button class="btn gray filled all-result">Все результаты</button>
        </div>
    </div>
    `;
    element.querySelector("button[name=do]").addEventListener("click", search_request);
    element.querySelector("input[name=query]").addEventListener("change", search_request);
    element.querySelector(".icon.close").addEventListener("click", search_close);
    document.body.appendChild(element);
    setTimeout(()=>{
        window.searchShowed = true;
    }, 0);
}

function search_request(){
    let query = document.querySelector("#search-wrapper input[name=query]").value;
    if(query != null){
        let sendData = new FormData();
        sendData.append("query", query);
        fetch("/productSearch/", {
            body: sendData,
            method: "POST"
        }).then(async(res) => {
            return await res.json();
        }).then((data) => {
            let search_result_wrapper = document.querySelector("#search-wrapper .search-result");
            search_result_wrapper.innerHTML
            if(data.data.products == null){
                search_result_wrapper.style.display = 'none';
                return;
            }

            if(data.data.products.length > 0) search_result_wrapper.style.display = 'flex';
            search_result_wrapper.querySelector("ul").innerHTML = "";
            search_result_wrapper.querySelector(".all-result").setAttribute("onclick", `location.href = '/search?q=${query}'`);

            let products = data.data.products;
            for(let product of products){
                search_result_wrapper.querySelector("ul").appendChild(generate_search_result(product, query));
            }
        }).catch((error) => {
            console.log(error);
        });
    }
}

function generate_search_result(product, query){
    let re = new RegExp(`(${query})`, 'i');
    let name_data = product.name.replace(re, "<b>$1</b>");

    let element = document.createElement("li");
    element.setAttribute("onclick", `location.href='/product/${product.id}/'`);
    element.className = "result";
    element.innerHTML = `
    <div class="container">
        <div class="image" style="background-image: url('${product.photo}')"></div>
        <div class="main-info">
            <h3 class="name">${name_data}</h3>
            <p class="price">${product.price} руб.</p>
        </div>
    </div>
    `;
    return element;
}

function search_close(){
    document.getElementById("search-wrapper").remove();
    window.searchShowed = false;
}

function search_page_apply(){
    let query = document.getElementById("filter_name").value;
    let category = document.getElementById("filter_category").value;
    let subcategory = document.getElementById("filter_subcategory").value;
    let resultHref = "/search?";
    if(query != ""){
        resultHref += `q=${query}&`
    }
    if(category != "null"){
        resultHref += `category=${category}&`
    }
    if(subcategory != "null"){
        resultHref += `subcategory=${subcategory}`
    }
    location.href = resultHref;
}

function search_page_load_subcategory(){
    let category_select = document.getElementById("filter_category");
    let subcategory_select = document.getElementById("filter_subcategory");

    let dataSend = new FormData();
    dataSend.append("id", category_select.value);
    fetch("/getSubcategorys/", {
        body: dataSend,
        method: "POST"
    }).then(async(res) => {
        return await res.json();
    }).then((data) => {
        if(data.data.subcategorys){
            subcategory_select.innerHTML = "";

            let def_option = document.createElement("option");
            def_option.textContent = "Не выбрана"
            def_option.value = "null";
            subcategory_select.appendChild(def_option);

            for(let subcategory of data.data.subcategorys){
                let new_option = document.createElement("option");
                new_option.textContent = subcategory.name;
                new_option.value = subcategory.id;
                subcategory_select.appendChild(new_option);
            }
        }
    }).catch((error) => {
        console.log(error);
    });
}

document.addEventListener("click", function(e){
    if( !e.target.closest(".search-content") && window.searchShowed){
        search_close();
    }
});

document.addEventListener("DOMContentLoaded", ready_search);