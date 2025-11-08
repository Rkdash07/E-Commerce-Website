<?php
include 'connection.php';

// Fetch categories with product counts
$catQuery = "
    SELECT c.id, c.category, COUNT(p.id) AS product_count
    FROM Category c
    LEFT JOIN Product p ON p.category_id = c.id
    GROUP BY c.id, c.category
    ORDER BY c.category ASC
";
$categories = $conn->query($catQuery);

// Determine selected category from URL param (for demo1, demo2, demo3, pass category_id accordingly)
$selectedCategoryId = isset($_GET['category_id']) && is_numeric($_GET['category_id']) ? (int)$_GET['category_id'] : null;

// Fetch products filtered by selected category or all products if none
if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
	$category_id = (int)$_GET['category_id'];
	$stmt = $conn->prepare("SELECT * FROM product WHERE category_id=?");
	$stmt->bind_param("i", $category_id);
	$stmt->execute();
	$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
	$result = $conn->query("SELECT * FROM Product");
	$products = $result->fetch_all(MYSQLI_ASSOC);
}
$totalProductCount = count($products);


$navQuery = "SELECT navbar.id, category.category
FROM navbar
JOIN category ON navbar.category_id = category.id";
$nav = [];
$result = $conn->query($navQuery);
while($row = $result->fetch_assoc())

{
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
		content="Get Canvas to build powerful websites easily with the Highly Customizable &amp; Best Selling Bootstrap Template, today.">

	<!-- Font Imports -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link
		href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital@0;1&display=swap"
		rel="stylesheet">

	<!-- Core Style -->
	<link rel="stylesheet" href="style.css">

	<!-- Font Icons -->
	<link rel="stylesheet" href="css/font-icons.css">

	<!-- Custom CSS -->
	<link rel="stylesheet" href="css/custom.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Document Title
	============================================= -->
	<title>Shop - Filter | Canvas</title>
	<style>
		.product-count-circle {
			background-color: #28a745;
			color: #fff;
			border-radius: 50%;
			padding: 3px 8px;
			font-size: 12px;
			font-weight: bold;
			margin-left: 8px;
			display: inline-block;
			min-width: 20px;
			text-align: center;
		}
		.image{
			height:300px;
			object-fit:cover;
			

		}
	</style>
</head>

<body class="stretched">

	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper">

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
										<a class="menu-link" href="shopping.php">
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

		<!-- Page Title
		============================================= -->
		<section class="page-title my-2 py-0 bg-transparent">
			<div class="container">
				<div class="page-title-row">
					<!-- 
					<div class="page-title-content">
						<h1>Shop</h1>
						<span>Products with Filter Functionality</span>
					</div> -->

					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="#">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page">Category</li>
						</ol>
					</nav>

				</div>
			</div>
			
		</section><!-- .page-title end -->
<section>
				<div class="container-fluid">
					<img src="./images/01_preview.jpg" alt="" class="img img-fluid image" height="100" width="100%" srcset="">
				</div>
			</section>
		<!-- Content
		============================================= -->
		<section id="content">
			<div class="content-wrap">
				<div class="container">

					<div class="  ">
						<!-- Post Content
						============================================= -->
						<main class="postcontent row gx-5 col-mb-80 order-lg-last">
							<aside class="sidebar col-md-3 px-md-0 px-3 mb-5">
							<div class="sidebar-widgets-wrap">

								<div class="widget widget-filter-links">
									<h4>Select Category</h4>
									<ul class="custom-filter ps-2">
										<li class="widget-filter-reset active-filter"><a href="shop-combination-filter.php"
												data-filter="*">Clear</a></li>
										<li class="<?= !isset($_GET['category_id']) ? 'active-filter' : '' ?>">
											<a href="shop-combination-filter.php">
												All Products
												<span class="product-count-circle"><?= $totalProductCount ?></span>
											</a>
										</li>
										<?php $categories->data_seek(0); ?>
										<?php while ($cat = $categories->fetch_assoc()): ?>
											<li class="<?= (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'active-filter' : '' ?>">
												<a href="shop-combination-filter.php?category_id=<?= $cat['id'] ?>">
													<?= htmlspecialchars($cat['category']) ?>
													<span class="product-count-circle"><?= $cat['product_count'] ?></span>
												</a>
											</li>
										<?php endwhile; ?>
									</ul>
								</div>




								<div class="widget widget-filter-links">

									<h4>Sort By</h4>
									<ul class="shop-sorting ps-2">
										<li class="widget-filter-reset active-filter"><a href="#"
												data-sort-by="original-order">Clear</a></li>
										<li><a href="#" data-sort-by="name">Name</a></li>
										<li><a href="#" data-sort-by="price_lh">Price: Low to High</a></li>
										<li><a href="#" data-sort-by="price_hl">Price: High to Low</a></li>
									</ul>

								</div>

							</div>
						</aside>
							<!-- Shop
							============================================= -->

							<div class="col">
							<div id="shop" class="shop row  grid-container  gutter-20" data-layout="fitRows">
								<?php if (!empty($products)): ?>
									<?php foreach ($products as $product): ?>
										<div class="col-md-4 my-4">										
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
								<?php else: ?>
									<p>No products found in this category.</p>
								<?php endif; ?>
							</div>
							</div>
							<!-- #shop end -->

						</main><!-- .postcontent end -->

						<!-- Sidebar
						============================================= -->
						<!-- .sidebar end -->
					</div>

				</div>
			</div>
		</section><!-- #content end -->
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
		<!-- Footer
		============================================= -->
		<footer id="footer" class="dark">
			<div class="container">

				<!-- Footer Widgets
				============================================= -->
				<div class="footer-widgets-wrap">

					<div class="row col-mb-50">
						<div class="col-lg-8">

							<div class="row col-mb-50">
								<div class="col-md-4">

									<div class="widget">

										<img src="images/footer-widget-logo.png" alt="Image" class="footer-logo">

										<p>We believe in <strong>Simple</strong>, <strong>Creative</strong> &amp;
											<strong>Flexible</strong> Design Standards.
										</p>

										<div
											style="background: url('images/world-map.png') no-repeat center center; background-size: 100%;">
											<address>
												<strong>Headquarters:</strong><br>
												795 Folsom Ave, Suite 600<br>
												San Francisco, CA 94107<br>
											</address>
											<abbr title="Phone Number"><strong>Phone:</strong></abbr> (1) 8547
											632521<br>
											<abbr title="Fax"><strong>Fax:</strong></abbr> (1) 11 4752 1433<br>
											<abbr title="Email Address"><strong>Email:</strong></abbr> info@canvas.com
										</div>

									</div>

								</div>

								<div class="col-md-4">

									<div class="widget widget_links">

										<h4>Blogroll</h4>

										<ul>
											<li><a href="https://codex.wordpress.org/">Documentation</a></li>
											<li><a
													href="https://wordpress.org/support/forum/requests-and-feedback">Feedback</a>
											</li>
											<li><a href="https://wordpress.org/extend/plugins/">Plugins</a></li>
											<li><a href="https://wordpress.org/support/">Support Forums</a></li>
											<li><a href="https://wordpress.org/extend/themes/">Themes</a></li>
											<li><a href="https://wordpress.org/news/">Canvas Blog</a></li>
											<li><a href="https://planet.wordpress.org/">Canvas Planet</a></li>
										</ul>

									</div>

								</div>

								<div class="col-md-4">

									<div class="widget">
										<h4>Recent Posts</h4>

										<div class="posts-sm row col-mb-30" id="post-list-footer">
											<div class="entry col-12">
												<div class="grid-inner row">
													<div class="col">
														<div class="entry-title">
															<h4><a href="#">Lorem ipsum dolor sit amet, consectetur</a>
															</h4>
														</div>
														<div class="entry-meta">
															<ul>
																<li>10th July 2021</li>
															</ul>
														</div>
													</div>
												</div>
											</div>

											<div class="entry col-12">
												<div class="grid-inner row">
													<div class="col">
														<div class="entry-title">
															<h4><a href="#">Elit Assumenda vel amet dolorum quasi</a>
															</h4>
														</div>
														<div class="entry-meta">
															<ul>
																<li>10th July 2021</li>
															</ul>
														</div>
													</div>
												</div>
											</div>

											<div class="entry col-12">
												<div class="grid-inner row">
													<div class="col">
														<div class="entry-title">
															<h4><a href="#">Debitis nihil placeat, illum est nisi</a>
															</h4>
														</div>
														<div class="entry-meta">
															<ul>
																<li>10th July 2021</li>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>

								</div>
							</div>

						</div>

						<div class="col-lg-4">

							<div class="row col-mb-50">
								<div class="col-md-4 col-lg-12">
									<div class="widget">

										<div class="row col-mb-30">
											<div class="col-lg-6">
												<div class="counter counter-small"><span data-from="50"
														data-to="15065421" data-refresh-interval="80" data-speed="3000"
														data-comma="true"></span></div>
												<h5 class="mb-0">Total Downloads</h5>
											</div>

											<div class="col-lg-6">
												<div class="counter counter-small"><span data-from="100" data-to="18465"
														data-refresh-interval="50" data-speed="2000"
														data-comma="true"></span></div>
												<h5 class="mb-0">Clients</h5>
											</div>
										</div>

									</div>
								</div>

								<div class="col-md-5 col-lg-12">
									<div class="widget subscribe-widget">
										<h5><strong>Subscribe</strong> to Our Newsletter to get Important News, Amazing
											Offers &amp; Inside Scoops:</h5>
										<div class="widget-subscribe-form-result"></div>
										<form id="widget-subscribe-form" action="include/subscribe.php" method="post"
											class="mb-0">
											<div class="input-group mx-auto">
												<div class="input-group-text"><i class="bi-envelope-plus"></i></div>
												<input type="email" id="widget-subscribe-form-email"
													name="widget-subscribe-form-email"
													class="form-control required email" placeholder="Enter your Email">
												<button class="btn btn-success" type="submit">Subscribe</button>
											</div>
										</form>
									</div>
								</div>

								<div class="col-md-3 col-lg-12">
									<div class="widget">

										<div class="row col-mb-30">
											<div class="col-6 col-md-12 col-lg-6 d-flex align-items-center">
												<a href="#"
													class="social-icon text-white border-transparent bg-facebook me-2 mb-0 float-none">
													<i class="fa-brands fa-facebook-f"></i>
													<i class="fa-brands fa-facebook-f"></i>
												</a>
												<a href="#" class="ms-1"><small class="d-block"><strong>Like
															Us</strong><br>on Facebook</small></a>
											</div>
											<div class="col-6 col-md-12 col-lg-6 d-flex align-items-center">
												<a href="#"
													class="social-icon text-white border-transparent bg-rss me-2 mb-0 float-none">
													<i class="fa-solid fa-rss"></i>
													<i class="fa-solid fa-rss"></i>
												</a>
												<a href="#" class="ms-1"><small
														class="d-block"><strong>Subscribe</strong><br>to RSS
														Feeds</small></a>
											</div>
										</div>

									</div>
								</div>

							</div>

						</div>
					</div>

				</div><!-- .footer-widgets-wrap end -->

			</div>

			<!-- Copyrights
			============================================= -->
			<div id="copyrights">
				<div class="container">

					<div class="row col-mb-30">

						<div class="col-md-6 text-center text-md-start">
							Copyrights &copy; 2023 All Rights Reserved by Canvas Inc.<br>
							<div class="copyright-links"><a href="#">Terms of Use</a> / <a href="#">Privacy Policy</a>
							</div>
						</div>

						<div class="col-md-6 text-center text-md-end">
							<div class="d-flex justify-content-center justify-content-md-end mb-2">
								<a href="#" class="social-icon border-transparent si-small h-bg-facebook">
									<i class="fa-brands fa-facebook-f"></i>
									<i class="fa-brands fa-facebook-f"></i>
								</a>

								<a href="#" class="social-icon border-transparent si-small h-bg-x-twitter">
									<i class="fa-brands fa-x-twitter"></i>
									<i class="fa-brands fa-x-twitter"></i>
								</a>

								<a href="#" class="social-icon border-transparent si-small h-bg-google">
									<i class="fa-brands fa-google"></i>
									<i class="fa-brands fa-google"></i>
								</a>

								<a href="#" class="social-icon border-transparent si-small h-bg-pinterest">
									<i class="fa-brands fa-pinterest-p"></i>
									<i class="fa-brands fa-pinterest-p"></i>
								</a>

								<a href="#" class="social-icon border-transparent si-small h-bg-vimeo">
									<i class="fa-brands fa-vimeo-v"></i>
									<i class="fa-brands fa-vimeo-v"></i>
								</a>

								<a href="#" class="social-icon border-transparent si-small h-bg-github">
									<i class="fa-brands fa-github"></i>
									<i class="fa-brands fa-github"></i>
								</a>

								<a href="#" class="social-icon border-transparent si-small h-bg-yahoo">
									<i class="fa-brands fa-yahoo"></i>
									<i class="fa-brands fa-yahoo"></i>
								</a>

								<a href="#" class="social-icon border-transparent si-small me-0 h-bg-linkedin">
									<i class="fa-brands fa-linkedin"></i>
									<i class="fa-brands fa-linkedin"></i>
								</a>
							</div>

							<i class="bi-envelope"></i> info@canvas.com <span class="middot">&middot;</span> <i
								class="fa-solid fa-phone"></i> +1-11-6541-6369 <span class="middot">&middot;</span> <i
								class="bi-skype"></i> CanvasOnSkype
						</div>

					</div>

				</div>
			</div><!-- #copyrights end -->
		</footer><!-- #footer end -->

	</div><!-- #wrapper end -->

	<!-- Go To Top
	============================================= -->
	<div id="gotoTop" class="uil uil-angle-up"></div>

	<!-- JavaScripts
	============================================= -->
	<script src="js/plugins.min.js"></script>
	<script src="js/functions.bundle.js"></script>

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

	<script>
		jQuery(window).on('load', function() {
			jQuery('#shop').isotope({
				transitionDuration: '0.65s',
				getSortData: {
					name: '.product-title',
					price_lh: function(itemElem) {
						if (jQuery(itemElem).find('.product-price').find('ins').length > 0) {
							var price = jQuery(itemElem).find('.product-price ins').text();
						} else {
							var price = jQuery(itemElem).find('.product-price').text();
						}

						priceNum = price.split("$");

						return parseFloat(priceNum[1]);
					},
					price_hl: function(itemElem) {
						if (jQuery(itemElem).find('.product-price').find('ins').length > 0) {
							var price = jQuery(itemElem).find('.product-price ins').text();
						} else {
							var price = jQuery(itemElem).find('.product-price').text();
						}

						priceNum = price.split("$");

						return parseFloat(priceNum[1]);
					}
				},
				sortAscending: {
					name: true,
					price_lh: true,
					price_hl: false
				}
			});

			jQuery('.custom-filter:not(.no-count)').children('li:not(.widget-filter-reset)').each(function() {
				var element = jQuery(this),
					elementFilter = element.children('a').attr('data-filter'),
					elementFilterContainer = element.parents('.custom-filter').attr('data-container');

				elementFilterCount = Number(jQuery(elementFilterContainer).find(elementFilter).length);

				element.append('<span>' + elementFilterCount + '</span>');
			});

			jQuery('.shop-sorting li').click(function() {
				jQuery('.shop-sorting').find('li').removeClass('active-filter');
				jQuery(this).addClass('active-filter');
				var sortByValue = jQuery(this).find('a').attr('data-sort-by');
				jQuery('#shop').isotope({
					sortBy: sortByValue
				});
				return false;
			});
		});
	</script>

</body>

</html>