<?php
include 'connection.php';
session_start();
if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
      header("Location: login.php");
      exit;
  }
$products = [];
$query = "SELECT p.*, c.category, s.sub_category, st.storeName 
          FROM Product p
          LEFT JOIN Category c ON p.category_id = c.id
          LEFT JOIN SubCategory s ON p.sub_category_id = s.id
          LEFT JOIN Stores st ON p.store_id = st.id
          ORDER BY p.id DESC";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$categories = $conn->query("SELECT * FROM Category");
$subcategories = $conn->query("SELECT * FROM SubCategory");
$stores = $conn->query("SELECT * FROM Stores");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $productName = $_POST['productName'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $sub_category_id = $_POST['sub_category_id'];
    $store_id = $_POST['store_id'];
    $MRP = $_POST['MRP'];
    $SP = $_POST['SP'];
    $status = isset($_POST['status']) && $_POST['status'] == '1' ? 1 : 0;
    $metaTitle = $_POST['Meta_title'];
    $metaDescription = $_POST['Meta_description'];
    $thumbnail = '';
    if (!empty($_FILES['mainPhoto']['name'])) {
        $mainDir = './UploadImg/ProductImg/';
        if (!file_exists($mainDir))
            mkdir($mainDir, 0755, true);

        $filename = time() . '_' . basename($_FILES['mainPhoto']['name']);
        $target = $mainDir . $filename;

        if (move_uploaded_file($_FILES['mainPhoto']['tmp_name'], $target)) {
            $thumbnail = $target;
        }
    }
    $stmt = $conn->prepare("INSERT INTO Product (
        productName, description,category_id, sub_category_id, store_id, MRP, SP, status, thumbnail,Meta_title,Meta_description
    ) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?,?,?)");
    $stmt->bind_param("ssiiiddisss", $productName,$description, $category_id, $sub_category_id, $store_id, $MRP, $SP, $status, $thumbnail,$metaTitle,$metaDescription);
    $stmt->execute();
    $product_id = $stmt->insert_id;
    $stmt->close();
    if (!empty($_FILES['otherPhotos']['name'][0])) {
        $galleryDir = './UploadImg/ProductImg/MultiImg/';
        if (!file_exists($galleryDir))
            mkdir($galleryDir, 0755, true);
        foreach ($_FILES['otherPhotos']['tmp_name'] as $i => $tmp_name) {
            if (!empty($tmp_name)) {
                $originalName = basename($_FILES['otherPhotos']['name'][$i]);
                $filename = time() . "_gallery_$i" . '_' . $originalName;
                $targetPath = $galleryDir . $filename;
                if (move_uploaded_file($tmp_name, $targetPath)) {
                    $insertImage = $conn->prepare("INSERT INTO ProductImg (product_id, image_path) VALUES (?, ?)");
                    $insertImage->bind_param("is", $product_id, $targetPath);
                    $insertImage->execute();
                    $insertImage->close();
                }
            }
        }
    }

    header("Location: Products.php?message=Product+added+successfully");
    exit();
}


if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $res = $conn->query("SELECT thumbnail FROM Product WHERE id = $id");
    $row = $res->fetch_assoc();
    if ($row && file_exists($row['thumbnail'])) {
        unlink($row['thumbnail']);
    }

    $conn->query("DELETE FROM Product WHERE id = $id");
    $_SESSION['message'] = "Product deleted successfully!";
    header("Location: Products.php");
    exit();
}


?>



<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE v4 | Dashboard</title>
    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->
    <!--begin::Primary Meta Tags-->
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description"
        content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance." />
    <meta name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant" />
    <!--end::Primary Meta Tags-->
    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="./css/adminlte.css" as="style" />
    <!--end::Accessibility Features-->
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
        onload="this.media='all'" />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="./css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
    <!-- jsvectormap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
        integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="../../datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <!-- DataTables CSS + Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables + Bootstrap 4 -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <style>
        .table-heading {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-dialog {
            max-width: 900px;
        }

        .modal-content {
            border-radius: 12px;
            overflow: hidden;
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-title {
            flex-grow: 1;
            text-align: center;
        }

        .tab-wrapper {
            display: flex;
            flex-wrap: wrap;
        }

        .nav-tabs {
            flex-direction: column;
            border: none;
            width: 100%;
        }

        .tab-buttons {
            min-width: 200px;
            max-width: 220px;
        }

        .nav-tabs .nav-link {
            background: #f8f9fa;
            color: #333;
            border: none;
            margin-bottom: 5px;
            text-align: left;
            padding: 12px 16px;
            border-left: 4px solid transparent;
            border-radius: 6px;
            font-weight: 500;
            transition: background 0.2s ease;
        }

        .nav-tabs .nav-link:hover {
            background: #e9ecef;
        }

        .nav-tabs .nav-link.active {
            background: #e0f0ff;
            color: #0d6efd;
            border-left: 4px solid #0d6efd;
            font-weight: 600;
        }

        .tab-content {
            flex: 1;
            padding: 20px;
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .form-label {
            font-weight: 500;
        }

        .form-control,
        .form-select {
            border-radius: 6px;
        }

        .modal-footer {
            border-top: none;
        }

        .btn-primary {
            border-radius: 6px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        @media (max-width: 768px) {
            .tab-wrapper {
                flex-direction: column;
            }

            .tab-buttons {
                width: 100%;
                display: flex;
                flex-direction: row;
                overflow-x: auto;
            }

            .nav-tabs {
                flex-direction: row;
                width: 100%;
            }

            .nav-tabs .nav-link {
                flex: 1;
                text-align: center;
                border-left: none;
                border-bottom: 4px solid transparent;
                border-radius: 0;
            }

            .nav-tabs .nav-link.active {
                border-bottom: 4px solid #0d6efd;
                border-left: none;
            }
        }

        .text-area {
            border-radius: 8px;
            border-color: rgb(189, 187, 187) !important;
            outline: none;
            color: black;
            height: 250px;

        }
    </style>
</head>
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <nav class="app-header navbar navbar-expand bg-body">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Start Navbar Links-->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
                    <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
                </ul>
                <!--end::Start Navbar Links-->
                <!--begin::End Navbar Links-->
                <ul class="navbar-nav ms-auto">
                    <!--begin::Navbar Search-->
                    <!-- <li class="nav-item">
              <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="bi bi-search"></i>
              </a>
            </li> -->
                    <!--end::Navbar Search-->
                    <!--begin::Messages Dropdown Menu-->

                    <!--end::Messages Dropdown Menu-->
                    <!--begin::Notifications Dropdown Menu-->

                    <!--end::Notifications Dropdown Menu-->
                    <!--begin::Fullscreen Toggle-->

                    <!--end::Fullscreen Toggle-->
                    <!--begin::User Menu Dropdown-->
                    <div class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </div>
                    <!--end::User Menu Dropdown-->
                </ul>
                <!--end::End Navbar Links-->
            </div>
            <!--end::Container-->
        </nav>
        <!--end::Header-->
        <!--begin::Sidebar-->
         <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!--begin::Sidebar Brand-->
            <div class="sidebar-brand">
                <!--begin::Brand Link-->
                <a href="./index.html" class="brand-link">
                    <!--begin::Brand Image-->
                    <img src="./assets/img/AdminLTELogo.png" alt="AdminLTE Logo"
                        class="brand-image opacity-75 shadow" />
                    <!--end::Brand Image-->
                    <!--begin::Brand Text-->
                    <span class="brand-text fw-light">AdminLTE 4</span>
                    <!--end::Brand Text-->
                </a>
                <!--end::Brand Link-->
            </div>
            <!--end::Sidebar Brand-->
            <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <!--begin::Sidebar Menu-->
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" 
                        aria-label="Main navigation" data-accordion="false" id="navigation">
                        <li class="nav-item">
                            <a href="index.html" class="nav-link">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                         <li class="nav-item">
              <a href="./Navbar.php" class="nav-link">
                <i class="nav-icon bi bi-speedometer"></i>
                <p>
                  Navbar
                </p>
              </a>
            </li>

                        <li class="nav-item menu-open">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>Settings
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ">
                                    <a href="./Category.php" class="nav-link">
                                        <i class="nav-icon bi bi-speedometer"></i>
                                        <p>
                                            Category
                                        </p>
                                    </a>

                                </li>

                                <li class="nav-item">
                                    <a href="./SubCategory.php" class="nav-link">
                                        <i class="nav-icon bi bi-speedometer"></i>
                                        <p>
                                            Sub-Category
                                        </p>
                                    </a>

                                </li>



                                <li class="nav-item">
                                    <a href="#" class="nav-link active">
                                        <i class="nav-icon bi bi-speedometer"></i>
                                        <p>
                                            Products
                                        </p>
                                    </a>

                                </li>
                                
                                <li class="nav-item ">
                                    <a href="./stores.php" class="nav-link">
                                        <i class="nav-icon bi bi-speedometer"></i>
                                        <p>
                                            Stores
                                        </p>
                                    </a>

                                </li>
                                 <li class="nav-item ">
                                    <a href="./coupon.php" class="nav-link ">
                                        <i class="nav-icon bi bi-speedometer"></i>
                                        <p>
                                            Coupons
                                        </p>
                                    </a>

                                </li>
                            </ul>
                        </li>
                         <li class="nav-item">

                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>Orders
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>

                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./Orders.php" class="nav-link">
                                        <i class="nav-icon bi bi-speedometer"></i>
                                        <p>
                                            Pending
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a href="./shipped-orders.php" class="nav-link">
                                        <i class="nav-icon bi bi-speedometer"></i>
                                        <p>
                                            Shipped
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a href="./completed-orders.html" class="nav-link" >
                                        <i class="nav-icon bi bi-speedometer"></i>
                                        <p>
                                            Completed
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>



                    </ul>


                    <!--end::Sidebar Menu-->
                </nav>
            </div>
            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->
        <!--begin::App Main-->
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Products</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Products</li>
                            </ol>
                        </div>

                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content Header-->
            <!--begin::App Content-->
            <div class="app-content">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->

                    <?php if (isset($_GET['message'])): ?>
                        <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                            <?= htmlspecialchars($_GET['message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <!-- Add Product Modal -->
                    <!-- Add Product Button -->
                    <button id="openAddProductBtn" type="button" class="btn btn-primary my-2" data-bs-toggle="modal"
                        data-bs-target="#addProductModal">Add Product</button>

                    <!-- Modal -->
                    <!-- <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content card-outline">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-light fw-bold text-center w-100"
                                        id="addProductModalLabel">Add a Product</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Product Name</label>
                                                    <input type="text" name="productName" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Thumbnail Image</label>
                                                    <input type="file" name="mainPhoto" class="form-control"
                                                        accept="image/*" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Additional Images</label>
                                                    <input type="file" name="otherPhotos[]" multiple
                                                        class="form-control" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Select Category</label>
                                                    <select name="category_id" class="form-select" required>
                                                        <option value="">Select</option>
                                                        <?php foreach ($categories as $cat): ?>
                                                            <option value="<?= $cat['id'] ?>"><?= $cat['category'] ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Select Subcategory</label>
                                                    <select name="sub_category_id" class="form-select" required>
                                                        <option value="">Select</option>
                                                        <?php foreach ($subcategories as $sub): ?>
                                                            <option value="<?= $sub['id'] ?>"><?= $sub['sub_category'] ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Store Name</label>
                                                    <select name="store_id" class="form-select" required>
                                                        <option value="">Select</option>
                                                        <?php foreach ($stores as $str): ?>
                                                            <option value="<?= $str['id'] ?>"><?= $str['storeName'] ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Product MRP (Rs.)</label>
                                                    <input type="text" name="MRP" class="form-control" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Selling Price (Rs.)</label>
                                                    <input type="text" name="SP" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="add_product" class="btn btn-primary w-100">Add
                                            Product</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> -->

                <!-- stepped form -->
                <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title fw-bold fs-4" id="addProductModalLabel">Add a Product</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <!-- Form -->
                            <form method="POST" enctype="multipart/form-data">
                                <div class="modal-body" style="height:350px !important;">
                                    <div class="tab-wrapper row">
                                        <!-- Left Tabs -->
                                        <div class="col-md-3 tab-buttons">
                                            <ul class="nav nav-tabs" id="productTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active form-control" id="basic-tab"
                                                        data-bs-toggle="tab" data-bs-target="#basic" type="button"
                                                        role="tab">
                                                        Category & SubCategory
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link  form-control" id="basic-tab"
                                                        data-bs-toggle="tab" data-bs-target="#product-details"
                                                        type="button" role="tab">
                                                        Product Deatils
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link form-control" id="images-tab"
                                                        data-bs-toggle="tab" data-bs-target="#images" type="button"
                                                        role="tab">
                                                        Images
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link form-control" id="pricing-tab"
                                                        data-bs-toggle="tab" data-bs-target="#pricing" type="button"
                                                        role="tab">
                                                        Pricing & Store
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link form-control" id="seo-tab"
                                                        data-bs-toggle="tab" data-bs-target="#seo" type="button"
                                                        role="tab">
                                                        SEO
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- Right Form Content -->
                                        <div class="col-md-9">
                                            <div class="tab-content" id="productTabContent">
                                                <!-- Tab 1 -->
                                                <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                                    <div class="mb-3">
                                                        <label for="category_id" class="form-label">Category</label>
                                                        <select name="category_id" id="category" class="form-select">
                                                            <option value="">Select</option>
                                                            <?php foreach ($categories as $cat): ?>
                                                                <option value="<?= $cat['id'] ?>"><?= $cat['category'] ?>
                                                                </option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="sub_category_id" class="form-label">Sub Category</label>
                                                        <select name="sub_category_id" id="subCategory" class="form-select">
                                                            <?php foreach ($subcategories as $sub): ?>
                                                                <option value="<?= $sub['id'] ?>">
                                                                    <?= $sub['sub_category'] ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div>

                                                </div>
                                                <!-- Product details -->
                                                <div class="tab-pane fade" id="product-details">
                                                    <div class="mb-3">
                                                        <label for="productName" class="form-label">Product Name</label>
                                                        <input type="text" name="productName" id="ProductName"
                                                            class="form-control" required>
                                                    </div>

                                                    <div class="mb-3 position-relative">
                                                        <label for="description">Description</label>

                                                        <!-- Tooltip trigger (textarea) -->
                                                        <textarea class="form-control" id="product-description"
                                                            name="description" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Click to open editor"
                                                            style="height: 100px;"></textarea>

                                                        <!-- Quill container (hidden by default) -->
                                                        <div id="quill-container" class="mt-2" style="display: none;">
                                                            <div id="quill-editor" style="height: 200px;"></div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- Tab 2 -->
                                                <div class="tab-pane fade" id="images" role="tabpanel">
                                                    <div class="mb-3">
                                                        <label for="mainPhoto" class="form-label">Thumbnail
                                                            Image</label>
                                                        <input type="file" accept="image/*" required name="mainPhoto"
                                                            id="mainPhoto" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="otherPhotos[]" class="form-label">Additional
                                                            Photos</label>
                                                        <input type="file" accept="image/*" name="otherPhotos[]"
                                                            id="otherPhotos" class="form-control" multiple>
                                                    </div>
                                                </div>

                                                <!-- Tab 3 -->
                                                <div class="tab-pane fade" id="pricing" role="tabpanel">
                                                    <div class="mb-3">
                                                        <label for="store_id" class="form-label">Store Name</label>
                                                        <select name="store_id" id="storeName" class="form-select">
                                                            <option value="">Select</option>
                                                            <?php foreach ($stores as $str): ?>
                                                            <option value="<?= $str['id'] ?>"><?= $str['storeName'] ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="price" class="form-label">Product MRP (Rs.)</label>
                                                        <input type="text" name="MRP" id="price" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="sp-price" class="form-label">Selling Price
                                                            (Rs.)</label>
                                                        <input type="text" name="SP" id="sp-price" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="seo">
                                                    <div class="mb-3">
                                                        <label for="Meta_title" class="form-label">Meta Title</label>
                                                        <input type="text" name="Meta_title" id="metaTile"
                                                            class="form-control" required>
                                                    </div>

                                                    <div class="mb-3 position-relative">
                                                        <label for="Meta_description">Meta Description</label>

                                                        <!-- Tooltip trigger (textarea) -->
                                                        <textarea class="form-control" id="metadescription"
                                                            name="Meta_description" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Click to open editor"
                                                            style="height: 100px;"></textarea>

                                                        <!-- Quill container (hidden by default) -->
                                                        <!-- <div id="quill-container" class="mt-2" style="display: none;">
                                                            <div id="quill-editor" style="height: 200px;"></div>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 <!--Tab 4-->
                                 

                                <!-- Modal Footer -->
                                <div class="modal-footer px-4 pb-4">
                                    <button type="submit" name="add_product" class="btn btn-primary w-100">Add Product</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                </div>

                <div class="container-fluid px-3 mb-5">
                    <div class="card">
                        <div class="table-heading px-3 mt-4 d-flex justify-content-between">
                            <h3>Product</h3>
                        </div>
                        <div class="table-responsive px-3 pb-3">
                            <table id="example1" class="table table-bordered table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Sub-category</th>
                                        <th>Store</th>
                                        <th>Price ₹</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($products as $p): ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td>
                                                <img src="<?= htmlspecialchars($p['thumbnail']) ?>" class="img-thumbnail"
                                                    width="60" height="60" style="object-fit:cover;">
                                                <div><?= htmlspecialchars($p['productName']) ?></div>
                                            </td>
                                            <td><?= htmlspecialchars($p['category']) ?></td>
                                            <td><?= htmlspecialchars($p['sub_category']) ?></td>
                                            <td><?= htmlspecialchars($p['storeName']) ?></td>
                                            <td><strong>SP: ₹<?= number_format($p['SP'], 2) ?></strong><br><small
                                                    class="text-muted">MRP: ₹<?= number_format($p['MRP'], 2) ?></small></td>
                                            <td>
                                                <span class="badge bg-<?= $p['status'] == 1 ? 'success' : 'secondary' ?>">
                                                    <?= $p['status'] == 1 ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="updateProduct.php?id=<?= $p['id'] ?>"
                                                    class="btn btn-sm btn-primary">Update</a>
                                                <a href="?delete=<?= $p['id'] ?>" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>
    </main>
    <!--end::App Main-->
    <!--begin::Footer-->
    <footer class="app-footer">
        <!--begin::To the end-->
        <div class="float-end d-none d-sm-inline">Anything you want</div>
        <!--end::To the end-->
        <!--begin::Copyright-->
        <strong>
            Copyright &copy; 2014-2025&nbsp;
            <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
        </strong>
        All rights reserved.
        <!--end::Copyright-->
    </footer>
    <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
        crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        crossorigin="anonymous"></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
        crossorigin="anonymous"></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="./js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
    <!--end::OverlayScrollbars Configure-->
    <!-- OPTIONAL SCRIPTS -->
    <!-- sortablejs -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" crossorigin="anonymous"></script>
    <!-- sortablejs -->
    <!-- apexcharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
        integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>
    <!-- ChartJS -->
    <script>
        // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
        // IT'S ALL JUST JUNK FOR DEMO
        // ++++++++++++++++++++++++++++++++++++++++++

        const sales_chart_options = {
            series: [
                {
                    name: 'Digital Goods',
                    data: [28, 48, 40, 19, 86, 27, 90],
                },
                {
                    name: 'Electronics',
                    data: [65, 59, 80, 81, 56, 55, 40],
                },
            ],
            chart: {
                height: 300,
                type: 'area',
                toolbar: {
                    show: false,
                },
            },
            legend: {
                show: false,
            },
            colors: ['#0d6efd', '#20c997'],
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: 'smooth',
            },
            xaxis: {
                type: 'datetime',
                categories: [
                    '2023-01-01',
                    '2023-02-01',
                    '2023-03-01',
                    '2023-04-01',
                    '2023-05-01',
                    '2023-06-01',
                    '2023-07-01',
                ],
            },
            tooltip: {
                x: {
                    format: 'MMMM yyyy',
                },
            },
        };

        const sales_chart = new ApexCharts(
            document.querySelector('#revenue-chart'),
            sales_chart_options,
        );
        sales_chart.render();
    </script>
    <!-- jsvectormap -->
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
        integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
        integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script>
    <!-- jsvectormap -->
    <script>
        // World map by jsVectorMap
        new jsVectorMap({
            selector: '#world-map',
            map: 'world',
        });

        // Sparkline charts
        const option_sparkline1 = {
            series: [
                {
                    data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
                },
            ],
            chart: {
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: 'straight',
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ['#DCE6EC'],
        };

        const sparkline1 = new ApexCharts(document.querySelector('#sparkline-1'), option_sparkline1);
        sparkline1.render();

        const option_sparkline2 = {
            series: [
                {
                    data: [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
                },
            ],
            chart: {
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: 'straight',
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ['#DCE6EC'],
        };

        const sparkline2 = new ApexCharts(document.querySelector('#sparkline-2'), option_sparkline2);
        sparkline2.render();

        const option_sparkline3 = {
            series: [
                {
                    data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
                },
            ],
            chart: {
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: 'straight',
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ['#DCE6EC'],
        };

        const sparkline3 = new ApexCharts(document.querySelector('#sparkline-3'), option_sparkline3);
        sparkline3.render();
    </script>
    <!--end::Script-->

    <script>
        $(function () {
            $('#example1').DataTable({
                responsive: true,
                scrollX: true,
                autoWidth: false
            });
        });

    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const openBtn = document.getElementById("openAddProductBtn");
            const modalEl = document.getElementById("addProductModal");

            if (openBtn && modalEl) {
                openBtn.addEventListener("click", function () {
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                });
            }
        });
    </script>

    
<!-- Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<!-- Bootstrap Tooltip -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
  // Initialize Bootstrap tooltip
  const tooltipTrigger = document.querySelector('#product-description');
  const tooltip = new bootstrap.Tooltip(tooltipTrigger);

  let quillInitialized = false;
  let quill;

  tooltipTrigger.addEventListener('click', function () {
    // Hide textarea and tooltip
    tooltip.hide();
    tooltipTrigger.style.display = 'none';

    // Show and initialize quill
    const quillContainer = document.getElementById('quill-container');
    quillContainer.style.display = 'block';

    if (!quillInitialized) {
      quill = new Quill('#quill-editor', {
        theme: 'snow'
      });
      quill.root.focus();
      quillInitialized = true;
    }

    // Optional: prefill quill with textarea content
    const text = tooltipTrigger.value;
    quill.root.innerHTML = text;
  });

  // On form submission, copy Quill content into the hidden textarea
  document.querySelector('form').addEventListener('submit', function (e) {
    if (quillInitialized) {
      const description = document.getElementById('product-description');
      description.value = quill.root.innerHTML;
    }
  });
</script>


</body>
<!--end::Body-->

</html>