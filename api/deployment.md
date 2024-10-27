
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
