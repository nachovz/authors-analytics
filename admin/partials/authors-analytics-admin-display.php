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

<div class="wrap">
    <h1>Authors Analytics</h1>
    <br/>
    <!-- The Sign-in button. This will run `queryReports()` on success. -->
    <div class="row">
      <div class="col-2">
        <p class="g-signin2" data-onsuccess="queryReports"></p>
      </div>
      <div class="col-2">
        <table class="table table-bordered">
          <tr>
            <td class="table-info"> Usuario </td>
            <td class="table-success"> REPOST </td>
          </tr>
        </table>
      </div>
      <div class="col-8">
        <form id="dateUpdateForm">
          <div class="form-row">
            <div class="col-5">
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">Desde:</div>
                </div>
                <input type="date" class="form-control" name="fromDate" >
              </div>
            </div>
            <div class="col-5">
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">Hasta:</div>
                </div>
                <input type="date" class="form-control" name="toDate" >
              </div>
            </div>
            <div class="col-2">
              <input type="submit" value="Actualizar" class="btn btn-primary"/>
            </div>
          </div>
        </form>
      </div>
    </div>
    
    <table id="aa_table" class="table table-hover table-bordered table-sm">
        <thead class="thead-dark">
            <tr>
                <th scope="col">
                    Artículo
                </th>
                <th scope="col">
                    Páginas Vistas
                </th>
                <th scope="col">
                    Sesiones
                </th>
                <th scope="col">
                    Bounce Rate
                </th>
                <th scope="col">
                    Tiempo en página
                </th>
            </tr>
        </thead>
        <tbody>
        <?php
            $all_users = get_users(['role' => 'contributor']);
            
            foreach($all_users as $a_user){
                $author_query = array('posts_per_page' => '-1','author' => $a_user->ID);
                $author_posts = new WP_Query($author_query);
                echo    '<tbody>
                            <tr id="tr_'.$a_user->user_nicename.'" class="table-info clickable" data-toggle="collapse" data-target="#'.$a_user->user_nicename.'">
                                <td colspan="5">'.get_avatar($a_user->ID).' <b>'.$a_user->display_name.'</b></td>
                            </tr>
                        </tbody>
                        <tbody id="'.$a_user->user_nicename.'" class="collapse">';
                while($author_posts->have_posts()) : $author_posts->the_post();
                  $is_repost = false;
                  $posttags = get_the_tags();
                  if ($posttags) {
                    foreach($posttags as $tag) {
                      if($tag->name === 'repost') $is_repost = true;
                    }
                  }
                ?>
                    <tr id="<?php echo wp_make_link_relative( get_permalink() ); ?>"  class="<?php if( $is_repost) echo "table-success"; ?>">
                        <td><a target="_blank" href="<?php the_permalink(); ?>"><?php echo wp_make_link_relative( get_permalink() ); ?></a></td>
                        <td></td>
                        <td></td>
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
        
        var TODAY_DATE = yyyy + '-' + mm + '-' + dd;
        var DEFAULT_DATE = '2017-01-01';
        
        var form = document.getElementById("dateUpdateForm");
        form[0].value = DEFAULT_DATE;
        form[1].value = TODAY_DATE;
        
        document.getElementById("dateUpdateForm").addEventListener('submit', function(event){
          
          if( event.target[1].valueAsDate > event.target[0].valueAsDate){
            alert("New dates: "+ event.target[0].value + " to " + event.target[1].value);
            queryReports("",event.target[0].value, event.target[1].value);
          }else{
            alert("Desde debe ser mayor que hasta");
          }
          
          event.preventDefault();
        });
    
      // Query the API and print the results to the page.
      function queryReports(token, initDate = DEFAULT_DATE, endDate = TODAY_DATE) {
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
                    {"endDate": endDate, "startDate": initDate}
                  ],
                  "metrics": [
                    {"expression": "ga:pageviews"},
                    {"expression": "ga:sessions"},
                    {"expression": "ga:bounceRate"},
                    {"expression": "ga:avgTimeOnPage"}
                  ],
                  "dimensions": [{"name": "ga:pagePath"}],
                  "dimensionFilterClauses": [
                    {
                      "filters": [
                          <?php
                          $all_users = get_users(['role' => 'contributor']);
                          
                          foreach($all_users as $a_user){
                              $author_query = array('posts_per_page' => '-1','author' => $a_user->ID);
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
                          }
                              ?>
                      ]
                    }
                  ]
                }
              ]
              
          }
        }).then(displayResults, console.error.bind(console));
      }
    
      function displayResults(response) {
        
        var types = response.result.reports[0].columnHeader.metricHeader.metricHeaderEntries;
        
        response.result.reports[0].data.rows.forEach( (row) => {
            var current_tr = document.getElementById(row.dimensions[0]);
            row.metrics[0].values.forEach( (value, index) => current_tr.children[index+1].innerHTML = formatValue(value, types[index].type) ) ;
        } );
      }
      
      function formatValue(aa_value, aa_type){
          switch(aa_type){
            case "PERCENT":
                return Math.round(parseFloat(aa_value))+"%";
            case "TIME":
                return Math.round(parseFloat(aa_value))+" segundos";
            default:
                return aa_value;
          }
      }
    </script>
    
    <!-- Load the JavaScript API client and Sign-in library. -->
    <script src="https://apis.google.com/js/client:platform.js"></script>

</div>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->



