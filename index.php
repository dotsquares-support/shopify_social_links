<?php

   // Custom Template/ Section file Create into 
   require_once("inc/functions.php");

   require_once("inc/db.php");   
   
   $requests = $_GET;
   $hmac = $_GET['hmac'];
   $serializeArray = serialize($requests);
   $requests = array_diff_key($requests, array( 'hmac' => '' ));
   ksort($requests);
   $shop = $_GET['shop'];
   $shopname = substr($shop, 0, strpos($shop, ".myshopify.com"));
   $shop_url = $shopname.".myshopify.com";  
   
   ?>
<?php
   if (isset($_POST['submit'])) {
       require_once("inc/db.php");
       $socialId = $_POST['socialId'];
       $fb = $_POST['facebook'];
       $tw = $_POST['twitter'];
       $ld = $_POST['linkedin'];
       $goo = $_POST['google'];
       $yt = $_POST['youtube'];
       $pt = $_POST['pinterest'];
       $insta = $_POST['instagram'];
       $tum = $_POST['tumblr'];
       $schat = $_POST['snapchat'];
       $vim = $_POST['vimeo'];
       $ttok = $_POST['tiktok'];
       $shop_url = $shopname.".myshopify.com";
       $sqld = mysqli_query($conn, "SELECT id FROM token_table  WHERE store_url ='".$shop_url."'");
       $row = $sqld->fetch_assoc();
       $tokenId = $row['id'];
       
           if(empty($socialId)){

                $sql = "INSERT INTO social_info (social_fb, social_tw, social_linkined, social_google, social_youtube, social_pinterest, social_insta, social_tumblr, social_schat, social_vimeo, social_tiktok, token_id) VALUES ('$fb', '$tw', '$ld', '$goo', '$yt', '$pt', '$insta', '$tum', '$schat', '$vim', '$ttok', '$tokenId')";
                if (mysqli_query($conn, $sql)) {

                     $gtoken = mysqli_query($conn, "SELECT * FROM token_table  WHERE store_url ='".$shop_url."'");
                        $grow = $gtoken->fetch_assoc();
                        $token = $grow['access_token'];
                        /* hERE start for section add code */
                        $sqls = "SELECT * FROM social_info";
                        $results = mysqli_query($conn, $sqls);
                        $socialMediaLinkArrLnk = array();
                        if (mysqli_num_rows($results) > 0) {
                           while($rows = mysqli_fetch_assoc($results)) {
                             $socialMediaLinkArrLnk = $rows;    
                            }
                         }

                        $url = parse_url( 'https://' . $requests['shop'] );
                        $host = explode('.', $url['host'] );
                        $shop  = $host[0];
                        
                       $socialMediaLinkArr = shopify_call($token,$shop,"/admin/api/2020-04/themes.json", array(), 'GET');
                       $socialMediaLinkArr = json_decode($socialMediaLinkArr['response'], JSON_PRETTY_PRINT);  
                           
                       foreach ($socialMediaLinkArrLnk as $cur_theme) {

                          foreach ($cur_theme as $key => $value) {
                               if($value['role'] === 'main') {
                                 $theme_id = $value['id'];
                                 $theme_role = $value['role'];
                                   $ttt = $arr_sec;
                                 $asset_file = array(
                                   "asset" => array(
                                             "key" => "sections/dotsociallinks.liquid",
                                             "value" => "<a  href='$ttt' traget='_blank'><i class='fa-brands fa-facebook-square'></i></a>"
                                           )
                                 );

                                 $asset = shopify_call($token, $shop, "/admin/api/2020-04/themes/" . $theme_id .  "/assets.json", $asset_file, 'PUT');
                                 $asset = json_decode($asset['response'], JSON_PRETTY_PRINT);
                            }
                           
                          }
                     }
                    
                    echo "<div class='success-msg'>Your social media icon Inserted successfully!!</div>";
                } else {


                    echo "<div class='error-msg'>Error inserting new record: " . mysqli_error($conn)."</div>";
                }
           
           }else{
           
              $updatequery = "UPDATE social_info SET social_fb= '$fb', social_tw ='$tw', social_linkined ='$ld', social_google ='$goo', social_youtube ='$yt', social_pinterest ='$pt', social_insta ='$insta', social_tumblr ='$tum', social_schat ='$schat', social_vimeo ='$vim', social_tiktok ='$ttok' WHERE social_id=".$socialId;

                $updateQry = mysqli_query($conn, $updatequery);

                  if($updateQry){

                     $gtoken = mysqli_query($conn, "SELECT * FROM token_table  WHERE store_url ='".$shop_url."'");

                     $grow = $gtoken->fetch_assoc();
                     $token = $grow['access_token'];

                     /* hERE start for section add code */
                     $sqls = "SELECT * FROM social_info";

                     $results = mysqli_query($conn, $sqls);

                     $socialMediaLinkArrLnk = array();

                     if (mysqli_num_rows($results) > 0) {
                        while($rows = mysqli_fetch_assoc($results)) {
                          $socialMediaLinkArrLnk = $rows;    
                         }
                      }

                     $url = parse_url( 'https://' . $requests['shop'] );
                     $host = explode('.', $url['host'] );
                     $shop  = $host[0];
                     
                    $socialMediaLinkArr = shopify_call($token,$shop,"/admin/api/2020-04/themes.json", array(), 'GET');
                    $socialMediaLinkArr = json_decode($socialMediaLinkArr['response'], JSON_PRETTY_PRINT);  
                        
                    foreach ($socialMediaLinkArrLnk as $cur_theme) {

                       foreach ($cur_theme as $key => $value) {
                            if($value['role'] === 'main') {
                              $theme_id = $value['id'];
                              $theme_role = $value['role'];
                                $ttt = $arr_sec;
                              $asset_file = array(
                                "asset" => array(
                                          "key" => "sections/dotsociallinks.liquid",
                                          "value" => "<a  href='$ttt' traget='_blank'><i class='fa-brands fa-facebook-square'></i></a>"
                                        )
                              );

                              $asset = shopify_call($token, $shop, "/admin/api/2020-04/themes/" . $theme_id .  "/assets.json", $asset_file, 'PUT');
                              $asset = json_decode($asset['response'], JSON_PRETTY_PRINT);
                         }
                        
                       }
                  }

                      echo "<div class='success-msg'>Your Social Media Icon Information Updated Successfully!!";   

                  }else{
                      echo "<div class='error-msg'>Error update new record: " . mysqli_error($conn)."</div>";
                  }

            }  

   }  
   
   ?>
<!DOCTYPE html>
<html>
   <link rel="stylesheet" type="text/css" href="assets/style.css"> 
   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">   
   <body>
      <div class="soical_section">
         <div class="soical_title">
            <h2>Social Media Links </h2>
            <p>Add your Social Media Links </p>

         </div>

         <div class="soical_content">
            <form name = "sociallinks" action="#" method = "post" enctype = "multipart/form-data" >
               <?php 
                  $shop_url = $shopname.".myshopify.com";
                  $sqld = mysqli_query($conn, "SELECT id FROM token_table  WHERE store_url ='".$shop_url."'");
                  $row = $sqld->fetch_assoc();
                  $tokenId = $row['id'];
                    $sqlf = "SELECT * FROM social_info WHERE token_id=".$tokenId;    
                  
                    $results = mysqli_query($conn, $sqlf);
                    $rowup = mysqli_fetch_assoc($results);     
                    ?>
               <p>
                  <input type="hidden" id="socialId" name="socialId" value="<?php if($rowup["social_id"]){ echo $rowup["social_id"]; } ?>">
                  <label for="facebook">Facebook:</label><br>
                  <input type="text" id="facebook" name="facebook" value="<?php echo $rowup["social_fb"]; ?>"><br>
               </p>
               <p>
                  <label for="twitter">Twitter:</label><br>
                  <input type="text" id="twitter" name="twitter" value="<?php echo $rowup["social_tw"];?>"><br>
               </p>
               <p>
                  <label for="linkedin">LinkedIn:</label><br>
                  <input type="text" id="linkedin" name="linkedin" value="<?php echo $rowup["social_linkined"];?>"><br>
               </p>
               <p>
                  <label for="google">Google:</label><br>
                  <input type="text" id="sl_google" name="google" value="<?php echo $rowup["social_google"];?>"><br>
               </p>
               <p>
                  <label for="youtube">Youtube:</label><br>
                  <input type="text" id="sl_youtube" name="youtube" value="<?php echo $rowup["social_youtube"];?>"><br>
               </p>
               <p>
                  <label for="Pinterest">Pinterest:</label><br>
                  <input type="text" id="pinterest" name="pinterest" value="<?php echo $rowup["social_pinterest"];?>"><br>
               </p>
               <p>
                  <label for="Instagram">Instagram:</label><br>
                  <input type="text" id="instagram" name="instagram" value="<?php echo $rowup["social_insta"];?>"><br>
               </p>
               <p>
                  <label for="Tumblr">Tumblr:</label><br>
                  <input type="text" id="tumblr" name="tumblr" value="<?php echo $rowup["social_tumblr"];?>"><br>
               </p>
               <p>
                  <label for="Snapchat">Snapchat:</label><br>
                  <input type="text" id="sl_snapchat" name="snapchat" value="<?php echo $rowup["social_schat"];?>"><br>
               </p>
               <p>
                  <label for="Vimeo">Vimeo:</label><br>
                  <input type="text" id="sl_vimeo" name="vimeo" value="<?php echo $rowup["social_vimeo"];?>"><br>
               </p>
               <p>
                  <label for="TikTok">TikTok:</label><br>
                  <input type="text" id="sl_tiktok" name="tiktok" value="<?php echo $rowup["social_tiktok"];?>"><br>
               </p>
               <p>
                  <input type="submit" value="Submit" name="submit">
               </p>
            </form>
         </div>
         <p>Note that the form itself is not visible.</p>
         <p>Also note that the default width of a text field is 20 characters.</p>
         <?php 
            ?>         
       
         <?php 
            $sqls = "SELECT * FROM social_info WHERE token_id=".$tokenId ;
            $results = mysqli_query($conn, $sqls);
            
            if (mysqli_num_rows($results) > 0) {
              // output data of each row
              while($rows = mysqli_fetch_assoc($results)) {?>
         <div class='social_group'>
          <?php if($rows["social_fb"]){?>
            <a  href='<?php echo $rows["social_fb"]; ?>' traget="_blank"><i class="fa-brands fa-facebook-square"></i></a>
              <?php } if($rows["social_tw"]){?>
            <a href='<?php echo $rows["social_tw"]; ?>' traget="_blank"><i class="fa-brands fa-twitter-square"></i></a>
             <?php } if($rows["social_insta"]){?>
            <a href='<?php echo $rows["social_insta"]; ?>' traget="_blank"><i class="fa-brands fa-instagram-square"></i></a>
             <?php } if($rows["social_linkined"]){?>
            <a href='<?php echo $rows["social_linkined"]; ?>' traget="_blank"><i class="fa-brands fa-linkedin"></i></a>
             <?php } if($rows["social_google"]){?>
            <a href='<?php echo $rows["social_google"]; ?>' traget="_blank"><i class="fa-brands fa-google-plus-square"></i></a>
             <?php } if($rows["social_youtube"]){?>
            <a href='<?php echo $rows["social_youtube"]; ?>' traget="_blank"><i class="fa-brands fa-youtube"></i></a>
             <?php } if($rows["social_pinterest"]){?>
            <a href='<?php echo $rows["social_pinterest"]; ?>' traget="_blank"><i class="fa-brands fa-pinterest-square"></i></a>
             <?php } if($rows["social_tumblr"]){?>
            <a href='<?php echo $rows["social_tumblr"]; ?>' traget="_blank"><i class="fa-brands fa-tumblr-square"></i></a>
             <?php } if($rows["social_schat"]){?>
            <a  href='<?php echo $rows["social_schat"]; ?>' traget="_blank"><i class="fa-brands fa-snapchat-square"></i></a>
             <?php } if($rows["social_vimeo"]){?>
            <a href='<?php echo $rows["social_vimeo"]; ?>' traget="_blank"><i class="fa-brands fa-vimeo-square"></i></a>
             <?php } if($rows["social_tiktok"]){?>
            <a href='<?php echo $rows["social_tiktok"]; ?>' traget="_blank"><i class="fa-brands fa-tiktok"></i></a>
             <?php } ?>
         </div>
         <?php
            }
            } else {
            echo "0 results";
            }
            ?>


      </div>
   </body>
</html>