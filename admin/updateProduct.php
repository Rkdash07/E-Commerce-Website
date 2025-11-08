<?php
include 'connection.php';
session_start();
if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
      header("Location: login.php");
      exit;
  }

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("Location: Products.php");
  exit;
}
$product_id = (int) $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM Product WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$product) {
  header("Location: updateProduct.php?message=Product+not+found");
  exit;
}

$images = [];
$res = $conn->query("SELECT * FROM ProductImg WHERE product_id = $product_id");
while ($img = $res->fetch_assoc()) {
  $images[] = $img;
}

$categories = $conn->query("SELECT * FROM Category");
$subcategories = $conn->query("SELECT * FROM SubCategory");
$stores = $conn->query("SELECT * FROM Stores");


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
  $productName = $_POST['productName'];
  $description= $_POST['description'];
  $category_id = $_POST['category_id'];
  $sub_category_id = $_POST['sub_category_id'];
  $store_id = $_POST['store_id'];
  $MRP = $_POST['MRP'];
  $SP = $_POST['SP'];
  $status = isset($_POST['status']) ?(int)$_POST['status'] : 0;
  $metaTitle = $_POST['Meta_title'];
  $metaDescription = $_POST['Meta_description'];
  $thumbnail = $product['thumbnail'];
  if (!empty($_FILES['mainPhoto']['name'])) {
    if ($product['thumbnail'] && file_exists($product['thumbnail'])) {
      unlink($product['thumbnail']);
    }
    $filename = time() . '_' . basename($_FILES['mainPhoto']['name']);
    $target = './UploadImg/ProductImg/' . $filename;
    if (move_uploaded_file($_FILES['mainPhoto']['tmp_name'], $target)) {
      $thumbnail = $target;
    }
  }

 $stmt = $conn->prepare("UPDATE Product SET productName = ?, description = ?, category_id = ?, sub_category_id = ?, store_id = ?, MRP = ?, SP = ?, status = ?, thumbnail = ?, Meta_title = ?, Meta_description = ? WHERE id = ?");
 $stmt->bind_param("ssiiiiddsssi", $productName, $description, $category_id, $sub_category_id, $store_id, $MRP, $SP, $status, $thumbnail, $metaTitle, $metaDescription, $product_id);
  $stmt->execute();
  if (!empty($_FILES['galleryPhotos']['name'][0])) {
    foreach ($_FILES['galleryPhotos']['tmp_name'] as $i => $tmpName) {
      if ($_FILES['galleryPhotos']['error'][$i] === 0) {
        $fname = time() . "_$i_" . basename($_FILES['galleryPhotos']['name'][$i]);
        $dest = './UploadImg/ProductImg/MultiImg/' . $fname;
        if (move_uploaded_file($tmpName, $dest)) {
          $stmtImg = $conn->prepare("INSERT INTO ProductImg (product_id, image_path) VALUES (?, ?)");
          $stmtImg->bind_param("is", $product_id, $dest);
          $stmtImg->execute();
          $stmtImg->close();

        }
      }
    }
  }

  header("Location: Products.php?id=$product_id&message=Updated+successfully");
  exit;
}

if (isset($_POST['delete_image'], $_POST['image_id'], $_POST['product_id'])) {

  $image_id = (int) $_POST['image_id'];
  $product_id = (int) $_POST['product_id'];
  $result = $conn->query("SELECT image_path FROM ProductImg WHERE id = $image_id LIMIT 1");
  $image = $result->fetch_assoc();

  if ($image && file_exists($image['image_path'])) {
    unlink($image['image_path']);
  }

  $conn->query("DELETE FROM ProductImg WHERE id = $image_id");

  // Fetch all additional images for the product
$images = [];
$res = $conn->query("SELECT * FROM ProductImg WHERE product_id = $product_id");
while ($img = $res->fetch_assoc()) {
    $images[] = $img;
}

  header("Location: updateProduct.php?id=$product_id&message=Image+deleted+successfully");
  exit;
}




if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("Location: Products.php");
  exit;
}


?>


<!DOCTYPE html>
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
  <link rel="preload" href="../../css/adminlte.css" as="style" />
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
    .image {
      object-fit: contain !important;
    }

    .card-image {
      position: relative;
      object-fit: contain;

    }

    .action {
      position: absolute;
      top: 0;
      right: 0;
    }

    .add-img {
      height: 300px !important;
      background: rgba(0, 0, 0, 0.3);
      position: relative;
    }

    .add-btn {
      position: relative;

    }
  </style>
</head>

<body>

  <?php if (isset($_GET['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
      <?= htmlspecialchars($_GET['message']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>


  <div class="container py-5">
    <section id="product-details">
      <form method="POST" enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3 ">
              <?php if ($product['thumbnail']): ?>
                <img src="<?= $product['thumbnail'] ?>" alt="Product Image" class="img-fluid image mb-2">
              <?php endif; ?>
              <input type="file" name="mainPhoto" class="form-control mb-2" accept="image/*">
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Product Name</label>
            <input type="text" class="form-control mb-2" name="productName" value="<?= $product['productName'] ?>"
              required>

            <label class="form-label">MRP (₹)</label>
            <input type="text" class="form-control mb-2" name="MRP" value="<?= $product['MRP'] ?>">

            <label class="form-label">Selling Price (₹)</label>
            <input type="text" class="form-control mb-2" name="SP" value="<?= $product['SP'] ?>">

            <label class="form-label">Category</label>
            <select name="category_id" class="form-select mb-2">
              <?php while ($row = $categories->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" <?= $row['id'] == $product['category_id'] ? 'selected' : '' ?>>
                  <?= $row['category'] ?>
                </option>
              <?php endwhile; ?>
            </select>

            <label class="form-label">Sub-Category</label>
            <select name="sub_category_id" class="form-select mb-2">
              <?php while ($row = $subcategories->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" <?= $row['id'] == $product['sub_category_id'] ? 'selected' : '' ?>>
                  <?= $row['sub_category'] ?>
                </option>
              <?php endwhile; ?>
            </select>

            <label class="form-label">Store</label>
            <select name="store_id" class="form-select mb-2">
              <?php while ($row = $stores->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" <?= $row['id'] == $product['store_id'] ? 'selected' : '' ?>>
                  <?= $row['storeName'] ?>
                </option>
              <?php endwhile; ?>
            </select>

            <div class="mb-3 position-relative">
              <label for="description">Description</label>

              <!-- Tooltip trigger (textarea) -->
              <textarea class="form-control" id="product-description" name="description" data-bs-toggle="tooltip"
                data-bs-placement="top" title="Click to open editor" style="height: 100px;"
                value="<?= $product['description'] ?>"></textarea>

              <!-- Quill container (hidden by default) -->
              <div id="quill-container" class="mt-2" style="display: none;">
                <div id="quill-editor" style="height: 200px;"></div>
              </div>
            </div>
             <label class="form-label">Meta Title</label>
            <input type="text" class="form-control mb-2" name="Meta_title" value="<?= $product['Meta_title'] ?>"
              required>
              <div class="mb-3 position-relative">
              <label for="Meta_description">Meta Description</label>

              <!-- Tooltip trigger (textarea) -->
              <textarea class="form-control" id="meta-description" name="Meta_description" data-bs-toggle="tooltip"
                data-bs-placement="top" title="Click to open editor" style="height: 100px;"
                value="<?= htmlspecialchars($product['Meta_description']) ?>"></textarea>
            </div>
            <label class="form-label">Status</label><br>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="status" value="1" <?= $product['status'] == 1 ? 'checked' : '' ?>>
              <label class="form-check-label">Active</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="status" value="0" <?= $product['status'] == 0 ? 'checked' : '' ?>>
              <label class="form-check-label">Inactive</label>
            </div>
          </div>
        </div>

        <div class="col-md-6">
        </div>

        <div class="mt-4 text-end">
          <button name="update_product" class="btn btn-success w-25">Update Product</button>
        </div>
      </form>



    </section>
    <section id="product-images" class="mb-4">
      <h1>Product images</h1>
      <div class="row">
        <div class="col-md-8">
          <div class="mb-3">
            <label class="form-label">Upload Additional Product Photos</label>
            <input type="file" name="galleryPhotos[]" class="form-control" multiple accept="image/*">
          </div>
          <div class="row mt-3">
            <?php foreach ($images as $img): ?>
              <div class="col-md-4 mb-3">
                <div class="card">
                  <img src="<?= $img['image_path'] ?>" class="card-img-top img-thumbnail"
                    style="height: 200px; object-fit: cover;">
                  <div class="card-body p-2 text-center">
                    <form method="POST" action="updateProduct.php" class="d-inline">
                      <input type="hidden" name="image_id" value="<?= $img['id'] ?>">
                      <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                      <button type="submit" class="btn btn-sm btn-danger" name="delete_image"
                        onclick="return confirm('Delete this image?')">Delete</button>
                    </form>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <div class="modal fade" id="addGalleryImageModal" tabindex="-1" aria-labelledby="addGalleryImageModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addGalleryImageModalLabel">Add Gallery Image</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="file" name="galleryPhotos[]" class="form-control" accept="image/*" required>
        </div>
        <div class="modal-footer">
          <button type="submit" name="update_product" class="btn btn-primary">Add Image</button>
        </div>
      </div>
    </form>
  </div>
</div>


  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
  <script src="./js/adminlte.js"></script>

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
    tooltipTrigger.value = "<?= $product['description'] ?>"
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

</html>