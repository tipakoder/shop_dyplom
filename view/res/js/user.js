function ready_user(){

}

function popup_edit_profile(){
	// Получаем информацию о текущем аккаунте и создаём форму
	fetch("/getAccount/", {
        method: "POST"
    }).then(async(res) => {
        return await res.json();
    }).then((data) => {
        if(data.type == "error"){
            alert(data.data);
        } else {
        	let readyData = data.data.account;
            let form = document.createElement("form");
		    form.className = "form-content";
		    form.innerHTML = `
		    <div class="field">
		        <input name="name" type="text" placeholder="Имя" value="${readyData.name}">
		    </div>

		    <div class="field">
		        <input name="email" type="email" placeholder="Электронная почта" value="${readyData.email}">
		    </div>

		    <div class="field">
		        <input name="telephone" type="tel" placeholder="Телефон" value="${readyData.telephone}">
		    </div>

		    <div class="field">
		        <input name="login" type="text" placeholder="Логин" value="${readyData.login}">
		    </div>
		    <div class="form-actions">
		        <button class="btn gray filled">Сохранить</button>
		    </div>
		    `;
		    submit_form(form, "/editAccount/", () => {
		        location.reload();
		    });
		    popup(form, "Редактирование профиля");
        }
    }).catch((error) => {
        console.log(error);
    });
}

document.addEventListener("DOMContentLoaded", ready_user);