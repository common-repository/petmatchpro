<div id="all">

  <h2>Play Video</h2>

  <a id="play-video" class="play-button" data-url="https://www.youtube.com/embed/ZGgTAv09Amo?rel=0&autoplay=1" data-toggle="modal" data-target="#myModal" title="XJj2PbenIsU"><i class="fab fa-youtube"></i></a>

</div>



<!-- VIDEO MODAL -->

<div class="modal fade youtube-video" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-body">

                <div id="video-container" class="video-container">

                    <iframe id="youtubevideo" src="" width="640" height="360" frameborder="0" allowfullscreen></iframe>

                </div>        

            </div>

            <div class="modal-footer">

                <button id="close-video" type="button" class="button btn btn-default" data-dismiss="modal"><i class="fas fa-times" aria-hidden="true"></i></button>

            </div>

        </div> 

    </div>

</div>

<!-- VIDEO MODAL -->



<?php
    wp_enqueue_style('fontawesome-all',plugin_dir_url( __DIR__ ).'public/css/fontawesome_all.css');
    wp_enqueue_style('bootstrap-all',plugin_dir_url( __DIR__ ).'public/css/bootstrap.min.css');
    
    wp_enqueue_script('jquery', plugin_dir_url( __DIR__ ).'public/js/jquery-3.2.1.slim.min.js', array(), null, true);
    
    wp_register_script('popper', plugin_dir_url( __DIR__ ).'public/js/popper.min.js');
    wp_enqueue_script('popper');
    
    wp_register_script('prefix_bootstrap', plugin_dir_url( __DIR__ ).'public/js/bootstrap.min.js');
    wp_enqueue_script('prefix_bootstrap');  