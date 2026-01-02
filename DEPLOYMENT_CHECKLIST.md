# Vercel Deployment Checklist

Use this checklist to ensure your deployment is successful.

## Pre-Deployment Checklist

### ✅ Configuration Files
- [x] `vercel.json` created and configured
- [x] `package.json` created
- [x] `.vercelignore` created
- [x] Database connection files updated to use environment variables
- [x] Email configuration updated to use environment variables

### ⚠️ Critical Issues to Address

#### 1. File Uploads (CRITICAL)
- [ ] **File uploads will NOT work on Vercel** - Read `FILE_UPLOAD_WARNING.md`
- [ ] Choose a cloud storage solution (Cloudinary, AWS S3, Vercel Blob)
- [ ] Update `admin/Products.php` to use cloud storage
- [ ] Update `admin/updateProduct.php` to use cloud storage
- [ ] Update `admin/Invoice.php` to use cloud storage

#### 2. Database Setup
- [ ] Set up external MySQL database (PlanetScale, Railway, AWS RDS, etc.)
- [ ] Import database schema from `databases/` folder
- [ ] Test database connection
- [ ] Configure database firewall to allow Vercel IPs

#### 3. Environment Variables
Set these in Vercel Dashboard → Settings → Environment Variables:

**Database:**
- [ ] `DB_HOST` - Your database host
- [ ] `DB_USER` - Database username
- [ ] `DB_PASSWORD` - Database password
- [ ] `DB_NAME` - Database name (e_commerce_db)
- [ ] `DB_PORT` - Database port (usually 3306)

**Email (PHPMailer):**
- [ ] `SMTP_HOST` - SMTP server (e.g., smtp.gmail.com)
- [ ] `SMTP_PORT` - SMTP port (usually 587)
- [ ] `SMTP_USER` - SMTP username/email
- [ ] `SMTP_PASSWORD` - SMTP password or app password
- [ ] `SMTP_FROM_EMAIL` - From email address
- [ ] `SMTP_FROM_NAME` - From name

**Cloud Storage (if using):**
- [ ] `CLOUDINARY_CLOUD_NAME` (if using Cloudinary)
- [ ] `CLOUDINARY_API_KEY` (if using Cloudinary)
- [ ] `CLOUDINARY_API_SECRET` (if using Cloudinary)

### 4. Security
- [ ] Remove hardcoded credentials from code (already done for database)
- [ ] Update email credentials to use environment variables (already done)
- [ ] Review `admin/encrypt.php` - consider using environment variables
- [ ] Ensure sensitive files are in `.vercelignore`

### 5. Testing
- [ ] Test database connection locally with environment variables
- [ ] Test email functionality locally
- [ ] Verify all PHP includes work with relative paths
- [ ] Test admin login functionality
- [ ] Test client-facing pages

## Deployment Steps

1. **Push to Git Repository**
   ```bash
   git add .
   git commit -m "Prepare for Vercel deployment"
   git push origin main
   ```

2. **Connect to Vercel**
   - Go to [vercel.com](https://vercel.com)
   - Click "Add New Project"
   - Import your Git repository

3. **Configure Project**
   - Vercel will auto-detect PHP
   - Add all environment variables
   - Review build settings

4. **Deploy**
   - Click "Deploy"
   - Wait for build to complete
   - Check deployment logs for errors

5. **Post-Deployment**
   - [ ] Test homepage loads correctly
   - [ ] Test admin panel access
   - [ ] Test database queries work
   - [ ] Test email sending (if configured)
   - [ ] Check Vercel function logs for errors

## Common Issues & Solutions

### Issue: Database Connection Failed
**Solution:**
- Verify environment variables are set correctly
- Check database allows connections from Vercel IPs
- Ensure database is publicly accessible or use connection pooling

### Issue: 404 Errors on Routes
**Solution:**
- Check `vercel.json` routing configuration
- Verify file paths are correct
- Check that PHP files are in correct directories

### Issue: File Uploads Fail
**Solution:**
- This is expected - Vercel has read-only filesystem
- Implement cloud storage solution (see `FILE_UPLOAD_WARNING.md`)

### Issue: PHP Errors
**Solution:**
- Check Vercel function logs
- Verify PHP version compatibility (8.2)
- Check that all includes use correct relative paths

### Issue: Composer Dependencies Not Found
**Solution:**
- Ensure `vendor/` directory is committed (or use Composer in build)
- Check that `composer.json` is in the correct location
- Consider using Vercel's build command to install dependencies

## Post-Deployment Monitoring

- [ ] Set up error monitoring (Sentry, etc.)
- [ ] Monitor Vercel function logs
- [ ] Set up database monitoring
- [ ] Configure alerts for critical errors

## Next Steps After Deployment

1. Fix file upload functionality (use cloud storage)
2. Implement proper session management (if needed)
3. Set up CDN for static assets
4. Configure caching strategies
5. Set up automated backups

## Support Resources

- [Vercel Documentation](https://vercel.com/docs)
- [Vercel PHP Runtime](https://vercel.com/docs/concepts/functions/serverless-functions/runtimes/php)
- [Vercel Community](https://github.com/vercel/vercel/discussions)

