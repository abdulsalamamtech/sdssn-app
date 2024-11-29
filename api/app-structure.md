

```php

use Illuminate\Support\Facades\DB;  

try {  
    DB::beginTransaction();  

    // Your database operations here  
    // e.g. Create, Update, or Delete Models  

    DB::commit();  
} catch (\Exception $e) {  
    DB::rollBack();  
    // Handle the error, e.g. log it or return a response  
}

```

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



### using youtube video api
- Docs link: https://developers.google.com/youtube/v3/quickstart/php
- Api reference: https://developers.google.com/youtube/v3/docs
- Maximum file size: 256GB
- Accepted Media MIME types: video/*, application/octet-stream
- Quota impact: A call to this method has a quota cost of 1600 units
- API Response: https://developers.google.com/youtube/v3/docs/videos#resource

```php
{
  "kind": "youtube#video",
  "etag": etag,
  "id": string,
  "snippet": {
    "publishedAt": datetime,
    "channelId": string,
    "title": string,
    "description": string,
    "thumbnails": {
      (key): {
        "url": string,
        "width": unsigned integer,
        "height": unsigned integer
      }
    },
    "channelTitle": string,
    "tags": [
      string
    ],
    "categoryId": string,
    "liveBroadcastContent": string,
    "defaultLanguage": string,
    "localized": {
      "title": string,
      "description": string
    },
    "defaultAudioLanguage": string
  },
  "contentDetails": {
    "duration": string,
    "dimension": string,
    "definition": string,
    "caption": string,
    "licensedContent": boolean,
    "regionRestriction": {
      "allowed": [
        string
      ],
      "blocked": [
        string
      ]
    },
    "contentRating": {
      "acbRating": string,
      "agcomRating": string,
      "anatelRating": string,
      "bbfcRating": string,
      "bfvcRating": string,
      "bmukkRating": string,
      "catvRating": string,
      "catvfrRating": string,
      "cbfcRating": string,
      "cccRating": string,
      "cceRating": string,
      "chfilmRating": string,
      "chvrsRating": string,
      "cicfRating": string,
      "cnaRating": string,
      "cncRating": string,
      "csaRating": string,
      "cscfRating": string,
      "czfilmRating": string,
      "djctqRating": string,
      "djctqRatingReasons": [,
        string
      ],
      "ecbmctRating": string,
      "eefilmRating": string,
      "egfilmRating": string,
      "eirinRating": string,
      "fcbmRating": string,
      "fcoRating": string,
      "fmocRating": string,
      "fpbRating": string,
      "fpbRatingReasons": [,
        string
      ],
      "fskRating": string,
      "grfilmRating": string,
      "icaaRating": string,
      "ifcoRating": string,
      "ilfilmRating": string,
      "incaaRating": string,
      "kfcbRating": string,
      "kijkwijzerRating": string,
      "kmrbRating": string,
      "lsfRating": string,
      "mccaaRating": string,
      "mccypRating": string,
      "mcstRating": string,
      "mdaRating": string,
      "medietilsynetRating": string,
      "mekuRating": string,
      "mibacRating": string,
      "mocRating": string,
      "moctwRating": string,
      "mpaaRating": string,
      "mpaatRating": string,
      "mtrcbRating": string,
      "nbcRating": string,
      "nbcplRating": string,
      "nfrcRating": string,
      "nfvcbRating": string,
      "nkclvRating": string,
      "oflcRating": string,
      "pefilmRating": string,
      "rcnofRating": string,
      "resorteviolenciaRating": string,
      "rtcRating": string,
      "rteRating": string,
      "russiaRating": string,
      "skfilmRating": string,
      "smaisRating": string,
      "smsaRating": string,
      "tvpgRating": string,
      "ytRating": string
    },
    "projection": string,
    "hasCustomThumbnail": boolean
  },
  "status": {
    "uploadStatus": string,
    "failureReason": string,
    "rejectionReason": string,
    "privacyStatus": string,
    "publishAt": datetime,
    "license": string,
    "embeddable": boolean,
    "publicStatsViewable": boolean,
    "madeForKids": boolean,
    "selfDeclaredMadeForKids": boolean,
    "containsSyntheticMedia": boolean
  },
  "statistics": {
    "viewCount": string,
    "likeCount": string,
    "dislikeCount": string,
    "favoriteCount": string,
    "commentCount": string
  },
  "paidProductPlacementDetails": {
    "hasPaidProductPlacement": boolean
  },
  "player": {
    "embedHtml": string,
    "embedHeight": long,
    "embedWidth": long
  },
  "topicDetails": {
    "topicIds": [
      string
    ],
    "relevantTopicIds": [
      string
    ],
    "topicCategories": [
      string
    ]
  },
  "recordingDetails": {
    "recordingDate": datetime
  },
  "fileDetails": {
    "fileName": string,
    "fileSize": unsigned long,
    "fileType": string,
    "container": string,
    "videoStreams": [
      {
        "widthPixels": unsigned integer,
        "heightPixels": unsigned integer,
        "frameRateFps": double,
        "aspectRatio": double,
        "codec": string,
        "bitrateBps": unsigned long,
        "rotation": string,
        "vendor": string
      }
    ],
    "audioStreams": [
      {
        "channelCount": unsigned integer,
        "codec": string,
        "bitrateBps": unsigned long,
        "vendor": string
      }
    ],
    "durationMs": unsigned long,
    "bitrateBps": unsigned long,
    "creationTime": string
  },
  "processingDetails": {
    "processingStatus": string,
    "processingProgress": {
      "partsTotal": unsigned long,
      "partsProcessed": unsigned long,
      "timeLeftMs": unsigned long
    },
    "processingFailureReason": string,
    "fileDetailsAvailability": string,
    "processingIssuesAvailability": string,
    "tagSuggestionsAvailability": string,
    "editorSuggestionsAvailability": string,
    "thumbnailsAvailability": string
  },
  "suggestions": {
    "processingErrors": [
      string
    ],
    "processingWarnings": [
      string
    ],
    "processingHints": [
      string
    ],
    "tagSuggestions": [
      {
        "tag": string,
        "categoryRestricts": [
          string
        ]
      }
    ],
    "editorSuggestions": [
      string
    ]
  },
  "liveStreamingDetails": {
    "actualStartTime": datetime,
    "actualEndTime": datetime,
    "scheduledStartTime": datetime,
    "scheduledEndTime": datetime,
    "concurrentViewers": unsigned long,
    "activeLiveChatId": string
  },
  "localizations": {
    (key): {
      "title": string,
      "description": string
    }
  }
}
```



To upload a video to YouTube using PHP, you will need to use the YouTube Data API. Here’s a basic outline of the steps you need to follow:

1. **Set Up API Access**: Create a new project in the Google Cloud Console, enable the YouTube Data API v3, and create OAuth 2.0 credentials.

2. **Install Google API Client Library**: Use Composer to install the Google API PHP Client library:
   ```
   composer require google/apiclient
   ```

3. **Authenticate**: Use the credentials to authenticate your app and authorize access to the YouTube channel.

4. **Upload the Video**: Utilize the API's `videos.insert` method, specifying the video parameters like title, description, and category, along with the video file upload.

Here is a simplified example code snippet for uploading a video:

```php
require 'vendor/autoload.php';

// Create a client object with your credentials
$client = new Google_Client();
$client->setApplicationName('YouTube Video Upload');
$client->setScopes(['https://www.googleapis.com/auth/youtube.upload']);
$client->setAuthConfig('path/to/your/credentials.json');

// Authenticate and create YouTube service
$youtube = new Google_Service_YouTube($client);

// Define video properties
$video = new Google_Service_YouTube_Video();
$video->setSnippet(new Google_Service_YouTube_VideoSnippet([
    'title' => 'Your Video Title',
    'description' => 'Your Video Description',
    'tags' => ['tag1', 'tag2'],
    'categoryId' => '22' // See https://developers.google.com/youtube/v3/docs/videoCategories/list
]));
$video->setStatus(new Google_Service_YouTube_VideoStatus(['privacyStatus' => 'private'])); // or 'public' or 'unlisted'

// Upload video
$filePath = '/path/to/your/video.mp4';
$chunkSizeBytes = 1 * 1024 * 1024; // 1MB
$client->setDefer(true);

$insertRequest = $youtube->videos->insert('status,snippet', $video);
$media = new Google_Http_MediaFileUpload(
    $client,
    $insertRequest,
    'video/*',
    null,
    true,
    $chunkSizeBytes
);
$media->setFileSize(filesize($filePath));

// Read the media file and upload
$handle = fopen($filePath, 'rb');
while (!$media->isComplete()) {
    $chunk = fread($handle, $chunkSizeBytes);
    $media->nextChunk($chunk);
}

fclose($handle);
```

Be sure to handle any errors appropriately and check Google's API documentation for the most current details.
