function ready_admin(){

}

// ---------- Promocodes

function popup_promocodes_new(){
    let form = document.createElement("form");
    form.className = "form-content";
    form.innerHTML = `
    <div class="field">
        <input name="code" type="text" placeholder="Код" required>
    </div>

    <div class="field">
        <input name="percent" type="number" placeholder="Процент скидки" required>
    </div>

    <div class="form-actions">
        <button class="btn gray filled">Добавить</button>
    </div>
    `;
    submit_form(form, "/newPromocode/", () => {
        location.reload();
    });
    popup(form, "Новый промокод");
}

function turn_promocode(id){
	let dataSend = new FormData();
	dataSend.append("id", id);
	fetch("/turnPromocode/", {
	  	body: dataSend,
        method: "POST"
    }).then(async(res) => {
        return await res.json();
    }).then((data) => {
        if(data.type == "error"){
            alert(data.data);
        } else {
        	location.reload();
        }
    }).catch((error) => {
        console.log(error);
    });
}

// ---------- Delivery Service

function popup_new_delivery_service(){
	let form = document.createElement("form");
    form.className = "form-content";
    form.innerHTML = `
    <div class="field">
        <input name="name" type="text" placeholder="Кодовое имя" required>
    </div>

    <div class="field">
        <input name="title" type="text" placeholder="Название" required>
    </div>

    <div class="form-actions">
        <button class="btn gray filled">Добавить</button>
    </div>
    `;
    submit_form(form, "/newDeliveryService/", () => {
        location.reload();
    });
    popup(form, "Новая служба доставки");
}

function remove_delivery_service(id){
	let dataSend = new FormData();
	dataSend.append("id", id);
	fetch("/removeDeliveryService/", {
	  	body: dataSend,
        method: "POST"
    }).then(async(res) => {
        return await res.json();
    }).then((data) => {
        if(data.type == "error"){
            alert(data.data);
        } else {
        	location.reload();
        }
    }).catch((error) => {
        console.log(error);
    });
}

// ---------- Products

function popup_new_product(){
	fetch("/getCategorys/", {
        method: "POST"
    }).then(async(res) => {
        return await res.json();
    }).then((data) => {
        if(data.type == "error"){
            alert(data.data);
        } else {
        	let category_html = `
        	<option value="null" selected disabled>Выберите категорию товара</option>
        	<option value="new">Новая категория</option>
        	`;
        	if(data.data.categorys){
        		for(let category of data.data.categorys){
        			category_html += `<option value="${category.id}">${category.name}</option>`;
        		}
        	}

        	let form = document.createElement("form");
		    form.className = "form-content new-product";
		    form.innerHTML = `
		    <div class="field">
		        <input name="name" type="text" placeholder="Название" required>
		    </div>

		    <div class="field">
		        <textarea name="description" rows="5" placeholder="Описание"></textarea>
		    </div>

		    <div class="field image">
		    	<div class="placeholder"><h3>Выберите основное фото товара</h3></div>
		        <input name="photo" type="file" accept="image/png, image/jpeg" required>
		    </div>

		    <div class="field-wrapper">
		    	<div class="column field image">
			    	<div class="placeholder"><h3>Выберите фото товара</h3></div>
			        <input name="photo1" type="file" accept="image/png, image/jpeg">
			    </div>

			    <div class="column field image">
			    	<div class="placeholder"><h3>Выберите фото товара</h3></div>
			        <input name="photo2" type="file" accept="image/png, image/jpeg">
			    </div>

			    <div class="column field image">
			    	<div class="placeholder"><h3>Выберите фото товара</h3></div>
			        <input name="photo3" type="file" accept="image/png, image/jpeg">
			    </div>
		    </div>

		    <div class="field">
			    <select name="category" required>
			    	${category_html}
			    </select>
		    </div>

		    <div class="field">
			    <select name="subcategory" required>
			    	<option value="null" selected disabled>Выберите подкатегорию товара</option>
			    	<option value="new">Новая подкатегория</option>
			    </select>
		    </div>

		    <div class="field">
		        <input name="price" type="number" placeholder="Цена" required>
		    </div>

		    <div class="form-actions">
		        <button class="btn gray filled">Добавить</button>
		    </div>
		    `;
		    // Действие по изменению категории
		    form.querySelector("[name=category]").addEventListener("change", () => {
		    	let category_select = form.querySelector("[name=category]");
		    	let subcategory_select = form.querySelector("[name=subcategory]");
		    	// Выполняем обновление поля подкатегории
		    	subcategory_select.dispatchEvent(new Event('update'));
		    	// Выполняем обновление подкатегории
		    	if(category_select.value == "new" && !form.querySelector("[name=categoryName]")){
		    		let category_name_element = document.createElement("input");
		    		category_name_element.name = "categoryName";
		    		category_name_element.placeholder="Имя категории";
		    		category_name_element.type = "text";
		    		category_name_element.required = true;
		    		category_select.parentElement.appendChild(category_name_element);
		    		// Если категория новая, значит и подкатегория тоже новая
		    		subcategory_select.value = "new";
		    		subcategory_select.dispatchEvent(new Event('change'));
		    	} else {
		    		// Убираем, если была выбрана новая категория
		    		if(form.querySelector("[name=categoryName]")) {
			    		form.querySelector("[name=categoryName]").remove();
			    	}
			    	// Получаем подкатегории, принадлежащие данной категории
			    	let dataSend = new FormData();
					dataSend.append("id", category_select.value);
					fetch("/getSubcategorys/", {
					  	body: dataSend,
				        method: "POST"
				    }).then(async(res) => {
				        return await res.json();
				    }).then((data) => {
				    	// Добавляем соответствующие пункты
				        if(data.type == "error"){
				            subcategory_select.value = "new";
				        } else {
				        	if(data.data.subcategorys){
				        		for(let subcategory of data.data.subcategorys){
				        			let new_option = document.createElement("option");
				        			new_option.textContent = subcategory.name;
				        			new_option.value = subcategory.id;
				        			subcategory_select.appendChild(new_option);
				        		}
				        	} else {
				        		subcategory_select.value = "new";
				        	}
				        }
				        // Обновляем, если необходима новая категория
				        if(subcategory_select.value == "new"){
				        	subcategory_select.dispatchEvent(new Event('change'));
				        }
				    }).catch((error) => {
				        console.log(error);
				    });
		    	}
		    });
		    // Действие по обновлению подкатегории
		    form.querySelector("[name=subcategory]").addEventListener("update", () => {
		    	let subcategory_select = form.querySelector("[name=subcategory]");
		    	subcategory_select.innerHTML = `
				<option value="null" selected disabled>Выберите подкатегорию товара</option>
		    	<option value="new">Новая подкатегория</option>
		    	`;
		    });
		    // Действие по изменению подкатегории
		    form.querySelector("[name=subcategory]").addEventListener("change", () => {
		    	let subcategory_select = form.querySelector("[name=subcategory]");
		    	if(subcategory_select.value == "new"){
		    		if(form.querySelector("[name=subcategoryName]")) return;

		    		let subcategorycategory_name_element = document.createElement("input");
		    		subcategorycategory_name_element.name = "subcategoryName";
		    		subcategorycategory_name_element.placeholder="Имя подкатегории";
		    		subcategorycategory_name_element.type = "text";
		    		subcategorycategory_name_element.required = true;
		    		subcategory_select.parentElement.appendChild(subcategorycategory_name_element);
		    	} else if(form.querySelector("[name=subcategoryName]")) {
		    		form.querySelector("[name=subcategoryName]").remove();
		    	}
		    });
		    // Кастомная отправка всей формы в нужном формате
		    submit_form(form, "/newProduct/", () => {
		        location.reload();
		    });
		    // Активировать все кастомные элементы формы
		    active_form(form);
		    // Показать модальное окно
		    popup(form, "Новый товар");
        }
    }).catch((error) => {
        console.log(error);
    });
}

function product_sale_off(id){
	if(!confirm("Вы действительно хотите cнять с продаж выбранный товар?")) return;

	let dataSend = new FormData();
	dataSend.append("id", id);
	fetch("/productSaleOff/", {
	  	body: dataSend,
        method: "POST"
    }).then(async(res) => {
        return await res.json();
    }).then((data) => {
        if(data.type == "error"){
            alert(data.data);
        } else {
        	location.reload();
        }
    }).catch((error) => {
        console.log(error);
    });
}

function product_remove(id){
	if(!confirm("Вы действительно хотите удалить выбранный товар?")) return;

	let dataSend = new FormData();
	dataSend.append("id", id);
	fetch("/productRemove/", {
	  	body: dataSend,
        method: "POST"
    }).then(async(res) => {
        return await res.json();
    }).then((data) => {
        if(data.type == "error"){
            alert(data.data);
        } else {
        	location.href = "/admin/products/";
        }
    }).catch((error) => {
        console.log(error);
    });
}

function product_edit(id){
	let body = new FormData();
	body.append("id", id);
	fetch("/editProductGet/", {
		body: body,
        method: "POST"
    }).then(async(res) => {
        return await res.json();
    }).then((data) => {
        if(data.type == "error"){
            alert(data.data);
        } else {
        	let product = data.data.product;
        	let photos = data.data.photos;

        	let category_html = `
        	<option value="null" disabled>Выберите категорию товара</option>
        	<option value="new">Новая категория</option>
        	`;
        	if(data.data.categorys){
        		for(let category of data.data.categorys){
        			let selected = (category.id == product.category_id) ? "selected" : "";
        			category_html += `<option value="${category.id}" ${selected}>${category.name}</option>`;
        		}
        	}

        	let form = document.createElement("form");
		    form.className = "form-content new-product";
		    form.innerHTML = `
		    <div class="field">
		        <input name="name" type="text" placeholder="Название" value="${product.name}" required>
		    </div>

		    <div class="field">
		        <textarea name="description" rows="5" placeholder="Описание">${product.description}</textarea>
		    </div>

		    <div class="field image" style="background-image: url('${product.photo}')">
		        <input name="photo" type="file" accept="image/png, image/jpeg" required>
		    </div>

		    <div class="field-wrapper">
		    	<div class="column field image" style="background-image: url('${(photos[0]) ? photos[0].path : ""}')">
			    	${(!photos[0]) ? '<div class="placeholder"><h3>Выберите фото товара</h3></div>' : ""}
			        <input name="photo1" type="file" accept="image/png, image/jpeg">
			    </div>

			    <div class="column field image" style="background-image: url('${(photos[1]) ? photos[1].path : ""}')">
			    	${(!photos[1]) ? '<div class="placeholder"><h3>Выберите фото товара</h3></div>' : ""}
			        <input name="photo2" type="file" accept="image/png, image/jpeg">
			    </div>

			    <div class="column field image" style="background-image: url('${(photos[2]) ? photos[2].path : ""}')">
			    	${(!photos[2]) ? '<div class="placeholder"><h3>Выберите фото товара</h3></div>' : ""}
			        <input name="photo3" type="file" accept="image/png, image/jpeg">
			    </div>
		    </div>

		    <div class="field">
			    <select name="category" required>
			    	${category_html}
			    </select>
		    </div>

		    <div class="field">
			    <select name="subcategory" required>
			    	<option value="null" selected disabled>Выберите подкатегорию товара</option>
			    	<option value="new">Новая подкатегория</option>
			    </select>
		    </div>

		    <div class="field">
		        <input name="price" type="number" placeholder="Цена" value="${product.price}" required>
		    </div>

		    <div class="form-actions">
		        <button name="remove-product" class="btn gray">Удалить</button>
		        <button name="save-product" class="btn gray filled">Сохранить</button>
		    </div>
		    `;
		    // Удаление товара
		    form.querySelector("[name=remove-product]").addEventListener("click", (e) => {
		    	e.preventDefault();
		    	product_remove(id);
		    });
		    // Сохранить продукт
		    form.querySelector("[name=save-product]").addEventListener("click", (e) => {
		    	e.preventDefault();
		    	let body = new FormData(form);
		    	// Добавление photo_id's
		    	if(body.get("photo1") && photos[0]) body.append("photo1_id", photos[0].id);
		    	if(body.get("photo2") && photos[1]) body.append("photo2_id", photos[1].id);
		    	if(body.get("photo3") && photos[2]) body.append("photo3_id", photos[2].id);
		    	// Добавление id товара
				body.append("id", id);
				// Отправить запрос
		    	fetch("/editProductProcess/", {
		            body: body,
		            method: "POST"
		        }).then(async(res) => {
		            return await res.json();
		        }).then((data) => {
		            if(data.type == "error"){
		                alert(data.data);
		            } else {
		                location.reload();
		            }
		        }).catch((error) => {
		            console.log(error);
		        });
		    });
		    // Действие по изменению категории
		    form.querySelector("[name=category]").addEventListener("change", () => {
		    	let category_select = form.querySelector("[name=category]");
		    	let subcategory_select = form.querySelector("[name=subcategory]");
		    	// Выполняем обновление поля подкатегории
		    	subcategory_select.dispatchEvent(new Event('update'));
		    	// Выполняем обновление подкатегории
		    	if(category_select.value == "new" && !form.querySelector("[name=categoryName]")){
		    		let category_name_element = document.createElement("input");
		    		category_name_element.name = "categoryName";
		    		category_name_element.placeholder="Имя категории";
		    		category_name_element.type = "text";
		    		category_name_element.required = true;
		    		category_select.parentElement.appendChild(category_name_element);
		    		// Если категория новая, значит и подкатегория тоже новая
		    		subcategory_select.value = "new";
		    		subcategory_select.dispatchEvent(new Event('change'));
		    	} else {
		    		// Убираем, если была выбрана новая категория
		    		if(form.querySelector("[name=categoryName]")) {
			    		form.querySelector("[name=categoryName]").remove();
			    	}
			    	// Получаем подкатегории, принадлежащие данной категории
			    	let dataSend = new FormData();
					dataSend.append("id", category_select.value);
					fetch("/getSubcategorys/", {
					  	body: dataSend,
				        method: "POST"
				    }).then(async(res) => {
				        return await res.json();
				    }).then((data) => {
				    	// Добавляем соответствующие пункты
				        if(data.type == "error"){
				            subcategory_select.value = "new";
				        } else {
				        	if(data.data.subcategorys){
				        		for(let subcategory of data.data.subcategorys){
				        			let new_option = document.createElement("option");
				        			new_option.textContent = subcategory.name;
				        			new_option.value = subcategory.id;
				        			if(subcategory.id == product.subcategory_id) new_option.selected = true;
				        			subcategory_select.appendChild(new_option);
				        		}
				        	} else {
				        		subcategory_select.value = "new";
				        	}
				        }
				        // Обновляем, если необходима новая категория
				        if(subcategory_select.value == "new"){
				        	subcategory_select.dispatchEvent(new Event('change'));
				        }
				    }).catch((error) => {
				        console.log(error);
				    });
		    	}
		    });
		    // Действие по обновлению подкатегории
		    form.querySelector("[name=subcategory]").addEventListener("update", () => {
		    	let subcategory_select = form.querySelector("[name=subcategory]");
		    	subcategory_select.innerHTML = `
				<option value="null" selected disabled>Выберите подкатегорию товара</option>
		    	<option value="new">Новая подкатегория</option>
		    	`;
		    });
		    // Действие по изменению подкатегории
		    form.querySelector("[name=subcategory]").addEventListener("change", () => {
		    	let subcategory_select = form.querySelector("[name=subcategory]");
		    	if(subcategory_select.value == "new"){
		    		if(form.querySelector("[name=subcategoryName]")) return;

		    		let subcategorycategory_name_element = document.createElement("input");
		    		subcategorycategory_name_element.name = "subcategoryName";
		    		subcategorycategory_name_element.placeholder="Имя подкатегории";
		    		subcategorycategory_name_element.type = "text";
		    		subcategorycategory_name_element.required = true;
		    		subcategory_select.parentElement.appendChild(subcategorycategory_name_element);
		    	} else if(form.querySelector("[name=subcategoryName]")) {
		    		form.querySelector("[name=subcategoryName]").remove();
		    	}
		    });
		    // Активировать все кастомные элементы формы
		    active_form(form);
		    // Эмитировать изменение категории
		    form.querySelector("[name=category]").dispatchEvent(new Event('change'));
		    // Показать модальное окно
		    popup(form, "Редактировать товар");
        }
    }).catch((error) => {
        console.log(error);
    });
}

document.addEventListener("DOMContentLoaded", ready_admin);