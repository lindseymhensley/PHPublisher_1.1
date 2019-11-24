<?php

///////////////////////////////////////////////////////////////
//	PHPublisher: A Dynamic Content Publishing System     //
//      ------------------------------------------------     //
//                                                           //
// Copyright (c) 2005 by TimTimTimma                         //
// http://TimTimTimma.com                        	     //
//                                                           //
// This program is free software; you can redistribute it    //
// and/or modify it under the terms of the GNU General Public//
// License as published by the Free Software Foundation;     //
// either version 2 of the License, or (at your option) any  //
// later version.                                            //
//                                                           //
// This program is distributed in the hope that it will be   //
// useful, but WITHOUT ANY WARRANTY; without even the implied//
// warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR   //
// PURPOSE.  See the GNU General Public License for more     //
// details.                                                  //
///////////////////////////////////////////////////////////////

///////////////////////////////
//   English Language File   //
///////////////////////////////

define("_MISSING_REQ_FIELDS", "All of the required fields must be filled out!");
define("_NEWSLETTER_DECIDE", "You <u>must</u> decide wither or not you want to subscribe to our Newsletter!");
define("_TERMS_AGREE", "You <u>must</u> agree to our Terms and Services before registering with us!");
define("_SECURITY_QUESTIONS_MISSING", "<b>You missed your security question/answer!</b>");
define("_USERNAME_INUSE", "<b>Username already taken!</b>");
define("_USERNAME", "Username");
define("_REQUIRED", "(Required)");
define("_PASSWORD", "Password");
define("_ILLEGAL_EMAIL", "<b>Illegal E-Mail</b>");
define("_INVALID_CHARACTERS_USR", "<b>Invalid Characters in your Username!</b>");
define("_PASSWORD_SAME", "<b><center>Password must be the same in both fields!</center></b>");
define("_CONFIRM_PASSWORD", "Confirm Password");
define("_EMAIL", "E-Mail");
define("_HOMEPAGE", "Homepage");
define("_NOT_REQUIRED", "(NOT Required)");
define("_OCCUPATION", "Occupation");
define("_LOCATION", "Location");
define("_INTERESTS", "Interests");
define("_ICQ", "ICQ Messenger");
define("_AIM", "AIM Messenger");
define("_MSN", "MSN Messenger");
define("_YIM", "Yahoo Messenger");
define("_SECURITY_QUESTION", "Security Question");
define("_SECURITY_ANSWER", "Security Answer");
define("_SUBSCRIBE_TO_NEWSLETTER", "Would you like to subscribe to our newsletter?");
define("_YES", "Yes");
define("_NO", "No");
define("_DO_YOU_UNDERSTAND", "Have you read, understood, and agree with the <a href=\"index.php?find=Members&file=Terms\">Terms and Services</a>.");
define("_REGISTER_NOW", "Register Now!");
define("_LOGIN", "Login");
define("_EXWL", "Example: http://..., http://www...");
if(isset($file)){
	$mem_file = $file;
}else{
	$mem_file = "index";
}

define("_ERROR", "An error has occured in the Members Module in the file ".$mem_file.".php, Please contact the webmaster about this error.");
define("_REMEMBER_ME", "Remember Me");
define("_USER_PASS_EMPTY", "Username / Password is empty!");
define("_INCORRECT_PASSWORD", "The password you entereed is incorrect!\n\n");
define("_INCORRECT_USERNAME", "The username you entered is incorrect!");
define("_LOGOUT", "Logout");
define("_LOGOUT_MESSAGE", "You have successfully logged out.");
define("_CLICK_HERE", "Click here to return home.");
define("_THEME_CHG_SUCCESS", "Theme successfully changed to ".$_GET['theme'].".");
define("_SECURITY_CODE", "Security Code");
define("_SECURITY_VERIFY", "Verify Code");
define("_SECURITY_CODE_MISSING", "The Security Code is either missing or Incorrect!");
define("_WELCOME", "Welcome to your personal members page ".$user->name().".");
define("_CHOOSE_YOUR_THEME", "Choose one");
define("_YOUR_CHANGES", "This option will change the look for the whole site.<br />Each user can view the site with a different theme.");
define("_REGISTER", "Register an Account (It's Free!)");
define("_PASS_RECOVERY", "Password Recovery");
define("_WELCOME_TO_RECOVERY", "Welcome to the Password Recovery Form. Please enter your personal account information below, and we will attempt to gather the information needed to continue with your passwords successful recovery.");
define("_STEP1", "<strong>Step 1</strong>");
define("_STEP2", "<strong>Step 2</strong>");
define("_USER_NOT_FOUND", "<b>".$_GET['username'] ."</b> not found. Are you sure you entered it correctly?");
define("_NOTICE_ON_SECURITY_Q", "Important!!!: Do <b>not</b> include a question mark!");
define("_USER_FOUND", " has been successfully located. Below is the security question provided on account registration.");
define("_SUCCESS", "<strong>Success</strong>");
define("_INCORRECT_ANSWER", "The security answer you provided us with was incorrect, please try again.");
define("_SERROR", "Error");
define("_PASS_SENT", "The security answer you provided us with was correct, your new randomized password has been emailed to ");
define("_CHANGED_PASSWORD", "Your password has been successfully changed.");
define("_VERIFICATION_WRONG", "The verification code you entered was incorrect.");
define("_MISSING_IMPORTANT_FIELDS", "The username or verification code is missing!");
define("_VERIFICATION_CODE", "Verification Code");
define("_MISSING_SOMETHING_HERE", "Either the current, new, or retyped password was missing!");
define("_CURRENT_IS_WRONG", "The current password you entered was incorrect!");
define("_SUCCESSFULLY_CHANGED_PASSWORD", "Password successfully changed!");
define("_CURRENT_PASS", "Current Password");
define("_NEW_PASS", "New Password");
define("_NEW_PASS_AGAIN", "Retype");
define("_NEW_AGAIN_DONT_MATCH", "The new password and retype must be the same!");
define("_U_ERROR", "<b>Error!</b>");
define("_YOUR_PROFILE_INFO", "Your Profile Information");
define("_AVATAR", "Avatar");
define("_WEBL", "Web Link");
define("_SIGNATURE", "Signature");
define("_VIEW_EMAIL", "Let others view my email");
define("_SUCCESSFULLY_UPDATED_PROFILE", "Your profile has been succesfully updated!");
define("_FAILURE_UPDATING_PROFILE", "You where missing some required fields, please check over the form and try again.");
define("_MAX_SIZE", "(Max size 50x50)");
define("_ILLEGAL_HP", "The link you provided as your homepage was not correct, please try again.");
define("_FROM", "From");
define("_ILLEGAL_SIG", "Your signature is to big, the maximum amount of characters is:");
define("_SITE_MAX_SIG_LENGTH", "The maximum amount of characters you may use in your signature is:");
define("_S_CODE", "Code");
define("_S_VERIFY", "Verify");
define("_R_ME", "Remember");
define("_FEATURE_DISABLED", "The feature you are trying to access has been disabled.");
define("", "");
define("", "");
define("", "");
define("", "");
define("", "");
define("", "");
define("", "");
define("", "");
define("", "");
define("", "");
define("", "");
define("", "");

?>