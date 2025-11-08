<?php
include 'connection.php';


$query = "SELECT id, productName, thumbnail, SP, MRP FROM Product WHERE status = 1 ORDER BY id DESC LIMIT 10";
$result = $conn->query($query);
$products = [];
while ($row = $result->fetch_assoc()) {
	$products[] = $row;
}

$query = "SELECT id, productName, thumbnail, SP, MRP FROM Product WHERE status = 1 ORDER BY `create` DESC LIMIT 4";
$result = $conn->query($query);
$latestProducts = [];
while ($row = $result->fetch_assoc()) {
	$latestProducts[] = $row;
}

$query = "SELECT id,category,thumbnail FROM Category";
$result = $conn->query($query);
$categories = [];
while ($row = $result->fetch_assoc()) {
	$categories[] = $row;
}


$navQuery = "SELECT navbar.id, category.category FROM navbar JOIN category ON navbar.category_id = category.id";
$result = $conn->query($navQuery);
$nav = [];
while ($row = $result->fetch_assoc()) {
	$nav[] = $row;
}
?>




<!DOCTYPE html>
<html dir="ltr" lang="en-US">

<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta http-equiv="x-ua-compatible" content="IE=edge">
	<meta name="author" content="SemiColonWeb">
	<meta name="description"
		content="Create Shop, Store &amp; eCommerce Websites with techCentrix Template. Get techCentrix to build powerful websites easily with the Highly Customizable &amp; Best Selling Bootstrap Template, today.">

	<!-- Font Imports -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link
		href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;1,400&family=Montserrat:wght@300;400;500;600;700&family=Merriweather:ital,wght@0,300;0,400;1,300;1,400&display=swap"
		rel="stylesheet">

	<!-- Core Style -->
	<link rel="stylesheet" href="style.css">

	<!-- Font Icons -->
	<link rel="stylesheet" href="css/font-icons.css">

	<!-- Plugins/Components CSS -->
	<link rel="stylesheet" href="css/swiper.css">

	<!-- Niche Demos -->
	<link rel="stylesheet" href="demos/shop/shop.css">

	<!-- Custom CSS -->
	<link rel="stylesheet" href="css/custom.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

	<!-- Document Title
	============================================= -->
	<title>Shop Demo | techCentrix</title>

</head>

<body class="stretched">

	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper">

		<div class="modal-on-load" data-target="#myModal1"></div>

		<!-- On Load Modal -->
		<div class="modal1 mfp-hide subscribe-widget mx-auto" id="myModal1" style="max-width: 750px;">
			<div class="row justify-content-center bg-white align-items-center" style="min-height: 380px;">
				<div class="col-md-5 p-0">
					<div
						style="background: url('images/modals/modal1.jpg') no-repeat center right; background-size: cover;  min-height: 380px;">
					</div>
				</div>
				<div class="col-md-7 bg-white p-4">
					<div class="heading-block border-bottom-0 mb-3">
						<h3 class="font-secondary text-transform-none ">Join Our Newsletter &amp; Get <span
								class="text-danger">40%</span> Off your First Order</h3>
						<span>Get Latest Fashion Updates &amp; Offers</span>
					</div>
					<div class="widget-subscribe-form-result"></div>
					<form class="widget-subscribe-form2 mb-2" action="include/subscribe.php" method="post">
						<input type="email" id="widget-subscribe-form2-email" name="widget-subscribe-form-email"
							class="form-control required email" placeholder="Enter your Email Address..">
						<div class="d-flex justify-content-between align-items-center mt-1">
							<button class="button button-dark  bg-dark text-white ms-0" type="submit">Subscribe</button>
							<a href="#" class="btn-link" onClick="jQuery.magnificPopup.close();return false;">Don't Show
								me</a>
						</div>
					</form>
					<small class="mb-0 fst-italic text-black-50">*We also hate Spam &amp; Junk Emails.</small>
				</div>
			</div>
		</div>

		<!-- Login Modal -->
		<!-- <div class="modal1 mfp-hide" id="modal-register">
			<div class="card mx-auto" style="max-width: 540px;">
				<div class="card-header py-3 bg-transparent text-center">
					<h3 class="mb-0 fw-normal">Hello, Welcome Back</h3>
				</div>
				<div class="card-body mx-auto py-5" style="max-width: 70%;">

					<a href="#"
						class="button button-large w-100 bg-facebook text-transform-none fw-normal ls-0 text-center m-0"><i
							class="fa-brands fa-facebook-f"></i> Log in with Facebook</a>

					<div class="divider divider-center"><span class="position-relative" style="top: -2px">OR</span>
					</div>

					<form id="login-form" name="login-form" class="mb-0 row" action="#" method="post">

						<div class="col-12">
							<input type="text" id="login-form-username" name="login-form-username" value=""
								class="form-control not-dark" placeholder="Username">
						</div>

						<div class="col-12 mt-4">
							<input type="password" id="login-form-password" name="login-form-password" value=""
								class="form-control not-dark" placeholder="Password">
						</div>

						<div class="col-12 text-end">
							<a href="#" class="text-dark fw-light mt-2">Forgot Password?</a>
						</div>

						<div class="col-12 mt-4">
							<button class="button w-100 m-0" id="login-form-submit" name="login-form-submit"
								value="login">Login</button>
						</div>
					</form>
				</div>
				<div class="card-footer py-4 text-center">
					<p class="mb-0">Don't have an account? <a href="#"><u>Sign up</u></a></p>
				</div>
			</div>
		</div> -->

		<!-- Top Bar
		============================================= -->
		<div id="top-bar" class="dark" style="background-color: #a3a5a7;">
			<div class="container">

				<div class="row justify-content-between align-items-center">

					<div class="col-12 col-lg-auto">
						<p class="mb-0 d-flex justify-content-center justify-content-lg-start py-3 py-lg-0"><strong>Free
								U.S. Shipping on Order above Rs99. Easy Returns.</strong></p>
					</div>

					<div class="col-12 col-lg-auto d-none d-lg-flex">

						<!-- Top Links
						============================================= -->
						<!-- <div class="top-links">
							<ul class="top-links-container">
								<li class="top-links-item"><a href="#">About</a></li>
								<li class="top-links-item"><a href="#">FAQS</a></li>
								<li class="top-links-item"><a href="#">Blogs</a></li>
								<li class="top-links-item"><a href="#">EN</a>
									<ul class="top-links-sub-menu">
										<li class="top-links-item"><a href="#"><img src="images/icons/flags/french.png"
													alt="French"> FR</a></li>
										<li class="top-links-item"><a href="#"><img src="images/icons/flags/italian.png"
													alt="Italian"> IT</a></li>
										<li class="top-links-item"><a href="#"><img src="images/icons/flags/german.png"
													alt="German"> DE</a></li>
									</ul>
								</li>
							</ul>
						</div> -->
						<!-- .top-links end -->

						<!-- Top Social
						============================================= -->
						<ul id="top-social">
							<li><a href="#" class="h-bg-facebook"><span class="ts-icon"><i
											class="fa-brands fa-facebook-f"></i></span><span
										class="ts-text">Facebook</span></a></li>
							<li><a href="#" class="h-bg-instagram"><span class="ts-icon"><i
											class="fa-brands fa-instagram"></i></span><span
										class="ts-text">Instagram</span></a></li>
							<li><a href="tel:+1.11.85412542" class="h-bg-call"><span class="ts-icon"><i
											class="fa-solid fa-phone"></i></span><span
										class="ts-text">+1.11.85412542</span></a></li>
							<li><a href="mailto:info@techCentrix.com" class="h-bg-email3"><span class="ts-icon"><i
											class="bi-envelope-fill"></i></span><span
										class="ts-text">info@techCentrix.com</span></a></li>
						</ul><!-- #top-social end -->

					</div>
				</div>

			</div>
		</div>

		<!-- Header
		============================================= -->
		<header id="header" class="full-header header-size-md">
			<div id="header-wrap">
				<div class="container">
					<div class="header-row justify-content-lg-between">

						<!-- Logo
						============================================= -->
						<div id="logo" class="me-lg-4">
							<a href="demo-shop.html">

							</a>
						</div><!-- #logo end -->

						<div class="header-misc">

							<!-- Top Search
							============================================= -->
							<!-- <div id="top-account">
								<a href="#modal-register" data-lightbox="inline"><i
										class="bi-person me-1 position-relative" style="top: 1px;"></i><span
										class="d-none d-sm-inline-block font-primary fw-medium">Login</span></a>
							</div>#top-search end -->

							<!-- Top Cart
							============================================= -->
							<!--<div id="top-cart" class="header-misc-icon d-none d-sm-block">
								<a href="#" id="top-cart-trigger"><i class="uil uil-shopping-bag"></i><span class="top-cart-number">5</span></a>
								<div class="top-cart-content">
									<div class="top-cart-title">
										<h4>Shopping Cart</h4>
									</div>
									<div class="top-cart-items">
										<div class="top-cart-item">
											<div class="top-cart-item-image">
												<a href="#"><img src="images/shop/small/1.jpg" alt="Blue Round-Neck Tshirt"></a>
											</div>
											<div class="top-cart-item-desc">
												<div class="top-cart-item-desc-title">
													<a href="#">Blue Round-Neck Tshirt with a Button</a>
													<span class="top-cart-item-price d-block">Rs19.99</span>
												</div>
												<div class="top-cart-item-quantity">x 2</div>
											</div>
										</div>
										<div class="top-cart-item">
											<div class="top-cart-item-image">
												<a href="#"><img src="images/shop/small/6.jpg" alt="Light Blue Denim Dress"></a>
											</div>
											<div class="top-cart-item-desc">
												
													<a href="#">Light Blue Denim Dress</a>
													
													<span class="top-cart-item-price d-block">Rs24.99</span>
												</div>
												<div class="top-cart-item-quantity">x 3</div>
											</div>
										</div>
									</div>
									<div class="top-cart-action">
										<span class="top-checkout-price">Rs114.95</span>
										<a href="#" class="button button-3d button-small m-0">View Cart</a>
									</div>
								</div>
							</div> #top-cart end -->

							<!-- Top Search
							============================================= -->
							<!--<div id="top-search" class="header-misc-icon">
								<a href="#" id="top-search-trigger"><i class="uil uil-search"></i><i class="bi-x-lg"></i></a>
							</div> #top-search end -->

						</div>

						<!--<div class="primary-menu-trigger">
							<button class="cnvs-hamburger" type="button" title="Open Mobile Menu">
								<span class="cnvs-hamburger-box"><span class="cnvs-hamburger-inner"></span></span>
							</button>
						</div>!-->

						<!-- Primary Navigation
						============================================= -->
						<div class="header-row w-100 d-flex justify-space-between align-items-center">

							<!-- Logo
						============================================= -->
							<div id="logo">
								<a href="shopping.php">
									<img class="logo-default" srcset="images/loo.png, images/lgo@2x.png 2x"
										src="images/ogo@.png" alt=" Logo">
									<!--<img class="logo-dark" srcset="images/logo-dark.png, images/logo-dark@2x.png 2x" src="images/logo-@2x.png" alt=" Logo">Logo-->
								</a>
							</div><!-- #logo end -->

							<div class="header-misc">

								<!-- Top Search
							============================================= -->
								<div id="top-search" class="header-misc-icon">
									<a href="#" id="top-search-trigger"><i class="uil uil-search"></i><i
											class="bi-x-lg"></i></a>
								</div><!-- #top-search end -->

								<!-- Top Cart
							============================================= -->
								<div id="top-cart" class="header-misc-icon d-none d-sm-block">
									<a href="#" id="top-cart-trigger"><i class="uil uil-shopping-bag"></i><span
											class="top-cart-number">0</span></a>
									<div class="top-cart-content">
										<div class="top-cart-title">
											<h4>Shopping Cart</h4>
										</div>
										<div class="top-cart-items">
											
										</div>
										<div class="top-cart-action">
											<a href="#" class="button button-3d button-small m-0" id="cart-nav">View
												Cart</a>
										</div>
									</div>
								</div>
								<!-- #top-cart end -->

							</div>

							<div class="primary-menu-trigger">
								<button class="cnvs-hamburger" type="button" title="Open Mobile Menu">
									<span class="cnvs-hamburger-box"><span class="cnvs-hamburger-inner"></span></span>
								</button>
							</div>

							<!-- Primary Navigation
						============================================= -->
							<nav class="primary-menu">

								<ul class="menu-container">
									<li class="menu-item">
										<a class="menu-link active" href="#">
											<div>Home</div>
										</a>

									</li>
									<?php foreach ($nav as $n): ?>
										<li class="menu-item">
											<a class="menu-link" href="#">
												<div><?php echo $n['category']; ?></div>
											</a>
										</li>
									<?php endforeach; ?>


									<!-- <li class="menu-item mega-menu">
										<a class="menu-link" href="#">
											<div>Pages</div>
										</a>

									</li>
									<li class="menu-item mega-menu">
										<a class="menu-link" href="#">
											<div>Portfolio</div>
										</a>

									</li>
									<li class="menu-item mega-menu">
										<a class="menu-link" href="#">
											<div>Blog</div>
										</a>

									</li>
									<li class="menu-item">
										<a class="menu-link" href="shop.html">
											<div>Shop</div>
										</a>

									</li>
									<li class="menu-item mega-menu">
										<a class="menu-link" href="#">
											<div>Shortcodes</div>
										</a>
										<div class="mega-menu-content">
											<div class="container">

											</div>
										</div>
									</li> -->
								</ul>

							</nav><!-- #primary-menu end -->

							<form class="top-search-form" action="search.html" method="get">
								<input type="text" name="q" class="form-control" value=""
									placeholder="Type &amp; Hit Enter.." autocomplete="off">
							</form>

						</div>

						<form class="top-search-form" action="search.html" method="get">
							<input type="text" name="q" class="form-control" value=""
								placeholder="Type &amp; Hit Enter.." autocomplete="off">
						</form>
					</div>
				</div>
			</div>
			<div class="header-wrap-clone"></div>
		</header><!-- #header end -->
		<div class="mobile-bottom-nav d-md-none">
			<a href="#" class="nav-item active"> <i class="fas fa-home"></i></a>
			<a href="#" class="nav-item"><i class="fas fa-male"></i></a>

			<a href="#" class="nav-item"><i class="fas fa-female"></i></a>
			<a href="#" class="nav-item"> <i class="fas fa-gem"></i></a><a id="top-cart-trigger" class="pe-3"
				data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false"
				aria-controls="collapseExample" style="color: rgb(102, 102, 102); text-decoration: none;">
				<i class="uil uil-shopping-bag"></i>
				<span class="top-cart-number"></span>
			</a>
			<div class="collapse" id="collapseExample"
				style="position: fixed; top: 30%; left: 30%; z-index: 1050; width: 300px;">
				<div class="card card-body shadow position-relative">

					<button type="button" class="btn-close position-absolute" style="top: 10px; right: 10px;"
						onclick="bootstrap.Collapse.getInstance(document.getElementById('collapseExample')).hide();">
					</button>

					<div class="top-cart-title mt-4">
						<h4>Shopping Cart</h4>
					</div>

					<!-- Cart Items -->
					<div class="top-cart-items">
						<!-- darshan's changes	 -->
					</div>
					<div class="top-cart-action">
						<a href="#" onclick="navigateCategory(event)" class="button button-3d button-small m-0"
							id="cart-nav">View
							Cart</a>
					</div>
				</div>
				<!-- darshan's changes ends here -->
			</div>
			<div class="top-cart-content">
				<div class="top-cart-title">
				</div>
			</div>

		</div>

		<!-- Slider
		============================================= -->
		<section id="slider" class="slider-element swiper_wrapper" data-autoplay="6000" data-speed="800"
			data-loop="true" data-grab="true" data-effect="fade" data-arrow="false" style="height: 600px;">

			<div class="swiper swiper-parent">
				<div class="swiper-wrapper">
					<div class="swiper-slide dark">
						<div class="container">
							<div class="slider-caption slider-caption-center">
								<div>
									<div class="h5 mb-2 font-secondary">Fresh Arrivals</div>
									<h2 class="mb-4 text-white">Winter / 2021</h2>
									<a href="#" class="button bg-white text-dark button-light">Shop Menswear</a>
								</div>
							</div>
						</div>
						<div class="swiper-slide-bg" style="background-image: url('demos/shop/images/slider/1.jpg');">
						</div>
					</div>
					<div class="swiper-slide dark">
						<div class="container">
							<div class="slider-caption slider-caption-center">
								<div>
									<div class="h5 mb-2 font-secondary">Summer Collections</div>
									<h2 class="mb-4 text-white">Sale 40% Off</h2>
									<a href="#" class="button bg-white text-dark button-light">Shop </a>
								</div>
							</div>
						</div>
						<div class="swiper-slide-bg"
							style="background-image: url('demos/shop/images/slider/1.jpg'); background-position: center 20%;">
						</div>
					</div>
					<div class="swiper-slide dark">
						<div class="container">
							<div class="slider-caption slider-caption-center">
								<div>
									<h2 class="mb-4 text-white">New Arrivals / 18</h2>
									<a href="#" class="button bg-white text-dark button-light">Shop Womenswear</a>
								</div>
							</div>
						</div>
						<div class="swiper-slide-bg"
							style="background-image: url('demos/shop/images/slider/2.jpg'); background-position: center 40%;">
						</div>
					</div>
				</div>
				<div class="swiper-pagination"></div>
			</div>

		</section><!-- #Slider End -->

		<!-- Content
		============================================= -->
		<section id="content">
			<div class="content-wrap">
				<div class="container">

					<!-- Shop Categories
					============================================= -->
					<div class="fancy-title title-border title-center mb-4">
						<h4>Shop popular categories</h4>
					</div>

					<div class="row shop-categories">

						<?php foreach ($categories as $category): ?>

							<div class="col-lg-4">
								<a href="shop-combination-filter.php?id"
									style="background: url('<?= htmlspecialchars($category['thumbnail']) ?>') no-repeat center center; background-size: cover;">
									<div class="vertical-middle dark text-center">
										<div class="heading-block m-0 border-0">
											<h3 class="text-transform-none fw-semibold ls-0">
												<?= htmlspecialchars(($category['category'])) ?>
											</h3>
											<small class="button bg-white text-dark button-light button-mini">Shop
												Now</small>
										</div>
									</div>
								</a>
							</div>
						<?php endforeach; ?>
						<!-- <div class="col-lg-4">
							<a href="#"
								style="background: url('demos/shop/images/categories/3.jpg') no-repeat center center; background-size: cover;">
								<div class="vertical-middle dark text-center">
									<div class="heading-block m-0 border-0">
										<h3 class="text-transform-none fw-semibold ls-0">Footwears</h3>
										<small class="button bg-white text-dark button-light button-mini">Shop
											Now</small>
									</div>
								</div>
							</a>
						</div>
						<div class="col-lg-4">
							<a href="#"
								style="background: url('demos/shop/images/categories/5.jpg') no-repeat center center; background-size: cover;">
								<div class="vertical-middle dark text-center">
									<div class="heading-block m-0 border-0">
										<h3 class="text-transform-none fw-semibold ls-0">Sportswear</h3>
										<small class="button bg-white text-dark button-light button-mini">Shop
											Now</small>
									</div>
								</div>
							</a>
						</div>
					</div> -->

						<!-- Featured Carousel
					============================================= -->
					<div class="fancy-title title-border mt-4 title-center">
						<h4>Generic Items</h4>
					</div>
					<div class="row">
						<!-- <div id="oc-products" class="owl-carousel products-carousel carousel-widget" data-margin="20"
						data-loop="true" data-autoplay="5000" data-nav="true" data-pagi="false" data-items-xs="1"
						data-items-sm="2" data-items-md="3" data-items-lg="4" data-items-xl="5"> -->
						<?php foreach ($products as $product): ?>
							<div class="oc-item mt-3  col-md-3 col-6 ">
								<div href="shop-single-right-sidebar.html" class="product shadow rounded-2 py-2"
									data-id="<?= intval($product['id']) ?>">
									<div class="product-image">
										<a href="productDetail.php?id=<?= intval($product['id']) ?>">
											<!-- src="<?= htmlspecialchars($product['thumbnail']) ?>" -->

											<img id="<?= $imgRandomId ?>" src="./images/shop/dress/1.jpg" height="250"
												width="350" class="img img-fluid image"
												alt="<?= htmlspecialchars($product['productName']) ?>">
										</a>
										<!--<?php if ($product['SP'] < $product['MRP']): ?>-->
											<div class="sale-flash badge bg-danger p-2">Sale!</div>
											<!--<?php endif; ?>-->
										<div class="bg-overlay">
											<div class="bg-overlay-content align-items-end justify-content-between"
												data-hover-animate="fadeIn" data-hover-speed="400">

											</div>
											<div class="bg-overlay-bg bg-transparent"></div>
										</div>
									</div>
									<div class="product-desc px-3">
										<div class="product-title mb-1">
											<h3><a
													href="shop-single-right-sidebar.html?id=<?= intval($product['id']) ?>"><?= htmlspecialchars($product['productName']) ?></a>
											</h3>
										</div>
										<div class="product-price font-primary">
											<!--<?php if ($product['SP'] < $product['MRP']): ?>-->
												<del class="me-1">Rs<?= number_format($product['MRP'], 2) ?></del>
												<!--<?php endif; ?>-->
											<ins>Rs<?= number_format($product['SP'], 2) ?></ins>
										</div>
										<button href="#" class="btn btn-dark me-2 cart-btn" title="Add to Cart"
											data-id="<?= intval($product['id']) ?>"
											data-img="<?= htmlspecialchars($product['thumbnail']) ?>"
											data-name="<?= htmlspecialchars($product['productName']) ?>"
											data-price="<?= number_format($product['SP'], 2, '.', '') ?>"><i
												class="bi-bag-plus"></i></button>

									</div>
								</div>
							</div>
						<?php endforeach; ?>
						<nav class="mt-4" aria-label="Page navigation example">
							<ul class="pagination justify-content-center">
								<li class="page-item disabled">
									<a class="page-link text-dark" href="#" tabindex="-1"
										aria-disabled="true">Previous</a>
								</li>
								<li class="page-item"><a class="page-link text-dark active" href="#">1</a></li>
								<li class="page-item"><a class="page-link text-dark" href="#">2</a></li>
								<li class="page-item"><a class="page-link text-dark" href="#">3</a></li>
								<li class="page-item">
									<a class="page-link bg-dark text-white" href="#">Next</a>
								</li>
							</ul>
						</nav>
					</div>

						<!-- New Arrival Section
				============================================= -->
						<!--<div class="section my-4">
					<div class="container">
						<div class="row align-items-stretch">
							<div class="col-md-5">
								<div class="row">
									<div class="col-md-12 text-center p-5">
										<div class="heading-block mb-1 border-bottom-0 mx-auto">
											<div class="font-secondary text-black-50 mb-1">New Arrivals</div>
											<h3 class="text-transform-none ls-0">Fresh Arrivals / Autumn 18</h3>
											<p class="fw-normal mt-2 mb-3 text-muted" style="font-size: 17px; line-height: 1.4">Dynamically drive interdependent metrics for worldwide portals. Proactively grow client technology schemas.</p>
											<a href="#" class="button bg-dark text-white button-dark button-small ms-0">Shop Now</a>
										</div>
									</div>
									<div class="col-6">
										<a href="demos/shop/images/sections/1-2.jpg" data-lightbox="image"><img src="demos/shop/images/sections/1-2.jpg" alt="Image"></a>
									</div>
									<div class="col-6">
										<a href="demos/shop/images/sections/1-3.jpg" data-lightbox="image"><img src="demos/shop/images/sections/1-3.jpg" alt="Image"></a>
									</div>
								</div>
							</div>
							<div class="col-md-7 min-vh-50">
								<a href="https://www.youtube.com/watch?v=bpNcuh_BnsA" data-lightbox="iframe" class="d-block position-relative h-100" style="background: url('demos/shop/images/sections/1.jpg') center center no-repeat; background-size: cover;">
									<div class="vertical-middle text-center">
										<div class="play-icon"><i class="fa-solid fa-circle-play display-3 text-light"></i></div>
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="clear"></div>

				<!-- New Arrivals Men
				============================================= -->
						<div class="container">

							<div class="fancy-title title-border mt-4 mb-4 title-center">
								<h4>New Arrivals: Men</h4>
							</div>


							<div class="row col-mb-30">
								<div id="oc-products" class="owl-carousel products-carousel carousel-widget "
									data-margin="20" data-loop="true" data-autoplay="5000" data-nav="true"
									data-pagi="false" data-items-xs="1" data-items-sm="2" data-items-md="4">

									<!-- Shop Item 1
						============================================= -->
									<?php foreach ($products as $product): ?>
										<div class="py-4 ">
											<div class="product  shadow rounded-1" data-id="<?= intval($product['id']) ?>">
												<div class="product-image">
													<a href="productDetail.php?id=<?= intval($product['id']) ?>">
														<img id="<?= $imgRandomId ?>" src="./images/shop/dress/1.jpg"
															height="250" width="350" class="img img-fluid image"
															alt="<?= htmlspecialchars($product['productName']) ?>">
													</a>
													<!-- <a href="#"><img src="demos/shop/images/items/new/1-1.jpg" alt="Image 1"></a> -->
													<div class="sale-flash badge bg-danger p-2">Sale!</div>
													<div class="bg-overlay">
														<div class="bg-overlay">
															<div class="bg-overlay-content align-items-end justify-content-between"
																data-hover-animate="fadeIn" data-hover-speed="400">
																<input type="number" class="quantity-input" min="1" max="99"
																	value="1"
																	style="position: absolute; top: -40px; left: 0; width: 60px; display: none; z-index:100; padding: 4px; border-radius: 4px; border: 1px solid #ccc; text-align: center;">

															</div>
															<div class="bg-overlay-bg bg-transparent"></div>
														</div>
														<div class="bg-overlay-bg bg-transparent"></div>
													</div>
												</div>
												<div class="product-desc px-3">
													<div class="product-title mb-1">
														<h3><a
																href="shop-single-right-sidebar.html?id=<?= intval($product['id']) ?>"><?= htmlspecialchars($product['productName']) ?></a>
														</h3>
													</div>
													<div class="product-price font-primary">
														<!--<?php if ($product['SP'] < $product['MRP']): ?>-->
															<del class="me-1">Rs<?= number_format($product['MRP'], 2) ?></del>
															<!--<?php endif; ?>-->
														<ins>Rs<?= number_format($product['SP'], 2) ?></ins>
													</div>
													<button href="#" class="btn btn-dark me-2 cart-btn" title="Add to Cart"
														data-id="<?= intval($product['id']) ?>"
														data-img="<?= htmlspecialchars($product['thumbnail']) ?>"
														data-name="<?= htmlspecialchars($product['productName']) ?>"
														data-price="<?= number_format($product['SP'], 2, '.', '') ?>"><i
															class="bi-bag-plus"></i></button>

												</div>
											</div>

										</div>
									<?php endforeach; ?>
								</div>
							</div>
							<!-- Shop Item 2
						============================================= -->
							<!-- <div class="col-lg-2 col-md-3 col-6 px-2">
							<div class="product">
								<div class="product-image">
									<a href="#"><img src="demos/shop/images/items/new/2.jpg" alt="Image 2"></a>
									<a href="#"><img src="demos/shop/images/items/new/2-1.jpg" alt="Image 2"></a>
									<div class="bg-overlay">
										<div class="bg-overlay-content align-items-end justify-content-between" data-hover-animate="fadeIn" data-hover-speed="400">
											<a href="#" class="btn btn-dark me-2" title="Add to Cart"><i class="bi-bag-plus"></i></a>
											<a href="demos/shop/ajax/shop-item.html" class="btn btn-dark" data-lightbox="ajax"><i class="bi-eye"></i></a>
										</div>
										<div class="bg-overlay-bg bg-transparent"></div>
									</div>
								</div>
								<div class="product-desc">
									<div class="product-title mb-1"><h3><a href="#">Deep Blue Sport Shoe</a></h3></div>
									<div class="product-price font-primary"><ins>Rs19.99</ins></div>
									
								</div>
							</div>
						</div> -->

							<!-- Shop Item 3
						============================================= -->
							<!-- <div class="col-lg-2 col-md-3 col-6 px-2">
							<div class="product">
								<div class="product-image">
									<a href="#"><img src="demos/shop/images/items/new/4.jpg" alt="Image 3"></a>
									<div class="bg-overlay">
										<div class="bg-overlay-content align-items-end justify-content-between" data-hover-animate="fadeIn" data-hover-speed="400">
											<a href="#" class="btn btn-dark me-2" title="Add to Cart"><i class="bi-bag-plus"></i></a>
											<a href="demos/shop/ajax/shop-item.html" class="btn btn-dark" data-lightbox="ajax"><i class="bi-eye"></i></a>
										</div>
										<div class="bg-overlay-bg bg-transparent"></div>
									</div>
								</div>
								<div class="sale-flash badge bg-danger p-2">Sale!</div>
								<div class="product-desc">
									<div class="product-title mb-1"><h3><a href="#">Printed Men Short</a></h3></div>
									<div class="product-price font-primary"><del class="me-1">Rs41.99</del> <ins>Rs35.00</ins></div>
									
								</div>
							</div>
						</div> -->

							<!-- Shop Item 4
						============================================= -->
							<!-- <div class="col-lg-2 col-md-3 col-6 px-2">
							<div class="product">
								<div class="product-image">
									<div class="fslider" data-arrows="false" data-autoplay="4500">
										<div class="flexslider">
											<div class="slider-wrap">
												<div class="slide"><a href="#"><img src="demos/shop/images/items/new/3.jpg" alt="Image 4"></a></div>
												<div class="slide"><a href="#"><img src="demos/shop/images/items/new/3-1.jpg" alt="Image 4"></a></div>
											</div>
										</div>
									</div>
									<div class="bg-overlay">
										<div class="bg-overlay-content align-items-end justify-content-between" data-hover-animate="fadeIn" data-hover-speed="400">
											<a href="#" class="btn btn-dark me-2" title="Add to Cart"><i class="bi-bag-plus"></i></a>
											<a href="demos/shop/ajax/shop-item.html" class="btn btn-dark" data-lightbox="ajax"><i class="bi-eye"></i></a>
										</div>
										<div class="bg-overlay-bg bg-transparent"></div>
									</div>
								</div>
								<div class="product-desc">
									<div class="product-title mb-1"><h3><a href="#">Women Sportd Track Pant</a></h3></div>
									<div class="product-price font-primary"><ins>Rs21.00</ins></div>
									
								</div>
							</div>
						</div> -->

							<!-- Shop Item 5
						============================================= -->
							<!-- <div class="col-lg-2 col-md-3 col-6 px-2">
							<div class="product">
								<div class="product-image">
									<a href="#"><img src="demos/shop/images/items/new/6.jpg" alt="Image 5"></a>
									<a href="#"><img src="demos/shop/images/items/new/6-1.jpg" alt="Image 5"></a>
									<div class="bg-overlay">
										<div class="bg-overlay-content align-items-end justify-content-between" data-hover-animate="fadeIn" data-hover-speed="400">
											<a href="#" class="btn btn-dark me-2" title="Add to Cart"><i class="bi-bag-plus"></i></a>
											<a href="demos/shop/ajax/shop-item.html" class="btn btn-dark" data-lightbox="ajax"><i class="bi-eye"></i></a>
										</div>
										<div class="bg-overlay-bg bg-transparent"></div>
									</div>
								</div>
								<div class="product-desc">
									<div class="product-title mb-1"><h3><a href="#">Cool Printed Dress</a></h3></div>
									<div class="product-price font-primary"><ins>Rs31.49</ins></div>
									
								</div>
							</div>
						</div> -->

							<!-- Shop Item 6
						============================================= -->
							<!-- <div class="col-lg-2 col-md-3 col-6 px-2">
							<div class="product">
								<div class="product-image">
									<a href="#"><img src="demos/shop/images/items/new/5.jpg" alt="Image 6"></a>
									<div class="bg-overlay">
										<div class="bg-overlay-content align-items-end justify-content-between" data-hover-animate="fadeIn" data-hover-speed="400">
											<a href="#" class="btn btn-dark me-2" title="Add to Cart"><i class="bi-bag-plus"></i></a>
											<a href="demos/shop/ajax/shop-item.html" class="btn btn-dark" data-lightbox="ajax"><i class="bi-eye"></i></a>
										</div>
										<div class="bg-overlay-bg bg-transparent"></div>
									</div>
								</div>
								<div class="sale-flash badge bg-danger p-2">Sale!</div>
								<div class="product-desc">
									<div class="product-title mb-1"><h3><a href="#">Red Stripe Girls Top</a></h3></div>
									<div class="product-price font-primary"><del class="me-1">Rs41.99</del> <ins>Rs35.00</ins></div>
									
								</div>
							</div>
						</div> -->

							<!-- Shop Item 7
						============================================= -->
							<!-- <div class="col-lg-2 col-md-3 col-6 px-2">
							<div class="product">
								<div class="product-image">
									<a href="#"><img src="demos/shop/images/items/new/7.jpg" alt="Image 7"></a>
									<div class="bg-overlay">
										<div class="bg-overlay-content align-items-end justify-content-between" data-hover-animate="fadeIn" data-hover-speed="400">
											<a href="#" class="btn btn-dark me-2" title="Add to Cart"><i class="bi-bag-plus"></i></a>
											<a href="demos/shop/ajax/shop-item.html" class="btn btn-dark" data-lightbox="ajax"><i class="bi-eye"></i></a>
										</div>
										<div class="bg-overlay-bg bg-transparent"></div>
									</div>
								</div>
								<div class="product-desc">
									<div class="product-title mb-1"><h3><a href="#">Dark Brown Lady Bag for Women</a></h3></div>
									<div class="product-price font-primary"><ins>Rs49.49</ins></div>
									
								</div>
							</div>
						</div> -->

							<!-- Shop Item 8
						============================================= -->
							<!-- <div class="col-lg-2 col-md-3 col-6 px-2">
							<div class="product">
								<div class="product-image">
									<a href="#"><img src="demos/shop/images/items/new/8.jpg" alt="Image 8"></a>
									<div class="bg-overlay">
										<div class="bg-overlay-content align-items-end justify-content-between" data-hover-animate="fadeIn" data-hover-speed="400">
											<a href="#" class="btn btn-dark me-2" title="Add to Cart"><i class="bi-bag-plus"></i></a>
											<a href="demos/shop/ajax/shop-item.html" class="btn btn-dark" data-lightbox="ajax"><i class="bi-eye"></i></a>
										</div>
										<div class="bg-overlay-bg bg-transparent"></div>
									</div>
								</div>
								<div class="product-desc">
									<div class="product-title mb-1"><h3><a href="#">UV Poection Sunglass</a></h3></div>
									<div class="product-price font-primary"><ins>Rs39.99</ins></div>
									
								</div>
							</div>
						</div> -->

							<!-- Shop Item 9
						============================================= -->
							<!-- <div class="col-lg-2 col-md-3 col-6 px-2">
							<div class="product">
								<div class="product-image">
									<a href="#"><img src="demos/shop/images/items/new/9.jpg" alt="Image 9"></a>
									<a href="#"><img src="demos/shop/images/items/new/9-1.jpg" alt="Image 3"></a>
									<div class="bg-overlay">
										<div class="bg-overlay-content align-items-end justify-content-between" data-hover-animate="fadeIn" data-hover-speed="400">
											<a href="#" class="btn btn-dark me-2" title="Add to Cart"><i class="bi-bag-plus"></i></a>
											<a href="demos/shop/ajax/shop-item.html" class="btn btn-dark" data-lightbox="ajax"><i class="bi-eye"></i></a>
										</div>
										<div class="bg-overlay-bg bg-transparent"></div>
									</div>
								</div>
								<div class="sale-flash badge bg-danger p-2">Sale!</div>
								<div class="product-desc">
									<div class="product-title mb-1"><h3><a href="#">Workout Sweat T-shirt</a></h3></div>
									<div class="product-price font-primary"><del class="me-1">Rs21.99</del> <ins>Rs15.00</ins></div>
									
								</div>
							</div>
						</div> -->

							<!-- Shop Item 10
						============================================= -->
							<!-- <div class="col-lg-2 col-md-3 col-6 px-2">
							<div class="product">
								<div class="product-image">
									<a href="#"><img src="demos/shop/images/items/new/10.jpg" alt="Image 10"></a>
									<div class="bg-overlay">
										<div class="bg-overlay-content align-items-end justify-content-between" data-hover-animate="fadeIn" data-hover-speed="400">
											<a href="#" class="btn btn-dark me-2" title="Add to Cart"><i class="bi-bag-plus"></i></a>
											<a href="include/ajax/shop-item.html" class="btn btn-dark" data-lightbox="ajax" title="Quick View"><i class="bi-eye"></i></a>
										</div>
										<div class="bg-overlay-bg bg-transparent"></div>
									</div>
								</div>
								<div class="sale-flash badge bg-secondary p-2">Out of Stock!</div>
								<div class="product-desc">
									<div class="product-title mb-1"><h3><a href="#">Sky Blue Printed Bag</a></h3></div>
									<div class="product-price font-primary"><ins>Rs61.49</ins></div>
									
								</div>
							</div>
						</div> -->

							<!-- Shop Item 11
						============================================= -->
							<!-- <div class="col-lg-2 col-md-3 col-6 px-2">
							<div class="product">
								<div class="product-image">
									<div class="fslider" data-arrows="false" data-autoplay="4500">
										<div class="flexslider">
											<div class="slider-wrap">
												<div class="slide"><a href="#"><img src="demos/shop/images/items/new/11.jpg" alt="Image 10"></a></div>
												<div class="slide"><a href="#"><img src="demos/shop/images/items/new/11-1.jpg" alt="Image 10"></a></div>
											</div>
										</div>
									</div>
									<div class="bg-overlay">
										<div class="bg-overlay-content align-items-end justify-content-between" data-hover-animate="fadeIn" data-hover-speed="400">
											<a href="#" class="btn btn-dark me-2" title="Add to Cart"><i class="bi-bag-plus"></i></a>
											<a href="demos/shop/ajax/shop-item.html" class="btn btn-dark" data-lightbox="ajax"><i class="bi-eye"></i></a>
										</div>
										<div class="bg-overlay-bg bg-transparent"></div>
									</div>
								</div>
								<div class="product-desc">
									<div class="product-title mb-1"><h3><a href="#">Blue Women Watch</a></h3></div>
									<div class="product-price font-primary"><ins>Rs23.00</ins></div>
									
								</div>
							</div>
						</div> -->

							<!-- Shop Item 12
						============================================= -->
							<!-- <div class="col-lg-2 col-md-3 col-6 px-2">
							<div class="product">
								<div class="product-image">
									<a href="#"><img src="demos/shop/images/items/new/12.jpg" alt="Image 6"></a>
									<div class="bg-overlay">
										<div class="bg-overlay-content align-items-end justify-content-between" data-hover-animate="fadeIn" data-hover-speed="400">
											<a href="#" class="btn btn-dark me-2" title="Add to Cart"><i class="bi-bag-plus"></i></a>
											<a href="demos/shop/ajax/shop-item.html" class="btn btn-dark" data-lightbox="ajax"><i class="bi-eye"></i></a>
										</div>
										<div class="bg-overlay-bg bg-transparent"></div>
									</div>
								</div>
								<div class="product-desc">
									<div class="product-title mb-1"><h3><a href="#">Blue Party Shoe</a></h3></div>
									<div class="product-price font-primary"><ins>Rs51.00</ins></div>
									
								</div>
							</div>
						</div> -->

						</div>

					</div>

					<!-- Sign Up
				============================================= -->
					<!--<div class="section my-4 py-5">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<div class="row align-items-stretch align-items-center">
									<div class="col-md-7 min-vh-50" style="background: url('demos/shop/images/sections/3.jpg') center center no-repeat; background-size: cover;">
										<div class="vertical-middle ps-5">
											<h2 class="ps-5"><strong>40%</strong> Off<br>First Order*</h2>
										</div>
									</div>
									<div class="col-md-5 bg-white">
										<div class="card border-0 py-2">
											<div class="card-body">
												<h3 class="card-title mb-4 ls-0">Sign up to get the most out of shopping.</h3>
												<ul class="iconlist">
													<li><i class="bi-check-circle"></i> Receive Exclusive Sale Alerts</li>
													<li><i class="bi-check-circle"></i> 30 Days Return Policy</li>
													<li><i class="bi-check-circle"></i> Cash on Delivery Accepted</li>
												</ul>
												<a href="#" class="button button-rounded ls-0 fw-semibold ms-0 mb-2 text-transform-none px-4">Sign Up</a><br>
												<small class="fst-italic text-black-50">Don't worry, it's totally free.</small>
											</div>
										</div>
									</div>
								</div>ggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg
							</div>
						</div>
					</div>
				</div>

				<div class="container">

					<!-- Features
					============================================= -->
					<div class="row col-mb-50 mb-0 mt-5">
						<div class="col-lg-7">
							<div class="row mt-3">
								<div class="col-sm-6">
									<div class="feature-box fbox-sm fbox-plain">
										<div class="fbox-icon">
											<a href="#"><i class="bi-gift text-dark text-dark"></i></a>
										</div>
										<div class="fbox-content">
											<h3 class="fw-normal">100% Original</h3>
											<p>techCentrix provides support for Native HTML5 Videos that can be added to
												a
												Full Width Background.</p>
										</div>
									</div>
								</div>

								<div class="col-sm-6 mt-4 mt-sm-0">
									<div class="feature-box fbox-sm fbox-plain">
										<div class="fbox-icon">
											<a href="#"><i class="bi-globe text-dark"></i></a>
										</div>
										<div class="fbox-content">
											<h3 class="fw-normal">Free Shipping</h3>
											<p>Display your Content attractively using Parallax Sections that have
												unlimited
												customizable areas.</p>
										</div>
									</div>
								</div>

								<div class="col-sm-6 mt-4">
									<div class="feature-box fbox-sm fbox-plain">
										<div class="fbox-icon">
											<a href="#"><i class="bi-arrow-clockwise text-dark"></i></a>
										</div>
										<div class="fbox-content">
											<h3 class="fw-normal">30-Days Returns</h3>
											<p>You have complete easy control on each &amp; every element that provides
												endless customization possibilities.</p>
										</div>
									</div>
								</div>

								<div class="col-sm-6 mt-4">
									<div class="feature-box fbox-sm fbox-plain">
										<div class="fbox-icon">
											<a href="#"><i class="bi-wallet2 text-dark"></i></a>
										</div>
										<div class="fbox-content">
											<h3 class="fw-normal">Payment Options</h3>
											<p>We accept Visa, MasterCard and American Express. And We also Deliver by
												COD(only in US)</p>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-5">
							<div class="accordion">

								<div class="accordion-header">
									<div class="accordion-icon">
										<i class="accordion-closed bi-check-circle-fill"></i>
										<i class="accordion-open bi-x-circle-fill"></i>
									</div>
									<div class="accordion-title">
										Our Mission
									</div>
								</div>
								<div class="accordion-content">Donec sed odio dui. Nulla vitae elit libero, a pharetra
									augue. Nullam id dolor id nibh ultricies vehicula ut id elit. Integer posuere erat a
									ante venenatis dapibus posuere velit aliquet.</div>

								<div class="accordion-header">
									<div class="accordion-icon">
										<i class="accordion-closed bi-check-circle-fill"></i>
										<i class="accordion-open bi-x-circle-fill"></i>
									</div>
									<div class="accordion-title">
										What we Do?
									</div>
								</div>
								<div class="accordion-content">Integer posuere erat a ante venenatis dapibus posuere
									velit
									aliquet. Duis mollis, est non commodo luctus. Aenean lacinia bibendum nulla sed
									consectetur. Cras mattis consectetur purus sit amet fermentum.</div>

								<div class="accordion-header">
									<div class="accordion-icon">
										<i class="accordion-closed bi-check-circle-fill"></i>
										<i class="accordion-open bi-x-circle-fill"></i>
									</div>
									<div class="accordion-title">
										Our Company's Values
									</div>
								</div>
								<div class="accordion-content">Nullam id dolor id nibh ultricies vehicula ut id elit.
									Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Duis mollis,
									est
									non commodo luctus. Aenean lacinia bibendum nulla sed consectetur.</div>

								<div class="accordion-header">
									<div class="accordion-icon">
										<i class="accordion-closed bi-check-circle-fill"></i>
										<i class="accordion-open bi-x-circle-fill"></i>
									</div>
									<div class="accordion-title">
										Our Return Policy
									</div>
								</div>
								<div class="accordion-content">Integer posuere erat a ante venenatis dapibus posuere
									velit
									aliquet. Duis mollis, est non commodo luctus. Aenean lacinia bibendum nulla sed
									consectetur. Cras mattis consectetur purus sit amet fermentum.</div>

							</div>
						</div>

					</div>

					<div class="clear"></div>

					<!-- Brands
					============================================= -->
					<!--<div class="row mt-5">
						<div class="col-12">
							<div class="fancy-title title-border title-center mb-4">
								<h4>Brands who give Flat <span class="text-danger">40%</span> Off</h4>
							</div>

							<ul class="clients-grid row mb-0 justify-content-center">
								<li class="grid-item"><a href="#"><img src="images/clients/logo/1.png" alt="Clients"></a></li>
								<li class="grid-item"><a href="#"><img src="images/clients/logo/2.png" alt="Clients"></a></li>
								<li class="grid-item"><a href="#"><img src="images/clients/logo/3.png" alt="Clients"></a></li>
								<li class="grid-item"><a href="#"><img src="images/clients/logo/4.png" alt="Clients"></a></li>
								<li class="grid-item"><a href="#"><img src="images/clients/logo/5.png" alt="Clients"></a></li>
								<li class="grid-item"><a href="#"><img src="images/clients/logo/6.png" alt="Clients"></a></li>
								<li class="grid-item"><a href="#"><img src="images/clients/logo/7.png" alt="Clients"></a></li>
								<li class="grid-item"><a href="#"><img src="images/clients/logo/8.png" alt="Clients"></a></li>
								<li class="grid-item"><a href="#"><img src="images/clients/logo/9.png" alt="Clients"></a></li>
								<li class="grid-item"><a href="#"><img src="images/clients/logo/10.png" alt="Clients"></a></li>
								<li class="grid-item"><a href="#"><img src="images/clients/logo/11.png" alt="Clients"></a></li>
								<li class="grid-item"><a href="#"><img src="images/clients/logo/12.png" alt="Clients"></a></li>
								<li class="grid-item"><a href="#"><img src="images/clients/logo/13.png" alt="Clients"></a></li>
								<li class="grid-item"><a href="#"><img src="images/clients/logo/14.png" alt="Clients"></a></li>
								<li class="grid-item"><a href="#"><img src="images/clients/logo/15.png" alt="Clients"></a></li>
								<li class="grid-item"><a href="#"><img src="images/clients/logo/16.png" alt="Clients"></a></li>
							</ul>
						</div>
					</div>

				</div>

				<div class="clear"></div>

				<!-- App Buttons
				============================================= -->
					<!--<div class="section pb-0 mb-0" style="background-color: #f8f5f0">
					<div class="container">
						<div class="row">
							<div class="col-md-6 offset-1 mb-6 d-flex flex-column align-self-center">
								<h3 class="card-title fw-normal ls-0">Follow. Find. Favorite.<br>Discover Fashion on the Go.</h3>
								<span>Proactively enable Corporate Benefits.</span>
								<div class="mt-3">
									<a href="#" class="button bg-appstore inline-block button-small button-rounded button-desc fw-normal ls-1"><i class="fa-brands fa-apple"></i><div><span>Download techCentrix Shop</span>App Store</div></a>
									<a href="#" class="button inline-block button-small button-rounded button-desc button-light text-dark fw-normal ls-1 bg-white border"><i class="fa-brands fa-google-play"></i><div><span>Download techCentrix Shop</span>Google Play</div></a>
								</div>
							</div>
							<div class="col-md-4 d-none d-md-flex align-items-end">
								<img src="demos/shop/images/sections/app.png" alt="Image" class="mb-0">
							</div>
						</div>
					</div>
				</div>

				<!-- Last Section
				============================================= -->
					<!--<div class="section footer-stick bg-white m-0 py-3 border-bottom">
					<div class="container">

						<div class="row">
							<div class="col-lg-4 col-md-6">
								<div class="shop-footer-features mb-3 mb-lg-3"><i class="bi-globe-americas"></i><h5 class="inline-block mb-0 ms-2 fw-semibold"><a href="#">Free Shipping</a><span class="fw-normal text-muted"> &amp; Easy Returns</span></h5></div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="shop-footer-features mb-3 mb-lg-3"><i class="bi-journal"></i><h5 class="inline-block mb-0 ms-2"><a href="#">Geniune Products</a><span class="fw-normal text-muted"> Guaranteed</span></h5></div>
							</div>
							<div class="col-lg-4 col-md-12">
								<div class="shop-footer-features mb-3 mb-lg-3"><i class="bi-lock"></i><h5 class="inline-block mb-0 ms-2"><a href="#">256-Bit</a> <span class="fw-normal text-muted">Secured Checkouts</span></h5></div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</section><!-- #content end -->

					<!-- Footer
		============================================= -->
					<footer id="footer" class="bg-transparent border-0">

						<div class="container">

							<!-- Footer Widgets
				============================================= -->
							<div class="footer-widgets-wrap pb-3 border-bottom">

								<div class="row">

									<div class="col-lg-2 col-md-3 col-6">
										<div class="widget">

											<h4 class="ls-0 mb-3 text-transform-none">Features</h4>

											<ul class="list-unstyled iconlist ms-0">
												<li><a href="#">Help Center</a></li>
												<li><a href="#">Paid with Moblie</a></li>
												<li><a href="#">Status</a></li>
												<li><a href="#">Changelog</a></li>
												<li><a href="#">Contact Support</a></li>
											</ul>

										</div>
									</div>
									<div class="col-lg-2 col-md-3 col-6">
										<div class="widget">

											<h4 class="ls-0 mb-3 text-transform-none">Support</h4>

											<ul class="list-unstyled iconlist ms-0">
												<li><a href="#">Home</a></li>
												<li><a href="#">About</a></li>
												<li><a href="#">FAQs</a></li>
												<li><a href="#">Support</a></li>
												<li><a href="#">Contact</a></li>
											</ul>

										</div>
									</div>
									<div class="col-lg-2 col-md-3 col-6">
										<div class="widget">

											<h4 class="ls-0 mb-3 text-transform-none">Trending</h4>

											<ul class="list-unstyled iconlist ms-0">
												<li><a href="#">Shop</a></li>
												<li><a href="#">Portfolio</a></li>
												<li><a href="#">Blog</a></li>
												<li><a href="#">Events</a></li>
												<li><a href="#">Forums</a></li>
											</ul>

										</div>
									</div>
									<div class="col-lg-2 col-md-3 col-6">
										<div class="widget">

											<h4 class="ls-0 mb-3 text-transform-none">Get to Know us</h4>

											<ul class="list-unstyled iconlist ms-0">
												<li><a href="#">Corporate</a></li>
												<li><a href="#">Agency</a></li>
												<li><a href="#">eCommerce</a></li>
												<li><a href="#">Personal</a></li>
												<li><a href="#">OnePage</a></li>
											</ul>

										</div>
									</div>
									<div class="col-lg-4 col-md-8">
										<div class="widget">

											<h4 class="ls-0 mb-3 text-transform-none">Subscribe Now</h4>
											<div class="widget subscribe-widget mt-2">
												<p class="mb-4"><strong>Subscribe</strong> to Our Newsletter to get
													Important News, Amazing Offers &amp; Inside Scoops:</p>
												<div class="widget-subscribe-form-result"></div>
												<form id="widget-subscribe-form" action="include/subscribe.php"
													method="post" class="mt-1 mb-0 d-flex">
													<input type="email" id="widget-subscribe-form-email"
														name="widget-subscribe-form-email"
														class="form-control form-control required email rounded-0"
														placeholder="Enter your Email Address">

													<button class="button text-transform-none fw-normal ms-1 my-0"
														type="submit">Subscribe Now</button>
												</form>
											</div>

										</div>
									</div>

								</div>

							</div><!-- .footer-widgets-wrap end -->

						</div>

						<!-- Copyrights
			============================================= -->
						<div id="copyrights" class="bg-transparent">

							<div class="container">

								<div class="row justify-content-between align-items-center">
									<div class="col-md-6">
										Copyrights &copy; 2023 All Rights Reserved by techCentrix Inc.<br>
										<div class="copyright-links"><a href="#">Terms of Use</a> / <a href="#">Privacy
												Policy</a></div>
									</div>

									<div class="col-md-6 d-md-flex flex-md-column align-items-md-end mt-4 mt-md-0">
										<div class="copyrights-menu copyright-links">
											<a href="#">About</a>/<a href="#">Features</a>/<a href="#">FAQs</a>/<a
												href="#">Contact</a>
										</div>
									</div>
								</div>

							</div>

						</div><!-- #copyrights end -->

					</footer><!-- #footer end -->

				</div><!-- #wrapper end -->

				<!-- Go To Top
	============================================= -->
				<div id="gotoTop" class="bi-arrow-up"></div>

				<!-- JavaScripts
	============================================= -->
				<script src="js/plugins.min.js"></script>
				<script src="js/functions.bundle.js"></script>
				<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
					integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
					crossorigin="anonymous"></script>
				<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
					integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
					crossorigin="anonymous"></script>
				<!-- Bootstrap CSS -->
				<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

				<!-- Bootstrap Bundle JS (includes Popper) -->
				<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

				<script>

					if (!sessionStorage.getItem("cart-items")) {
						sessionStorage.setItem("cart-items", JSON.stringify([]));
					}

					if (!sessionStorage.getItem("cart-detailed")) {
						sessionStorage.setItem("cart-detailed", JSON.stringify([]));
					}
					let shopCartItems = Array.from(document.querySelectorAll(".top-cart-items"));
					console.log(shopCartItems);
					let topCartNumber = document.querySelector(".top-cart-number");
					let cartItemsDetailed = [];
					let products = Array.from(document.querySelectorAll('.product'));
					console.log(products);

					document.addEventListener('DOMContentLoaded', () => {
						if (JSON.parse(sessionStorage.getItem("cart-items")).length > 0) {
							console.log(sessionStorage.getItem('cart-items'));
							let cartItems = JSON.parse(sessionStorage.getItem('cart-detailed'));
							console.log("session cart items:  ",cartItems)
							let cartIds = JSON.parse(sessionStorage.getItem('cart-items'));
							products.forEach((product) => {
								console.log(product.dataset.id)
								if (cartIds.includes(parseInt(product.dataset.id))) {
									let btn = product.querySelector(".cart-btn");
									btn.disabled = true;
									btn.innerHTML = '<i class="fa-solid fa-square-minus"></i>';
									console.log(btn);
								}
								else {
									console.log("nothing")
								}
							})
							cartItems.forEach((item) => {
								if (parseInt(topCartNumber.innerText) >= 2) {

									topCartNumber.innerHTML = parseInt(topCartNumber.innerHTML) + 1
									return;
								}
								let image = document.createElement('img');
								image.src = item.img;
								console.log(image);
								let imageAnchor = document.createElement("a")
								imageAnchor.href = "#";
								imageAnchor.append(image);
								let imageDiv = document.createElement('div');
								imageDiv.classList.add('top-cart-item-image');
								imageDiv.append(imageAnchor);
								//description div
								let descriptionAnchor = document.createElement('a');
								descriptionAnchor.innerText = item.name;
								let descriptionSpan = document.createElement('span');
								descriptionSpan.classList.add('top-cart-item-price');
								descriptionSpan.classList.add('d-block');
								descriptionSpan.innerText = "Rs. " + item.price;
								let descriptionTitle = document.createElement("div");
								descriptionTitle.classList.add('top-cart-item-desc-title');
								descriptionTitle.append(descriptionAnchor);
								descriptionTitle.append(descriptionSpan);
								let descriptionDiv = document.createElement('div');
								descriptionDiv.classList.add('top-cart-item-desc');
								descriptionDiv.append(descriptionTitle);


								//quantity div
								let quantityDiv = document.createElement("div");
								quantityDiv.classList.add('top-cart-item-quantity');
								quantityDiv.innerText = "x 1";


								//main shopping item div

								let shopCartItem = document.createElement('div');
								shopCartItem.classList.add('top-cart-item');

								shopCartItem.append(imageDiv);
								shopCartItem.append(descriptionDiv);
								shopCartItem.append(quantityDiv);

								console.log(shopCartItems.map(ele => ele.children));
								shopCartItems[0].append(shopCartItem);
								shopCartItems[1].append(shopCartItem.cloneNode(true));
								console.log(shopCartItems.map(ele => ele.children));
								topCartNumber.innerHTML = parseInt(topCartNumber.innerHTML) + 1


							})
						}
					})
					
					document.addEventListener('click', function (e) {
						if (e.target.closest('.cart-btn')) {
							const item = e.target.closest('.cart-btn');
							console.log(item);


							let id = parseInt(item.dataset.id);
							let cartItem = {
								id: parseInt(item.dataset.id),
								name: item.dataset.name,
								img: item.dataset.img,
								price: parseFloat(item.dataset.price)
							}
							cartItemsDetailed.push(cartItem);
							// img: item.dataset.img,
							// name: item.dataset.name,
							// price: parseFloat(item.dataset.price)


							let cartItems = JSON.parse(sessionStorage.getItem('cart-items')) || [];
							let duplicateFound = cartItems.some(i => i === id);
							item.disabled = true;
							item.innerHTML = '<i class="fa-solid fa-square-minus"></i>';
							console.log(item);
							if (!duplicateFound) {
								cartItems.push(id);
								sessionStorage.setItem('cart-items', JSON.stringify(cartItems));
								sessionStorage.setItem('cart-detailed', JSON.stringify(cartItemsDetailed));
								topCartNumber.innerHTML = parseInt(topCartNumber.innerHTML) + 1

								if (parseInt(topCartNumber.innerText) > 2) {
									console.log("exited the adding of the cart items")
									return;
								}
								// image div
								let image = document.createElement('img');
								image.src = item.dataset.img;
								console.log(image);
								let imageAnchor = document.createElement("a")
								imageAnchor.href = "#";
								imageAnchor.append(image);
								let imageDiv = document.createElement('div');
								imageDiv.classList.add('top-cart-item-image');
								imageDiv.append(imageAnchor);
								//description div
								let descriptionAnchor = document.createElement('a');
								descriptionAnchor.innerText = item.dataset.name;
								let descriptionSpan = document.createElement('span');
								descriptionSpan.classList.add('top-cart-item-price');
								descriptionSpan.classList.add('d-block');
								descriptionSpan.innerText = "Rs. " + item.dataset.price;
								let descriptionTitle = document.createElement("div");
								descriptionTitle.classList.add('top-cart-item-desc-title');
								descriptionTitle.append(descriptionAnchor);
								descriptionTitle.append(descriptionSpan);
								let descriptionDiv = document.createElement('div');
								descriptionDiv.classList.add('top-cart-item-desc');
								descriptionDiv.append(descriptionTitle);


								//quantity div
								let quantityDiv = document.createElement("div");
								quantityDiv.classList.add('top-cart-item-quantity');
								quantityDiv.innerText = "x 1";


								//main shopping item div

								let shopCartItem = document.createElement('div');
								shopCartItem.classList.add('top-cart-item');

								shopCartItem.append(imageDiv);
								shopCartItem.append(descriptionDiv);
								shopCartItem.append(quantityDiv);
								shopCartItems[0].append(shopCartItem);
								shopCartItems[1].append(shopCartItem.cloneNode(true));

							} else {
								alert("Sorry the product already exists");
							}
							console.log(sessionStorage.getItem('cart-items'));

							// console.log(JSON.parse(sessionStorage.getItem("cart-items")));
						
						}
						
					});

					let cartNav = document.getElementById("cart-nav");
					console.log(cartNav);
					function navigateCategory(e) {

						e.preventDefault();
						console.log("clicked");
						let form = document.createElement("form");
						form.method = "POST";
						form.action = "cart.php";
						console.log(form);
						let input = document.createElement("input");
						input.type = "hidden";
						input.name = "cart_ids";
						input.value = sessionStorage.getItem('cart-items') || [];

						form.append(input);
						document.body.append(form);
						form.submit();


					}
					cartNav.addEventListener('click', (e) => {
						e.preventDefault();
						console.log("clicked");
						let form = document.createElement("form");
						form.method = "POST";
						form.action = "cart.php";
						console.log(form);
						let input = document.createElement("input");
						input.type = "hidden";
						input.name = "cart_ids";
						input.value = sessionStorage.getItem('cart-items') || [];

						form.append(input);
						document.body.append(form);
						form.submit();

					})
				</script>
</body>

</html>