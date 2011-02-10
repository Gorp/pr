<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of String
 *
 * @author gorp
 */
class Local_String {
       /* extra = array(
                "form_subject"	=> false,
                "form_cc"		=> false,
                "ip"			=> true,
                "user_agent"	=> true
        );
*/
    static public function translit($str) {

            $tr = array(
                    "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
                    "Д"=>"d","Е"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
                    "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
                    "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
                    "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
                    "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
                    "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
                    "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
                    "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
                    "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
                    "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
                    "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
                    "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
                    "і" => "i","І"=>"I", "ї"=>"i", "Ї"=>"I", "ґ"=>"g", "Ґ"=>"G",
                    " "=> "-", "."=> "", "/"=> "-"
            );
            return strtr($str,$tr);
        }



        static public function smcf_token($s) {
            return md5("smcf-" . $s . date("WY"));
        }

        // Validate and send email
        static public function smcf_send($name, $email, $emailto, $subject, $message) {


            // Filter and validate fields
            /*
            $name = Local_String::smcf_filter($name);
            $subject = Local_String::smcf_filter($subject);
            $email = Local_String::smcf_filter($email);
             */

            // Add additional info to the message
            /*if (Local_String::$extra["ip"]) {
                $message .= "\n\nIP: " . $_SERVER["REMOTE_ADDR"];
            }
            if (Local_String::$extra["user_agent"]) {
                $message .= "\n\nUSER AGENT: " . $_SERVER["HTTP_USER_AGENT"];
            }*/

            // Set and wordwrap message body
            //$body = "От: $name\n\n";
            $body .= "$message\n\n";
          //  $body = wordwrap($body, 70);

            // Build header
            $headers = "From: $email\n";
            //if ($cc == 1) {
            //$headers .= "Cc: digorp@gmail.com\n";
            //	}
            //$headers .= "X-Mailer: PHP/SimpleModalContactForm";

            // UTF-8
           /* if (function_exists('mb_encode_mimeheader')) {
                $subject = mb_encode_mimeheader($subject, "UTF-8", "B", "\n");
            }
            else {
                // you need to enable mb_encode_mimeheader or risk
                // getting emails that are not UTF-8 encoded
            }*/
            //$headers .= "MIME-Version: 1.0\n";
            //$headers .= "Content-type: text/plain; charset=utf-8\n";
            //$headers .= "Content-Transfer-Encoding: quoted-printable\n";

            
            // Send email
            if (mail($emailto, $subject, $body, $headers)) {
                return true;
            }
            return false;
        }

        // Remove any un-safe values to prevent email injection
        static public function smcf_filter($value) {
            $pattern = array("/\n/","/\r/","/content-type:/i","/to:/i", "/from:/i", "/cc:/i");
            $value = preg_replace($pattern, "", $value);
            return $value;
        }

        // Validate email address format in case client-side validation "fails"
        static public function smcf_validate_email($email) {
            $at = strrpos($email, "@");

            // Make sure the at (@) sybmol exists and
            // it is not the first or last character
            if ($at && ($at < 1 || ($at + 1) == strlen($email)))
                return false;

            // Make sure there aren't multiple periods together
            if (preg_match("/(\.{2,})/", $email))
                return false;

            // Break up the local and domain portions
            $local = substr($email, 0, $at);
            $domain = substr($email, $at + 1);


            // Check lengths
            $locLen = strlen($local);
            $domLen = strlen($domain);
            if ($locLen < 1 || $locLen > 64 || $domLen < 4 || $domLen > 255)
                return false;

            // Make sure local and domain don't start with or end with a period
            if (preg_match("/(^\.|\.$)/", $local) || preg_match("/(^\.|\.$)/", $domain))
                return false;

            // Check for quoted-string addresses
            // Since almost anything is allowed in a quoted-string address,
            // we're just going to let them go through
            if (!preg_match('/^"(.+)"$/', $local)) {
                // It's a dot-string address...check for valid characters
                if (!preg_match('/^[-a-zA-Z0-9!#$%*\/?|^{}`~&\'+=_\.]*$/', $local))
                    return false;
            }

            // Make sure domain contains only valid characters and at least one period
            if (!preg_match("/^[-a-zA-Z0-9\.]*$/", $domain) || !strpos($domain, "."))
                return false;

            return true;
        }
    
}
?>
