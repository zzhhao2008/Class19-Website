<nav class="navbar fixed-top navtopc">
	<div class="container">
		<a class="navbar-brand" href="/">
			<img src="/icon.jpg" alt="Logo" width="30" height="30" class="d-inline-block align-text-top rounded-circle">
			班级网站</a>
		<button class="navbar-toggler">
			<span class="navbar-toggler-icon" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar"></span>
		</button>
		<div class="offcanvas offcanvas-start navmainc" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
			<div class="offcanvas-header">
				<h5 class="offcanvas-title" id="offcanvasNavbarLabel">
					<img src="/icon.jpg" alt="Logo" width="30" height="30" class="d-inline-block align-text-top rounded-circle">
					<?= $title ?>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
			</div>
			<div class="offcanvas-body">
				<ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
					<li class="nav-item">
						<a class="nav-link" id="honournaver" href="/honour"><?= view::icon("award") ?>荣誉</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="picnaver" href="/pic"><?= view::icon("images") ?>照片墙</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="cxnaver" href="/cx"><?= view::icon("check2-all") ?>查成绩</a>
					</li>

					<li class="border-top my-3"></li>
					<?php
					if (!in_array(Router::getUri(), $navitems)) {
					?>
						<li class="nav-item">
							<a class="nav-link" id="<?= Router::getUri() ?>naver" href="#"><?= $title ?></a>
						</li>
					<?php } ?>
					<?php if (user::is_superuser()) : ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								管理
							</a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" id='user_manage' href="/user_manage">用户管理</a></li>
								<li><a class="dropdown-item" id='user_manage' href="/cx/manage">成绩单管理</a></li>
								<li><a class="dropdown-item" id='user_manage' href="/mpt">首页管理</a></li>
							</ul>
						</li>
					<?php endif; ?>
					<?php if (user::read()['name']) : ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								工具
							</a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" id='user_manage' href="/themeset">主题</a></li>
							</ul>
						</li>
					<?php endif; ?>
					<hr>
					<li class="nav-item"><a class="nav-link" href="/profile"><?= view::icon("person-circle") ?>
							<?= $GLOBALS['userprofile'] ? $GLOBALS['userprofile']['nick'] : "登录" ?></a></li>
				</ul>
				<hr>
				<form class="d-flex" role="search" action="/search">
					<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="s">
					<button class="btn btn-outline-success" type="submit">Go</button>
				</form>
			</div>
		</div>
	</div>
</nav>