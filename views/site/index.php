<?php
//echo realpath("index.php");
/* @var $this yii\web\View */

$this->title = 'My PR';
?>
<?php $this->beginBlock('indexHeaderSlide'); ?>

<div id="owl-hero" class="owl-carousel owl-theme">
    <div class="item" style="background-image: url(img/sliders/Slide.jpg)">
        <div class="caption">
            <h6>Build your</h6>
            <h1>Public <span>Relations</span></h1>
            <a class="btn btn-transparent" href="/subscription">Become member</a><a class="btn btn-light" href="/contact">Contact</a>
        </div>
    </div>
    <div class="item" style="background-image: url(img/sliders/Slide2.jpg)">
        <div class="caption">
            <h6>Build your</h6>
            <h1>Public <span>Relations</span></h1>
            <a class="btn btn-transparent" href="subscription">Become member</a><a class="btn btn-light" href="/contact">Contact</a>
        </div>
    </div>
    <div class="item" style="background-image: url(img/sliders/Slide3.jpg)">
        <div class="caption">
            <h6>Build your</h6>
            <h1>Public <span>Relations</span></h1>
            <a class="btn btn-transparent" href="subscription">Become member</a><a class="btn btn-light" href="/contact">Contact</a>
        </div>
    </div>
</div>

<?php $this->endBlock(); ?>


<?php $this->beginBlock('indexSections'); ?>

<!-- Welcome
	============================================= -->
<section id="welcome">
    <div class="container">
        <h2>Welcome To <span>My PR</span></h2>
        <hr class="sep">
        <p>Make Yourself At Home Don't Be Shy</p>
        <img class="img-responsive center-block wow fadeInUp" data-wow-delay=".3s" src="img/welcome/logo.png" alt="logo">
        <div>
            <video src=""></video>
        </div>
    </div>
</section>

<!-- Services
    ============================================= -->
<section id="services">
    <div class="container">
        <h2>What We Do</h2>
        <hr class="light-sep">
        <p>We Can Do Crazy Things</p>
        <div class="services-box">
            <div class="row wow fadeInUp" data-wow-delay=".3s">
                <?php foreach ($privileges as $p) { ?>
                    <div class="col-md-4">
                        <div class="media-left"><span class="icon-lightbulb"></span></div>
                        <div class="media-body">
                            <h3><?= $p->titre ?></h3>
                            <p><?= $p->libelle_court ?>...</p>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <!--<div class="row wow fadeInUp" data-wow-delay=".3s">
                <div class="col-md-4">
                    <div class="media-left"><span class="icon-lightbulb"></span></div>
                    <div class="media-body">
                        <h3>Creative Design</h3>
                        <p>Fringilla augue at maximus vestibulum. Nam pulvinar vitae neque et porttitor. Praesent sed nisi eleifend.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="media-left"><span class="icon-mobile"></span></div>
                    <div class="media-body">
                        <h3>Bootstrap</h3>
                        <p>Fringilla augue at maximus vestibulum. Nam pulvinar vitae neque et porttitor. Praesent sed nisi eleifend.</p>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="media-left"><span class="icon-compass"></span></div>
                    <div class="media-body">
                        <h3>Sass &amp; Compass</h3>
                        <p>Fringilla augue at maximus vestibulum. Nam pulvinar vitae neque et porttitor. Praesent sed nisi eleifend.</p>
                    </div>

                </div>

                <div class="row wow fadeInUp" data-wow-delay=".6s">
                    <div class="col-md-4">
                        <div class="media-left"><span class="icon-adjustments"></span></div>
                        <div class="media-body">
                            <h3>Easy To Customize</h3>
                            <p>Fringilla augue at maximus vestibulum. Nam pulvinar vitae neque et porttitor. Praesent sed nisi eleifend.</p>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="media-left"><span class="icon-tools"></span></div>
                        <div class="media-body">
                            <h3>Photoshop</h3>
                            <p>Fringilla augue at maximus vestibulum. Nam pulvinar vitae neque et porttitor. Praesent sed nisi eleifend.</p>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="media-left"><span class="icon-wallet"></span></div>
                        <div class="media-body">
                            <h3>Money Saver</h3>
                            <p>Fringilla augue at maximus vestibulum. Nam pulvinar vitae neque et porttitor. Praesent sed nisi eleifend.</p>
                        </div>

                    </div>
                </div>
            </div>-->
        </div>
    </div>
    
</section>

<!-- Portfolio
    ============================================= -->
<section id="portfolio">
    <div class="container-fluid">
        <h2>News</h2>
        <hr class="sep">
        <p>News</p>
        <div class="row">
            <div class="col-md-offset-2 col-lg-8 wow fadeInUp">
            <?= dosamigos\gallery\Carousel::widget([
                'items' => $liste_nouv,
                'json' => true,
                'clientEvents' => [
                    'onslide' => 'function(index, slide) {
                        console.log(slide);
                    }'
            ]]); ?>
            </div>
        </div>
    </div>
</section>
<!-- Work Process
    ============================================= -->
<section id="work-process">
    <div class="container">
        <h2>Work Process</h2>
        <hr class="sep">
        <p>What Happen In The Background</p>
        <div class="row wow fadeInUp" data-wow-delay=".3s">
            <div class="col-lg-3">
                <span class="icon-chat"></span>
                <h4>1.Discuss</h4>
            </div>
            <div class="col-lg-3">
                <span class="icon-circle-compass"></span>
                <h4>2.Sketch</h4>
            </div>
            <div class="col-lg-3">
                <span class="icon-browser"></span>
                <h4>3.Make</h4>
            </div>
            <div class="col-lg-3">
                <span class="icon-global"></span>
                <h4>4.Publish</h4>
            </div>
        </div>
    </div>
</section>
<!-- Some Fune Facts
    ============================================= -->
<section id="fun-facts">
    <div class="container">
        <h2>Some Facts </h2>
        <hr class="light-sep">
        <p>Fun Facts</p>
        <div class="row wow fadeInUp" data-wow-delay=".3s">
            <div class="col-lg-3">
                <span class="icon-happy"></span>
                <h2 class="number timer">367</h2>
                <h4>Happy Clients</h4>
            </div>
            <div class="col-lg-3">
                <span class="icon-trophy"></span>
                <h2 class="number timer">150</h2>
                <h4>Project Done</h4>
            </div>
            <div class="col-lg-3">
                <span class="icon-wine"></span>
                <h2 class="number timer">121</h2>
                <h4>Glass Of Wine</h4>
            </div>
            <div class="col-lg-3">
                <span class="icon-documents"></span>
                <h2 class="number timer">10000</h2>
                <h4>Lines Of Code</h4>
            </div>
        </div>
    </div>
</section>
<!-- Team
    ============================================= -->
<section id="team">
    <div class="container">
        <h2>Our Team</h2>
        <hr class="sep">
        <p>Designers Behind This Work</p>
        <div class="row wow fadeInUp" data-wow-delay=".3s">
            <div class="col-md-4">
                <div class="team">
                    <img class="img-responsive center-block" src="img/team/MariaDoe.jpg" alt="1">
                    <h4>Maria Doe</h4>
                    <p>Designer</p>
                    <div class="team-social-icons">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-dribbble"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="team">
                    <img class="img-responsive center-block" src="img/team/JasonDoe.jpg" alt="2">
                    <h4>Jason Doe</h4>
                    <p>Developer</p>
                    <div class="team-social-icons">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-dribbble"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="team">
                    <img class="img-responsive center-block" src="img/team/MikeDoe.jpg" alt="3">
                    <h4>Mike Doe</h4>
                    <p>Photographer</p>
                    <div class="team-social-icons">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-dribbble"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Testimonials
    ============================================= -->
<section id="testimonials">
    <div class="container">
        <h2>Testimonials</h2>
        <hr class="light-sep">
        <p>What Clients Say About Us</p>
        <div id="owl-testi" class="owl-carousel owl-theme">
            <?php foreach ($temoignages as $tem){ ?>
            
                <div class="item">
                <div class="quote">
                    <img class="center-block thumbnail" style="max-width: 200px; max-height: 200px;" src="<?= $tem->image ?>">
                    <h5> <span> <?= $tem->auteur ?> : </span>
                    <i class="fa fa-quote-left left fa-2x"></i>
                        <?= $tem->contenu ?>
                    <i class="fa fa-quote-right right fa-2x"></i>
                    </h5>
                </div>
                </div>

            <?php } ?>
            
            
        </div>
    </div>
</section>
<!-- Google Map
    ============================================= -->
<div id="map"></div>

<?php $this->endBlock(); ?>