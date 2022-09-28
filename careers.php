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
function TestInputFormElements($data)
{
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $missingValues = FALSE;
    if (empty($_POST['firstname'])) {
        $fNameErrMsg = "First Name is required";
        $missingValues = TRUE;
    } else {
        $firstName = TestInputFormElements($_POST['firstname']);
    }
    if (empty($_POST['lastname'])) {
        $lNameErrMsg = "Last Name is required";
        $missingValues = TRUE;
    } else {
        $lastName = TestInputFormElements($_POST['lastname']);
    }
    if (empty($_POST['email'])) {
        $emailErrMsg = "Email is required for communication";
        $missingValues = TRUE;
    } else {
        $email = TestInputFormElements($_POST['email']);
    }
    if (empty($_POST['phone'])) {
        $phoneErrMsg = "phone is required";
        $missingValues = TRUE;
    } else {
        $phone = TestInputFormElements($_POST['phone']);
    }
    if (empty($_POST['detail'])) {
        $detailErrMsg = "It is required to tell us about your self";
        $missingValues = TRUE;
    } else {
        $detail = TestInputFormElements($_POST['detail']);
    }
    if ($missingValues == FALSE) {
        if (isset($_FILES["resume"]) && $_FILES["resume"]["error"] == 0) {
            $resume = $_FILES["resume"]["name"];
            if (empty($resume)) {
                $resumeErrMsg = "Upload your resume, it is required";
                $script =  "<script> $(document).ready(function(){ $('#career_form').modal('show'); }); </script>";
                $missingValues = TRUE;
            } else {
                $pattern = "/(\.pdf|\.doc|\.docx)$/";
                //$resume = TestInputFormElements($_POST['resume']);
                if (!preg_match($pattern, strtolower($resume))) {
                    $script =  "<script> $(document).ready(function(){ $('#career_form').modal('show'); }); </script>";
                    $resumeErrMsg = $resume . " Upload your resume only in (.pdf,.doc,.docx) format";
                }
            }
            if ($script == "") {
                $filetype = end(explode(".", $_FILES["resume"]['name']));
                if ($filetype == "") {
                    $script =  "<script> $(document).ready(function(){ $('#career_form').modal('show'); }); </script>";
                    $resumeErrMsg = "Error: File extension is not supported or it is missing from the file name";
                }
                $filesize = $_FILES["resume"]["size"];
                // Verify file size - 5MB maximum
                $maxsize = 2 * 1024 * 1024;
                if ($filesize > $maxsize) {
                    $script =  "<script> $(document).ready(function(){ $('#career_form').modal('show'); }); </script>";
                    $resumeErrMsg = "Error: File size is larger than the allowed(2 MByte) limit";
                } else {
                    // Check whether file exists before uploading it
                    $hash = hash('md5', $email);
                    $destPath = "resumes/" . $firstName . "_" . $lastName . "_" . $hash . "." . $filetype;
                    $destFile = $firstName . "_" . $lastName . "_" . $hash . "." . $filetype;
                    if (!move_uploaded_file($_FILES["resume"]["tmp_name"], $destPath)) {
                        $tempMessage = "<div class='error_msg'>Error in Uploading. Please send mail to resumes@smiansh.com</div>";
                    } else {
                        $tempMessage = "<div class='success_msg'>" . $destFile . " - Your file was uploaded successfully</div>";
                    }
                }
            }
        }
        if ($script == "") {
            $sql = "SELECT * FROM CareersTb where Email='" . $email . "'";
            $rs = $db->query($sql);
            if ($rs->num_rows > 0) {
                $sql = "UPDATE CareersTb SET ResumePath='" . $destPath . "'";
                $tempMessage = '<div class="success_msg">' . $destFile . ' - We see you have already submitted your resume. We have updated the latest resume you just uploaded</div>';
                $emailNotRegistered = TRUE;
            }
            if ($emailNotRegistered == FALSE) {
                $sql = "INSERT INTO CareersTb(FirstName, LastName, Email, AboutMe,ResumePath)
                        VALUES ('" . $firstName . "','" . $lastName . "','" . $email . "','" . $detail . "','" . $destPath . "')";
                $rs = $db->query($sql);
                if ($rs == FALSE) {
                    $tempMessage = "<div class='success_msg'>Your file was uploaded successfully</div>";
                }
            }
        }
    }
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
                                <a class="nav-link active" href="careers.php">Careers</a>
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
                            <h2>Careers</h2>
                        </div>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="index.php">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Careers</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Inner header End -->


    <!-- Career Start -->
    <section class="career_page common_padding">
        <div class="container">
            <div class="row mb-50 wow fadeInUp">
                <div class="col-md-12">
                    <span class="error">
                        <p><?php if (isset($tempMessage)) echo $tempMessage; ?></p>
                    </span>
                    <h4 class="career_title">ANDROID DEVELOPER - 1 POSITION</h4>
                    <p>
                        We are looking for the associate who is committed towards the work and decipline. You should be
                        able to work in tight deadline and with minimul supervision. The person should be individual performer and track
                        his own work accurately to provide project status report on day to day basis.
                    </p>
                    <h5>ROLES AND RESPONSIBILITIES</h5>
                    <small><ul>
                        <li>Translate designs and wireframes into high-quality code</li>
                        <li>Design, build and maintain high performance, reusable and reliable Java/kotlin code</li>
                        <li>Ensure the best performance, quality and responsiveness of applications</li>
                        <li>Collaborate with team to define, design and ship new features</li>
                        <li>Identify and correct bottlenecks and fix bugs</li>
                        <li>Mentor team member and take the responsibility for the deliverables and releases</li>
                        <li>Help maintain code quality, organization and automatization</li>
                    </ul></small>
                    <h5>SKILLS REQUIRED</h5>
                    <small><ul>
                        <li>Strong knowledge of Android SDK, different versions of Android and how to deal with different screen sizes</li>
                        <li>CCAvenue, PayUMoney, RazorPay etc. Payment Integrations is MUST.</li>
                        <li>Familiarity with RESTful APIs and JSON</li>
                        <li>Deep understanding of MySQL, POSTGRESQL, MongoDb, SQLite database, Web services and location-based services</li>
                        <li>Strong knowledge of Android UI design principles, patterns, and best practices</li>
                        <li>Strong knowledge of Firebase or similar technology</li>
                        <li>Good knowledge of various design patterns</li>
                        <li>Experienced with the integration of Google and other popular external APIs</li>
                        <li>Experience with offline storage, threading and performance tuning</li>
                        <li>Solid understanding of data structures and ORM</li>
                        <li>Enough knowledge on the code versioning tool like GIT</li>
                    </ul></small>
                    <h5>EXPERIENCE</h5>
                    <p><small>0 - 2.5 Years(s)</small></p>
                    <h5>HONORARIUM</h5>
                    <p><small>Up To 2.5 Lacs Per Annum</small></p>
                    <div class="row">
                        <div class="col-md-12 mt-20">
                            <a href="#" class="smiansh_btn" data-toggle="modal" data-target="#career_form">Apply Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="row mb-50 wow fadeInUp">
                <div class="col-md-12">
                    <span class="error">
                        <p><?php if (isset($tempMessage)) echo $tempMessage; ?></p>
                    </span>
                    <h6 class="career_title">Full Stack Developer - 2 Opening.</h6>
                    <div class="career_detail">
                        <h6>Minimum Experience:</h6>
                        <span>2+ Years</span>
                    </div>
                    <ul class="proper_list">
                        <li>Fast learning capabilities in a competitive environment</li>
                        <li>Excellent English communication skills</li>
                        <li>Strong analytical and debugging skills</li>
                        <li>Should be able to work independently or require minimum supervision</li>
                        <li>Hands on experience in database systems such as MySQL, PostgreSQL, Oracle, SQLite, MongoDB etc.</li>
                        <li>Knowledge of UI Frameworks like Bootstrap / Core U.I</li>
                        <li>Experience with HTML5 & CSS3.</li>
                        <li>Cross-Platform experience in Node JS</li>
                    </ul>
                    <h6>Nice to have: </h6>
                    <ul class="proper_list">
                        <li>Good to have experience in developing websites in Wordpress, WooCommerse, Shopify, Payment Integration</li>
                        <li>Knowledge of Unix/Linux Operating system having good knowledge in Shell, Perl, Python languages</li>
                    </ul>
                    <div class="career_detail">
                        <h6>Qualification: </h6>
                        <p>BE / BCA / B.Tech / MCA / M.E.
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-20">
                            <a href="#" class="smiansh_btn" data-toggle="modal" data-target="#career_form">Apply Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-50 wow fadeInUp">
                <div class="col-md-12">
                    <h6 class="career_title">Graphic Designer - 1 Opening.</h6>
                    <div class="career_detail">
                        <h6>Minimum Experience:</h6>
                        <span>Fresher or 1 Year</span>
                    </div>
                    <ul class="proper_list">
                        <li>Fast learning capabilities in a competitive environment</li>
                        <li> Excellent English communication skills</li>
                        <li>Strong analytical and debugging skills</li>
                        <li>Should be able to work independently or require minimum supervision</li>
                    </ul>
                    <div class="career_detail">
                        <h6>Key Skills: </h6>
                        <p>Photoshop, Illustrator, Corel Draw, Adobe XD, After Effect
                        </p>
                    </div>
                    <div class="career_detail">
                        <h6>Qualification: </h6>
                        <p>BE / BCA / B.Tech / MCA / M.E.
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-20">
                            <a href="#" class="smiansh_btn" data-toggle="modal" data-target="#career_form">Apply Now</a>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </section>
    <!-- Career End -->

    <!-- The Modal -->
    <div class="modal" id="career_form">
        <div class="modal-dialog career_form">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="careers.php" method="POST" name="profileUploadForm" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">First Name</label>
                                    <input type="text" class="form-control" id="firstname" placeholder="Enter Your First Name" name="firstname" required>
                                    <span class="error">
                                        <p><?php echo $fNameErrMsg; ?></p>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="Enter Your Email" name="email" required>
                                    <span class="error">
                                        <p><?php echo $emailErrMsg; ?></p>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sub">Last Name</label>
                                    <input type="text" class="form-control" id="lastname" placeholder="Enter Your Last Name" name="lastname" required>
                                    <span class="error">
                                        <p><?php echo $lNameErrMsg; ?></p>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" placeholder="Enter Your Phone Number" name="phone" required>
                                    <span class="error">
                                        <p><?php echo $phoneErrMsg; ?></p>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="detail">Describe Yourself</label>
                                    <textarea type="text" class="form-control" id="detail" rows="4" placeholder="Why do you consider yourself the best fit for the role?" name="detail" required style="resize:none;"></textarea>
                                    <span class="error">
                                        <p><?php echo $detailErrMsg; ?></p>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="detail">You can attach your Resume(PDF|DOC|DOCX extensions only)</label>
                                    <input type="file" name="resume" required id="resume" accept=".pdf,.doc,.docx">
                                    <span class="error">
                                        <p><?php echo $resumeErrMsg; ?></p>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12 mt-20 text-center">
                                <button type="submit" class="smiansh_btn">Apply Now</button>
                            </div>
                        </div>
                    </form>

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
    <?php if (isset($script)) echo $script; ?>
</body>

</html>
