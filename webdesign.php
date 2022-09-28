<?php
include "authorization.php";

session_start();
$time = $_SERVER['REQUEST_TIME'];

/**
 * for a 30 minute timeout, specified in seconds
 */
$timeout_duration = 1800;

/**
 * Here we look for the user's LAST_ACTIVITY timestamp. If
 * it's set and indicates our $timeout_duration has passed,
 * blow away any previous $_SESSION data and start a new one.
 */
if (
    isset($_SESSION['LAST_ACTIVITY']) && ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration
) {
    if (isset($_SESSION['id'])) {
        $sql = "CALL checkLoginStp('deactivate','" . $_SESSION['id'] . "'," . $timeout_duration . ")";
        $records = $db->query($sql);
        session_destroy();
        unset($_SESSION['user']);
        unset($_SESSION['id']);
        unset($_SESSION['LAST_ACTIVITY']);
    }
} else {
    if (isset($_SESSION['id'])) {
        $sql = "CALL checkLoginStp('activate','" . $_SESSION['id'] . "',null')";
        $records = $db->query($sql);
        // header("Refresh: 0; URL=".$_SERVER['HTTP_REFERER']);
    }
}

/**
 * Finally, update LAST_ACTIVITY so that our timeout
 * is based on it and not the user's login time.
 */
$_SESSION['LAST_ACTIVITY'] = $time;
?>

<html lang="en">

<head>

    <meta charset="utf-8">
    <!-- responsive meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- For IE -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="title" content="Smiansh - Cost Effective Technology Solutions Provider" />
    <meta name="DC.title" content="Smiansh - Cost Effective Technology Solutions Provider" />
    <meta name="description" content="Smiansh is an Information Technology Company, we, at Smiansh, emphasis on excellency in work. It is our strength to deliver quality solution. SMIANSH will help you grow your Business online in a competitive market. In Today’s market, the new entrepreneurs struggle a lot to keep up competing with other businesses in the same domain. In such a situation, you must outperform them and attract new customers to your business showcasing your innovative ideas." />
    <meta name="keywords" content="android,mobile,website,web, application,developer,logo,desinger,smiansh,project,company,information,technology,developer,digital,marketing,india,surat,fast,speed,project,full stack,mean stack,database,php,javascript,jquery,css,java,UI,user, interface,UX,UI,experince,work,portfolio,blog,Artificial,brand,vendor,outsource,outsourcing,offshore,Intelligence,income,tax,success,mantra,article" />
    <meta name="robots" content="index,follow" />
    <meta name="google-site-verification" content="5Z-KL36CuKgr87orIk6cdHaksSrmCXxysx0-Vgc93P4" />
    <!-- Google Analytics -->
    <script>
        (function(i, s, o, g, r, a, m) {
            i["GoogleAnalyticsObject"] = r;
            i[r] = i[r] || function() {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, "script", "https://www.google-analytics.com/analytics.js", "ga");

        ga("create", "UA-121962609-2", "auto");
        ga("send", "pageview");
    </script>
    <!-- End Google Analytics -->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Font Awsome CSS -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <!-- Link Swipers CSS -->
    <link rel="stylesheet" href="assets/css/swiper.min.css" async defer>
    <!-- Animation css -->
    <link rel="stylesheet" href="assets/css/animate.css">
    <!-- Custom css -->
    <link rel="stylesheet" href="assets/css/custom.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/image/favicon-36.png" sizes="36x36">

    <title>Smiansh - Cost Effective Technology Solutions Provider</title>
</head>

<body>

    <div class="page_loader">
        <div class="ring">
            Loading
            <span class="loader_span"></span>
        </div>
    </div>

    <!-- Header start -->
    <header>
        <div class="main-header">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <a class="navbar-brand" href="index.php">
                        <img src="assets/image/logo.png" alt="img">
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="fa fa-bars"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavDropdown">
                        <ul class="navbar-nav ml-auto">

                            <li class="nav-item">
                                <a class="nav-link" href="index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="about.php">About us</a>
                            </li>
                            <li class="nav-item dropdown dropdown-slide dropdown-hover">
                                <a class="nav-link active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">Services</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="webdesign.php">Web Design & Development</a>
                                    <a class="dropdown-item" href="digital_marketing.php">Digital Marketing</a>
                                    <a class="dropdown-item" href="mobile_app.php">Mobile Application Development</a>
                                    <a class="dropdown-item" href="seo.php">SEO</a>
                                    <a class="dropdown-item" href="underConstruction.php">Artificial Intelligence</a>
                                    <a class="dropdown-item" href="underConstruction.php">Brand Development</a>
                                    <a class="dropdown-item" href="underConstruction.php">Outsourcing Service</a>
                                    <a class="dropdown-item" href="underConstruction.php">Talent Acquisition Service</a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="portfolio.php">Portfolio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="careers.php">Careers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="contact.php">Contact us</a>
                            </li>
                            <li class="nav-item dropdown dropdown-slide dropdown-hover">
                                <?php
                                if (isset($_SESSION['user'])) {
                                    ?>
                                    <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">
                                        <form style="margin:0;" id="userUpdate" method="GET" name="userUpdate" action="updateProfile.php">
                                            <label style="color:#f49727; margin-bottom:0;">Welcome
                                                <?php echo $_SESSION['user'] ?>
                                            </label>
                                        </form>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" onClick="document.getElementById('userUpdate').submit();">Edit Profile</a>
                                        <a class="dropdown-item" href="logout.php">Logout</a>
                                    </div>
                                <?php
                            } else {
                                echo '<a class="nav-link active smiansh_loginbtn" href="login.php">Login</a>';
                            }
                            ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <!-- Header end -->

    <!-- Inner header Start -->
    <section class="inner_header">
        <div class="inner_balls"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="inner_header_info">
                        <div class="inner_heading">
                            <h2>Web Design and Development</h2>
                        </div>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="index.php">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="index.php">Services</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Web Design and Development</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Inner header End -->

    <!-- Srvice detail section Start -->
    <section class="service_detail_sec common_padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="main_title mb-30">Web Design and Development</h4>
                    <b>We design and build the IT you need. Your business enjoys the growth you want.</b>
                    <p>It is necessary to possess a good web site for your business in today’s competitive world. Our skilled
                        creative Designers can produce a distinct persona for your business on the World Wide Web (WWW).
                        At SMIANSH, we tend to closely co-ordinate together with your consultants to make sure to make the
                        most effective style for your wants and engagements.
                    </p>
                    <p>Creating a web site for your business is incredibly important these days. It is an era of Information
                        Technology. The INTERNET is dominating the entire world. Therefore, if you have a profiting business
                        offline, you must maximize your profit by bringing it online. People don’t go shopping in the streets.
                        They purchase through online shopping web sites. It saves their time as well as money because these
                        sites give huge discounts and offers.</p>
                    <p>
                        You can also increase your customers by putting your business online in the form of a web site. Creating a web site for your
                        business can reach the population of the entire world. You can imagine how much this can be important
                        for your business. In short, you can promote your business to the whole world and increase the customer
                        base. More customers you engaged more profit you earn.
                    </p>
                    <p>
                        At SMIANSH, the Web Design and Development services are very cost effective. We develop web sites which are mobile first
                        and responsive in any device when viewed. We make it look beautiful with easy navigation result0ing
                        in user-friendliness.
                    </p>
                    <h6 class="font-weight-bold mb-10">Technologies We Offer </h6>
                    <ul class="proper_list">
                        <li>
                            Wordpress
                        </li>
                        <li>
                            Magento
                        </li>
                        <li>
                            PHP Frameworks
                        </li>
                        <li>
                            AngularJS
                        </li>
                        <li>
                            NodeJS
                        </li>
                        <li>
                            ExpressJS
                        </li>
                        <li>
                            React Native
                        </li>
                        <li>
                            Python/Django
                        </li>
                        <li>
                            Perl/CGI
                        </li>
                    </ul>
                    <h6 class="font-weight-bold mb-10">Features Include</h6>

                    <ul class="proper_list">
                        <li>Responsive Web Design</li>
                        <li>Easy Navigation</li>
                        <li>Easy to read Typography and understandable Icons</li>
                        <li>Attractive Home Pages with Sliders and Banners</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Start -->
    <section class="service_portfolio mb-50">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="main_title text-center mb-50">Portfolio</h4>

                    <!-- Swiper -->
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide wow fadeInUp">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 align_center">
                                        <div class="project_details">
                                            <h4 class="main_title mb-20">Re-Designing SMIANSH.COM</h4>
                                            <p class="project_para">
                                                Time to time upgrade of our own website is necessary with new look and feel. Our expert designers completely changed the
                                                site look and did a makeover.
                                            </p>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <a href="https://www.smiansh.com" target="_blank" class="smiansh_btn mt-20">Go to site</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="project_image">
                                            <img src="assets/image/project/portfolio_smiansh.jpg" alt="img" class="src">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 align_center">
                                        <div class="project_details">
                                            <h4 class="main_title mb-20">Brochure Design</h4>
                                            <p class="project_para">
                                                Brochure for the XEON Training Institute designed for the introductory course for Shell Scripting. We used Adobe Photoshop,
                                                Adobe Illustrator to create magic. Client put the complete trust on us for this design and she
                                                allowed the complete freedom to use our creativity for this.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 z_index">
                                        <div class="project_image">
                                            <img src="assets/image/project/portfolio_brochure.jpg" alt="img" class="src">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 align_center">
                                        <div class="project_details">
                                            <h4 class="main_title mb-20">Visiting Card</h4>
                                            <p class="project_para">
                                                Designed for Client Engagement Manager who does the frequent client visits. We wanted the design with vibrant colors with
                                                professional look. Tools Used: Adobe Photoshop and Illustrator
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="project_image">
                                            <img src="assets/image/project/portfolio_visiting card.jpg" alt="img" class="src">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 align_center">
                                        <div class="project_details">
                                            <h4 class="main_title mb-20">Home Textile (E-Commerce Web Development)</h4>
                                            <p class="project_para">
                                                This is the complete e-commerce solution from SMIANSH. Client wanted the web platform where he can sale various products
                                                online, accept payments, track orders, product returns etc. Technology Stack: HTML5, CSS3, JAVASCRIPT,
                                                PHP, MYSQL
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 z_index">
                                        <div class="project_image">
                                            <img src="assets/image/project/portfolio_ecommerce.jpg" alt="img" class="src">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 align_center">
                                        <div class="project_details">
                                            <h4 class="main_title mb-20">Logo Desing(Weeden)</h4>
                                            <p class="project_para">
                                                Client wanted the unique logo with the design of plant leaves. We designed the logo as you can see in the result. Tools used:
                                                Adobe Photoshop and Illustrator.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="project_image">
                                            <img src="assets/image/project/portfolio_weeden.jpg" alt="img" class="src">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="row mb-50">
                                    <div class="col-lg-6 col-md-12 align_center">
                                        <div class="project_details">
                                            <h4 class="main_title mb-20">Logo Designing (Smiansh)</h4>
                                            <p class="project_para">
                                                We wanted a very simple logo yet creative and apealing. We used Adobe Photoshop and Illustrator to design this.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 z_index">
                                        <div class="project_image">
                                            <img src="assets/image/project/smiansh_logo.jpg" alt="img" class="src">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="row mb-50">
                                    <div class="col-lg-6 col-md-12 align_center">
                                        <div class="project_details">
                                            <h4 class="main_title mb-20">Application Design for Restaurant</h4>
                                            <p class="project_para">
                                                This is an android application design created in Adobe Photoshop, developed for a local restaurant to support their business online.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="project_image">
                                            <img src="assets/image/project/restaurant.jpg" alt="img" class="src">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="row mb-50">
                                    <div class="col-lg-6 col-md-12 align_center">
                                        <div class="project_details">
                                            <h4 class="main_title mb-20">Inauguration Card for Electronic Shop</h4>
                                            <p class="project_para">
                                                This is a local security store assignment where we created an inauguration Card for invitation.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 z_index">
                                        <div class="project_image">
                                            <img src="assets/image/project/inaugration-card.jpg" alt="img" class="src">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="row mb-50">
                                    <div class="col-lg-6 col-md-12 align_center">
                                        <div class="project_details">
                                            <h4 class="main_title mb-20">Website Redesigning</h4>
                                            <p class="project_para">
                                                This is a complete redesign of the site from the older outdated html version.
                                            </p>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <a href="http://gradient.smiansh.com" target="_blank" class="smiansh_btn mt-20">Go to Site</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="project_image">
                                            <img src="assets/image/project/gradient.jpg" alt="img" class="src">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="row mb-50">
                                    <div class="col-lg-6 col-md-12 align_center">
                                        <div class="project_details">
                                            <h4 class="main_title mb-20">Theme for Doctor</h4>
                                            <p class="project_para">
                                                This is developed for the client who provides the Health Care Solutions to their customers. They wanted beautiful design wchich can generate the leads to their business.
                                            </p>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <a href="https://doctorpro.smiansh.com" target="_blank" class="smiansh_btn mt-20">Go to Site</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 z_index">
                                        <div class="project_image">
                                            <img src="assets/image/project/doctor.jpg" alt="img" class="src">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="row mb-50">
                                    <div class="col-lg-6 col-md-12 align_center">
                                        <div class="project_details">
                                            <h4 class="main_title mb-20">Logo Desing(EV)</h4>
                                            <p class="project_para">
                                                Logo design for client who deals in green energy sector and wanted something showcasing green energy usage and use their name initials.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="project_image">
                                            <img src="assets/image/project/portfolio_evlogo.jpg" alt="img" class="src">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="row mb-50">
                                    <div class="col-lg-6 col-md-12 align_center">
                                        <div class="project_details">
                                            <h4 class="main_title mb-20">Certificate for Club</h4>
                                            <p class="project_para">
                                                Custom Winning Certificate for local club
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 z_index">
                                        <div class="project_image">
                                            <img src="assets/image/project/certificate.jpg" alt="img" class="src">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="row mb-50">
                                    <div class="col-lg-6 col-md-12 align_center">
                                        <div class="project_details">
                                            <h4 class="main_title mb-20">Logo Desing(b)</h4>
                                            <p class="project_para">
                                                Our client wanted a very creative but stong presence of the initial letter "B" of their business.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="project_image">
                                            <img src="assets/image/project/B-logo.jpg" alt="img" class="src">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Add Arrows -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- Portfolio End -->

    <!-- Srvice detail section End -->

    <!-- Subscribe Start -->
    <section class="subscribe_us common_padding">
        <div class="subscribe_balls"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center wow fadeInUp">
                    <img src="assets/image/subscribe_img.png" alt="img" class="subscribe_img wow fadeInUp">
                </div>
                <div class="col-md-6 align_center">
                    <div class="subscribe_content wow fadeInUp">
                        <h4 class="main_title">Subscribe us</h4>
                        <p>Get latest update about innovation in our newsletters and interesting offers.</p>
                        <div class="subscribe_input">
                            <form class="form-inline" method="POST" action="subscription.php" id="scbscribe">
                                <input type="email" class="form-control email_input" placeholder="Enter your email" name="email" required>
                                <!-- <a href="#" class="smiansh_btn" onclick="document.getElementById('scbscribe').submit();">Subscribe</a> -->
                                <button type="submit" class="smiansh_btn">Subscribe</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Subscribe End -->


    <!--Footer Start -->
    <footer class="smiansh_footer">
        <div class="footer_bg">
            <div class="footer_balls"></div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 pl-50">
                        <div class="footer_logo_detail">
                            <img src="assets/image/logo.png" alt="img" class="footer_logo">
                            <p class="footer_para">Thank you for visiting SMIANSH and we are excited to have an engagement with us for our future
                                endeavor.
                            </p>
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="contact.php" class="smiansh_btn mt-20">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 align_bottom">
                        <div class="footer_detail">
                            <ul class="main_list">
                                <li>
                                    <a href="mailto:contact@smiansh.com" class="mail_id">contact@smiansh.com</a>
                                </li>
                                <li>
                                    <a href="tel:+919766320048" class="phn_no">+91 976 632 0048</a>
                                </li>
                                <ul class="social_media">
                                    <li>
                                        <a href="https://www.facebook.com/smiansh19" class="href" target="_blank">
                                            <i class="fa fa-facebook"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.instagram.com/smiansh_tech/" class="href" target="_blank">
                                            <i class="fa fa-instagram"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="href">
                                            <i class="fa fa-linkedin"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="href">
                                            <i class="fa fa-twitter"></i>
                                        </a>
                                    </li>
                                </ul>
                                <li>
                                    <div class="copyrightFooter">Copyright <span id="spanYear"><?php echo date('Y'); ?></span> © <strong>Smiansh</strong></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--Footer End -->

    <!-- Back to top Button -->
    <a class="scroll-top" href="#top" id="top" style="display: inline;">
        <i class="fa fa-angle-up"></i>
    </a>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="assets/js/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Swiper JS -->
    <script src="assets/js/swiper.min.js"></script>
    <!-- Animation JS -->
    <script src="assets/js/wow.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/custom.js"></script>

    <script>
        $("#top").hide();
        $(window).scroll(function() {
            if ($(window).scrollTop() > 200) {
                jQuery("#top").fadeIn(500);
            } else {
                $("#top").fadeOut(500);
            }
        });
        $("#top").click(function() {
            $('body,html').animate({
                scrollTop: 0
            }, 1000);
            return false;
        });
    </script>
</body>

</html>