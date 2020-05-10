# Shughal

It is a social website created with HTML5, CSS3 (a bit Bootstrap), core JS (a bit JQuery) and core PHP.

#### Features

- Fully Responsive
- Post, Like, Comment and Direct Message Functionality
- Upload Photos in Posts
- Change Profile Picture, email, username and password
- Forgotten Password Functionality with Password Reset Email
- Live Search For Finding People & Friends
- Live Notifications
- Trends Based on hashtags and likes
- Live Friend's Online & Offline Status

#### Demo Account To Check

You can login with this account in the website if you don't wanna make a new account to check the website

- Email : talha@gmail.com
- Password : abc123

#### Making Setup Guide

- All the database related code is in dbCon.php file so you don't need to change it multiple times.
- The Forgot Password is uses your email address to send email to the users as a password reset email. You can find all the code related to email sending in mail.php . You just need to change the value of $mail->Username to your email address and the value of $mail->Password to your email password. I used my alternative gmail account for this purpose. NOTE: you need to make sure that you have allowed less secure app in your gmail settings. If you don't know how to do it then follow this guide https://hotter.io/docs/email-accounts/secure-app-gmail/
- If you are hosting it live then you need to change the navbar links found in the includes/header.php file.
- Make sure to add the database from the Database folder to your server's PHPMYADMIN.
