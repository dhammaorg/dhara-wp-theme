<?php 
    get_header();
    if (is_restricted() ) {
        show_404();
    } else {
?>
   <div id="os-home-page-content" class="page-content">
      <div id="front-page-table">
         <table>
            <tr>
               <td>
                  <img alt="The Meditation Hall" src="/wp-content/uploads/your-practice-1.jpg" />
                  <h2>Your Practice</h2>
                  <ul class="old-student-home-list">
                     <li><a href="/os/regions/group-sittings/">Local Group Sittings</a></li>
                     <li><a href="/os/home/courses-types/">Old Student Courses</a></li>
                     <li><a target="_blank" href="https://www.dhamma.org/en-US/schedules/schdhara">Course Schedule</a></li>
                     <li><a href="/os/home/guidelines-for-practicing/">Guidelines for Practicing</a></li>
                     <li><a href="/os/home/faqs-for-old-students/">FAQs for Old Students</a></li>
                     <li><a href="/os/home/inspirational-videos/">Inspirational Videos</a></li>
                  </ul>
               </td>
               <td>
                  <img alt="Dhamma Servers" src="/wp-content/uploads/dhamma-service.jpg" />
                  <h2>Dhamma Service</h2>
                  <ul class="old-student-home-list">
                     <li><a href="/os/dhamma-service/overview/">Overview</a></li>
                     <li><a href="/os/home/dana/">Donations (Dāna)</a></li>
                     <li><a href="/os/dhamma-service/code-of-conduct/">Dhamma Service Code of Conduct</a></li>
                     <li><a href="/os/dhamma-service/service-periods/">Service Periods</a></li>
                     <li><a href="/os/dhamma-service/regular-tasks/">Sign up for Regular Tasks</a></li>
                     <li><a href="/os/dhamma-service/long-term-service/">Long Term Service</a></li>
                     <li><a href="/os/trust/schedule-and-minutes/">Attend a Trust Meeting</a></li>
                  </ul>
               </td>
               <td>
                      <img alt="Zedi Bells above the Meditation Pagoda" src="/wp-content/uploads/news.jpg" />
                      <h2>Recent News</h2>
                  <ul id="os-welcome-news-feed" class="old-student-home-list">
                     <?php
                          $recent_posts = wp_get_recent_posts (  [ 'numberposts' => '6', 'post_status' => 'publish' ]  );
                          $k = 0;
                          foreach ( $recent_posts as $recent ) {
                            echo '<li class="os-news-item"><a href="' . get_permalink($recent["ID"]) . '">' . $recent["post_title"].'</a> </li>';
                            $k++;
                            if ($k >= 6) {
                               break;
                            }
                          }
                          echo '<li class="os-welcome-news-item"><a href="/category/all-news/">... more news</a></li>';
                          wp_reset_query();
                     ?> 
                  </ul>
               </td>
            </tr>
         </table>
      </div>
   </div>
<?php }
get_footer();
