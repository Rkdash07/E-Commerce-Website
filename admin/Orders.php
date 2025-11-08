<?php
include 'connection.php';
session_start();
if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
      header("Location: login.php");
      exit;
  }
$sql = "
SELECT
    o.id,
    o.purchase_date,
    o.total,
    o.order_status,
    bd.fname AS billing_fname,
    bd.lname AS billing_lname,
    bd.email AS billing_email,
    sd.fname AS shipping_fname,
    sd.lname AS shipping_lname,
    sd.email AS shipping_email,
    GROUP_CONCAT(p.productName SEPARATOR ', ') AS products,
    SUM(oi.quantity) AS total_quantity
FROM `order` o
LEFT JOIN billing_details bd ON o.billing_details_id = bd.id
LEFT JOIN shipping_details sd ON o.shipping_details_id = sd.id
LEFT JOIN orderitems oi ON oi.orderId = o.id
LEFT JOIN product p ON oi.productId = p.id
WHERE o.order_status IN ('0', '1')
GROUP BY o.id

";

$result = $conn->query($sql);
$orders = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
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

        </ul>
        <!--end::Start Navbar Links-->
        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
          <!--begin::Navbar Search-->


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

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>Settings
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ">
                                    <a href="#" class="nav-link">
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
                         <li class="nav-item menu-open">

                            <a href="#" class="nav-link ">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>Orders
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>

                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./Orders.php" class="nav-link active">
                                        <i class="nav-icon bi bi-speedometer"></i>
                                        <p>
                                            Pending
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a href="./Shipped-orders.php" class="nav-link">
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
              <h3 class="mb-0">Orders</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="logout.php">Logout</a></li>

              </ol>
            </div>
          </div>
          <!--end::Row-->
        </div>
        
      </div>
      
      <div class="app-content">
        
        <div class="container-fluid">
          
          <!-- <div class="row">
            <div class="#">
              <div class="#">
                <div class="card-header">
                  <h3 class="card-title">Order Table</h3>
                </div>
               
                <div class="card-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th style="width: 10px">#</th>
                        <th>Order id</th>
                        <th>Name</th>
                        <th>Email id</th>
                        <th>Date and Time</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>View More</th>

                      </tr>
                    </thead>
                    <tbody>
                      <tr class="align-middle">
                        <td>1.</td>
                        <td>P101</td>
                        <td>
                          <p>ABC</p>

                        </td>
                        <td><span class="badge text-bg-danger"></span>abcd@gmail.com </td>
                        <td>12/07/2025 and 14:31:50</td>
                        <td>1500</td>
                        <td>pending</td>
                        <td><a href="./invoice.html" class="btn btn-primary">
                            View
                          </a></td>
                      </tr>
                      <tr class="align-middle">
                        <td>2.</td>
                        <td>Prod2</td>
                        <td>
                          <p>XYZ</p>
                        </td>
                        <td><span class="badge text-bg-danger"></span>harinipreethi686@gmail.com</td>
                        <td>13/07/2025 and 10:42:37</td>
                        <td>2500</td>
                        <td>delivered</td>
                        <td><a href="./invoice.html" class="btn btn-primary">
                            View
                          </a></td>

                      </tr>
                      <tr class="align-middle">
                        <td>3.</td>
                        <td>Prod3</td>
                        <td>
                          <p>ALICE</p>
                        </td>
                        <td><span class="badge text-bg-danger"></span>harinipreethi2002@gmail.com</td>
                        <td>14/07/2025 and 11:57:39</td>
                        <td>2560</td>
                        <td>pending</td>
                        <td><a href="./invoice.html" class="btn btn-primary">
                            View
                          </a></td>
                      </tr>
                      <tr class="align-middle">
                        <td>4.</td>
                        <td>Prod4</td>
                        <td>
                          <p>BOB</p>
                        </td>
                        <td><span class="badge text-bg-danger"></span>xyz@gmail.com</td>
                        <td>15/07/2025 and 13:58:39</td>
                        <td>3000</td>
                        <td>delivered</td>
                        <td><a href="./invoice.html" class="btn btn-primary">
                            View
                          </a></td>
                      </tr>
                      <tr class="align-middle">
                        <td>5.</td>
                        <td>Prod5</td>
                        <td>
                          <p>CHINTU</p>
                        </td>
                        <td><span class="badge text-bg-danger"></span>efg@gmail.com</td>
                        <td>16/07/2025 and 16:59:40</td>
                        <td>3500</td>
                        <td>pending</td>
                        <td><a href="./invoice.html" class="btn btn-primary">
                            View
                          </a></td>
                      <tr class="align-middle">
                        <td>6.</td>
                        <td>Prod6</td>
                        <td>
                          <p>DART</p>
                        </td>
                        <td><span class="badge text-bg-danger"></span>pree@gmail.com</td>
                        <td>17/07/2025 and 17:60:34</td>
                        <td>3570</td>
                        <td>delivered</td>
                        <td><a href="./invoice.html" class="btn btn-primary">
                            View
                          </a></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                

              </div> -->


              
<?php if (isset($_SESSION['message'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <?= $_SESSION['message']; unset($_SESSION['message']) ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<!-- <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addOrderModal">Add Order</button>
<div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Add New Order</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="form-group mb-2">
          <label>Order ID</label>
          <input type="text" name="orderId" class="form-control" required>
        </div>
        <div class="form-group mb-2">
          <label>Product Name</label>
          <input type="text" name="productName" class="form-control" required>
        </div>
        <div class="form-group mb-2">
          <label>Customer Name</label>
          <input type="text" name="customerName" class="form-control">
        </div>
        <div class="form-group mb-2">
          <label>Email</label>
          <input type="email" name="email" class="form-control">
        </div>
        <div class="form-group mb-2">
          <label>Price</label>
          <input type="number" name="price" class="form-control">
        </div>
        <div class="form-group mb-2">
          <label>Status</label>
          <select name="status" class="form-control" required>
            <option value="pending">Pending</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_order" class="btn btn-primary w-100">Add Order</button>
      </div>
    </form>
  </div>
</div> -->
<table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                      <tr>
                        <th style="width: 10px">#</th>
                        <th>Order id</th>
                        <th>Name</th>
                        <th>Email id</th>
                        <th>Date and Time</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>View More</th>
                      </tr>
                    </thead>
  <tbody>
    <?php $i=1; foreach ($orders as $row): ?>
    <tr>
        <td><?= $i++;?></td>
        <td><?= htmlspecialchars($row['id']); ?></td>
        <td><?= htmlspecialchars($row['billing_fname'] . ' ' . $row['billing_lname']); ?></td>
        <td><?= htmlspecialchars($row['billing_email']); ?></td>
        <td><?= date("d/m/Y H:i", strtotime($row['purchase_date'])); ?></td>
        <td><?= htmlspecialchars($row['total']); ?></td>
        <td> <?php
            $statusMap = [
                0 => ['label' => 'Pending',    'color' => 'secondary'],
                1 => ['label' => 'Processing', 'color' => 'warning'],
                2 => ['label' => 'Shipping',   'color' => 'info'],
                3 => ['label' => 'Delivered',  'color' => 'success'],
            ];
            $status = $statusMap[$row['order_status']] ?? $statusMap[0];
            ?>
            <span class="badge bg-<?= $status['color']; ?>">
                <?= $status['label']; ?>
            </span>
            </td>
            <td>
                <a href="invoice.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">
                    View More
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
  </tbody>
</table>



            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!-- /.Start col -->

      </div>
      <!-- /.row (main row) -->
  </div>
  <!--end::Container-->
  </div>
  <!--end::App Content-->
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