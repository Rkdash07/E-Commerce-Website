<?php

include 'connection.php';
session_start();
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
  header("Location: login.php");
  exit;
}
$coupons = [];
$result = $conn->query("SELECT * FROM coupon ");
while ($row = $result->fetch_assoc()) {
  $coupons[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_coupon'])) {
  $coupon_name = trim($_POST['CouponName']);
  $coupon_code = trim($_POST['CouponCode']);
  $discount_type = $_POST['Discount'];
  $amount = floatval($_POST['Amount']);
  $description = trim($_POST['Coupondescription']);
  $status = (isset($_POST['status']) && $_POST['status'] === 'Enable') ? (int)$_POST['status'] : 0;
  $stmt = $conn->prepare("INSERT INTO Coupon (coupon_name, coupon_code, discount_type, amount, description, status) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssi", $coupon_name, $coupon_code, $discount_type, $amount, $description, $status);

  if ($stmt->execute()) {
    $stmt->close();
    header("Location: coupon.php?message=Coupon+added+successfully");
    exit();
  } else {
    $error = "Error adding coupon: " . $conn->error;
    $stmt->close();
  }
  if (isset($_POST['update_coupon'])) {
    $coupon_id = intval($_POST['coupon_id']);
    $coupon_name = trim($_POST['CouponName']);
    $coupon_code = trim($_POST['CouponCode']);
    $discount_type = $_POST['Discount'];
    $amount = floatval($_POST['Amount']);
    $description = trim($_POST['Coupondescription']);
    $status = (isset($_POST['status']) && $_POST['status'] === 'Enable') ? (int)$_POST['status'] : 0;

    $stmt = $conn->prepare("UPDATE Coupon SET coupon_name = ?, coupon_code = ?, discount_type = ?, amount = ?, description = ?, status = ? WHERE id = ?");
    $stmt->bind_param("sssssii", $coupon_name, $coupon_code, $discount_type, $amount, $description, $status, $coupon_id);
    $stmt->execute();
    $stmt->close();

    header("Location: coupon.php?message=Coupon+updated+successfully");
    exit();
  }
}

?>





<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>AdminLTE v4 | Coupons</title>
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
          <!--end::End Navbar Links-->
      </div>
      <div class="nav-item mt-2">
        <a class="nav-link  me-4 " href="logout.php">Logout</a>
      </div>
      </ul>

      <!--end::Container-->
    </nav>
    <!--end::Header-->
    <!--begin::Sidebar-->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
      <!--begin::Sidebar Brand-->
      <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="./index.php" class="brand-link">
          <!--begin::Brand Image-->
          <img src="./assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
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
          <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" aria-label="Main navigation"
            data-accordion="false" id="navigation">
            <li class="nav-item">
              <a href="index.php" class="nav-link">
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
                  <a href="./Products.php" class="nav-link">
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
                <li class="nav-item">
                  <a href="#" class="nav-link active">
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
              <h3 class="mb-0">Coupons</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Coupons</li>
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
          <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
              <?= htmlspecialchars($_GET['message']) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger mx-3" role="alert">
              <?= htmlspecialchars($error) ?>
            </div>
          <?php endif; ?>

          <button type="button" class="btn btn-primary my-2 " data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            Coupons
          </button>
          <!--begin::Row-->
          <!-- Add Coupon Modal -->
          <form method="POST" enctype="multipart/form-data">
            <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
              aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content card-primary card-outline">
                  <div class="modal-header bg-primary">
                    <h5 class="modal-title text-center w-100 text-light fw-bold fs-4" id="addCategoryModalLabel">Add
                      Coupon</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                      aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="CouponName" class="form-label text-center">Coupon Name</label>
                          <input type="text" name="CouponName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                          <label for="CouponCode" class="col-form-label text-center">Coupon Code</label>
                          <input type="text" name="CouponCode" class="form-control" required>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="category" class="form-label">Discount</label>
                          <select name="Discount" id="category" class="form-select" required>
                            <option value="" selected>Select</option>
                            <option value="Percentage">Percentage</option>
                            <option value="Rs">In Rs</option>
                          </select>
                        </div>

                        <div class="mb-3">
                          <label for="Amount" id="amount1" class="col-form-label">Amount</label>
                          <input type="number" name="Amount" id="Amount" step="0.01" min="0" class="form-control"
                            required>
                        </div>
                      </div>

                      <div class="mb-3">
                        <div class="my-2">
                          <label for="Coupondescription" class="col-form-label">Coupon
                            description(Shortdescription)</label>
                          <!-- <input type="text" name="Coupondescription" id=" " class="form-control"> -->
                          <textarea input="text" name="Coupondescription" id="" class="form-control"></textarea>
                        </div>
                      </div>


                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="add_coupon" class="btn btn-primary w-100">Add</button>
                  </div>
                </div>
              </div>
            </div>
          </form>

        </div>





        <!--end::App Content-->
        <div class="container">
          <div class="row card">
            <div class="table-heading ">
              <h3 class="p-0 my-4 "> Coupons</h3>
            </div>
            <div class="table-responsive ">
              <table style="text-align: center;" id="example1" class="table border table-bordered table-striped">

                <thead>
                  <tr>
                    <th>id</th>
                    <th>Coupon Name</th>
                    <th>Coupon Code</th>
                    <th>Coupon description</th>
                    <!-- <th>From</th>
                      <th>To</th> -->
                    <th>Status</th>
                    <th>Action
                    </th>

                  </tr>
                </thead>
                <tbody class="text-center align-middle">


                  <?php $count = 1;
                  foreach ($coupons as $c): ?>
                    <tr>

                      <td><?= $count++ ?>.</td>
                      <td><?= htmlspecialchars($c['coupon_name']) ?></td>
                      <td><?= htmlspecialchars($c['coupon_code']) ?></td>
                      <td><?= htmlspecialchars($c['description']) ?></td>
                      <?php
                        if($c['status']){
                          echo '<td> <span class="bg-success text-white px-2 py-1 rounded"> active </span> </td>';

                        }
                        else{
                          echo '<td><span class="bg-secondary text-white px-2 py-1 rounded"> in active</span></td>';
                        }
                          ?>
                      
                      <td>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                          data-bs-target="#updateCouponModal<?= $c['id'] ?>">
                          View More
                        </button>

                        <div class="modal fade" id="updateCouponModal<?= $c['id'] ?>" tabindex="-1"
                          aria-labelledby="updateCouponModalLabel<?= $c['id'] ?>" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered modal-lg">
                            <form method="POST" class="modal-content card-primary card-outline">
                              <div class="modal-header bg-primary">
                                <h5 class="modal-title text-center w-100 text-light fw-bold fs-4"
                                  id="updateCouponModalLabel<?= $c['id'] ?>">
                                  Update Coupon
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                  aria-label="Close"></button>
                              </div>
                              <div class="modal-body text-start">
                                <input type="hidden" name="coupon_id" value="<?= $c['id'] ?>">

                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="mb-3">
                                      <label for="CouponName<?= $c['id'] ?>" class="form-label">Coupon Name</label>
                                      <input type="text" name="CouponName" id="CouponName<?= $c['id'] ?>"
                                        class="form-control" value="<?= htmlspecialchars($c['coupon_name']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                      <label for="CouponCode<?= $c['id'] ?>" class="col-form-label">Coupon Code</label>
                                      <input type="text" name="CouponCode" id="CouponCode<?= $c['id'] ?>"
                                        class="form-control" value="<?= htmlspecialchars($c['coupon_code']) ?>" required>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="mb-3">
                                      <label for="updateCategory" class="form-label">Discount</label>
                                      <select name="Discount" id="updateCategory" class="form-select" required>
                                        <option value="" <?= $c['discount_type'] == '' ? 'selected' : '' ?>></option>
                                        <option value="Percentage" <?= $c['discount_type'] == 'Percentage' ? 'selected' : '' ?>>Percentage</option>
                                        <option value="Rs" <?= $c['discount_type'] == 'Rs' ? 'selected' : '' ?>>In Rs
                                        </option>
                                      </select>
                                    </div>
                                    <div class="mb-3">
                                      <label for="Amount<?= $c['id'] ?>" id="amount2"
                                        class="col-form-label">Amount</label>
                                      <input type="number" name="Amount" id="Amount<?= $c['id'] ?>" class="form-control"
                                        value="<?= number_format($c['amount'], 2) ?>" step="0.01" required>
                                    </div>
                                  </div>
                                  <!-- Other fields as is -->
                                  <div class="col-12">
                                    <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="radio" name="status"
                                        id="statusEnable<?= $c['id'] ?>" value="Enable" <?= $c['status'] == 1 ? 'checked' : '' ?>>
                                      <label class="form-check-label" for="statusEnable<?= $c['id'] ?>">Enable</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="radio" name="status"
                                        id="statusDisable<?= $c['id'] ?>" value="Disable" <?= $c['status'] == 0 ? 'checked' : '' ?>>
                                      <label class="form-check-label" for="statusDisable<?= $c['id'] ?>">Disable</label>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="submit" name="update_coupon" class="btn btn-primary w-100">Update</button>
                              </div>
                            </form>
                          </div>
                        </div>

                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>

              </table>
            </div>
          </div>
        </div>
      </div>
    </main>





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
  <script>
    let a = document.getElementById('category');
    let b = document.querySelector('#amount1');
    console.log(a);
    // console.log("hello");
    console.log(b);
    a.addEventListener('click', () => {
      console.log("clicked");
    })
    a.onchange = (e) => {
      console.log(e.target.value);

      if (e.target.value === 'Percentage') {

        b.innerText = 'Percentage';
      }
      else {
        b.innerText = '₹ Amount';
      }
    }

    let c = document.getElementById('updateCategory');
    let d = document.querySelector('#amount2');
    console.log(c);
    console.log(d);

    c.addEventListener('click', () => {
      console.log("clicked");
    })
    c.onchange = (e) => {
      console.log(e.target.value);

      if (e.target.value === 'Percentage') {

        d.innerText = 'Percentage';
      }
      else {
        d.innerText = '₹ Amount';
      }
    }
  </script>
  <!--end::App Wrapper-->
  <!--begin::Script-->
  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
    crossorigin="anonymous"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
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
  <script>
    new Sortable(document.querySelector('.connectedSortable'), {
      group: 'shared',
      handle: '.card-header',
    });

    const cardHeaders = document.querySelectorAll('.connectedSortable .card-header');
    cardHeaders.forEach((cardHeader) => {
      cardHeader.style.cursor = 'move';
    });
  </script>
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
</body>
<!--end::Body-->

</html>