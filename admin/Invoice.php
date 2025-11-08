<?php
session_start();
include 'connection.php';
$orderId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order'])) {
//   $newStatus = (int) $_POST['status'];
//   $newMode = $_POST['mode'];

//   $stmt = $conn->prepare("UPDATE `order` SET order_status = ?, payment_mode = ? WHERE id = ?");
//   $stmt->bind_param("isi", $newStatus, $newMode, $orderId);
//   $stmt->execute();

//   $_SESSION['message'] = "Order updated successfully!";
//   header("Location: invoice.php?id=" . $orderId);
//   exit();
// }
$sql = "SELECT o.*, 
        bd.fname AS bf_name, bd.lname AS bl_name, bd.address AS b_address, bd.city AS b_city, bd.pincode AS b_pincode, bd.email AS b_email, bd.phone_no AS b_phone,
        sd.fname AS sf_name, sd.lname AS sl_name, sd.address AS s_address, sd.city AS s_city, sd.pincode AS s_pincode, sd.email AS s_email, sd.phone_no AS s_phone
        FROM `order` o
        LEFT JOIN billing_details bd ON o.billing_details_id = bd.id
        LEFT JOIN shipping_details sd ON o.shipping_details_id = sd.id
        WHERE o.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$orderData = $stmt->get_result()->fetch_assoc();
$sql_items = "SELECT oi.*, p.productName, p.description, p.SP AS price
              FROM orderitems oi
              LEFT JOIN product p ON oi.productId = p.id
              WHERE oi.orderId = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $orderId);
$stmt_items->execute();
$orderItems = $stmt_items->get_result()->fetch_all(MYSQLI_ASSOC);
$subtotal = array_sum(array_column($orderItems, 'subTotal'));
$discount = 0;
$total = $subtotal - $discount;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_shipment'])) {
  $shipping_mode = $_POST['shipping_mode'] ?? '';
  $charges = floatval($_POST['charges'] ?? 0);
  $total_charges = floatval($_POST['total_charges'] ?? 0);
  $date_of_shipping = $_POST['date_of_shipping'] ?? date('Y-m-d');
  $newMode = $_POST['shipping_mode'] ?? null;
  $img_url = '';
  if (isset($_FILES['courier_image']) && $_FILES['courier_image']['error'] == 0) {
    $ext = pathinfo($_FILES['courier_image']['name'], PATHINFO_EXTENSION);
    $target = './UploadImg/ShippingImg/' . $orderId . '_' . time() . '.' . $ext;

    // Only one move call with check
    if (!move_uploaded_file($_FILES['courier_image']['tmp_name'], $target)) {
      die("Failed to move uploaded file.");
    }

    $img_url = $target;
  } else if (!empty($orderItems)) {
    $product_id = $orderItems[0]['productId'];
    $prod_stmt = $conn->prepare("SELECT thumbnail FROM product WHERE id = ?");
    $prod_stmt->bind_param('i', $product_id);
    $prod_stmt->execute();
    $imgRow = $prod_stmt->get_result()->fetch_assoc();
    $img_url = $imgRow['thumbnail'] ?? '';
    $prod_stmt->close();
  }
  $selectedStatus = isset($_POST['status']) ? (int) $_POST['status'] : null;

  if ($selectedStatus === 2 && $newMode !== null) {
    $stmt_order = $conn->prepare("UPDATE `order` SET order_status = ?, payment_mode = ? WHERE id = ?");
    $stmt_order->bind_param("isi", $selectedStatus, $newMode, $orderId);
    if (!$stmt_order->execute()) {
      die("Order update failed: " . $stmt_order->error);
    }
    $stmt_order->close();
  }
  $stmt_check = $conn->prepare("SELECT id FROM shipped_orders WHERE orderId = ? LIMIT 1");
  $stmt_check->bind_param("i", $orderId);
  $stmt_check->execute();
  $res = $stmt_check->get_result();
  if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $shipped_order_id = $row['id'];
    $stmt_update = $conn->prepare("UPDATE shipped_orders SET image_url = ?, charges = ?, total_charges = ?, shipping_mode = ?, date_of_shipping = ? WHERE id = ?");
    $stmt_update->bind_param("sdsssi", $img_url, $charges, $total_charges, $shipping_mode, $date_of_shipping, $shipped_order_id);
    if (!$stmt_update->execute()) {
      die("Update failed: " . $stmt_update->error);
    }
    $stmt_update->close();
  } else {
    $stmt_insert = $conn->prepare("INSERT INTO shipped_orders (orderId, image_url, charges, total_charges, shipping_mode, date_of_shipping) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt_insert->bind_param("isdsss", $orderId, $img_url, $charges, $total_charges, $shipping_mode, $date_of_shipping);
    if (!$stmt_insert->execute()) {
      die("Insert failed: " . $stmt_insert->error);
    }
    $stmt_insert->close();
  }
  $stmt_check->close();
  $_SESSION['message'] = "Shipment saved or updated.";
  header("Location: Shipped-orders.php");
  exit;
}


?>
<!doctype html>
<html lang="en">

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
  <!-- DataTables CSS + Bootstrap 4 (CDN only) -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

  <!-- jQuery (required for DataTables) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- DataTables + Bootstrap 4 (CDN only) -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

</head>
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper">
    <!--begin::Header-->

    <!--end::Header-->
    <!--begin::Sidebar-->

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
              <h3 class="mb-0">Order Invoice</h3>
            </div>
            <!-- <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item">Logout</a></li>

              </ol>
            </div> -->
          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
      </div>
      <!--end::App Content Header-->
      <!--begin::App Content-->


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
          series: [{
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
          series: [{
            data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
          },],
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
          series: [{
            data: [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
          },],
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
          series: [{
            data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
          },],
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

      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Invoice</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
      </head>

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
        <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
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


      <body>

        <section class="content" id="content">
          <div class="container-fluid">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Invoice</h3>
                <div class="card-tools">
                  <small class="text-muted">Date: 15/07/2025</small>
                </div>
              </div>

              <div class="card-body">
                <!-- Info Row -->
                <div class="row mb-3">
                  <div class="col-sm-3">
                    <strong>From</strong>
                    <address>
                      MyCompany Pvt Ltd<br>
                      123 MG Road<br>
                      Bengaluru, KA 560001<br>
                      Phone: +91 9876543210<br>
                      Email: support@mycompany.com
                    </address>
                  </div>
                  <div class="col-sm-3">
                    <strong>Billing Details:</strong>
                    <address>
                      <?= htmlspecialchars($orderData['bf_name'] . " " . $orderData['bl_name']); ?><br>
                      <?= htmlspecialchars($orderData['b_address']); ?><br>
                      <?= htmlspecialchars($orderData['b_city']); ?> -
                      <?= htmlspecialchars($orderData['b_pincode']); ?><br>
                      Phone: <?= htmlspecialchars($orderData['b_phone']); ?><br>
                      Email: <?= htmlspecialchars($orderData['b_email']); ?>
                    </address>
                  </div>
                  <div class="col-sm-3">
                    <strong>Shipping Details:</strong>
                    <address>
                      <?= htmlspecialchars($orderData['sf_name'] . " " . $orderData['sl_name']); ?><br>
                      <?= htmlspecialchars($orderData['s_address']); ?><br>
                      <?= htmlspecialchars($orderData['s_city']); ?> -
                      <?= htmlspecialchars($orderData['s_pincode']); ?><br>
                      Phone: <?= htmlspecialchars($orderData['s_phone']); ?><br>
                      Email: <?= htmlspecialchars($orderData['s_email']); ?>
                    </address>
                  </div>
                  <div class="col-sm-3">
                    <strong>Invoice Details</strong><br>
                    Invoice #: <b>INV-<?php echo htmlspecialchars($orderData['id']); ?></b><br>
                    Order ID: <b>ORD-<?php echo htmlspecialchars($orderData['id']); ?></b><br>,<i class="fa fa-sliders"
                      aria-hidden="true"></i>
                  </div>
                </div>

                <!-- Table Row -->
                <div class="row">
                  <div class="col-12 table-responsive">
                    <table class="table table-bordered">
                      <thead class="thead-light">
                        <tr>
                          <th>SI NO</th>

                          <th>Product</th>
                          <th>Description</th>
                          <th>price</th>
                          <th>Qty</th>
                          <th>Subtotal</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $i = 1;
                        foreach ($orderItems as $item): ?>
                          <tr>
                            <td><?= $i++; ?></td>
                            <td><?= htmlspecialchars($item['productName']); ?></td>
                            <td><?= htmlspecialchars($item['description']); ?></td>
                            <td>₹<?= number_format($item['price'], 2); ?></td>
                            <td><?= $item['quantity']; ?></td>
                            <td>₹<?= number_format($item['subTotal'], 2); ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- Summary Row -->
                <div class="row">
                  <div class="col-md-6">
                    <div class="container-fluid">
                      <form method="POST" enctype="multipart/form-data">
                        <?php
                        $statusMap = [
                          0 => 'Pending',
                          1 => 'Processing',
                          2 => 'Shipping',
                        ];
                        ?>
                        <div class="row p-0 m-0">
                          <div class="d-flex flex-md-row flex-column justify-md-content-center align-md-items-center">
                            <span class="col-md-2 py-4 m-0"><b>Status :</b></span>
                            <select class="col-md-4 bg-light text-dark rounded px-0 py-3" name="status" id="status">
                              <?php foreach ($statusMap as $code => $label): ?>
                                <option value="<?= $code ?>" <?= ($orderData['order_status'] == $code) ? 'selected' : ''; ?>>
                                  <?= $label ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="container col-md-9 my-2" id="shipped_detailes" <?= $orderData['order_status'] == 2 ? '' : 'class="d-none"' ?>>
                          <h3>Shippment Details</h3>
                          <div class="row">
                            <div class=" form-group">
                              <label for="courier_image" class="form-label fw-bold ">Provide a courier picture</label>
                              <input type="file" name="courier_image" id="courier_image" class="form-select"
                                accept="image/*">
                            </div>
                          </div>
                          <div class="row my-2">
                            <div class="form-group visually-hidden">
                              <label for="total_charges" class="form-label fw-bold my-2">Total Charges:</label>
                              <input type="text" name="total_charges" id="total_charges"
                                value="<?= htmlspecialchars($total) ?>" class="form-control">
                            </div>
                          </div>
                          <div class="row my-2">
                            <div class="form-group">
                              <label for="charges" class="form-label fw-bold my-2">delivery charges:</label>
                              <input type="text" id="charges" name="charges" value="80" class="form-control">
                            </div>
                          </div>
                          <div class="row my-2">
                            <div class="form-group">
                              <label for="shipping_mode" class="form-label fw-bold">Mode of Delivery</label>
                              <select name="shipping_mode" class="bg-light text-dark col-md-6">
                                <option value="Courier" <?= (strtolower($orderData['payment_mode']) == 'courier') ? 'selected' : '' ?>>Courier</option>
                                <option value="Post" <?= (strtolower($orderData['payment_mode']) == 'post') ? 'selected' : '' ?>>Post</option>
                              </select>
                            </div>
                          </div>

                          <div class="row my-2">
                            <div class="mb-3 input-group gap-2">
                              <label for="date_of_shipping">Date of Shipping</label>
                              <input type="date" name="date_of_shipping" id="date_of_shipping" class="form-date "
                                value="<?= date('Y-m-d') ?>">
                            </div>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php
            $subtotal = array_sum(array_column($orderItems, 'subTotal'));
            $discount = 0;
            $total = $subtotal;

            if (!empty($orderData['applied_coupon_id']) && $orderData['applied_coupon_id'] > 0) {

              $query = "SELECT coupon_code,discount_type,amount FROM coupon WHERE id=?";
              $stmt_coupon = $conn->prepare($query);
              $stmt_coupon->bind_param("i", $orderData['applied_coupon_id']);
              $stmt_coupon->execute();
              $coupon = $stmt_coupon->get_result()->fetch_assoc();

              if ($coupon) {
                if (strtolower($coupon['discount_type']) === 'percentage') {
                  $discount = ($subtotal * $coupon['amount']) / 100;
                } else {
                  $discount = $coupon['amount'];
                }
              }
            }
            $total = $subtotal - $discount
              ?>
            <div class="col-md-6">
              <p class="lead">Amount Due</p>
              <table class="table">
                <tr>
                  <th>Subtotal:</th>
                  <td>₹<?= number_format($subtotal, 2); ?></td>
                </tr>
                <tr>
                  <th>Discount:</th>
                  <td>- ₹<?= number_format($discount, 2); ?></td>
                </tr>
                <tr>
                  <th>Total:</th>
                  <td><strong>₹<?= number_format($total, 2); ?></strong>
                    <label for="total_charges" class="visually-hidden">Additional Charges</label>
                    <input type="hidden" name="total_charges" id="total_charges"
                      value="<?= htmlspecialchars($total) ?>">
              </table>
            </div>

          </div>
  </div>

  <!-- Footer -->
  <div class="card-footer text-end">
    <button class="btn btn-primary" type="submit" name="save_shipment">
      <i class="bi bi-save"></i>update
    </button>
    <button class="btn btn-secondary" type="button" onclick="window.print();">
      <i class="bi bi-printer"></i> Print
    </button>
      </div>

  </form>
  </div>
  </div>
  </section>


  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
    crossorigin="anonymous"></script>
  <script>
    function printSection(elementId) {
      // 1. Get the HTML content of the specific section
      const printContent = document.getElementById(elementId).innerHTML;

      // 2. Open a new browser window
      const printWindow = window.open('', '_blank', 'height=600,width=800');

      // 3. Write the content to the new window's document
      printWindow.document.write(`
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>AdminLTE v4 | Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="color-scheme" content="light dark" />
  <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
  <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
  <meta name="title" content="AdminLTE v4 | Dashboard" />
  <meta name="author" content="ColorlibHQ" />
  <meta name="description"
    content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance." />
  <meta name="keywords"
    content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant" />
  <meta name="supported-color-schemes" content="light dark" />
  <link rel="preload" href="../../css/adminlte.css" as="style" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
    onload="this.media='all'" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
    crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    crossorigin="anonymous" />
  <link rel="stylesheet" href="../../css/adminlte.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
    integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
    integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

 
  </head>

  <body>
    ${printContent}
  </body>

  </html>
  `);

      printWindow.document.close();
      printWindow.focus();
      printWindow.print();
    }
    let status = document.getElementById("status");
    let shipped_detailes = document.getElementById("shipped_detailes");
    if (parseInt(status.value) === 2) {
      shipped_detailes.classList.remove('d-none')
    } else {
      shipped_detailes.classList.add('d-none')
    }
    status.addEventListener('change', () => {
      console.log(status.value)
      if (parseInt(status.value) === 2) {
        shipped_detailes.classList.remove('d-none')
      } else {
        shipped_detailes.classList.add('d-none')
      }
    })
  </script>

</body>


</html>