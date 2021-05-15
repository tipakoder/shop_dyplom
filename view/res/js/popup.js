(function(){
    // Функция показа модального окна
    window.popup = function(element, title){
        // Создаём элемент модального окна
        let popupWrapper = document.createElement("div");
        popupWrapper.id = "popup-wrapper";
        popupWrapper.innerHTML = `
        <div class="popup-window">
            <i class="popup-close fas fa-times"></i>
            <h2 class="popup-title">${title}</h2>
        </div>
        `;
        // Задаём действие кнопке закрытия
        popupWrapper.querySelector(".popup-close").addEventListener("click", function(e){
            e.preventDefault();
            window.popupClose();
        });
        // Задаём элемент контента
        element.classList.add("popup-content");
        popupWrapper.querySelector(".popup-window").appendChild(element);
        // Показываем
        document.body.style.overflow = 'hidden';
        document.querySelector("#page-wrapper").classList.add("popup-active");
        document.body.appendChild(popupWrapper);
        // Включаем индикатор
        setTimeout(()=>{
            window.popupShowed = true;
        }, 0);
    }

    window.popupClose = function(){
        document.getElementById("popup-wrapper").style.animation = 'cart-close 0.3s';
        document.getElementById("page-wrapper").classList.remove("popup-active");
        document.body.style.overflow = 'auto';
        setTimeout(() => { 
            document.getElementById("popup-wrapper").remove(); 
        }, 300);
        window.popupShowed = false;
    }

    // Отключаем модальное окно по клику из вне
    document.addEventListener("click", function(e){
        if( !e.target.closest(".popup-window") && window.popupShowed === true ){
            window.popupClose();
        }
    });
})();