(function(){
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
        // Задаём элемент контента
        element.classList.add("popup-content");
        popupWrapper.appendChild(element);
        // Показываем
        document.body.classList.add("popup-active");
        document.body.appendChild(popupWrapper);
    }
});