<?php
function get_hellosign99(){
        $api_key = '97d560f2599859cd2ba98b6fe4e65d5e570fd11470afdd0b2ce67c611a259926';
        $client_id = '4423b00413a324df72a1fd29ee8b19b2';
        // Instance of a client for you to use for calls
        $client = new HelloSign\Client($api_key);
        // Example call with logging for embedded requests
        $request = new HelloSign\SignatureRequest;
        $request = new HelloSign\TemplateSignatureRequest;
        $request->enableTestMode(1);
        $request->setTitle("Testing Form Fields Per Document - Prod");
        $request->setSubject('Embedded Signature Request with Form Fields');
        $request->setMessage('Awesome, right?');
        $request->setSigner('Client', 'tjohann434@gmail.com', 'Tom Johnannsen');
        $request->setTemplateId('ccb5771cb82c2596d5727137cb85d3ded5407193');  
        // Turn it into an embedded request
        $embedded_request = new HelloSign\EmbeddedSignatureRequest($request, $client_id);
        // Send it to HelloSign
        $response = $client->createEmbeddedSignatureRequest($embedded_request);
        // wait for callback with signature_request_sent
        // 
        // Grab the signature ID for the signature page that will be embedded in the page
        $signatures = $response->getSignatures();
        $signature_id = $signatures[0]->getId();
        // Retrieve the URL to sign the document
        $embedded_response = $client->getEmbeddedSignUrl($signature_id);
        // Store it to use with the embedded.js HelloSign.open() call
        $sign_url = $embedded_response->getSignUrl();
      // call the html page with the embedded.js lib and HelloSign.open()
        return $sign_url;
}
add_shortcode('hellosign99', 'get_hellosign99');