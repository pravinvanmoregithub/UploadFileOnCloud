# UploadFileOnCloud

1) Read file from URL and upload files on Aws server on s3 in php

2) Resize Image with provided width and height in php

1) 
=> Created API for posting third party URL, Width and Height.
=> API Requires URL, Request Body and headers
=> Request Body:

{

"FileUrl":"https://images-na.ssl-images-amazon.com/images/I/91HErHUHyyL._SL1500_.jpg",

"FileWidth":"100",

"FileHeight":"100"

}

=> Request URL:

http://localhost/onlinetest/api/uploadfile

=> Request headers:

Content-Type:application/json
Authorization:UFJBVklOOlZBTk1PUkU=

It will read the files from url and resize the image to specific width and height and then upload on AWS S3 bucket as public read.

Output will be:

An Uploaded image url (hosted_image_url) containing image of w*h pixels
