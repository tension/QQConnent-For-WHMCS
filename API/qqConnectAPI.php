<?php
require_once( "../../../../init.php" );
session_start();
require_once(dirname(__FILE__)."/comm/config.php");
require_once(CLASS_PATH."QC.class.php");

function qqMessage($status = '', $content = '', $extended = true)
{
	// 获取 网站地址
	$SystemURL = \WHMCS\Config\Setting::getValue('SystemURL');
	$SystemURL = $SystemURL.'/clientarea.php';

	// default code
	$code = '<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">';
	$code .= ($status == 'success' ? '<div class="sa-icon sa-success animate"><span class="sa-line sa-tip"></span><span class="sa-line sa-long"></span><div class="sa-placeholder"></div><div class="sa-fix"></div></div>' : '<div class="sa-icon sa-error animateErrorIcon"><span class="sa-x-mark animateXMark"><span class="sa-line sa-left"></span><span class="sa-line sa-right"></span></span></div>') . "<h2 class='sa-title'>{$content}</h2>";
	
	if ($extended) {
		$code .= "<script> window.parent.location.href='{$SystemURL}'; </script>";
	} else {
		$code .= "<script> setTimeout(function(){ window.parent.location.href='{$SystemURL}';},5000); </script>";
	}
	
	$code .= <<<EOF
<style type='text/css'>
.login_iframe {
	padding: 20px 0;
	background-color: #FFF;
}
.sa-title {
	text-align: center;
    font-size: 20px;
    font-weight: 400;
    margin-top: 20px;
}
.sa-icon {
    width: 80px;
    height: 80px;
    border: 4px solid gray;
    -webkit-border-radius: 40px;
    border-radius: 40px;
    border-radius: 50%;
    margin: 20% auto 50px;
    padding: 0;
    position: relative;
    box-sizing: content-box;
}
.sa-icon.sa-error {
    border-color: #F27474;
}
.sa-icon.sa-error .sa-x-mark {
    position: relative;
    display: block;
}
.sa-icon.sa-error .sa-line {
    position: absolute;
    height: 5px;
    width: 47px;
    background-color: #F27474;
    display: block;
    top: 37px;
    border-radius: 2px;
}
.sa-icon.sa-error .sa-line.sa-left {
    -webkit-transform: rotate(45deg);
    transform: rotate(45deg);
    left: 17px;
}
.sa-icon.sa-error .sa-line.sa-right {
    -webkit-transform: rotate(-45deg);
    transform: rotate(-45deg);
    right: 16px;
}
.sa-icon.sa-warning {
    border-color: #F8BB86;
}
.sa-icon.sa-warning .sa-body {
    position: absolute;
    width: 5px;
    height: 47px;
    left: 50%;
    top: 10px;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    margin-left: -2px;
    background-color: #F8BB86;
}
.sa-icon.sa-warning .sa-dot {
    position: absolute;
    width: 7px;
    height: 7px;
    -webkit-border-radius: 50%;
    border-radius: 50%;
    margin-left: -3px;
    left: 50%;
    bottom: 10px;
    background-color: #F8BB86;
}
.sa-icon.sa-info {
    border-color: #C9DAE1;
}
.sa-icon.sa-info::before {
    content: "";
    position: absolute;
    width: 5px;
    height: 29px;
    left: 50%;
    bottom: 17px;
    border-radius: 2px;
    margin-left: -2px;
    background-color: #C9DAE1;
}
.sa-icon.sa-info::after {
    content: "";
    position: absolute;
    width: 7px;
    height: 7px;
    border-radius: 50%;
    margin-left: -3px;
    top: 19px;
    background-color: #C9DAE1;
}
.sa-icon.sa-success {
    border-color: #A5DC86;
}
.sa-icon.sa-success::before,
.sa-icon.sa-success::after {
    content: '';
    -webkit-border-radius: 40px;
    border-radius: 40px;
    border-radius: 50%;
    position: absolute;
    width: 60px;
    height: 120px;
    background: white;
    -webkit-transform: rotate(45deg);
    transform: rotate(45deg);
}
.sa-icon.sa-success::before {
    -webkit-border-radius: 120px 0 0 120px;
    border-radius: 120px 0 0 120px;
    top: -7px;
    left: -33px;
    -webkit-transform: rotate(-45deg);
    transform: rotate(-45deg);
    -webkit-transform-origin: 60px 60px;
    transform-origin: 60px 60px;
}
.sa-icon.sa-success::after {
    -webkit-border-radius: 0 120px 120px 0;
    border-radius: 0 120px 120px 0;
    top: -11px;
    left: 30px;
    -webkit-transform: rotate(-45deg);
    transform: rotate(-45deg);
    -webkit-transform-origin: 0px 60px;
    transform-origin: 0px 60px;
}
.sa-icon.sa-success .sa-placeholder {
    width: 80px;
    height: 80px;
    border: 4px solid rgba(165, 220, 134, 0.2);
    -webkit-border-radius: 40px;
    border-radius: 40px;
    border-radius: 50%;
    box-sizing: content-box;
    position: absolute;
    left: -4px;
    top: -4px;
    z-index: 2;
}
.sa-icon.sa-success .sa-fix {
    width: 5px;
    height: 90px;
    background-color: white;
    position: absolute;
    left: 28px;
    top: 8px;
    z-index: 1;
    -webkit-transform: rotate(-45deg);
    transform: rotate(-45deg);
}
.sa-icon.sa-success .sa-line {
    height: 5px;
    background-color: #A5DC86;
    display: block;
    border-radius: 2px;
    position: absolute;
    z-index: 2;
}
.sa-icon.sa-success .sa-line.sa-tip {
    width: 25px;
    left: 14px;
    top: 46px;
    -webkit-transform: rotate(45deg);
    transform: rotate(45deg);
}
.sa-icon.sa-success .sa-line.sa-long {
    width: 47px;
    right: 8px;
    top: 38px;
    -webkit-transform: rotate(-45deg);
    transform: rotate(-45deg);
}
.sa-icon.sa-custom {
    background-size: contain;
    border-radius: 0;
    border: none;
    background-position: center center;
    background-repeat: no-repeat;
}
/*
 * Animations
 */
@-webkit-keyframes showSweetAlert {
    0% {
        transform: scale(0.7);
        -webkit-transform: scale(0.7);
    }
    45% {
        transform: scale(1.05);
        -webkit-transform: scale(1.05);
    }
    80% {
        transform: scale(0.95);
        -webkit-transform: scale(0.95);
    }
    100% {
        transform: scale(1);
        -webkit-transform: scale(1);
    }
}
@keyframes showSweetAlert {
    0% {
        transform: scale(0.7);
        -webkit-transform: scale(0.7);
    }
    45% {
        transform: scale(1.05);
        -webkit-transform: scale(1.05);
    }
    80% {
        transform: scale(0.95);
        -webkit-transform: scale(0.95);
    }
    100% {
        transform: scale(1);
        -webkit-transform: scale(1);
    }
}
@-webkit-keyframes hideSweetAlert {
    0% {
        transform: scale(1);
        -webkit-transform: scale(1);
    }
    100% {
        transform: scale(0.5);
        -webkit-transform: scale(0.5);
    }
}
@keyframes hideSweetAlert {
    0% {
        transform: scale(1);
        -webkit-transform: scale(1);
    }
    100% {
        transform: scale(0.5);
        -webkit-transform: scale(0.5);
    }
}
@-webkit-keyframes slideFromTop {
    0% {
        top: 0%;
    }
    100% {
        top: 50%;
    }
}
@keyframes slideFromTop {
    0% {
        top: 0%;
    }
    100% {
        top: 50%;
    }
}
@-webkit-keyframes slideToTop {
    0% {
        top: 50%;
    }
    100% {
        top: 0%;
    }
}
@keyframes slideToTop {
    0% {
        top: 50%;
    }
    100% {
        top: 0%;
    }
}
@-webkit-keyframes slideFromBottom {
    0% {
        top: 70%;
    }
    100% {
        top: 50%;
    }
}
@keyframes slideFromBottom {
    0% {
        top: 70%;
    }
    100% {
        top: 50%;
    }
}
@-webkit-keyframes slideToBottom {
    0% {
        top: 50%;
    }
    100% {
        top: 70%;
    }
}
@keyframes slideToBottom {
    0% {
        top: 50%;
    }
    100% {
        top: 70%;
    }
}
.showSweetAlert[data-animation=pop] {
    -webkit-animation: showSweetAlert 0.3s;
    animation: showSweetAlert 0.3s;
}
.showSweetAlert[data-animation=none] {
    -webkit-animation: none;
    animation: none;
}
.showSweetAlert[data-animation=slide-from-top] {
    -webkit-animation: slideFromTop 0.3s;
    animation: slideFromTop 0.3s;
}
.showSweetAlert[data-animation=slide-from-bottom] {
    -webkit-animation: slideFromBottom 0.3s;
    animation: slideFromBottom 0.3s;
}
.hideSweetAlert[data-animation=pop] {
    -webkit-animation: hideSweetAlert 0.2s;
    animation: hideSweetAlert 0.2s;
}
.hideSweetAlert[data-animation=none] {
    -webkit-animation: none;
    animation: none;
}
.hideSweetAlert[data-animation=slide-from-top] {
    -webkit-animation: slideToTop 0.4s;
    animation: slideToTop 0.4s;
}
.hideSweetAlert[data-animation=slide-from-bottom] {
    -webkit-animation: slideToBottom 0.3s;
    animation: slideToBottom 0.3s;
}
@-webkit-keyframes animateSuccessTip {
    0% {
        width: 0;
        left: 1px;
        top: 19px;
    }
    54% {
        width: 0;
        left: 1px;
        top: 19px;
    }
    70% {
        width: 50px;
        left: -8px;
        top: 37px;
    }
    84% {
        width: 17px;
        left: 21px;
        top: 48px;
    }
    100% {
        width: 25px;
        left: 14px;
        top: 45px;
    }
}
@keyframes animateSuccessTip {
    0% {
        width: 0;
        left: 1px;
        top: 19px;
    }
    54% {
        width: 0;
        left: 1px;
        top: 19px;
    }
    70% {
        width: 50px;
        left: -8px;
        top: 37px;
    }
    84% {
        width: 17px;
        left: 21px;
        top: 48px;
    }
    100% {
        width: 25px;
        left: 14px;
        top: 45px;
    }
}
@-webkit-keyframes animateSuccessLong {
    0% {
        width: 0;
        right: 46px;
        top: 54px;
    }
    65% {
        width: 0;
        right: 46px;
        top: 54px;
    }
    84% {
        width: 55px;
        right: 0px;
        top: 35px;
    }
    100% {
        width: 47px;
        right: 8px;
        top: 38px;
    }
}
@keyframes animateSuccessLong {
    0% {
        width: 0;
        right: 46px;
        top: 54px;
    }
    65% {
        width: 0;
        right: 46px;
        top: 54px;
    }
    84% {
        width: 55px;
        right: 0px;
        top: 35px;
    }
    100% {
        width: 47px;
        right: 8px;
        top: 38px;
    }
}
@-webkit-keyframes rotatePlaceholder {
    0% {
        transform: rotate(-45deg);
        -webkit-transform: rotate(-45deg);
    }
    5% {
        transform: rotate(-45deg);
        -webkit-transform: rotate(-45deg);
    }
    12% {
        transform: rotate(-405deg);
        -webkit-transform: rotate(-405deg);
    }
    100% {
        transform: rotate(-405deg);
        -webkit-transform: rotate(-405deg);
    }
}
@keyframes rotatePlaceholder {
    0% {
        transform: rotate(-45deg);
        -webkit-transform: rotate(-45deg);
    }
    5% {
        transform: rotate(-45deg);
        -webkit-transform: rotate(-45deg);
    }
    12% {
        transform: rotate(-405deg);
        -webkit-transform: rotate(-405deg);
    }
    100% {
        transform: rotate(-405deg);
        -webkit-transform: rotate(-405deg);
    }
}
.animateSuccessTip {
    -webkit-animation: animateSuccessTip 0.75s;
    animation: animateSuccessTip 0.75s;
}
.animateSuccessLong {
    -webkit-animation: animateSuccessLong 0.75s;
    animation: animateSuccessLong 0.75s;
}
.sa-icon.sa-success.animate::after {
    -webkit-animation: rotatePlaceholder 4.25s ease-in;
    animation: rotatePlaceholder 4.25s ease-in;
}
@-webkit-keyframes animateErrorIcon {
    0% {
        transform: rotateX(100deg);
        -webkit-transform: rotateX(100deg);
        opacity: 0;
    }
    100% {
        transform: rotateX(0deg);
        -webkit-transform: rotateX(0deg);
        opacity: 1;
    }
}
@keyframes animateErrorIcon {
    0% {
        transform: rotateX(100deg);
        -webkit-transform: rotateX(100deg);
        opacity: 0;
    }
    100% {
        transform: rotateX(0deg);
        -webkit-transform: rotateX(0deg);
        opacity: 1;
    }
}
.animateErrorIcon {
    -webkit-animation: animateErrorIcon 0.5s;
    animation: animateErrorIcon 0.5s;
}
@-webkit-keyframes animateXMark {
    0% {
        transform: scale(0.4);
        -webkit-transform: scale(0.4);
        margin-top: 26px;
        opacity: 0;
    }
    50% {
        transform: scale(0.4);
        -webkit-transform: scale(0.4);
        margin-top: 26px;
        opacity: 0;
    }
    80% {
        transform: scale(1.15);
        -webkit-transform: scale(1.15);
        margin-top: -6px;
    }
    100% {
        transform: scale(1);
        -webkit-transform: scale(1);
        margin-top: 0;
        opacity: 1;
    }
}
@keyframes animateXMark {
    0% {
        transform: scale(0.4);
        -webkit-transform: scale(0.4);
        margin-top: 26px;
        opacity: 0;
    }
    50% {
        transform: scale(0.4);
        -webkit-transform: scale(0.4);
        margin-top: 26px;
        opacity: 0;
    }
    80% {
        transform: scale(1.15);
        -webkit-transform: scale(1.15);
        margin-top: -6px;
    }
    100% {
        transform: scale(1);
        -webkit-transform: scale(1);
        margin-top: 0;
        opacity: 1;
    }
}
.animateXMark {
    -webkit-animation: animateXMark 0.5s;
    animation: animateXMark 0.5s;
}
@-webkit-keyframes pulseWarning {
    0% {
        border-color: #F8D486;
    }
    100% {
        border-color: #F8BB86;
    }
}
@keyframes pulseWarning {
    0% {
        border-color: #F8D486;
    }
    100% {
        border-color: #F8BB86;
    }
}
.pulseWarning {
    -webkit-animation: pulseWarning 0.75s infinite alternate;
    animation: pulseWarning 0.75s infinite alternate;
}
@-webkit-keyframes pulseWarningIns {
    0% {
        background-color: #F8D486;
    }
    100% {
        background-color: #F8BB86;
    }
}
@keyframes pulseWarningIns {
    0% {
        background-color: #F8D486;
    }
    100% {
        background-color: #F8BB86;
    }
}
.pulseWarningIns {
    -webkit-animation: pulseWarningIns 0.75s infinite alternate;
    animation: pulseWarningIns 0.75s infinite alternate;
}
@-webkit-keyframes rotate-loading {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
@keyframes rotate-loading {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
/* Internet Explorer 9 has some special quirks that are fixed here */
/* The icons are not animated. */
/* This file is automatically merged into sweet-alert.min.js through Gulp */
/* Error icon */
.sa-icon.sa-error .sa-line.sa-left {
    -ms-transform: rotate(45deg) \9;
}
.sa-icon.sa-error .sa-line.sa-right {
    -ms-transform: rotate(-45deg) \9;
}
/* Success icon */
.sa-icon.sa-success {
    border-color: transparent\9;
}
.sa-icon.sa-success .sa-line.sa-tip {
    -ms-transform: rotate(45deg) \9;
}
.sa-icon.sa-success .sa-line.sa-long {
    -ms-transform: rotate(-45deg) \9;
}
/*!
 * Load Awesome v1.1.0 (http://github.danielcardoso.net/load-awesome/)
 * Copyright 2015 Daniel Cardoso <@DanielCardoso>
 * Licensed under MIT
 */
.la-ball-fall,
.la-ball-fall > div {
    position: relative;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.la-ball-fall {
    display: block;
    font-size: 0;
    color: #fff;
}
.la-ball-fall.la-dark {
    color: #333;
}
.la-ball-fall > div {
    display: inline-block;
    float: none;
    background-color: currentColor;
    border: 0 solid currentColor;
}
.la-ball-fall {
    width: 54px;
    height: 18px;
}
.la-ball-fall > div {
    width: 10px;
    height: 10px;
    margin: 4px;
    border-radius: 100%;
    opacity: 0;
    -webkit-animation: ball-fall 1s ease-in-out infinite;
    -moz-animation: ball-fall 1s ease-in-out infinite;
    -o-animation: ball-fall 1s ease-in-out infinite;
    animation: ball-fall 1s ease-in-out infinite;
}
.la-ball-fall > div:nth-child(1) {
    -webkit-animation-delay: -200ms;
    -moz-animation-delay: -200ms;
    -o-animation-delay: -200ms;
    animation-delay: -200ms;
}
.la-ball-fall > div:nth-child(2) {
    -webkit-animation-delay: -100ms;
    -moz-animation-delay: -100ms;
    -o-animation-delay: -100ms;
    animation-delay: -100ms;
}
.la-ball-fall > div:nth-child(3) {
    -webkit-animation-delay: 0ms;
    -moz-animation-delay: 0ms;
    -o-animation-delay: 0ms;
    animation-delay: 0ms;
}
.la-ball-fall.la-sm {
    width: 26px;
    height: 8px;
}
.la-ball-fall.la-sm > div {
    width: 4px;
    height: 4px;
    margin: 2px;
}
.la-ball-fall.la-2x {
    width: 108px;
    height: 36px;
}
.la-ball-fall.la-2x > div {
    width: 20px;
    height: 20px;
    margin: 8px;
}
.la-ball-fall.la-3x {
    width: 162px;
    height: 54px;
}
.la-ball-fall.la-3x > div {
    width: 30px;
    height: 30px;
    margin: 12px;
}
/*
 * Animation
 */
@-webkit-keyframes ball-fall {
    0% {
        opacity: 0;
        -webkit-transform: translateY(-145%);
        transform: translateY(-145%);
    }
    10% {
        opacity: .5;
    }
    20% {
        opacity: 1;
        -webkit-transform: translateY(0);
        transform: translateY(0);
    }
    80% {
        opacity: 1;
        -webkit-transform: translateY(0);
        transform: translateY(0);
    }
    90% {
        opacity: .5;
    }
    100% {
        opacity: 0;
        -webkit-transform: translateY(145%);
        transform: translateY(145%);
    }
}
@-moz-keyframes ball-fall {
    0% {
        opacity: 0;
        -moz-transform: translateY(-145%);
        transform: translateY(-145%);
    }
    10% {
        opacity: .5;
    }
    20% {
        opacity: 1;
        -moz-transform: translateY(0);
        transform: translateY(0);
    }
    80% {
        opacity: 1;
        -moz-transform: translateY(0);
        transform: translateY(0);
    }
    90% {
        opacity: .5;
    }
    100% {
        opacity: 0;
        -moz-transform: translateY(145%);
        transform: translateY(145%);
    }
}
@-o-keyframes ball-fall {
    0% {
        opacity: 0;
        -o-transform: translateY(-145%);
        transform: translateY(-145%);
    }
    10% {
        opacity: .5;
    }
    20% {
        opacity: 1;
        -o-transform: translateY(0);
        transform: translateY(0);
    }
    80% {
        opacity: 1;
        -o-transform: translateY(0);
        transform: translateY(0);
    }
    90% {
        opacity: .5;
    }
    100% {
        opacity: 0;
        -o-transform: translateY(145%);
        transform: translateY(145%);
    }
}
@keyframes ball-fall {
    0% {
        opacity: 0;
        -webkit-transform: translateY(-145%);
        -moz-transform: translateY(-145%);
        -o-transform: translateY(-145%);
        transform: translateY(-145%);
    }
    10% {
        opacity: .5;
    }
    20% {
        opacity: 1;
        -webkit-transform: translateY(0);
        -moz-transform: translateY(0);
        -o-transform: translateY(0);
        transform: translateY(0);
    }
    80% {
        opacity: 1;
        -webkit-transform: translateY(0);
        -moz-transform: translateY(0);
        -o-transform: translateY(0);
        transform: translateY(0);
    }
    90% {
        opacity: .5;
    }
    100% {
        opacity: 0;
        -webkit-transform: translateY(145%);
        -moz-transform: translateY(145%);
        -o-transform: translateY(145%);
        transform: translateY(145%);
    }
}
</style>
EOF;
	return '<div class="login_iframe">'. $code . '</div>';
}