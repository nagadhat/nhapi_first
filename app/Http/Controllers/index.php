<?php
function route_url($name = "")
{
    return "https://wetalents.net{$name}";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <title>We Talents | Home</title>

    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">

    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
   <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PRBSRLD');</script>
<!-- End Google Tag Manager -->	
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PRBSRLD"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <header>
        <div class="container">
            <div class="all_part">
                <div class="logo_part">
                    <div class="logo">
                        <a href="<?= route_url() ?>">
                            <svg width="101" height="44" viewBox="0 0 101 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M94.3243 32.9C92.462 34.1934 90.5119 35.4812 87.189 35.4812C82.3774 35.4812 80.4755 33.4349 80.4755 33.4349C80.4755 33.4349 85.4116 27.5931 85.4116 17.9755C85.4116 9.29203 82.451 0.0877401 75.9158 0.0877401C68.4606 0.0877401 64.6906 9.14485 64.6906 15.8924C64.6906 24.7655 66.5218 29.7611 66.5218 29.7611C66.5218 29.7611 61.5716 35.1218 55.8316 32.9651C55.8316 32.9651 61.0932 27.3044 61.0932 17.5312C61.0932 11.644 59.7828 0.0877401 51.6569 0.0877401C44.6489 0.0877401 40.9015 8.02686 40.9015 16.2207C40.9412 20.7861 41.5237 25.3305 42.6366 29.7583C42.6366 29.7583 37.8051 35.7982 32.3652 32.9651C32.3652 32.9651 37.2108 26.8515 37.2108 17.5312C37.2108 12.8356 35.272 0 27.0696 0C19.9145 0 16.3426 10.444 16.3426 15.6688C16.3716 20.4626 17.1744 25.2204 18.7201 29.7583C11.0187 36.2681 8.59314 35.5718 4.43536 33.367C3.28907 32.7613 1.77483 32.7613 0.787039 33.7462C-0.3451 34.8925 -0.260189 37.103 1.07007 38.6201C5.80241 44.0232 15.2982 46.6385 25.7281 40.2702C37.1429 48.5857 48.5774 40.1428 48.5774 40.1428C48.5774 40.1428 60.2498 48.7301 73.5213 40.1966C80.7189 45.6648 93.1837 45.2516 99.2775 38.6625C103.458 34.1425 98.6406 29.6564 94.3243 32.9ZM24.9214 24.2023C24.9214 24.2023 23.8487 18.7992 23.8487 16.5122C23.8487 14.2253 24.1968 10.1666 26.4838 10.1666C27.9754 10.1666 29.3679 12.6375 29.3679 15.2216C29.3707 19.7926 24.9214 24.2023 24.9214 24.2023ZM49.6558 24.2023C49.6558 24.2023 48.5888 18.7992 48.5888 16.5122C48.5888 14.2253 48.9369 10.1666 51.2238 10.1666C52.7126 10.1666 54.1051 12.6375 54.1051 15.2216C54.1051 19.7926 49.6558 24.2023 49.6558 24.2023ZM73.2666 24.2023C73.2666 24.2023 72.1995 18.7992 72.1995 16.5122C72.1995 14.2253 72.5477 10.1666 74.8317 10.1666C76.3233 10.1666 77.7159 12.6375 77.7159 15.2216C77.7159 19.7926 73.2666 24.2023 73.2666 24.2023Z" fill="#1A171B" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="discover_part">
                    <a href="<?= route_url("/search/talents") ?>">We match together!</a>
                </div>
                <div class="menu_part">
                    <div class="right_menu">
                        <ul>
                            <li><a href="<?= route_url("/login") ?>" class="signin">Sign In</a></li>
                        </ul>
                    </div>
                    <div class="hamburger" onclick="menuOpen()">
                        <div class="icon-line"></div>
                        <div class="icon-line"></div>
                        <div class="icon-line"></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div id="mobile_toggle" class="mobile_header">
        <div id="mobile-menu">
            <div class="top-part">
                <div class="cross">
                    <button onclick="menuClose()"></button>
                </div>
                <div class="top-menu">
                    <a href="<?= route_url("/login") ?>" class="signin">Sign In</a>
                </div>
            </div>

            <ul class="last-menu">
                <li>
                    <a href="<?= route_url("/search/talents") ?>" class="discover">We match together!</a>
                </li>
            </ul>

            <div class="signup">
                <a href="<?= route_url("/register") ?>">Sign Up</a>
            </div>
        </div>
    </div>

    <section id="home_banner" class="banner_overlay">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Einfach Freelancer:innen abrechnen</h2>
                    <div class="home_banner_link">
                        <a href="<?= route_url("/direct-invoice-job/create") ?>" class="black_btn width25" type="button">Payroll Tool ausprobieren</a>
                    </div>
                </div>
                <div class="offset-lg-6"></div>
            </div>
        </div>
    </section>

    <section id="home_option">
        <div class="container">
            <div class="row">
                <div class="offset-lg-3 offset-md-3"></div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="all_option">
                        <h3>Radikal einfach</h3>
                        <p>Erstelle dein Projekt, füge Freelancer:innen hinzu, halte fest, wie hoch der auszubezahlende Betrag ist und löse mit einem Klick eine Abrechnungseinladung für eine:n oder gleich mehrere Freelancer:innen gleichzeitig aus. Damit ist es für dich quasi erledigt.</p>
                        <a href="#talentSection" class="black_btn scroll_slowly">Alle Payroll Tool Vorteile</a>
                    </div>
                </div>
                <div class="offset-lg-3 offset-md-3"></div>
            </div>
        </div>
    </section>

    <?php
    $db = "ronorp";
    $username = "root";
    $host = "127.0.0.1";
    $password = "$$123admin";

    $conn = new mysqli($host, $username, $password, $db);

    if ($conn->connect_error) {
        echo ("Connection failed: " . $conn->connect_error);
    }

    $tablename = "unsere_photo_management";
    $userphoto_sql = "select * from {$tablename} where `status` = 0";
    $gold_userphoto_sql = "select * from {$tablename} where `status` = 2 limit 6";
    $userphoto_result = $conn->query($userphoto_sql);
    $gold_userphoto_result = $conn->query($gold_userphoto_sql);

    if ($userphoto_result->num_rows > 0 || $gold_userphoto_result->num_rows > 0) {
    ?>
        <section id="partner" class="gray_background">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2>We match! Mit und dank</h2>
                        <p>#JoinUs #BecomePartner #DoTheWeThing</p>
                        <?php if ($gold_userphoto_result->num_rows > 0) { ?>
                            <div class="fixed_image">
                                <div class="row">
                                    <?php while ($row = $gold_userphoto_result->fetch_assoc()) { ?>
                                        <div class="col-lg-2 col-md-4 col-sm-6 vm">
                                            <div class="stat_img">
                                                <img src="<?= route_url("/storage/" . $row['image_name']) ?>" class="img-fluid" alt="partner-photo">
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($userphoto_result->num_rows > 0) { ?>
                            <div class="owl-carousel">
                                <?php while ($item = $userphoto_result->fetch_assoc()) { ?>
                                    <div class="partner_pic">
                                        <img src="<?= route_url("/storage/" . $item['image_name']) ?>" class="img-fluid" alt="partner-photo">
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
    <?php
    }

    $conn->close();

    ?>

    <section class="home_feature" id="talentSection">
        <div class="container">
            <h2>Für Unternehmen und Organisationen</h2>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="feature_step">
                        <div class="step_icon">
                            <?php include('homesvg/j1.php') ?>
                        </div>
                        <h4>Zeitgewinn</h4>
                        <p>Du zahlst Honorare bequem und mit wenigen Klicks per Kreditkarte oder Banktransfer aus.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="feature_step">
                        <div class="step_icon">
                            <?php include('homesvg/j6.php') ?>
                        </div>
                        <h4>Alles inklusive</h4>
                        <p>Du bekommst eine clevere Talentverwaltung inklusive Onboarding und Abrechnung deiner Freelancer:innen.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="feature_step">
                        <div class="step_icon">
                            <?php include('homesvg/j2.php') ?>
                        </div>
                        <h4>Kostensparend</h4>
                        <p>Alle administrativen Arbeiten rund um die Löhne übernimmt We Talents für dich.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="feature_step">
                        <div class="step_icon">
                            <?php include('homesvg/j3.php') ?>
                        </div>
                        <h4>Payrolling läuft über uns</h4>
                        <p>Mühsames Lohnmanagement und Abrechnen – spar dir den Stress. Payrolling und Lohnabrechnung laufen über uns. Egal, ob deine Talente juristisch Selbständig sind oder nicht. Dank dem integrierten Payrolling übernehmen wir an dieser Stelle die Rolle des Arbeitgebenden inkl. Lohnzahlung, Versicherungen, Sozialbeiträge und den ganzen Papierkram.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="feature_step">
                        <div class="step_icon">
                            <?php include('homesvg/t5.php') ?>
                        </div>
                        <h4>Übersichtlich & preiswert</h4>
                        <p>Alle Freelancer:innen, Honorarübersichten und Auszahlungen findest du in deinem persönlichen Payroll Tool Dashboard. Im besten Paket zahlst du nur 1 Franken pro Rechnung.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="feature_step">
                        <div class="step_icon">
                            <?php include('homesvg/j5.php') ?>
                        </div>
                        <h4>Digital & persönlich</h4>
                        <p>Du tauschst dich mit Freelancer:innen über den One-to-one Chat aus.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home_feature mt-5 gray_background" id="jobSection">
        <div class="container">
            <h2>Kundenecho</h2>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="feature_step">
                        <div class="step_icon">
                            <img src="assets/images/Testimonial1.jpg" class="img-fluid" alt="icon">
                        </div>
                        <p> "Endlich! Meine Ressourcen sind zurück bei meiner Leidenschaft. <span style="color: red;">&#10084;</span> We Talents."</p>
                        <p>Mia, Studio MK2</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="feature_step">
                        <div class="step_icon">
                            <img src="assets/images/Testimonial2.jpg" class="img-fluid" alt="icon">
                        </div>
                        <p>"Drei Klicks und die Honorare sind ausbezahlt."</p>
                        <p>Sabine, Ron Orp</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="feature_step">
                        <div class="step_icon">
                            <img src="assets/images/Testimonial3.jpg" class="img-fluid" alt="icon">
                        </div>
                        <p>"Der Zeitgewinn ist phänomenal!"</p>
                        <p>Michael, Spezialist für HR und Arbeitsmarkt</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2>Stick with the people who pull the magic out of you and not the madness</h2>
                    <p>#wedothewething #newworkmatch #alwaysbettertogether</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="down_menu">
                <ul>
                    <li><a target="_blank" href="https://journal.wetalents.net">Journal</a></li>
                    <li><a target="_blank" href="<?= route_url("/plans") ?>">Abos</a></li>
                    <li><a target="_blank" href="<?= route_url("/faq") ?>">FAQ</a></li>
                    <li><a target="_blank" href="<?= route_url("/about-us") ?>">Über uns</a></li>
                    <li><a target="_blank" href="<?= route_url("/partner-photos") ?>">Supporter</a></li>
                    <li><a target="_blank" href="<?= route_url("/contact-us") ?>">Kontakt</a></li>
                    <li><a target="_blank" href="<?= route_url("/impressum") ?>">Impressum</a></li>
                    <li><a target="_blank" href="<?= route_url("/wtfi") ?>">Future Institute</a></li>
                    <li><a target="_blank" href="<?= route_url("/terms") ?>">AGB</a></li>
                </ul>
            </div>
            <div class="last-part">
                <div class="footer_logo">
                    <div class="image">
                        <a href="/">
                            <img src="assets/images/footer-logo.png" class="img-fluid" alt="logo">
                        </a>
                    </div>
                    <div class="copyright">
                        <p>&copy; <?php echo date('Y') ?> We Talents AG, Zürich, Schweiz</p>
                    </div>
                </div>
                <div class="social_icons">
                    <ul>
                        <li><a href="#"><i class="fa fa-facebook-f"></i></a></li>
                        <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                        <?php /* ?>
                        <li>
                            <div class="dropdown">
                                <button type="button" data-toggle="dropdown">
                                    English <i class="fa fa-angle-down"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">German</a>
                                </div>
                            </div>
                        </li>
                        <?php */ ?>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <button id="top_bottom_btn">
        <i class="fa fa-angle-up"></i>
    </button>

    <script src="assets/js/app.js "></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-smooth-scroll/1.7.1/jquery.smooth-scroll.min.js"></script>
    <script>
        function menuOpen() {
            document.getElementById("mobile_toggle").classList.toggle("menu-active");
        }

        function menuClose() {
            document.getElementById("mobile_toggle").classList.remove("menu-active");
        }

        const prevIcon = '<img src="assets/images/arrow-left.png" class="previcon">';
        const nextIcon = '<img src="assets/images/arrow-right.png">';
        $('.owl-carousel').owlCarousel({
            margin: 30,
            dots: false,
            nav: true,
            loop: false,
            autoplay: true,
            autoplayTimeout: 2000,
            autoplayHoverPause: true,
            navText: [
                prevIcon,
                nextIcon
            ],
            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                },
                480: {
                    items: 3,
                },
                768: {
                    items: 4,
                },
                992: {
                    items: 6,
                }
            }
        })

        var btn = $('#top_bottom_btn');
        $(window).scroll(function() {
            if ($(window).scrollTop() > 600) {
                btn.addClass('show');
            } else {
                btn.removeClass('show');
            }
        });

        btn.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, '600');
        });

        $(function() {
            $('.scroll_slowly').smoothScroll();
        });
    </script>
</body>

</html>
