<?php
include "authorization.php";
$TOKEN_VALIDATED = FALSE;
$REQUEST_METHOD = "";
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $REQUEST_METHOD = "GET";
    // Check for tokens
    $tokenSelector = filter_input(INPUT_GET, 'selector');
    $validator = filter_input(INPUT_GET, 'validator');

    if (false !== ctype_xdigit($tokenSelector) && false !== ctype_xdigit($validator)) {
        //Check if the selector is geneuine and not expired
        $sql = "SELECT * FROM UserTb WHERE selector='" . $tokenSelector . "' AND expires>=" . time();
        //echo $sql."-----";
        $rc = $db->query($sql);
        //echo $rc;
        if ($rc->num_rows > 0) {
            $row = $rc->fetch_assoc();
            $email = $row['UserName'];
            $authToken = $row['token']; #Token from Database
            $token = hash('sha256', $validator);
            if (hash_equals($token, $authToken)) {
                $TOKEN_VALIDATED = TRUE;
                //$message = "Token Validated";
            } else {
                $TOKEN_VALIDATED = FALSE;
                //$message = "Token Invalid";//[<br>".$token."==<br>".$validator."==<br>".$authToken."]";
            }
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
    $REQUEST_METHOD = "POST";
    $missingValues = FALSE;
    if (empty($_POST['Pass'])) {
        $passErrMsg = "Password is required";
        $missingValues = TRUE;
    } else {
        $secret = TestInputFormElements($_POST['Pass']);
    }
    if (empty($_POST['CnfPass'])) {
        $cnfPassErrMsg = "Confirm Password is required";
        $missingValues = TRUE;
    } else {
        $cnfSecret = TestInputFormElements($_POST['CnfPass']);
    }
    if ($_POST['Pass'] != $_POST['CnfPass']) {
        $cnfPassErrMsg = "Confirm Password is different";
        $missingValues = TRUE;
    }
    if ($missingValues == FALSE) {
        $tokenSelector = $_POST['selector'];
        $token = $_POST['validator'];
        $secret = $_POST['Pass'];
        $email = $_POST['email'];

        $sql = "CALL resetPasswordStp('" . $email . "','" . $token . "','" . $secret . "')";
        $rc = $db->query($sql);
        //echo $rc->fetch_assoc()['Token Expired'];
        //$PASS_RESET=FALSE;
        if ($rc == TRUE and $rc->fetch_assoc()['Output'] != 'Token Expired') {
            $message = '<p class="footer_para">Password has been reset.<br>Please retry login.</p>';
            //$PASS_RESET=TRUE;
        } else {
            $message = '<p class="footer_para">There seems to be some error, please report to support@smiansh.com immediately.</p>';
        }
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

    <section class="under_construcion_page">
        <div class="under_construction_content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <img src="assets/image/under_con.png" alt="under construction" class="under_con_img">

                        <?php
                        if ($REQUEST_METHOD == "GET") {
                            if ($TOKEN_VALIDATED == TRUE) {
                                echo '<div class="my-form col-md-6 mx-auto new_password">
                                        <form action="newPassword.php" method="POST" name="reset" class="new_pass_form">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" id="pass" name="Pass">
                                            <span class="error">' . $passErrMsg . '</span>
                                        </div>
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input type="password" class="form-control" id="cnfpass" name="CnfPass">
                                            <span class="error">' . $cnfPassErrMsg . '</span>
                                        </div>
                                        <input type="hidden" name="selector" id="selector" value="' . $tokenSelector . '">
                                        <input type="hidden" name="validator" id="validator" value="' . $authToken . '">
                                        <input type="hidden" name="email" id="email" value="' . $email . '">
                                        <div class="form-group text-center">
                                            <button type="submit" class="btn btn-login">Submit</button>
                                        </div>
                                        <p><a class="smiansh_loginbtn" href="login.php">Login</a></p>
                                    </form></div>';
                            } else {
                                echo '<p class="footer_para">Link is expired. Please click on Forgot Password again to generate fresh link.</p>';
                            }
                        } else {
                            echo $message;
                        }
                        ?>
                        <img src="assets/image/under_con.png" alt="under construction" class="under_con_img">
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

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