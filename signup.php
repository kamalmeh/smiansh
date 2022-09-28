<?php
include "authorization.php";

session_start();
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
    <?php
    $fNameErrMsg = $lNameErrMsg = $emailErrMsg = $passErrMsg = $cnfPassErrMsg = ""; #Initialize to empty
    $firstName = $lastName = $email = $secret = $cnfSecret = "";
    $missingValues = $emailNotRegistered = FALSE;

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (empty($_POST['FirstName'])) {
            $fNameErrMsg = "First Name is required";
            $missingValues = TRUE;
        } else {
            $firstName = TestInputFormElements($_POST['FirstName']);
        }
        if (empty($_POST['LastName'])) {
            $lNameErrMsg = "Last Name is required";
            $missingValues = TRUE;
        } else {
            $lastName = TestInputFormElements($_POST['LastName']);
        }
        if (empty($_POST['Email'])) {
            $emailErrMsg = "Email is required and will be used as your login username";
            $missingValues = TRUE;
        } else {
            $email = TestInputFormElements($_POST['Email']);
        }
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
        if ($missingValues == FALSE) {
            $sql = "SELECT 123 FROM UserTb where UserName='" . $email . "'";
            $rs = $db->query($sql);
            if ($rs->num_rows > 0) {
                $emailErrMsg = 'Email is already registered.Click <a href="forgotPassword.php" class="hreg">Here</a> to Reset the Password';
                $emailNotRegistered = TRUE;
            }
            if ($secret == $cnfSecret and $emailNotRegistered == FALSE) {
                $sql = "INSERT INTO UserTb(FirstName, LastName, UserName, Password)
                            VALUES ('" . $firstName . "','" . $lastName . "','" . $email . "',PASSWORD('" . $secret . "'))";
                echo $sql;
                $rs = $db->query($sql);
                if ($rs === true) {
                    echo "Registration Successful. Redirecting...";
                    header("Refresh: 1;URL=login.php");
                } else {
                    if ($db->query($sql) === false) {
                        echo "There seems to be some issue, pease try after sometime.";
                    }
                    if (isset($_SERVER["HTTP_REFERER"])) {
                        echo '<a href="' . $_SERVER['HTTP_REFERER'] . '">Go Back</a>';
                    }
                }
            } else {
                $cnfPassErrMsg = "Conirm Password is not matching";
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
                            <li class="nav-item">
                                <a class="nav-link active smiansh_loginbtn" href="login.php">Login</a>
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

    <!-- login form Start-->
    <section class="login1 common_padding mt-50">
        <div class="container">
            <div class="login-form">
                <div class="row just_cen">
                    <div class="col-lg-6 col-md-12">
                        <div class="my-form wow zoomIn">
                            <!-- <img src="assets/image/user.png" class="title-img" alt="login"> -->
                            <i class="fa fa-user user_img"></i>
                            <!-- <span>Sign up</span> -->
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" name="register">
                                <div class="row">
                                    <div class="form-group col-lg-6 col-md-12">
                                        <label for="fname">First Name</label>
                                        <input type="text" class="form-control" id="firstname" value="<?php echo $firstName; ?>" name="FirstName">
                                        <span class="error"><?php echo $fNameErrMsg; ?></span>
                                        <!-- required aria-describedby="name-format" aria-required="true" pattern="[A-Za-z]+"> -->
                                    </div>

                                    <div class="form-group col-lg-6 col-md-12">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control" id="lasttname" value="<?php echo $lastName; ?>" name="LastName">
                                        <span class="error"><?php echo $lNameErrMsg; ?></span>
                                        <!-- aria-describedby="name-format" aria-required="true" required pattern="[A-Za-z]+"> -->
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Email</label>
                                        <input type="email" class="form-control" id="email" name="Email" value="<?php echo $email; ?>">
                                        <span class="error"><?php echo $emailErrMsg; ?></span>
                                        <!-- aria-describedby="emailHelpId"> -->
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6 col-md-12">
                                        <label>Password</label>
                                        <input type="password" class="form-control" id="password-field" name="Pass">
                                        <span toggle="#password-field" class="fa fa-eye field-icon toggle-password"></span>
                                        <span class="error"><?php echo $passErrMsg; ?></span>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-12">
                                        <label>Confirm Password</label>
                                        <input type="password" class="form-control" id="password-field" name="CnfPass">
                                        <span toggle="#password-field" class="fa fa-eye field-icon toggle-password"></span>
                                        <span class="error"><?php echo $cnfPassErrMsg; ?></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" required> <a data-toggle="modal" data-target="#privacy_policy">Privacy Policy</a></label>
                                    </div>
                                    <div class="checkbox float-right">
                                        <label>
                                            <input type="checkbox" required> <a data-toggle="modal" data-target="#terms_condition">Terms & Conditions</a></label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-login">Sign Up</button>
                                </div>
                            </form>
                            <div class="signup-line">
                                <p>You already have an account?
                                    <a href="login.php">Login</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- login form End -->


    <!-- Privacy Policy -->
    <div class="modal" id="privacy_policy">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <img src="assets/image/favicon-36.png" alt="img"> &nbsp;
                    <h5 class="modal-title">Smiansh's Privacy Policy</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <textarea rows="15" style="width: 100%;" disabled><?php echo file_get_contents("pp.txt"); ?></textarea>

                </div>


            </div>
        </div>
    </div>

    <!-- Terms & condition -->
    <div class="modal" id="terms_condition">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <img src="assets/image/favicon-36.png" alt="img"> &nbsp;
                    <h5 class="modal-title">Smiansh's Terms of Serivces</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <textarea rows="15" style="width: 100%;" disabled><?php echo file_get_contents("tc.txt"); ?></textarea>

                </div>


            </div>
        </div>
    </div>

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