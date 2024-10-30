
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



