<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/nachovz
 * @since      1.0.0
 *
 * @package    Authors_Analytics
 * @subpackage Authors_Analytics/admin/partials
 */
 
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Hello Analytics Reporting API V4</title>
  <meta name="google-signin-client_id" content="973385141930-bvkmvs2pgoiaakvkrcklh2r4noajhl71.apps.googleusercontent.com">
  <meta name="google-signin-scope" content="https://www.googleapis.com/auth/analytics.readonly">
</head>
<body>

<h1>Hello Analytics Reporting API V4</h1>

<!-- The Sign-in button. This will run `queryReports()` on success. -->
<p class="g-signin2" data-onsuccess="queryReports"></p>

<div class="container">
    <table id="aa_table" class="table table-hover table-bordered">
        <thead>
            <tr class="table-dark">
                <th scope="col">
                    Artículo
                </th>
                <th scope="col">
                    Páginas Vistas
                </th>
                <th scope="col">
                    Sesiones
                </th>
            </tr>
        </thead>
        <tbody>
            <tr id="/">
                <td>/</td>
                <td></td>
                <td></td>
            </tr>
            <tr id="/index.php/biztalk-for-your-organization/">
                <td>/index.php/biztalk-for-your-organization/</td>
                <td></td>
                <td></td>
            </tr>
        <?php
            $all_users = get_users();
            
            foreach($all_users as $a_user){
                /*global $current_user;
                wp_get_current_user();*/
                //$author_query = array('posts_per_page' => '-1','author' => $current_user->ID);
                $author_query = array('posts_per_page' => '-1','author' => $a_user->ID);
                $author_posts = new WP_Query($author_query);
                echo    '<tbody>
                            <tr id="tr_'.$a_user->user_nicename.'" class="table-success clickable" data-toggle="collapse" data-target="#'.$a_user->user_nicename.'">
                                <td colspan="3">'.get_avatar($a_user->ID).' <b>'.$a_user->display_name.'</b></td>
                            </tr>
                        </tbody>
                        <tbody id="'.$a_user->user_nicename.'" class="collapse">';
                while($author_posts->have_posts()) : $author_posts->the_post();
                ?>
                    <tr id="<?php echo wp_make_link_relative( get_permalink() ); ?>" >
                        <td><a target="_blank" href="<?php the_permalink(); ?>"><?php echo wp_make_link_relative( get_permalink() ); ?></a></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php           
                endwhile;
                echo '</tbody>';
            }
            ?>
        </tbody>
    </table>
</div>
<script>
  // Replace with your view ID.
    var VIEW_ID = '119282868';
    
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
    
    if(dd<10) {
    dd = '0'+dd
    } 
    
    if(mm<10) {
    mm = '0'+mm
    } 
    
    //today = mm + '/' + dd + '/' + yyyy;
    var TODAY_DATE = yyyy + '-' + mm + '-' + dd;

  // Query the API and print the results to the page.
  function queryReports() {
    gapi.client.request({
      path: '/v4/reports:batchGet',
      root: 'https://analyticsreporting.googleapis.com/',
      method: 'POST',
      body: {
        "reportRequests":
          [
            {
              "viewId": VIEW_ID,
              "dateRanges": [
                {"endDate": TODAY_DATE, "startDate": "2017-01-01"}
              ],
              "metrics": [
                {"expression": "ga:pageviews"},
                {"expression": "ga:sessions"}
              ],
              "dimensions": [{"name": "ga:pagePath"}],
              "dimensionFilterClauses": [
                {
                  "filters": [
                      <?php
                        global $current_user;
                        wp_get_current_user();
                        $author_query = array('posts_per_page' => '-1','author' => $current_user->ID);
                        $author_posts = new WP_Query($author_query);
                        while($author_posts->have_posts()) : $author_posts->the_post();
                        ?>
                        {
                            "dimensionName": "ga:pagePath",
                            "operator": "EXACT",
                            "expressions": [ "<?php 
                            $link = get_permalink();
                            echo wp_make_link_relative( $link ); ?>" ]
                        },
                        
                        <?php           
                        endwhile;
                        ?>
                    {
                      "dimensionName": "ga:pagePath",
                      "operator": "EXACT",
                      "expressions": ["/"]
                    },
                    {
                      "expressions": [
                        "/index.php/biztalk-for-your-organization/"
                      ],
                      "dimensionName": "ga:pagePath",
                      "operator": "EXACT"
                    }
                  ]
                }
              ]
            }
          ]
          
      }
    }).then(displayResults, console.error.bind(console));
  }

  function displayResults(response) {
    
    response.result.reports[0].data.rows.forEach( (row) =>{
        var current_tr = document.getElementById(row.dimensions[0]);
        
        var node_pageviews = document.createTextNode(row.metrics[0].values[0]);
        current_tr.children[1].appendChild(node_pageviews);
        
        var node_sessions = document.createTextNode(row.metrics[0].values[1]);
        current_tr.children[2].appendChild(node_sessions);
    } );
    
    calculateTotals();
  }
  
  function calculateTotals(){
      
  }
</script>

<!-- Load the JavaScript API client and Sign-in library. -->
<script src="https://apis.google.com/js/client:platform.js"></script>


<!-- This file should primarily consist of HTML with a little bit of PHP. -->



