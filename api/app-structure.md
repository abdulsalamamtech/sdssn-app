



```php

    public function fileUploadPost(Request $request)

    {

        $request->validate([

            'file' => 'required|mimes:pdf,xlx,csv|max:2048',

        ]);

        $fileName = time().'.'.$request->file->extension();  

        $request->file->move(public_path('uploads'), $fileName);
        // $request->file('banner')->storeAs('assets', $fileName);


        return back()

            ->with('success','You have successfully upload file.')

            ->with('file',$fileName);

   

    }

```

```php

    $file = $request->file('photo');

    //File Name
    $file->getClientOriginalName();

    //Display File Extension
    $file->getClientOriginalExtension();

    //Display File Real Path
    $file->getRealPath();

    //Display File Size
    $file->getSize();

    //Display File Mime Type
    $file->getMimeType();

    //Move Uploaded File
    $destinationPath = 'uploads';
    $file->move($destinationPath,$file->getClientOriginalName());
    
```


```sh


sudo chmod -R 777 /tmp

php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*
rm -rf storage/framework/cache/*


sudo service apache2 restart    # For Apache
sudo service nginx restart      # For Nginx
sudo service php-fpm restart    # For PHP-FPM, if applicable


```
