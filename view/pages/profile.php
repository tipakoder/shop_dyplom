<section id="profile">
	<div class="container">
		<div class="userbox">
			<div class="column">
				<img src="/view/res/img/avatar.jpg" class="avatar">
				<button class="btn gray filled" onclick="popup_edit_profile()">Редактировать</button>
				<button class="btn gray filled" onclick="popup_change_password()">Сменить пароль</button>
			</div>

			<div class="column maininfo">
				<h2 class="name"><?=$CURRENTUSER['name']?></h2>
				<p class="subinfo email"><i class="fas fa-envelope"></i> <?=$CURRENTUSER['email']?></p>
				<p class="subinfo telephone"><i class="fas fa-phone-alt"></i> <?=$CURRENTUSER['telephone']?></p>
			</div>
		</div>
	</div>
</section>