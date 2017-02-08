<?php

// add scripts and functions

add_action('wp_enqueue_scripts', 'inputtitle_submit_scripts');
add_action('wp_ajax_ajax-inputtitleSubmit', 'myajax_inputtitleSubmit_func');
add_action('wp_ajax_ajax-searchSubmit', 'myajax_searchSubmit_func');

//not logged in users
add_action('wp_ajax_nopriv_ajax-inputtitleSubmit', 'not_logged_in_func');
add_action('wp_ajax_nopriv_ajax-searchSubmit', 'not_logged_in_func');

function not_logged_in_func() {
    echo("REQUIRES LOGIN ");
}

function myajax_searchSubmit_func() {

    // check nonce
    $nonce = $_POST['nextNonce'];
    if (!wp_verify_nonce($nonce, 'myajax-next-nonce')) {
        die('Busted!');
    }
    global $wpdb;
    $table = $wpdb->prefix . "sthassessment";
    $class = $_POST['pupil_class'];
    // $html = "";
    $retrieve_data = $wpdb->get_results("Select * from $table WHERE class='$class' ");
    if (!empty($retrieve_data)) {
        echo '<thead><tr><td>Class</td><td>Name</td><td>Question1</td></tr></thead>';
        foreach ($retrieve_data as $retrieved_data) {
            
            // $html = $retrieved_data->name;
            echo '<tbody><tr><td>' . $retrieved_data->class . '</td>';
            echo '<td>' . $retrieved_data->name . '</td>';
            echo '<td>' . $retrieved_data->question1 . '</td></tr></tbody>';
            // echo $retrieved_data->question1;
        }
    } else {
        echo '<tr><td>NO DATA FOUND - CHECK CLASS NAME</td></tr>';
    } //else
    // echo json_encode(array( 'html' => $html ));
}

function inputtitle_submit_scripts() {
    wp_enqueue_script('inputtitle_submit', get_template_directory_uri() . '/js/inputtitle_submit.js', array('jquery'));
    wp_localize_script('inputtitle_submit', 'PT_Ajax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nextNonce' => wp_create_nonce('myajax-next-nonce')
            )
    );
}

function myajax_inputtitleSubmit_func() {
    global $wpdb;
    $table = $wpdb->prefix . "sthassessment";
    $name = $_POST['pupil_name'];
    $class = $_POST['pupil_class'];
    //question to percentage
    $q1 = ($_POST['q1']) / 3 * 100;
    $user = get_current_user_id();
    $existcheck = 0;

    // check if fields missing
    $fields = array('pupil_name', 'pupil_class', 'q1');
    $error = false; //No errors yet
    foreach ($fields AS $fieldname) { //Loop trough each field
        if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
            echo 'Field ' . $fieldname . ' is missing!<br />'; //Display error with field
            $errors[$fieldname] = ' is missing!<br />'; //Display error with field
            $error = true; //Yup there are errors                                     
        }
    }
    // errors are returned in session as an array
    if ($error) {
        echo("Errors were found " . $error);
        exit;
    } else {

        // check nonce
        $nonce = $_POST['nextNonce'];
        if (!wp_verify_nonce($nonce, 'myajax-next-nonce')) {
            die('Busted!');
        }

        // Check Pupil exists
        $existcheck = pupil_exist_check($name, $class);

        // if pupil exists update pupil records
        $response = ("Existing Pupil updating...");
        if ($existcheck > 0) {
            $wpdb->update(
                    $table, array(
                'question1' => $q1
                    ), array('teacherid' => $user, 'name' => $name, 'class' => $class)
            );
            ?> <h3>-Pupil already exists - updated records-</h3><br /><?php
        } else {

            //input some data to the table
            echo ($name);
            $wpdb->insert(
                    $table, array(
                'teacherid' => $user,
                'name' => $name,
                'class' => $class,
                'question1' => $q1
                    )
            );

            // This is in the PHP file and sends a Javascript alert to the client
            // generate the response
            $response = ("Data Successfully added" . json_encode($_POST));
            // response output
            header("Content-Type: application/json");
            echo $response;
        }
        // IMPORTANT: don't forget to "exit"     
        exit;
    }
}

function pupil_exist_check($name, $class) {
    //check if pupil already exists   
    global $wpdb;
    $user = get_current_user_id();
    $aname = $name;
    $aclass = $class;
    $table = $wpdb->prefix . "sthassessment";
    $dbexist = $wpdb->get_results(
            $wpdb->prepare("
                        SELECT * from $table
                        WHERE name = %s
                        AND class = %s
                        AND teacherid = %d"
                    , $aname, $aclass, $user
            )
    );
    //check DB results for user id
    foreach ($dbexist as $results) {
        $existingid = $results->id;
    }
    //echo "DB results check: " . $existingid;
    //return value of DB results check as count of rows
    $existcheck = count($dbexist);
    //echo ("existcheck value = " . $existcheck);
    return $existcheck;
}
