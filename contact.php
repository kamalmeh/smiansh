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
        unset($_SESSION['email']);
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

##Process the submitted contact form
$nameErrMsg = $emailErrMsg = $phoneErrMsg = $queryErrorMsg = ""; #Initialize to empty
$name = $queryText = $email = $phone = "";
$missingValues = FALSE;
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty($_POST['Name'])) {
        $nameErrMsg = "Name is required";
        $missingValues = TRUE;
    } else {
        $name = TestInputFormElements($_POST['Name']);
    }
    if (empty($_POST['Email'])) {
        $emailErrMsg = "Email is required and will be used to contact you to answer your query";
        $missingValues = TRUE;
    } else {
        $email = TestInputFormElements($_POST['Email']);
    }
    if (empty($_POST['Phone'])) {
        $passErrMsg = "Phone is required";
        $missingValues = TRUE;
    } else {
        $phone = TestInputFormElements($_POST['Phone']);
    }
    if (empty($_POST['Query'])) {
        $passErrMsg = "Query should not be empty and less than 1000 characters";
        $missingValues = TRUE;
    } else {
        $queryText = TestInputFormElements($_POST['Query']);
    }
    if ($missingValues == FALSE) {
        $sql = "CALL insertQueryStp('" . $name . "','" . $email . "','" . $phone . "','" . $queryText . "')";
        $rs = $db->query($sql);
        if ($rs === true) {
            //echo "Query Submitted. Redirecting...";
            // ini_set('SMTP', 'mail.smiansh.com');
            // ini_set('smtp_port', 465);
            // ini_set('sendmail_from', 'kamal.h.mehta@smiansh.com');
            mail("contact@smiansh.com", "QUERY - " . $email, $queryText, "From: " . $email);
            // header("Refresh: 10;URL=login.php");
        } else {
            if ($db->query($sql) === false) {
                echo "There seems to be some issue, pease try after sometime.";
            }
            if (isset($_SERVER["HTTP_REFERER"])) {
                echo '<a href="' . $_SERVER['HTTP_REFERER'] . '">Go Back</a>';
            }
        }
    } else {
        echo "There seems to be some issue, pease try after sometime.";
    }
}
function TestInputFormElements($data)
{
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
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
        <div class="main-header bg_black">
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
                                <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">Services</a>
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
                                <a class="nav-link active" href="contact.php">Contact us</a>
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
    <!-- Inner header End -->

    <section class="contactpage_form common_padding mt-50">
        <div class="container">
            <div class="row just_cen">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <form action="contact.php" class="contact_form wow zoomIn" method="POST">
                        <h4 class="main_title text-center mb-20">Contact Us</h4>
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-12">
                                <label for="fname">Enter Your Name</label>
                                <input type="text" class="form-control" id="name" name="Name" required value=<?php if (isset($_SESSION['user'])) echo $_SESSION['user']; ?>>
                            </div>
                            <div class="form-group col-lg-6 col-md-12">
                                <label for="pnumber">Enter Your Phone number</label>
                                <div style="display: flex;">
                                    <input type="tel" class="form-control" id="code" name="code" pattern="\+[0-9]+" placeholder="+91" style="width: 47px; text-align: center" required>
                                    <input type="tel" class="form-control" id="phone" name="Phone" pattern="[0-9]{10}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="email">Enter Your Email</label>
                                <input type="email" class="form-control" id="email" name="Email" required value=<?php if (isset($_SESSION['email'])) echo $_SESSION['email']; ?>>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="detail">Enter Your Details</label>
                                <textarea type="textarea" class="form-control" id="sub" rows="4" name="Query" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-12 mt-30 text-center">
                            <button type="submit" class="smiansh_btn">Submit</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

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