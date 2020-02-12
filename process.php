<?php 
$errors = array(); //To store errors
$form_data = array(); //Pass back the data to `form.php`

//$requestUrl = "https://dev-frm1.circlecare.io/activity/v3.9/api/BinaryData/SignUpCorporateGroup";
$requestUrl = "https://frm1.circlecare.io/activity/v3.9/api/BinaryData/SignUpCorporateGroup";
$name = isset($_POST['Name']) ? $_POST['Name'] : '';
$address = isset($_POST['Address']) ? $_POST['Address'] : '';
$phone = isset($_POST['Phone']) ? $_POST['Phone'] : '';
$country = isset($_POST['Country']) ? $_POST['Country'] : '';
$email = isset($_POST['Email']) ? $_POST['Email'] : '';
$photo = isset($_POST['Photo']) ? $_POST['Photo'] : '';
$description = $_POST['Description'];

if ( $name == "" ) {
    $errors['message'] = 'Name cannot be blank';
}
elseif ( $address == "" ) {
    $errors['message'] = 'Address cannot be blank';
}
elseif ( $phone == "" ) {
    $errors['message'] = 'Phone cannot be blank';
}
elseif ( $email == "" ) {
    $errors['message'] = 'Email cannot be blank';
}
elseif ( $photo == "" ) {
    $errors['message'] = 'Please select company logo';
}

if ( !empty($errors) ) { //If errors in validation
    $form_data['success'] = false;
    $form_data['errors']  = $errors;
}
else { //If not, process the form, and return true on success
    // excetue CURL
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $requestUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\r\n  \"Name\": \"$name\",\r\n  \"Address\": \"$address\",\r\n  \"Phone\": \"$phone\",\r\n  \"Country\": \"$country\",\r\n  \"Email\": \"$email\",\r\n  \"Description\": \"$description\",\r\n  \"Photo\": \"$photo\"\r\n}",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 4fe434c5-228e-56e8-7bd9-13b3010d06ec"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    // check email exist
    $res = json_decode($response);
    if ( $res->ResponseCode == 13 ) {
        $form_data['success'] = false;
        $form_data['reason'] = 13;
        $err = $res->Message;
    }
    else {
        $form_data['success'] = true;
    }
    
    $form_data['response'] = $response;
    $form_data['err_response'] = $err;
    $form_data['posted'] = 'Data Was Posted Successfully';
}

//Return the data back to form.php
echo json_encode($form_data);