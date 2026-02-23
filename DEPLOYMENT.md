# Deployment Guide - Railway

This guide explains how to deploy the hairdresser booking application to Railway with the custom domain `hairydresser.site`.

## Prerequisites

- Railway account created (https://railway.app)
- GitHub repository connected to Railway
- Domain `hairydresser.site` purchased
- MySQL database provisioned on Railway (or locally)

## Step 1: Connect GitHub to Railway

1. Go to https://railway.app/dashboard
2. Click "New Project"
3. Select "Deploy from GitHub"
4. Authorize Railway to access your GitHub account
5. Select the `hairdresser-booking` repository
6. Click "Deploy"

Railway will automatically detect it's a PHP project and set up the environment.

## Step 2: Configure Environment Variables

In the Railway dashboard for your project:

1. Go to "Variables" tab
2. Add these variables:

```
PATH_ADD = ./public
PHP_VERSION = 8.3
```

3. Add database credentials:

```
DB_HOST = localhost (or Railway MySQL service host)
DB_USER = your_db_user
DB_PASSWORD = your_db_password
DB_NAME = hairdresser_booking
ADMIN_USER = admin
ADMIN_PASS = your_secure_admin_password
```

## Step 3: Set Up MySQL on Railway

1. In your Railway project, click "Add Service"
2. Select "MySQL"
3. Configure the MySQL service
4. Copy the connection details to your environment variables

**Important:** Run the database schema after connecting:

```bash
mysql -h [railway-host] -u [railway-user] -p[railway-password] [database-name] < db/schema.sql
```

## Step 4: Create .htaccess for URL Rewriting

Create a `.htaccess` file in the `public/` directory (already included):

```apache
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule ^(.*)$ /index.php?url=$1 [QSA,L]
</IfModule>
```

This allows the router to handle all requests through `index.php`.

## Step 5: Connect Custom Domain

1. In Railway dashboard, go to "Settings" → "Domains"
2. Click "Add Custom Domain"
3. Enter `hairydresser.site`
4. Railway will provide DNS records to add to your domain registrar

### Update DNS Records

Go to your domain registrar (where you bought hairydresser.site):

1. Find DNS/Nameserver settings
2. Add the CNAME record that Railway provides
3. Wait for DNS to propagate (5-30 minutes typically)

Example DNS record:
```
Type: CNAME
Name: hairydresser
Value: railway-provided-value.railway.app
TTL: 3600
```

## Step 6: Verify HTTPS

Once the domain is connected, Railway automatically provisions an SSL certificate with Let's Encrypt. Your site should be available at:

```
https://hairydresser.site
```

## Step 7: First Deployment

1. Make a commit in GitHub
2. Push to `main` branch
3. Railway automatically redeploys on each push

## Troubleshooting

### Site shows 502 or 503 error

- Check logs in Railway dashboard
- Verify environment variables are set correctly
- Check database connection in logs

### Database connection fails

- Verify DB_HOST, DB_USER, DB_PASSWORD in environment variables
- Ensure MySQL service is running on Railway
- Check that schema has been imported

### Domain not working

- Wait for DNS propagation (can take up to 1 hour)
- Verify DNS records are correctly added at registrar
- Check Railway domain settings

### URL routing not working

- Verify `.htaccess` is in `public/` directory
- Check that `mod_rewrite` is enabled on server
- Review Router.php for correct route definitions

## Post-Deployment

### Update Admin Credentials

Before going live, change the default admin credentials:

1. Update `ADMIN_USER` and `ADMIN_PASS` in Railway environment variables
2. Or manually in database: `UPDATE admin_users SET password_hash = ... WHERE username = 'admin'`

### Monitor Application

- Check Railway logs regularly for errors
- Set up alerts for deployment failures
- Monitor database usage

### Backups

Regular database backups are recommended:

```bash
mysqldump -h [host] -u [user] -p[password] [database] > backup.sql
```

## Redeploy

To redeploy after making changes:

1. Commit and push to GitHub
2. Railway automatically triggers redeployment
3. Or manually trigger in Railway dashboard under "Deployments"

## Support

For Railway-specific issues: https://docs.railway.app
For application issues: Check GitHub issues or contact developer
