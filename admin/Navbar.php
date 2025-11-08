<?php

include 'connection.php';
session_start();
if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
      header("Location: login.php");
      exit;
  }
$message = "";
$error = "";


include 'connection.php';

$message = "";
$error = "";

// Handle add single category submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $categoryId = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;

    // Validate category ID
    if ($categoryId <= 0) {
        $error = "Please select a valid category.";
    } else {
        // Count existing entries in navbar table
        $countRes = $conn->query("SELECT COUNT(*) as total FROM navbar");
        $countRow = $countRes->fetch_assoc();
        $currentCount = (int) $countRow['total'];

        if ($currentCount >= 4) {
            $error = "You cannot add more than 4 categories to the navbar.";
        } else {
            // Check if category_id already added (optional)
            $existsRes = $conn->prepare("SELECT 1 FROM navbar WHERE category_id = ?");
            $existsRes->bind_param("i", $categoryId);
            $existsRes->execute();
            $existsRes->store_result();

            if ($existsRes->num_rows > 0) {
                $error = "This category is already added to navbar.";
            } else {
                // Insert new category to navbar
                $insertStmt = $conn->prepare("INSERT INTO navbar (category_id) VALUES (?)");
                $insertStmt->bind_param("i", $categoryId);
                $insertStmt->execute();
                $insertStmt->close();

                $message = "Category added to navbar successfully!";
            }
            $existsRes->close();
        }
    }
}

if(isset($_GET['delete']) && is_numeric($_GET['delete'])) {
  $deleteId = (int)$_GET['delete'];
  $delStmt = $conn->prepare("DELETE FROM navbar WHERE id = ?");
  $delStmt->bind_param("i", $deleteId);
  if($delStmt->execute()) {
    $message = "Navigation deleted successfully!";
  } else {
    $error = "Error deleting navigation.";
  }
  $delStmt->close();
}


$categories = [];
$query = "SELECT * FROM category";
$result = $conn->query($query);

while($row= $result->fetch_assoc()){
  $categories[] = $row;
}

$nav = [];
$navQuery = "SELECT category_id FROM navbar";
$navResult = $conn->query($navQuery);

while($row = $navResult->fetch_assoc()) {
  $nav[] = (int)$row['category_id'];
}

// Fetch navigation data for display in the table
$navigations = [];
$navTableQuery = "SELECT navbar.id, category.category FROM navbar JOIN category ON navbar.category_id = category.id";
$navTableResult = $conn->query($navTableQuery);
while ($row = $navTableResult->fetch_assoc()) {
  $navigations[] = $row;
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
  <!-- DataTables CSS + Bootstrap 4 (CDN only) -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

  <!-- jQuery (required for DataTables) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- DataTables + Bootstrap 4 (CDN only) -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>


  <style>
    .table-heading {
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-block: 1rem;
    }

    .input-box {
      border: 1px solid black;
      height: fit-content;
      background-color: white;
    }

    .input-box input {
      background: white;
      border: none;
      outline: none;
      color: black;
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
              <a href="index.html" class="nav-link">
                <i class="nav-icon bi bi-speedometer"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link active">
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
                  <a href="./Category.php" class="nav-link ">
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
                  <a href="#" class="nav-link">
                    <i class="nav-icon bi bi-speedometer"></i>
                    <p>
                      Shipped
                    </p>
                  </a>
                </li>
                <li class="nav-item ">
                  <a href="./completed-orders.html" class="nav-link">
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
              <h3 class="mb-0">Navbar</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Navbar</li>
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

          <!--end::Row-->
          <!--begin::Horizontal Form-->
          <!-- Button to trigger modal -->
           
                  <?php if (isset($_GET['message'])): ?>
  <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
    <?= htmlspecialchars($_GET['message']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>
          <button type="button" class="btn btn-primary my-2" data-bs-toggle="modal" data-bs-target="#addProductModal">
            Add Navigation
          </button>

          <!-- Modal -->
          <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content card-outline">

                <!-- Modal Header -->
                <div class="modal-header bg-primary">
                  <h5 class="modal-title text-light fw-bold fs-3 w-100 text-center" id="addProductModalLabel">
                    Add a Navigation
                  </h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                </div>

                <!-- Modal Form -->
                <form method="POST" enctype="multipart/form-data">
                   <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add category</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                  <div class="modal-body">
                    <div class="row justify-content-center">
                      <div class="mb-3">
                        <label class="mt-2">Category</label>
                        <select name="category_id" id="categorySelect" class="form-select" required>
                          <option value="">-- Select --</option>
                          <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category']) ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <!-- Modal Footer -->
                  <div class="modal-footer">
                    <button type="submit" name="submit" class="btn btn-primary w-100">Add</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
               <?php if (!empty($error)): ?>
                  <div class="alert alert-danger mt-2"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

              <?php if (!empty($message)): ?>
                <div class="alert alert-success mt-2"><?= htmlspecialchars($message) ?></div>
              <?php endif; ?>

          <!-- end::Horizontal Form -->
          <!--end::Container-->

          <div class="modal fade" id="updateProductModal" tabindex="-1" aria-labelledby="updateProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content">

                <div class="modal-header bg-warning text-white">
                  <h5 class="modal-title w-100 text-center fw-bold" id="updateProductModalLabel">
                    Update Store</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" enctype="multipart/form-data">
                  <div class="modal-body">
                    <div class="row">
                      <div class="mb-3">
                        <label for="navigationName" class="form-label"> Name</label>
                        <input type="text" name="navigationName" id="navigationName" class="form-control">
                      </div>


                    </div>
                  </div>

                  <div class="modal-footer">
                    <button class="btn btn-warning w-100" type="submit">Update</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="container">
            <div class="row">
              <div class="card rounded p-2">
                <h2 class="table-heading">Navigations</h2>
                <div class="table-responsive ">
                  <table id="example1" class="table border table-bordered table-striped">

                    <thead>
                      <tr>
                        <th>id</th>
                        <th> Name</th>
                        <th>Action</th>


                      </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                      <?php foreach ($navigations as $nav): ?>
                      <tr>
                        <td><?= $nav['id'] ?></td>
                        <td><?= $nav['category'] ?></td>
                        <td>
                            <a href="?delete=<?= $nav['id'] ?>" onClick="return confirm('Do you want to delete this category?');" class="btn btn-danger">Delete</a>

                        </td>
                      </tr>
                      <?php endforeach; ?>
                      <!-- <tr>
                        <td>2.</td>
                        <td>Link 2</td>
                        <td>
                            <button class="btn btn-danger">delete</button>
                        
                        </td>
                      </tr> <tr>
                        <td>3.</td>
                        <td>Link 3</td>
                        <td>
                            <button class="btn btn-danger">delete</button>
                        
                        </td>
                      </tr> <tr>
                        <td>4.</td>
                        <td>Link 4</td>
                        <td>
                            <button class="btn btn-danger">delete</button>
                        
                        </td>
                      </tr> -->

                      <!-- More rows -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
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
</body>
<!--end::Body-->

</html>