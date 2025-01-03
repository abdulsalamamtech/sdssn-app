
# Laravel Deployment

- Run Commands
```bash
    composer install --no-dev && php artisan migrate && php artisan cache:clear && php artisan config:cache
```

- For Cors error
```js
    axios.defaults.withCredentials = true;
    axios.defaults.withXSRFToken = true;
```

- For authenticate your SPA, your SPA's "login" page should first make a request to the /sanctum/csrf-cookie endpoint to initialize CSRF protection for the application
```js
    axios.get('/sanctum/csrf-cookie').then(response => {
        // Login...
    });
```
Laravel will set an XSRF-TOKEN cookie containing the current CSRF token. This token should then be passed in an X-XSRF-TOKEN header on subsequent requests




# SETTING UP GITHUB ACTION

To deploy to a VPS server on CloudPanel using GitHub Actions, you can create a GitHub Actions workflow that automates the deployment process. Here are the steps:

1. **Set Up SSH Access**: Ensure your VPS allows SSH access and that you have the credentials (username, password, or SSH key).

2. **Store Secrets in GitHub**: Go to your GitHub repository settings, then "Secrets", and add your SSH key (as `VPS_SSH_KEY`), server IP (as `VPS_IP`), and user (as `VPS_USER`), among any other required secrets.

3. **Create a Workflow**: In your repository, create a `.github/workflows/deploy.yml` file and add the following sample configuration:
   ```yaml
   name: Deploy to VPS

   on:
     push:
       branches:
         - main

   jobs:
     deploy:
       runs-on: ubuntu-latest

       steps:
       - name: Checkout code
         uses: actions/checkout@v2

       - name: Copy files to VPS
         uses: appleboy/scp-action@v0.1.5
         with:
           host: ${{ secrets.VPS_IP }}
           username: ${{ secrets.VPS_USER }}
           key: ${{ secrets.VPS_SSH_KEY }}
           source: "."
           target: "/path/to/your/target/directory"

       - name: Execute remote command
         uses: appleboy/ssh-action@v0.1.5
         with:
           host: ${{ secrets.VPS_IP }}
           username: ${{ secrets.VPS_USER }}
           key: ${{ secrets.VPS_SSH_KEY }}
           script: |
             cd /path/to/your/target/directory
             npm install  # or any build commands
             pm2 restart all  # or any command to restart your application
   ```

4. **Push Changes**: When you push changes to your repository's main branch, GitHub Actions will trigger the workflow to deploy your changes to the VPS.

Make sure to customize the above steps according to your specific project setup and requirements. Always double-check your commands and directory paths to avoid deployment errors.
