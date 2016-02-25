# SDK for Mobile App Using PHP/Mysql and JSON

## Introduction
This is a PHP and JSON based SDK for any Mobile based App which can be used to transfer data back and forth from database to any mobile device application. I created this SDK for an app which requests data based on user interaction from the device. The SDK can be accessible using HTTP url call from inside the app which returns JSON formatted data in response. JSON is a formatted way of representation of data. Its been widely used in various applications. Using JSON, we can easily parse data back and forth easily. 

This SDK got files that are used to take request from the device in-app functionality based on user interaction, process the request and send back the response in JSON format. 

## How it works

1. Based in the HTTP call from the in app request, the SDK accpepts query string from the url and process the request.
2. When a request comes to the SDK, it generates an encryption key with a public key, private key and the string. So when the response sends back to the app, the response contains the encrypted key along with the response. The app then decrypt the key with that public and private key and matches data for security purpose.


## Request and Response

You can request the SDK using HTTP call from your app, which is something like: http://www.yourappdomain.com/V1-0/getCategoryWiseContentAll.php?categoryId=1

So when you call this url, you are requesting to get the content based on the category id 1. It will send you back the response in JSON format with the encrption key for security purpose.
