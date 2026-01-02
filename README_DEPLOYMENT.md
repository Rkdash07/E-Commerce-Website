# Vercel Deployment Guide

This guide will help you deploy your E-Commerce PHP application to Vercel.

## Prerequisites

1. A Vercel account (sign up at [vercel.com](https://vercel.com))
2. A MySQL database (use services like PlanetScale, Railway, or AWS RDS)
3. Vercel CLI installed (optional, for local testing)

## Step 1: Database Setup

Since Vercel doesn't provide MySQL databases, you need to set up an external database:

### Recommended Database Services:
- **PlanetScale** (MySQL-compatible, serverless)
- **Railway** (MySQL)
- **AWS RDS** (MySQL)
- **DigitalOcean** (Managed MySQL)

### Import Your Database:
1. Export your local database using phpMyAdmin or MySQL command line
2. Import the SQL files from the `databases/` folder to your cloud database

## Step 2: Environment Variables

Set the following environment variables in your Vercel project dashboard:

1. Go to your project settings → Environment Variables
2. Add the following variables:

```
DB_HOST=your-database-host
DB_USER=your-database-username
DB_PASSWORD=your-database-password
DB_NAME=e_commerce_db
DB_PORT=3306
```

### Email Configuration (Optional):
If you're using PHPMailer, also add:

```
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=your-email@gmail.com
SMTP_PASSWORD=your-app-password
SMTP_FROM_EMAIL=noreply@yourdomain.com
SMTP_FROM_NAME=E-Commerce Store
```

## Step 3: File Upload Configuration

**IMPORTANT:** Vercel's serverless functions have a read-only filesystem. File uploads won't persist.

### Solutions:
1. **Use Cloud Storage Services:**
   - AWS S3
   - Cloudinary
   - Google Cloud Storage
   - Vercel Blob Storage

2. **Update Upload Code:**
   You'll need to modify your file upload code to use one of these services instead of local file storage.

## Step 4: Deploy to Vercel

### Option A: Using Vercel Dashboard
1. Go to [vercel.com](https://vercel.com)
2. Click "Add New Project"
3. Import your Git repository
4. Vercel will auto-detect the configuration
5. Add your environment variables
6. Click "Deploy"

### Option B: Using Vercel CLI
```bash
# Install Vercel CLI
npm i -g vercel

# Login to Vercel
vercel login

# Deploy
vercel

# For production
vercel --prod
```

## Step 5: Update Database Connection

The connection files have been updated to use environment variables. Make sure:
- `client/connection.php` uses environment variables
- `admin/connection.php` uses environment variables

## Step 6: Verify Deployment

After deployment:
1. Visit your Vercel URL
2. Test the client-facing site: `https://your-app.vercel.app/`
3. Test admin panel: `https://your-app.vercel.app/admin/`

## Important Notes

### Limitations:
1. **File Uploads:** Local file uploads won't work. Use cloud storage.
2. **Sessions:** May need Redis or database-backed sessions for production
3. **Cron Jobs:** Use Vercel Cron Jobs or external services
4. **Large Files:** Vercel has limits on function size and execution time

### Recommendations:
1. Use a CDN for static assets
2. Implement proper error logging (use external services)
3. Set up monitoring and alerts
4. Use database connection pooling
5. Implement caching where possible

## Troubleshooting

### Database Connection Issues:
- Verify environment variables are set correctly
- Check database firewall allows Vercel IPs
- Ensure database is publicly accessible (or use connection pooling)

### PHP Errors:
- Check Vercel function logs
- Enable error reporting in development
- Review PHP version compatibility (set to 8.2)

### File Path Issues:
- Use relative paths from project root
- Don't use absolute paths like `/var/www/`
- Check that file includes use correct relative paths

## Support

For issues specific to:
- **Vercel:** Check [Vercel Documentation](https://vercel.com/docs)
- **PHP on Vercel:** Check [Vercel PHP Runtime](https://vercel.com/docs/concepts/functions/serverless-functions/runtimes/php)

## Project Structure

```
/
├── client/          # Client-facing application
├── admin/           # Admin panel
├── databases/       # SQL files (not deployed)
├── vercel.json      # Vercel configuration
├── package.json     # Node.js configuration
└── .env.example     # Environment variables template
```

