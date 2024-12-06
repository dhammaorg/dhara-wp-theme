<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <title>Virtual Group Sitting - Dhara Dhamma</title>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style-virtual-group-sitting.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script type="text/JavaScript">
            function toggleTextHide() {
            if(document.getElementById("intro-text").style.display == "none") {
            document.getElementById("intro-text").style.display = "block";
            document.getElementById("toggle-text-button").innerHTML = "hide text";
            document.getElementById("audio-controls").style.opacity = "1";
            } else {
            document.getElementById("intro-text").style.display = "none";
            document.getElementById("toggle-text-button").innerHTML = "show text";
            document.getElementById("audio-controls").style.opacity = ".5";
            }
            }

            function changeAudioPlayerTo(playerIndex) {
            document.getElementById("audio-one").style.display = "none";
            document.getElementById("audio-two").style.display = "none";
            document.getElementById("audio-three").style.display = "none";
            document.getElementById("audio-four").style.display = "none";
            document.getElementById("audio-five").style.display = "none";
            document.getElementById("audio-six").style.display = "none";
            document.getElementById("audio-seven").style.display = "none";
            document.getElementById("audio-eight").style.display = "none";
            document.getElementById("audio-nine").style.display = "none";
            document.getElementById("audio-ten").style.display = "none";
            document.getElementById("audio-eleven").style.display = "none";
            document.getElementById("audio-twelve").style.display = "none";
            document.getElementById("audio-one").pause();
            document.getElementById("audio-two").pause();
            document.getElementById("audio-three").pause();
            document.getElementById("audio-four").pause();
            document.getElementById("audio-five").pause();
            document.getElementById("audio-six").pause();
            document.getElementById("audio-seven").pause();
            document.getElementById("audio-eight").pause();
            document.getElementById("audio-nine").pause();
            document.getElementById("audio-ten").pause();
            document.getElementById("audio-eleven").pause();
            document.getElementById("audio-twelve").pause();

            switch (playerIndex) {
            case 0:
            document.getElementById("audio-one").style.display = "block";
            break;
            case 1:
            document.getElementById("audio-two").style.display = "block";
            break;
            case 2:
            document.getElementById("audio-three").style.display = "block";
            break;
            case 3:
            document.getElementById("audio-four").style.display = "block";
            break;
            case 4:
            document.getElementById("audio-five").style.display = "block";
            break;
            case 5:
            document.getElementById("audio-six").style.display = "block";
            break;
            case 6:
            document.getElementById("audio-seven").style.display = "block";
            break;
            case 7:
            document.getElementById("audio-eight").style.display = "block";
            break;
            case 8:
            document.getElementById("audio-nine").style.display = "block";
            break;
            case 9:
            document.getElementById("audio-ten").style.display = "block";
            break;
            case 10:
            document.getElementById("audio-eleven").style.display = "block";
            break;
            case 11:
            document.getElementById("audio-twelve").style.display = "block";
            break;
            }
            }

            function changeBackgroundTo(backgroundIndex) {
            imageFileName = "1.png";
            switch (backgroundIndex) {
            case 0:
            imageFileName = "1.png";
            break;
            case 1:
            imageFileName = "2.jpg";
            break;
            case 2:
            imageFileName = "3.jpg";
            break;
            case 3:
            imageFileName = "4.jpg";
            break;
            case 4:
            imageFileName = "5.jpg";
            break;
            case 5:
            imageFileName = "6.jpg";
            break;
            case 6:
            imageFileName = "7.jpg";
            break;
            case 7:
            imageFileName = "8.jpg";
            break;
            /*case 8:
            imageFileName = "9.jpg";
            break;*/
            case 8:
            imageFileName = "10.jpg";
            break;
            }
            document.getElementById( "vgs-body").style.backgroundImage = "url('/filebase/virtual-group-sittings/backgrounds/" + imageFileName + "')";
            }

            function changeTextTo(textIndex) {
            switch (textIndex) {
            case 0:
            document.getElementById("vgs-welcome").style.display = "block";
            document.getElementById("vgs-in-progress-instructions").style.display = "none";
            document.getElementById("vgs-in-progress-silent").style.display = "none";
            break;
            case 1:
            document.getElementById("vgs-welcome").style.display = "none";
            document.getElementById("vgs-in-progress-instructions").style.display = "block";
            document.getElementById("vgs-in-progress-silent").style.display = "none";
            break;
            case 2:
            document.getElementById("vgs-welcome").style.display = "none";
            document.getElementById("vgs-in-progress-instructions").style.display = "none";
            document.getElementById("vgs-in-progress-silent").style.display = "block";
            break;
            }
            }

            window.onload = function() {
            document.getElementById("session-chooser").onchange = function() {
            changeAudioPlayerTo( this.selectedIndex );
            }
            document.getElementById("background-chooser").onchange = function() {
            changeBackgroundTo( this.selectedIndex );
            }
            document.getElementById("text-chooser").onchange = function() {
            changeTextTo( this.selectedIndex );
            }
            }
        </script>
        <?php wp_head(); ?>
    </head>
    <body id="vgs-body">
        <div id="intro-text">
            <?php
            // JJD 4/17/24 #24 require dhammaworker login
            $user = wp_get_current_user();
            $userData = get_userdata($user->ID); // returns false if user is not logged in
            $login = (false == $userData ? 'newstudent' : $userData->user_login);
            if ('dhammaworker' !== $login) {
                show_404();
                exit; // don't show remainder of page content
            }
            if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    the_content(__('(more...)'));
                }
            }
            ?>
        </div>

        <div id="audio-controls">

            <div id="audio-sliders">
                <audio controls id="audio-one" class="audio">
                    <source src="https://discourses.dhamma.org/oml/recordings/uuid/121a78a9-1f19-477f-b465-4ef10e754812.mp3" type="audio/mpeg">
                </audio>
                <audio controls id="audio-two" class="audio">
                    <source src="https://discourses.dhamma.org/oml/recordings/uuid/dd619c48-7176-4b5a-8423-48b8ca0677ef.mp3" type="audio/mpeg">
                </audio>
                <audio controls id="audio-three" class="audio">
                    <source src="https://discourses.dhamma.org/oml/recordings/uuid/f172e558-fb5c-45d5-919c-9ecfbca7ec4e.mp3" type="audio/mpeg">
                </audio>
                <audio controls id="audio-four" class="audio">
                    <source src="https://discourses.dhamma.org/oml/recordings/uuid/6cb58916-5c4c-4834-aad5-7edd4099fd39.mp3" type="audio/mpeg">
                </audio>
                <audio controls id="audio-five" class="audio">
                    <source src="https://discourses.dhamma.org/oml/recordings/uuid/b6d63e17-850c-4530-bd44-10b7195e51d2.mp3" type="audio/mpeg">
                </audio>
                <audio controls id="audio-six" class="audio">
                    <source src="https://discourses.dhamma.org/oml/recordings/uuid/8545248c-7b45-4d7a-9dde-edc5dea71d2c.mp3" type="audio/mpeg">
                </audio>
                <audio controls id="audio-seven" class="audio">
                    <source src="https://discourses.dhamma.org/oml/recordings/uuid/cf7d590f-75e5-4707-87f8-105c74225669.mp3" type="audio/mpeg">
                </audio>
                <audio controls id="audio-eight" class="audio">
                    <source src="https://discourses.dhamma.org/oml/recordings/uuid/12dfbc7c-d06d-42e5-87e0-94c7046076cb.mp3" type="audio/mpeg">
                </audio>
                <audio controls id="audio-nine" class="audio">
                    <source src="https://discourses.dhamma.org/oml/recordings/uuid/6506bfcc-548b-4fca-a93b-968db754de9b.mp3" type="audio/mpeg">
                </audio>
                <audio controls id="audio-ten" class="audio">
                    <source src="https://discourses.dhamma.org/oml/recordings/uuid/f26d0a84-40da-4164-8a50-4cc81a63bd20.mp3" type="audio/mpeg">
                </audio>
                <audio controls id="audio-eleven" class="audio">
                    <source src="https://discourses.dhamma.org/oml/recordings/uuid/ace0da45-e219-4ae4-b979-8af98828eb50.mp3" type="audio/mpeg">
                </audio>
                <audio controls id="audio-twelve" class="audio">
                    <source src="https://discourses.dhamma.org/oml/recordings/uuid/f7fa61ac-41f1-4a43-9974-58262c7ce392.mp3" type="audio/mpeg">
                </audio>
            </div>
            <div id="control-choosers">
                <!--label for="session-chooser">Audio Session:</label-->
                <select id="session-chooser">
                    <option>1 - Khetta Short (1984) [edited for VGS]</option>
                    <option>2 - Sikhara Short (1998) [edited for VGS]</option>
                    <option>3 - Sikhara Long (1998)</option>
                    <option>4 - Setu Long (2000) [edited for VGS]</option>
                    <option>5 - Dubai Long (1999) [Hindi/Eng]</option>
                    <option>6 - Juhu Mumbai Short (1987) [edited for VGS]</option>
                    <option>7 - Salila Long (1998)</option>
                    <option>8 - Salila Short (1998)</option>
                    <option>9 – Giri Minimal Short (1985) [edited for VGS]</option>
                    <option>10 - Khetta Short (1984) [Fr/Eng]</option>
                    <option>11 - Giri Minimal Short (1985)  [Fr/Eng]</option>
                    <option>12 - Dubai Long (1999) [edited for VGS]</option>
                </select>
                <!--label for="background-chooser">Background:</label-->
                <select id="background-chooser">
                    <option>1 - Dharā Aerial</option>
                    <option>2 - Dharā Tree</option>
                    <option>3 - Dharā Gong</option>
                    <option>4 - Patapa Lotus</option>
                    <option>5 - Patapa Sign</option>
                    <option>6 - Patapa Dhamma Hall</option>
                    <option>7 - Pubannanda Day</option>
                    <option>8 - Pubannanda Night</option>
                    <!--<option>9 - Pakasa Pond</option>-->
                    <option>9 - Pakasa Gong</option>
                </select>
                <select id="text-chooser">
                    <option>1 - Intro Text</option>
                    <option>2 - Instructions</option>
                    <option>3 - Silent</option>
                </select>
                <button id="toggle-text-button" type="button" onclick="toggleTextHide()">Hide Text</button>
            </div>
        </div>
    </body>
</html>
