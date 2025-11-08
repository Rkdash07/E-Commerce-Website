<?php
include 'connection.php';
$navQuery = "SELECT navbar.id,navbar.category_id, category.category FROM navbar JOIN category ON navbar.category_id = category.id";
$result = $conn->query($navQuery);
$nav = [];
while ($row = $result->fetch_assoc()) {
	$nav[] = $row;
}
$slabs = "SELECt * from charge_slabs";
$result = $conn->query($slabs);
$slabsData = [];
while ($row = $result->fetch_assoc()) {
	$slabsData[] = $row;
}
if (isset($_POST['cart_ids'])) {
	$cart_ids = json_decode($_POST['cart_ids'], true);



	// echo '<pre>';
	// echo "Received cart IDs:\n";
	// var_dump($cart_ids);
	// echo '</pre>';


	// 	if (is_array($cart_ids) && count($cart_ids) > 0) {
//         echo "Successfully received " . count($cart_ids) . " product ID(s).";
//     } else {
//         echo "No valid product IDs received.";
//     }
// } else {
//     echo "No cart_ids found in POST data.";
// }

	if (is_array($cart_ids)) {

		$cart_ids = array_unique(array_filter($cart_ids, 'is_int'));
	} else {
		$cart_ids = [];
	}
} else {
	$cart_ids = [];
}

if (!empty($cart_ids)) {
	$placeholders = implode(',', array_fill(0, count($cart_ids), '?'));

	$stmt = $conn->prepare("SELECT * FROM product WHERE id IN ($placeholders)");
	$stmt->execute($cart_ids);


	$products_in_cart = [];
	$result = $stmt->get_result();
	if ($result) {
		while ($row = $result->fetch_assoc()) {
			$products_in_cart[] = $row;
		}
	}

} else {
	$products_in_cart = [];
}



$coupons = [];
$result = $conn->query("SELECT id as id, coupon_name AS title,coupon_code AS name, discount_type AS type, amount AS value FROM coupon WHERE status = 1");
if ($result && $result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$coupons[] = $row;
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {

	$billing_fname = $_POST['billing_fname'] ?? '';
	$billing_lname = $_POST['billing_lname'] ?? '';
	$billing_address = $_POST['billing_address'] ?? '';
	$billing_city = $_POST['billing_city'] ?? '';
	$billing_pincode = $_POST['billing_pincode'] ?? '';
	$billing_email = $_POST['billing_email'] ?? '';
	$billing_phone = $_POST['billing_phone'] ?? '';

	if (isset($_POST['shipping_same_as_billing'])) {
		$shipping_fname = $billing_fname;
		$shipping_lname = $billing_lname;
		$shipping_address = $billing_address;
		$shipping_city = $billing_city;
		$shipping_pincode = $billing_pincode;
		$shipping_email = $billing_email;
		$shipping_phone = $billing_phone;
	} else {
		$shipping_fname = $_POST['shipping_fname'] ?? '';
		$shipping_lname = $_POST['shipping_lname'] ?? '';
		$shipping_address = $_POST['shipping_address'] ?? '';
		$shipping_city = $_POST['shipping_city'] ?? '';
		$shipping_pincode = $_POST['shipping_pincode'] ?? '';
		$shipping_email = $_POST['shipping_email'] ?? '';
		$shipping_phone = $_POST['shipping_phone'] ?? '';
	}
	$stmt = $conn->prepare("INSERT INTO billing_details (fname, lname, address, city, pincode, email, phone_no)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
	$stmt->bind_param("ssssiss", $billing_fname, $billing_lname, $billing_address, $billing_city, $billing_pincode, $billing_email, $billing_phone);
	if (!$stmt->execute()) {
		die("Failed to insert billing details: " . $stmt->error);
	}
	$billingId = $stmt->insert_id;
	$stmt = $conn->prepare("INSERT INTO shipping_details (fname, lname, address, city, pincode, email, phone_no)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
	$stmt->bind_param("ssssiss", $shipping_fname, $shipping_lname, $shipping_address, $shipping_city, $shipping_pincode, $shipping_email, $shipping_phone);
	if (!$stmt->execute()) {
		die("Failed to insert shipping details: " . $stmt->error);
	}
	$shippingId = $stmt->insert_id;
	$payment_mode = 1;
	$total = $_POST['order_total'] ?? 0;
	$order_status = 0;
	$applied_coupon_id = $_POST['applied_coupon_id'] ?? 0;
	$purchase_date = time();
	$weight = $_POST['weight'];
	$shipping_mode = isset($_POST['shipping_mode']) ? (int)$_POST['shipping_mode'] : 0; 
    $shipping_charges = isset($_POST['shipping_charges']) ? (float)$_POST['shipping_charges'] : 0;
	$order_id = mt_rand(1000000000, 9999999999);
	$stmt = $conn->prepare("INSERT INTO `order` (
    shipping_details_id, billing_details_id, payment_mode, total, order_status,applied_coupon_id, purchase_date, id, shipping_mode, shipping_charges) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param( "iiidiiiidi",$shippingId, $billingId, $payment_mode, $total, $order_status,$applied_coupon_id, $purchase_date, $order_id, $shipping_mode, $shipping_charges);
	$stmt->execute();
	$orderId = $stmt->insert_id;
	if (!empty($_POST['confirmedCartItems'])) {
		$cartItems = json_decode($_POST['confirmedCartItems'], true);
		if (is_array($cartItems)) {
			$stmt = $conn->prepare("INSERT INTO orderitems (orderId, productId, quantity, subTotal) VALUES (?, ?, ?, ?)");
			foreach ($cartItems as $item) {
				$productId = (int) $item['productId'];
				$quantity = (int) $item['quantity'];
				$subTotal = (float) $item['finalTotal'];
				$stmt->bind_param("iiid", $orderId, $productId, $quantity, $subTotal);
				$stmt->execute();
			}
		}
	}
	header("Location: OrderSuccess.php?order_id=$orderId&email=$billing_email");
	exit();
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

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

	<!-- Core Style -->
	<link rel="stylesheet" href="style.css">

	<!-- Font Icons -->
	<link rel="stylesheet" href="css/font-icons.css">

	<!-- Custom CSS -->
	<link rel="stylesheet" href="css/custom.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Swiper CSS -->

	<!-- Document Title
	============================================= -->
	<title>Cart | Canvas</title>
	<style>
		.cart-icon {
			position: relative;
			margin-right: 15px;

		}

		.top-cart-number {
			position: absolute;
		}

		.search-list li {
			list-style-type: none;
			padding: 4px;
			cursor: pointer;
		}

		.search-list li:hover {
			background: lightblue;
		}

		.search-list {
			cursor: pointer;
		}

		.swiper {
			width: 100%;
			height: fit-content !important;
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

					<!-- Primary Navigation
						============================================= -->
					<div class="header-row w-100 d-flex justify-space-between align-items-center">

						<!-- Logo
						============================================= -->
						<div id="logo" class="border-0">
							<a href="shopping.php" class="border-0">
								<img class="logo-default border-0" srcset="images/loo.png, images/lgo@2x.png 2x"
									src="images/ogo@.png" alt=" Logo">
								<!--<img class="logo-dark" srcset="images/logo-dark.png, images/logo-dark@2x.png 2x" src="images/logo-@2x.png" alt=" Logo">Logo-->
							</a>
						</div><!-- #logo end -->

						<div class="header-misc">

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
											<a class="menu-link" href=<?php echo "shop-combination-filter.php?category_id=" . $n['category_id']; ?>>
												<div><?php echo $n['category']; ?></div>
											</a>
										</li>
									<?php endforeach; ?>


								</ul>

							</nav><!-- #primary-menu end -->


						</div>
					</div>

				</div>
			</div>
	</div>
	</header><!-- #header end -->

	<!-- Page Title
		============================================= -->



	<!-- .page-title end -->
	<?php if (isset($_GET['message'])): ?>
		<div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
			<?= htmlspecialchars($_GET['message']); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>


	<!-- Content
		============================================= -->
	<section id="content">
		<div class="content-wrap">

			<div class="container">
				<div class="swiper mySwiper mb-5">
					<div class="swiper-wrapper">

						<!-- Slide 1 -->
						<?php foreach ($coupons as $c): ?>
							<div class="swiper-slide">
								<div class="card text-center shadow">
									<div class="card-body">
										<h5 class="card-title"><?= htmlspecialchars($c['title']) ?></h5>
										<p class="card-text">Use Code: <strong><?= htmlspecialchars($c['name']) ?></strong>
										</p>
										<p class="card-text">Discount: <?= htmlspecialchars($c['value']) ?></p>
										<span class="badge bg-success"><?= htmlspecialchars($c['type']) ?></span>
									</div>
								</div>
							</div>
						<?php endforeach; ?>


						<!-- Add more slides here -->


					</div>

					<!-- Pagination & Navigation -->
					<!-- <div class="swiper-pagination mt-3"></div> -->
					<div class="swiper-button-prev"></div>
					<div class="swiper-button-next"></div>
				</div>

				<form id="cart-form" action="" method="post">
					<table id="cart" class="table cart mb-5">
						<thead>
							<tr>
								<th class="cart-product-remove">&nbsp;</th>
								<th class="cart-product-thumbnail">&nbsp;</th>
								<th class="cart-product-name">Product</th>
								<th class="cart-product-price">Unit Price</th>
								<th class="cart-product-quantity">Quantity</th>
								<th class="cart-product-subtotal">Total</th>
							</tr>
						</thead>
						<tbody id="table-body">
							<?php foreach ($products_in_cart as $product): ?>
								<tr class="cart_item" data-id=<?php echo $product['id'] ?>>
									<td class="cart-product-remove">
										<button type="button" class="remove btn" data-id=<?php echo $product['id'] ?>
											title="Remove this item"><i class="fa-solid fa-trash"></i></a>
									</td>
									<td class="cart-product-thumbnail">
										<a href="#"><img width="64" height="64"
												src="<?= "../admin/" . htmlspecialchars($product['thumbnail']) ?>"
												alt="Pink Printed Dress"></a>
									</td>

									<td class="cart-product-name">
										<a href="#"><?= htmlspecialchars($product['productName']) ?></a>
									</td>

									<td class="cart-product-price" data-price=<?= $product['SP'] ?>>
										₹ <span class="amount"><?= $product['SP'] ?></span>
									</td>

									<td class="cart-product-quantity">
										<div class="quantity">
											<input type="button" value="-" class="minus">
											<input type="text" value="1" class="qty" data-weight=<?= $product['weight'] ?>>
											<input type="button" value="+" class="plus">
										</div>
									</td>

									<td class="cart-product-subtotal">
										Rs. <span class="amount">00.00</span>
									</td>
								</tr>

							<?php endforeach; ?>
							<tr class="cart_item">
								<td colspan="5"><strong>Total</strong></td>
								<td><span class="amount color lead">₹ <strong name="amount"
											id="cart-grand-total-amount">00.00</strong></span>
									<input type="hidden" name="order_total" id="order_total" value="">
								</td>
							</tr>

							<!-- <tr class="cart_item">
									<td class="cart-product-remove">
										<a href="#" class="remove" title="Remove this item"><i
												class="fa-solid fa-trash"></i></a>
									</td>

									<td class="cart-product-thumbnail">
										<a href="#"><img width="64" height="64"
												src="images/shop/thumbs/small/shoes-2.jpg"
												alt="Checked Canvas Shoes"></a>
									</td>

									<td class="cart-product-name">
										<a href="#">Checked Canvas Shoes</a>
									</td>

									<td class="cart-product-price" data-price="24.99">
										<span class="amount">Rs.24.99</span>
									</td>

									<td class="cart-product-quantity">
										<div class="quantity">
											<input type="button" value="-" class="minus">
											<input type="text" name="quantity" value="1" class="qty">
											<input type="button" value="+" class="plus">
										</div>
									</td>

									<td class="cart-product-subtotal text-md-star">
										<span class="amount">Rs.24.99</span>
									</td>
								</tr>
								<tr class="cart_item">
									<td class="cart-product-remove">
										<a href="#" class="remove" title="Remove this item"><i
												class="fa-solid fa-trash"></i></a>
									</td>

									<td class="cart-product-thumbnail">
										<a href="#"><img width="64" height="64"
												src="images/shop/thumbs/small/tshirt-2.jpg"
												alt="Pink Printed Dress"></a>
									</td>

									<td class="cart-product-name">
										<a href="#">Blue Men Tshirt</a>
									</td>

									<td class="cart-product-price" data-price="13.99">
										<span class="amount">Rs.13.99</span>
									</td>

									<td class="cart-product-quantity">
										<div class="quantity">
											<input type="button" value="-" class="minus">
											<input type="text" name="quantity" value="1" class="qty">
											<input type="button" value="+" class="plus">
										</div>
									</td>

									<td class="cart-product-subtotal">
										<span class="amount">Rs.41.97</span>
									</td>
								</tr>

								<tr class="cart_item">
									<td colspan="5"><strong>Total</strong></td>
									<td><span class="amount color lead">Rs. <strong
												id="cart-grand-total-amount">96.94</strong></span></td>
								</tr> -->

						</tbody>

					</table>
					<div class="container">
						<div class="row">

							<div class="col-md-6 mb-5">
								<div class="container my-3">
									<div class="row border shadow py-3 px-3">
										<h2>Coupons</h2>
										<div class="form-input">
											<label for="coupon-code">Coupon Code</label>
											<input type="text" id="coupon-code" data-coupon-value="0"
												data-coupon-type="" class="form-control">
											<div id="hidden-div"></div>
											<div id="hidden-div"></div>
										</div>
										<input type="button" class="mx-3 my-2 btn btn-success w-50" id="apply-btn"
											value="Apply">

									</div>
								</div>
								<div class="container my-3">

									<h3>Choose Delivery Mode</h3>
									<div class="form-group">
										<label for="mode" class="form-label">
											Choose
										</label>
										<select name="mode" id="d-mode" class="form-control">
											<option value=0>Post</option>
											<option value=1>Courier</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-6">

								<div class="accordion">
									<div class="accordion-header">
										<div class="accordion-icon">
											<i class="accordion-closed uil uil-minus"></i>
											<i class="accordion-open bi-check-lg"></i>
										</div>
										<div class="accordion-title">
											Cash On Delivery(COD)
										</div>
									</div>
									<div class="accordion-content">
										Pay conveniently using cash after the product has been delivered to your
										home
									</div>


								</div>
								<h4>Cart Totals</h4>

								<div class="table-responsive">
									<table class="table cart">
										<tbody>
											<tr class="cart_item">
												<td class="border-top-0 cart-product-name">
													<strong>Cart Subtotal</strong>
												</td>

												<td class="border-top-0 cart-product-name">
													Rs. <span class="amount" id="cart-total">0</span>
												</td>
											</tr>
											<tr class="cart_item">
												<td class="cart-product-name">
													<strong>Shipping</strong>
												</td>

												<td class="cart-product-name">
													Rs. <span class="amount" id="shipping" data-shipping=0>80</span>
												</td>
											</tr>

											<tr class="cart_item">
												<td class="cart-product-name">
													<strong>Coupon discount</strong>
												</td>

												<td class="cart-product-name">
													Rs. <span class="amount" id="amount" data-amount=0>0</span>
												</td>
											</tr>
											<tr class="cart_item">
												<td class="cart-product-name">
													<strong>Total</strong>
												</td>

												<td class="cart-product-name">
													<span class="amount color lead">₹ <strong
															id="total">00.00</strong></span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="row col-mb-30 mt-5">

						<div class="col-md-6 mt-4">

							<div class="col-lg-12 accordion mb-4 " id="billingAccordion">
								<div class="accordian-item">
									<h2 class="accordian-header" id="billingHeading">
										<button class="accordion-button" type="button" data-bs-toggle="collapse"
											data-bs-target="#billingCollapse" aria-expanded="true">Billing
											Address</button>
									</h2>
									<div class="accordian-collapse collapse show" data-bs-parent="#billingAccordion"
										id="billingCollapse" aria-labelledby="billingHeading">
										<div class="accordian-body">
											<!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Unde, vel odio
												non
												dicta
												provident sint ex autem mollitia dolorem illum repellat ipsum aliquid
												illo
												similique
												sapiente fugiat minus ratione.</p> -->



											<div class="col-md-6 form-group">
												<label for="billing-form-name">Name:</label>
												<input type="text" id="billing-form-name" name="billing_fname" value=""
													class="form-control">
											</div>

											<div class="col-md-6 form-group">
												<label for="billing-form-lname">Last Name:</label>
												<input type="text" id="billing-form-lname" name="billing_lname" value=""
													class="form-control">
											</div>

											<div class="w-100"></div>



											<div class="col-12 form-group">
												<label for="billing-form-address">Address:</label>
												<textarea id="billing-form-address" name="billing_address" value=""
													class="form-control">
												</textarea>

											</div>

											<div class="col-12 form-group">
												<label for="billing-form-city">City / Town</label>
												<input type="text" id="billing-form-city" name="billing_city" value=""
													class="form-control">
											</div>

											<div class="col-12 form-group">
												<label for="billing-form-pin-code">PIN Code</label>
												<input type="text" id="billing-form-pin-code" name="billing_pincode"
													value="" class="form-control">
											</div>


											<div class="col-md-6 form-group">
												<label for="billing-form-email">Email Address:</label>
												<input type="email" id="billing-form-email" name="billing_email"
													value="" class="form-control">
											</div>

											<div class="col-md-6 form-group">
												<label for="billing-form-phone">Phone:</label>
												<input type="text" id="billing-form-phone" name="billing_phone" value=""
													class="form-control">
											</div>


										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="col-md-12 form-check">

								<input type="checkbox" id="shipping-same" name="shipping_same_as_billing" value="1"
									class="form-check-input">
								<label for="shipping-same">Same as Billing Address</label>
							</div>
							<div class="col-lg-12 mb-4">

								<div class="accordion" id="shippingAccordion">
									<div class="accordion-item">
										<h2 class="accordian-header" id="shippingHeading">
											<button class="accordion-button collapsed" id="shipping-btn"
												data-bs-target="#shippingCollapse" type="button"
												data-bs-toggle="collapse" aria-expanded="false"
												aria-controls="shippingCollapse">Shipping
												Address</button>
										</h2>
										<div class="accodion-collapse collapse " aria-labelledby="shippingHeading"
											data-bs-parent="#shippingAccordion" id="shippingCollapse">
											<div class="accordion-body">



												<div class="col-md-6 form-group">

													<label for="shipping-form-name">Name:</label>
													<input type="text" id="shipping-form-name" name="shipping_fname"
														value="" class="form-control">
												</div>

												<div class="col-md-6 form-group">
													<label for="shipping-form-lname">Last Name:</label>
													<input type="text" id="shipping-form-lname" name="shipping_lname"
														value="" class="form-control">
												</div>




												<div class="col-12 form-group">
													<label for="shipping-form-address">Address:</label>
													<textarea type="text" id="shipping-form-address"
														name="shipping_address" value="" class="form-control">
														</textarea>
												</div>


												<div class="col-12 form-group">
													<label for="shipping-form-city">City / Town</label>
													<input type="text" id="shipping-form-city" name="shipping_city"
														value="" class="form-control">
												</div>

												<div class="col-12 form-group">
													<label for="shipping-form-pincode">Pin Code</label>
													<input type="text" id="shipping-form-pincode"
														name="shipping_pincode" value="" class="form-control">
												</div>

												<div class="col-6 form-group">
													<label for="shipping-form-email">Email</label>
													<input type="email" id="shipping-form-email" name="shipping_email"
														value="" class="form-control">
												</div>
												<div class="col-6 form-group">
													<label for="shipping-form-phone">Phone no</label>
													<input type="tel" id="shipping-form-phone" name="shipping_phone"
														value="" class="form-control">
												</div>


											</div>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
					<div class="row col-mb-30">

						<div class="d-flex justify-content-end mb-4">
							<button type="submit" name="place_order" id="place" class="button button-3d">Place
								Order</button>



						</div>
				</form>
			</div>
		</div>
		</div>
	</section><!-- #content end -->
	<div class="mobile-bottom-nav d-md-none">
		<a href="#" class="nav-item active"> <i class="fas fa-home"></i></a>
		<a href="#" class="nav-item"><i class="fas fa-male"></i></a>

		<a href="#" class="nav-item"><i class="fas fa-female"></i></a>
		<a href="#" class="nav-item"> <i class="fas fa-gem"></i></a>


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
											<div class="counter counter-small"><span data-from="50" data-to="15065421"
													data-refresh-interval="80" data-speed="3000"
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
												name="widget-subscribe-form-email" class="form-control required email"
												placeholder="Enter your Email">
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
	<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
	<script>

		const swiper = new Swiper('.mySwiper', {
			slidesPerView: 1,
			spaceBetween: 20,
			loop: true,
			autoplay: {
				delay: 3000,        // Time between slides (in ms)
				disableOnInteraction: true  // Continue autoplay after manual swipe
			},

			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev'
			},
			breakpoints: {
				576: { slidesPerView: 1 },
				768: { slidesPerView: 2 },
				992: { slidesPerView: 3 }
			}
		});
	</script>
	<script src="js/plugins.min.js"></script>
	<script src="js/functions.bundle.js"></script>


	<script>
		let tbody = document.querySelector('#table-body');
		console.log(tbody);
		let cartItems = JSON.parse(sessionStorage.getItem('cart-items'));
		console.log((cartItems));


	</script>

	<script>
		let hiddenAppliedCouponId = document.createElement('input');
		hiddenAppliedCouponId.type = "hidden";
		hiddenAppliedCouponId.id = "applied-coupon-id";
		hiddenAppliedCouponId.name = "applied_coupon_id";
		console.log(hiddenAppliedCouponId);
		let cartForm = document.getElementById('cart-form');
		cartForm.append(hiddenAppliedCouponId);
		console.log(cartForm.children);
		let shippingAmount = parseInt(document.getElementById("shipping").innerHTML);
		console.log("shipping amount: ", shippingAmount);

		const check = document.getElementById("shipping-same");
		const shipbody = document.getElementById("shippingAccordion");
		const ship = document.getElementById("shipping-btn");
		let cartTotal = document.getElementById("cart-total");
		let cartWeight = [];
		console.log(ship);
		check.onchange = () => {


			if (check.checked) {
				ship.disabled = true;
				shipbody.classList.add("d-none");
			}
			else {
				ship.disabled = false;
				shipbody.classList.remove("d-none");
			}
		}

		// Coupons fetched directly from DB
		let Coupons = <?php echo json_encode($coupons); ?>;
		console.log(Coupons);
		const slabs = <?php echo json_encode($slabsData); ?>;
		console.log(slabs);


		function updateGrandTotal(amount, total) {

			console.log(shippingAmount);
			let a = new Number(total.innerHTML) - new Number(amount.innerHTML) + shippingAmount;
			if (a <= 0) {
				alert("Sorry the coupon is not valid");
				amount.innerHTML = 0;
			}
			else {
				total.innerHTML = a;
			}

		}
		const inputBox1 = document.getElementById("coupon-code");
		inputBox1.dataset.couponValue = 0;

		console.log(inputBox1.dataset);
		const hiddenDiv = document.getElementById("hidden-div");
		let applyBtn = document.getElementById("apply-btn");
		let amount = document.getElementById("amount");
		let total = document.getElementById("total");

		applyBtn.addEventListener('click', () => {
			console.log(inputBox1.dataset.couponValue);
			hiddenAppliedCouponId.value = parseInt(inputBox1.dataset.id);
			console.log(inputBox1.dataset.couponType);
			if (inputBox1.dataset.couponType.toLowerCase() === "percentage" && inputBox1.dataset.couponValue != "0") {
				amount.innerText = parseFloat(document.getElementById("cart-total").innerText * (parseFloat(inputBox1.dataset.couponValue) / 100));
				total.innerText = parseFloat(document.getElementById("cart-total").innerText) - parseFloat(amount.innerText) + parseInt(document.getElementById("shipping").dataset.shipping);
				//hiddenAppliedCouponId.value=parseInt(inputBox1.dataset.id);
				console.log(amount);

				console.log("checked the percentage")
			}
			else {
				amount.innerText = inputBox1.dataset.couponValue;
				total.innerText = parseFloat(document.getElementById("cart-total").innerText) - parseFloat(amount.innerText) + parseInt(document.getElementById('shipping').dataset.shipping);

			}
			console.log(hiddenAppliedCouponId);
		})
		
		const searchList = document.createElement("ul");
		searchList.classList.add("search-list");
		inputBox1.addEventListener("keyup", () => {
			if (inputBox1.value === "") {
				searchList.innerHTML = '';
				total.innerText = parseFloat(document.getElementById("cart-total").innerText);
				inputBox1.dataset.couponValue = 0;
				inputBox1.dataset.id = null;
				amount.dataset.amount = 0;
				inputBox1.dataset.couponType = "";
				total.innerText = cartTotal.innerText - amount.dataset.amount;

			}
		})
		inputBox1.addEventListener("input", (e) => {

			if (inputBox1.value == "")
				searchList.innerHTML = "";
			console.log(e.target.value);
			let filt = Coupons.filter((el) => {
				if (el.name.toLowerCase().includes(e.target.value.toLowerCase())) {
					return el;
				}


			});
			searchList.innerHTML = '';
			filt.map((el) => {
				let li = document.createElement('li');
				li.innerHTML = el.name;
				li.dataset.id = el.id;
				li.dataset.type = el.type;
				li.dataset.value = el.value;
				li.addEventListener('click', (e) => {
					inputBox1.value = e.target.innerHTML;
					inputBox1.dataset.id = li.dataset.id;
					inputBox1.dataset.couponType = li.dataset.type;
					inputBox1.dataset.couponValue = li.dataset.value;
					console.log(inputBox1.dataset);
					searchList.innerHTML = '';
				})
				searchList.appendChild(li);
			})
			hiddenDiv.appendChild(searchList);
			console.log(filt);
		})



	</script>

	<script>
		// Wait for the entire page to load before running the script
		document.addEventListener('DOMContentLoaded', function () {
			function shippingAmount() {
				let shippingAmount = 0;
				let sumWeight = 0;
				let shipping=document.getElementById("shipping");
				let mode=document.getElementById("d-mode").value;
				let filter_post_charges=0;
				let filter_courier_charges=0;
				console.log("delivery mode:",mode);
				cartWeight.forEach(item =>
			     {
					sumWeight+=item;
				 }
			);
			slabs.forEach(item=>{
				let min=item.min_weight;
				let max=item.max_weight;
				let p_charges=item.post_charges;
				let c_charges=item.courier_charges;
				if(sumWeight>min && sumWeight<max){
					console.log("executed the slab",min,max);
						filter_post_charges=p_charges;
						filter_courier_charges=c_charges;

				}
				
			})

			if(mode==0){
				shippingAmount=filter_post_charges;
			}
			if(mode==1){
				shippingAmount=filter_courier_charges;
			}
			if(shippingAmount==0){
				shipping.dataset.shipping=80;
			}
			else{
				console.log(shippingAmount);
				shipping.innerText=shippingAmount;
				shipping.dataset.shipping=shippingAmount;
			}
		console.log("total weight",sumWeight);
		}
		function CouponUpdate(){
			console.log(inputBox1.dataset.couponValue);
			hiddenAppliedCouponId.value = parseInt(inputBox1.dataset.id);
			console.log(inputBox1.dataset.couponType);
			if (inputBox1.dataset.couponType.toLowerCase() === "percentage" && inputBox1.dataset.couponValue != "0") {
				amount.innerText = parseFloat(document.getElementById("cart-total").innerText * (parseFloat(inputBox1.dataset.couponValue) / 100));
				total.innerText = parseFloat(document.getElementById("cart-total").innerText) - parseFloat(amount.innerText) + parseInt(document.getElementById("shipping").dataset.shipping);
				//hiddenAppliedCouponId.value=parseInt(inputBox1.dataset.id);
				console.log(amount);

				console.log("checked the percentage")
			}
			else {
				amount.innerText = inputBox1.dataset.couponValue;
				total.innerText = parseFloat(document.getElementById("cart-total").innerText) - parseFloat(amount.innerText) + parseInt(document.getElementById('shipping').dataset.shipping);

			}
			console.log(hiddenAppliedCouponId);
		}
		let mode=document.getElementById("d-mode");
		mode.addEventListener('change',()=>{
			shippingAmount();
			CouponUpdate();
		})
		function intialTotalWeight() {
			const cartItems = document.querySelectorAll('tr.cart_item');
			cartWeight = [];
			cartItems.forEach(item => {
				const priceElement = item.querySelector('.cart-product-price');
				const quantityInput = item.querySelector('.qty');
				const subtotalElement = item.querySelector('.cart-product-subtotal .amount');
				if (priceElement && quantityInput && subtotalElement) {


					const totalWeight = quantityInput.dataset.weight * quantityInput.value;
					cartWeight.push(totalWeight);
					console.log(cartWeight);
					
				}
			})
			shippingAmount();
		}
		intialTotalWeight();

		// This is the master function that calculates everything
		function updateCartTotals() {
			let cartSubtotal = 0;
			let couponDiscount = document.getElementById("amount");
			// Get all the product rows in the cart
			const cartItems = document.querySelectorAll('tr.cart_item');
			let cartTotal = document.getElementById("cart-total");
			let total = document.getElementById("total");
			// Loop through each product row
			cartItems.forEach(item => {
				const priceElement = item.querySelector('.cart-product-price');
				const quantityInput = item.querySelector('.qty');
				const subtotalElement = item.querySelector('.cart-product-subtotal .amount');

				// Check if the necessary elements exist in this row
				if (priceElement && quantityInput && subtotalElement) {
					const price = parseFloat(priceElement.dataset.price);
					const quantity = parseInt(quantityInput.value, 10);

					// Calculate the subtotal for the row
					const rowSubtotal = price * quantity;
					console.log(price);

					// Update the row's subtotal display, formatted to 2 decimal places
					subtotalElement.textContent = rowSubtotal.toFixed(2);

					// Add the row's subtotal to the main cart subtotal
					cartSubtotal += rowSubtotal;
				}

			});

			// Now, update the final totals at the bottom of the page
			const finalSubtotalElement = document.getElementById('cart-subtotal-amount');
			const grandTotalElement = document.getElementById('cart-grand-total-amount');
            let shippingCharges=document.getElementById('shipping');
			if (finalSubtotalElement) {
				finalSubtotalElement.textContent = cartSubtotal.toFixed(2);

			}
			if (grandTotalElement) {
				// Here you would also subtract discounts or add shipping
				// For now, let's just show the same subtotal
				grandTotalElement.textContent = cartSubtotal.toFixed(2);
				cartTotal.innerText = grandTotalElement.textContent;

				total.innerText = cartTotal.innerText - amount.dataset.amount+parseInt(shippingCharges.dataset.shipping);
			}
		}

		// --- Event Listeners ---

		// Listen for clicks on all PLUS buttons
		document.querySelectorAll('.plus').forEach(button => {
			button.addEventListener('click', function () {
				updateCartTotals(); // Recalculate everything
				intialTotalWeight();
			});
		});

		// Listen for clicks on all MINUS buttons
		document.querySelectorAll('.minus').forEach(button => {
			button.addEventListener('click', function () {
				// Prevent quantity from going below 1
				updateCartTotals(); // Recalculate everything
				intialTotalWeight();
			});
		});

		// Listen for direct manual input in the quantity fields
		document.querySelectorAll('.qty').forEach(input => {
			input.addEventListener('input', updateCartTotals);
		});

		// Run the function once on page load to ensure totals are correct initially

		updateCartTotals();
		updateGrandTotal(amount, total);
		let removebtns = document.querySelectorAll("td .remove");
		let rows = document.querySelectorAll("#table-body .cart_item");
		console.log(rows);
		removebtns.forEach((btn) => {
			btn.addEventListener('click', () => {
				let id = parseInt(btn.dataset.id);
				rows.forEach((row) => {
					if (id === parseInt(row.dataset.id)) {
						row.remove();
						updateCartTotals();
						updateGrandTotal(amount, total);
					}
					intialTotalWeight();

				});
			})
		})

		});
	</script>
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			function getCart() {
				return JSON.parse(sessionStorage.getItem('cart-items') || '[]');
			}
			function saveCart(cart) {
				sessionStorage.setItem('cart-items', JSON.stringify(cart));
			}
			document.querySelectorAll('a.remove').forEach(removeBtn => {
				removeBtn.addEventListener('click', (e) => {
					e.preventDefault();
					const row = removeBtn.closest('tr.cart_item');
					if (!row) return;
					const productId = parseInt(row.getAttribute('data-id'), 10);
					if (isNaN(productId)) return;
					let cart = getCart();
					cart = cart.filter(item => item.id !== productId);
					saveCart(cart);
					location.reload();
				});
			});
		});
		document.getElementById("place").addEventListener("click", function (e) {
			var displayTotal = document.getElementById("cart-total").innerText;//.replace(/[^\d.]/g, '');
			document.getElementById("order_total").value = displayTotal;
		});
		let form = document.getElementById("cart-form");
		let placeBtn = document.getElementById("place-btn");
		form.addEventListener('submit', (e) => {
			e.preventDefault();
			let cartItems = Array.from(JSON.parse(sessionStorage.getItem('cart-items')));
			console.log(cartItems);

			let tableBody = Array.from(document.querySelector('#cart #table-body').children);
			console.log(tableBody);
			let confirmedCartItems = [];
			let input = document.createElement('input');
			input.name = "confirmedCartItems";
			input.type = "hidden";
			let shipping_charges=document.getElementById("shipping").dataset.shipping;
			let shipping_mode=document.getElementById("d-mode").value;

			tableBody.map(tr => {
				if (!(tr.children.length > 2))
					return;
				let td = tr.children[4];
				let quantity = td.children[0];
				let qty = quantity.children[1].value;
				let subTotal = tr.children[5];
				let finalProductTotal = subTotal.children[0].innerHTML;

				console.log("sub total: ", finalProductTotal);
				let confirmedCartItem = {
					productId: tr.dataset.id,
					quantity: qty,
					finalTotal: finalProductTotal,
					final_shipping_charges:shipping_charges,
					final_delivery_mode:shipping_mode
				}
				console.log(confirmedCartItem);
				confirmedCartItems.push(confirmedCartItem);
				input.value = JSON.stringify(confirmedCartItems);
				form.append(input);
				console.log(input);
				console.log(confirmedCartItems);
			})
			let formData = new FormData(form);
			let formValues = Object.fromEntries(formData.entries());
			console.log(formValues);
			const placeOrderInput = document.createElement('input');
			placeOrderInput.type = 'hidden';
			placeOrderInput.name = 'place_order';
			placeOrderInput.value = 'true';
			form.append(placeOrderInput);
			form.submit();

		})


	</script>
</body>

</html>