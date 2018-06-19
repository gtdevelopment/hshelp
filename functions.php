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
$sign_url = add_shortcode('hellosign99', 'get_hellosign99');
?>
<script type="text/javascript" src="//s3.amazonaws.com/cdn.hellosign.com/public/js/hellosign-embedded.LATEST.min.js"></script>

  <script>
                HelloSign.init("<?php echo $client_id ?>");
                HelloSign.open({
                    url: "<?php echo $sign_url ?>",
                    uxVersion: 2,
                    allowCancel: true,
//                     skipDomainVerification: true,
                    debug: true,
                    messageListener: function (eventData) {
                        ("Got message data: " + JSON.stringify(eventData));
                        if (eventData.event == HelloSign.EVENT_SIGNED) {
                            HelloSign.close();
                            console.log(eventData.signature_id + "is the signature_id itself");
                            window.location = "index.php";
                        } else if (eventData.event == HelloSign.EVENT_CANCELED) {
                            alert("Event Canceled And Stuff!");
                            console.log(eventData);
                            window.location = "index.php";
                        } else if (eventData.event == HelloSign.EVENT_ERROR) {
                            alert("There Was An Error And Stuff!");
                            console.log(eventData);
                            window.location = "index.php";
                        } else if (eventData.event == HelloSign.EVENT_SENT) {
                            alert("Signature Request Sent And Stuff!");
                            console.log(eventData);
                            window.location = "index.php";
                        } else if (eventData.event == HelloSign.EVENT_TEMPLATE_CREATED) {
                            window.alert("Template id <?php echo $template_id ?> created!");
                            console.log(eventData);
                            window.location = "index.php";
                        } else if (eventData.event == HelloSign.EVENT_DECLINED) {
                            alert("Signature Request declined And Stuff!");
                            console.log(eventData);
                            window.location = "index.php";
                        }
                    }
                });
            </script>
