# ⚠️ CRITICAL: File Upload Issue on Vercel

## Problem
Vercel's serverless functions have a **read-only filesystem**. This means:
- ❌ `move_uploaded_file()` will **FAIL**
- ❌ Local file storage (`./UploadImg/`) will **NOT WORK**
- ❌ Files uploaded will be **LOST** after function execution

## Affected Files
The following files contain file upload code that needs to be updated:

1. **admin/Products.php** (lines 36-72)
   - Product thumbnail uploads
   - Product gallery image uploads

2. **admin/updateProduct.php** (lines 47-75)
   - Product thumbnail updates
   - Product gallery updates

3. **admin/Invoice.php** (lines 47-56)
   - Shipping/courier image uploads

## Solutions

### Option 1: Use Vercel Blob Storage (Recommended)
Vercel provides Blob Storage for file uploads. Update your upload code to use it.

### Option 2: Use Cloudinary (Easy Integration)
Cloudinary provides a free tier and easy PHP SDK.

### Option 3: Use AWS S3
More complex but highly scalable.

### Option 4: Use Cloudflare R2
S3-compatible, cost-effective.

## Quick Fix Example (Cloudinary)

Install Cloudinary PHP SDK:
```bash
composer require cloudinary/cloudinary_php
```

Update upload code in `admin/Products.php`:
```php
<?php
require_once 'vendor/autoload.php';
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

// Configure Cloudinary
Configuration::instance([
    'cloud' => [
        'cloud_name' => getenv('CLOUDINARY_CLOUD_NAME'),
        'api_key' => getenv('CLOUDINARY_API_KEY'),
        'api_secret' => getenv('CLOUDINARY_API_SECRET')
    ],
    'url' => [
        'secure' => true
    ]
]);

$cloudinary = new Cloudinary();

// Instead of move_uploaded_file
if (!empty($_FILES['mainPhoto']['name'])) {
    $uploadResult = $cloudinary->uploadApi()->upload(
        $_FILES['mainPhoto']['tmp_name'],
        [
            'folder' => 'products',
            'public_id' => 'product_' . time()
        ]
    );
    $thumbnail = $uploadResult['secure_url'];
}
?>
```

## Environment Variables Needed

Add these to your Vercel project:
```
CLOUDINARY_CLOUD_NAME=your-cloud-name
CLOUDINARY_API_KEY=your-api-key
CLOUDINARY_API_SECRET=your-api-secret
```

## Temporary Workaround

If you need to deploy immediately without fixing uploads:
1. Comment out file upload functionality
2. Use placeholder images
3. Update the code later to use cloud storage

## Status
🔴 **CRITICAL** - File uploads will not work until this is fixed.

