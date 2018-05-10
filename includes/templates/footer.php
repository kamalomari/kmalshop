<br />
<!-- Start Scroll To Top -->

<div id="scroll-top">
    <i class="fa fa-chevron-up fa-2x"></i>
</div>

<!-- End Scroll To Top -->
<div class="content">
</div>
<footer id="myFooter">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 text-center feat wow pulse-grow" data-wow-duration="2s" data-wow-offset="200">
                <h4>Get started</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="login.php">Sign up</a></li>
                    <li><a href="downloads.php">Downloads</a></li>
                </ul>
            </div>
            <div class="col-sm-4 text-center feat wow pulse-grow" data-wow-duration="2s" data-wow-offset="200">
                <h4>About us</h4>
                <ul>
                    <li><a href="about.php">Company Information</a></li>
                    <li><a href="contact.php">Contact us</a></li>
                    <li><a href="#">Reviews</a></li>
                </ul>
            </div>
            <div class="col-sm-4 text-center feat wow pulse-grow" data-wow-duration="2s" data-wow-offset="200">
                <h4>Support</h4>
                <ul>
                    <li><a href="faq.php">FAQ</a></li>
                    <li><a href="#">Help desk</a></li>
                    <li><a href="#">Forums</a></li>
                </ul>
            </div>
        </div>
        <!-- Here we use the Google Embed API to show Google Maps. -->
        <!-- In order for this to work in your project you will need to generate a unique API key.  -->
<!--        <iframe id="map-container" frameborder="0" style="border:0"-->
<!--                src="https://www.google.com/maps/embed/v1/place?q=place_id:ChIJOwg_06VPwokRYv534QaPC8g&key=AIzaSyDhaZNPFYeVaDkUZdyqqwxxm3Xp5k00R4A" >-->
<!--        </iframe>-->
    </div>
<!--    Choice Do You Need Maps Site-->
    <?php
    if (!isset($noMap)) {
        ?>
        <div class="container">

            <iframe id="map"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d105816.67617904086!2d-5.071780977873374!3d34.024085275777466!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd9f8b484d445777%3A0x10e6aaaeedd802ef!2sFes%2C+Morocco!5e0!3m2!1sen!2s!4v1507724101114"
                    width="100%"
                    height="400"
                    frameborder="0"
                    style="border:0"
                    allowfullscreen></iframe>
        </div>
        <?
    }
    ?>
    <div class="social-networks">
        <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
        <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
        <a href="#" class="google"><i class="fa fa-google-plus"></i></a>
        <a href="#" class="google"><i class="fa fa-youtube"></i></a>
    </div>
    <div class="footer-copyright">
        <p>© 2018 Copyright </p>
    </div>
</footer>
<!--no scrpt sbmt five -->
<noscript>
    <style>
        form{display: none;}
    </style><!-- bideH belemE tormF -->
</noscript>
<!-- Start incluse google translate to website by JavaScript -->
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<!-- jQueryUI (JavaScript plugins) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/js/bootstrap.min.js"></script>
<!-- CDN selectbott jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.selectboxit/3.8.0/jquery.selectBoxIt.min.js"></script>
<script src="<? echo $js; ?>frontend.js"></script>
<script src="<? echo $js; ?>wow.min.js"></script>
<script src="<? echo $js; ?>jquery.nicescroll.min.js"></script>
<script>new WOW().init();</script>
</body>
</html>