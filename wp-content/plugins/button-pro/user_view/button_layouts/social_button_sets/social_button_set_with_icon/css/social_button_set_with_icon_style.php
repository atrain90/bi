<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<style type="text/css">
* {
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
}
*:before,
*:after {
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
}
#social_platforms {  
  text-align:center;  
  overflow:hidden;
  font-family: 'Roboto', sans-serif;
  font-size:16px; 
}

/*Pen code from this point on*/
.wd_btn {
  clear:both;
  white-space:nowrap;
  font-size:.8em;
  display:inline-block;
  border-radius:5px;
  box-shadow: 0 1px 5px 0 rgba(0, 0, 0, 0.35)!important;
  margin:2px;
  -webkit-transition:all .5s;
  -moz-transition:all .5s;
  transition:all .5s;
  overflow:hidden
}

.wd_btn:hover {
  box-shadow: 0 5px 15px 0 rgba(0, 0, 0, 0.45)!important;
}

.wd_btn:focus {
  box-shadow: 0 3px 10px 0 rgba(0, 0, 0, 0.4)!important;
}

.wd_btn > span,.wd_btn_icon > i {
  float:left;
  padding:13px;
  -webkit-transition:all .5s;
  -moz-transition:all .5s;
  transition:all .5s;
  line-height:1em
}

.wd_btn > span {
  padding:14px 18px 16px;
  white-space:nowrap;
  color:#FFF;
  background:#b8b8b8
}

.wd_btn:focus > span {
  background:#9a9a9a
}

.wd_btn_icon > i {
  border-radius:5px 0 0 5px;
  position:relative;
  width:40px;
  text-align:center;
  font-size:1.25em;
  color:#fff;
  background:#212121
}

.wd_btn_icon > i:after {
  content:"";
  border:8px solid;
  border-color:transparent transparent transparent #222;
  position:absolute;
  top:13px;
  right:-15px
}

.wd_btn_icon:hover > i,.wd_btn_icon:focus > i {
  color:#FFF
}

.wd_btn_icon > span {
  border-radius:0 5px 5px 0;
 
}

/*Facebook*/
.wd_btn_facebook:hover > i,.wd_btn_facebook:focus > i {
  color:#3b5998
}

.wd_btn_facebook > span {
  background:#3b5998
}

/*Twitter*/
.wd_btn_twitter:hover > i,.wd_btn_twitter:focus > i {
  color:#55acee
}

.wd_btn_twitter > span {
  background:#55acee
}

/*Google*/
.wd_btn_googleplus:hover > i,.wd_btn_googleplus:focus > i {
  color:#dd4b39
}

.wd_btn_googleplus > span {
  background:#dd4b39
}

/*Pinterest*/
.wd_btn_pinterest:hover > i,.wd_btn_pinterest:focus > i {
  color:#cb2028
}

.wd_btn_pinterest > span {
  background:#cb2028
}

/*LinkedIn*/
.wd_btn_linkedin:hover > i,.wd_btn_linkedin:focus > i {
  color:#007bb6
}

.wd_btn_linkedin > span {
  background:#007bb6
}

/*instagram*/

.wd_btn_instagram:hover >i,.wd_btn_instagram:focus > i{
color:#5f82ac;
}

.wd_btn_instagram span{
  background:#5f82ac;
}

/*vimeo*/

.wd_btn_vimeo:hover >i,.wd_btn_vimeo:focus > i{
color:#67aaba;
}

.wd_btn_vimeo span{
  background:#67aaba;
}

/*skype*/

.wd_btn_skype:hover >i,.wd_btn_skype:focus > i{
color:#13bfff;
}

.wd_btn_skype span{
  background:#13bfff;
}

/*youtube*/

.wd_btn_youtube:hover >i,.wd_btn_youtube:focus > i{
color:#d21921;
}

.wd_btn_youtube span{
  background:#d21921;
}

/*tumblr*/

.wd_btn_tumblr:hover >i,.wd_btn_tumblr:focus > i{
color:#2d4762;
}

.wd_btn_tumblr span{
  background:#2d4762;
}

</style>