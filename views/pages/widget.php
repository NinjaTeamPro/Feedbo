<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_action( 'wp_enqueue_scripts', 'addFeedboWidgetStyle' );
add_filter( 'body_class', 'removeClassBigNinjaFeedbo' );
function removeClassBigNinjaFeedbo( $classes ) {
	foreach ( $classes as $key => $value ) {
		if ( $value == 'big-ninja-feedbo' ) {
			unset( $classes[ $key ] );
		}
	}
	return $classes;

}
function addFeedboWidgetStyle() {
	wp_enqueue_style( 'feedbo-widget-style', FB_PLUGIN_URL . 'assets/css/feedbo_widget_style.css', array() );
}
$boardId = get_query_var( 'widget' );
get_header();
?>
<div class="feedbo-outer-content">
  <div class="feedbo-content">
	<div class="inner-content">
	  <div id="index">
		<div class="fb-header"></div>
		<div class="fb-menu">
		  <div class="fb-tabs">
			<div data-selected="" data-tab="roadmap" class="fb-tab">
			  <i class="anticon">
				<svg viewBox="64 64 896 896" data-icon="control" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">
				  <path d="M880 112H144c-17.7 0-32 14.3-32 32v736c0 17.7 14.3 32 32 32h736c17.7 0 32-14.3 32-32V144c0-17.7-14.3-32-32-32zm-40 728H184V184h656v656zM340 683v77c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8v-77c-10.1 3.3-20.8 5-32 5s-21.9-1.8-32-5zm64-198V264c0-4.4-3.6-8-8-8h-48c-4.4 0-8 3.6-8 8v221c10.1-3.3 20.8-5 32-5s21.9 1.8 32 5zm-64 198c10.1 3.3 20.8 5 32 5s21.9-1.8 32-5c41.8-13.5 72-52.7 72-99s-30.2-85.5-72-99c-10.1-3.3-20.8-5-32-5s-21.9 1.8-32 5c-41.8 13.5-72 52.7-72 99s30.2 85.5 72 99zm.1-115.7c.3-.6.7-1.2 1-1.8v-.1l1.2-1.8c.1-.2.2-.3.3-.5.3-.5.7-.9 1-1.4.1-.1.2-.3.3-.4.5-.6.9-1.1 1.4-1.6l.3-.3 1.2-1.2.4-.4c.5-.5 1-.9 1.6-1.4.6-.5 1.1-.9 1.7-1.3.2-.1.3-.2.5-.3.5-.3.9-.7 1.4-1 .1-.1.3-.2.4-.3.6-.4 1.2-.7 1.9-1.1.1-.1.3-.1.4-.2.5-.3 1-.5 1.6-.8l.6-.3c.7-.3 1.3-.6 2-.8.7-.3 1.4-.5 2.1-.7.2-.1.4-.1.6-.2.6-.2 1.1-.3 1.7-.4.2 0 .3-.1.5-.1.7-.2 1.5-.3 2.2-.4.2 0 .3 0 .5-.1.6-.1 1.2-.1 1.8-.2h.6c.8 0 1.5-.1 2.3-.1s1.5 0 2.3.1h.6c.6 0 1.2.1 1.8.2.2 0 .3 0 .5.1.7.1 1.5.2 2.2.4.2 0 .3.1.5.1.6.1 1.2.3 1.7.4.2.1.4.1.6.2.7.2 1.4.4 2.1.7.7.2 1.3.5 2 .8l.6.3c.5.2 1.1.5 1.6.8.1.1.3.1.4.2.6.3 1.3.7 1.9 1.1.1.1.3.2.4.3.5.3 1 .6 1.4 1 .2.1.3.2.5.3.6.4 1.2.9 1.7 1.3s1.1.9 1.6 1.4l.4.4 1.2 1.2.3.3c.5.5 1 1.1 1.4 1.6.1.1.2.3.3.4.4.4.7.9 1 1.4.1.2.2.3.3.5l1.2 1.8s0 .1.1.1a36.18 36.18 0 0 1 5.1 18.5c0 6-1.5 11.7-4.1 16.7-.3.6-.7 1.2-1 1.8 0 0 0 .1-.1.1l-1.2 1.8c-.1.2-.2.3-.3.5-.3.5-.7.9-1 1.4-.1.1-.2.3-.3.4-.5.6-.9 1.1-1.4 1.6l-.3.3-1.2 1.2-.4.4c-.5.5-1 .9-1.6 1.4-.6.5-1.1.9-1.7 1.3-.2.1-.3.2-.5.3-.5.3-.9.7-1.4 1-.1.1-.3.2-.4.3-.6.4-1.2.7-1.9 1.1-.1.1-.3.1-.4.2-.5.3-1 .5-1.6.8l-.6.3c-.7.3-1.3.6-2 .8-.7.3-1.4.5-2.1.7-.2.1-.4.1-.6.2-.6.2-1.1.3-1.7.4-.2 0-.3.1-.5.1-.7.2-1.5.3-2.2.4-.2 0-.3 0-.5.1-.6.1-1.2.1-1.8.2h-.6c-.8 0-1.5.1-2.3.1s-1.5 0-2.3-.1h-.6c-.6 0-1.2-.1-1.8-.2-.2 0-.3 0-.5-.1-.7-.1-1.5-.2-2.2-.4-.2 0-.3-.1-.5-.1-.6-.1-1.2-.3-1.7-.4-.2-.1-.4-.1-.6-.2-.7-.2-1.4-.4-2.1-.7-.7-.2-1.3-.5-2-.8l-.6-.3c-.5-.2-1.1-.5-1.6-.8-.1-.1-.3-.1-.4-.2-.6-.3-1.3-.7-1.9-1.1-.1-.1-.3-.2-.4-.3-.5-.3-1-.6-1.4-1-.2-.1-.3-.2-.5-.3-.6-.4-1.2-.9-1.7-1.3s-1.1-.9-1.6-1.4l-.4-.4-1.2-1.2-.3-.3c-.5-.5-1-1.1-1.4-1.6-.1-.1-.2-.3-.3-.4-.4-.4-.7-.9-1-1.4-.1-.2-.2-.3-.3-.5l-1.2-1.8v-.1c-.4-.6-.7-1.2-1-1.8-2.6-5-4.1-10.7-4.1-16.7s1.5-11.7 4.1-16.7zM620 539v221c0 4.4 3.6 8 8 8h48c4.4 0 8-3.6 8-8V539c-10.1 3.3-20.8 5-32 5s-21.9-1.8-32-5zm64-198v-77c0-4.4-3.6-8-8-8h-48c-4.4 0-8 3.6-8 8v77c10.1-3.3 20.8-5 32-5s21.9 1.8 32 5zm-64 198c10.1 3.3 20.8 5 32 5s21.9-1.8 32-5c41.8-13.5 72-52.7 72-99s-30.2-85.5-72-99c-10.1-3.3-20.8-5-32-5s-21.9 1.8-32 5c-41.8 13.5-72 52.7-72 99s30.2 85.5 72 99zm.1-115.7c.3-.6.7-1.2 1-1.8v-.1l1.2-1.8c.1-.2.2-.3.3-.5.3-.5.7-.9 1-1.4.1-.1.2-.3.3-.4.5-.6.9-1.1 1.4-1.6l.3-.3 1.2-1.2.4-.4c.5-.5 1-.9 1.6-1.4.6-.5 1.1-.9 1.7-1.3.2-.1.3-.2.5-.3.5-.3.9-.7 1.4-1 .1-.1.3-.2.4-.3.6-.4 1.2-.7 1.9-1.1.1-.1.3-.1.4-.2.5-.3 1-.5 1.6-.8l.6-.3c.7-.3 1.3-.6 2-.8.7-.3 1.4-.5 2.1-.7.2-.1.4-.1.6-.2.6-.2 1.1-.3 1.7-.4.2 0 .3-.1.5-.1.7-.2 1.5-.3 2.2-.4.2 0 .3 0 .5-.1.6-.1 1.2-.1 1.8-.2h.6c.8 0 1.5-.1 2.3-.1s1.5 0 2.3.1h.6c.6 0 1.2.1 1.8.2.2 0 .3 0 .5.1.7.1 1.5.2 2.2.4.2 0 .3.1.5.1.6.1 1.2.3 1.7.4.2.1.4.1.6.2.7.2 1.4.4 2.1.7.7.2 1.3.5 2 .8l.6.3c.5.2 1.1.5 1.6.8.1.1.3.1.4.2.6.3 1.3.7 1.9 1.1.1.1.3.2.4.3.5.3 1 .6 1.4 1 .2.1.3.2.5.3.6.4 1.2.9 1.7 1.3s1.1.9 1.6 1.4l.4.4 1.2 1.2.3.3c.5.5 1 1.1 1.4 1.6.1.1.2.3.3.4.4.4.7.9 1 1.4.1.2.2.3.3.5l1.2 1.8v.1a36.18 36.18 0 0 1 5.1 18.5c0 6-1.5 11.7-4.1 16.7-.3.6-.7 1.2-1 1.8v.1l-1.2 1.8c-.1.2-.2.3-.3.5-.3.5-.7.9-1 1.4-.1.1-.2.3-.3.4-.5.6-.9 1.1-1.4 1.6l-.3.3-1.2 1.2-.4.4c-.5.5-1 .9-1.6 1.4-.6.5-1.1.9-1.7 1.3-.2.1-.3.2-.5.3-.5.3-.9.7-1.4 1-.1.1-.3.2-.4.3-.6.4-1.2.7-1.9 1.1-.1.1-.3.1-.4.2-.5.3-1 .5-1.6.8l-.6.3c-.7.3-1.3.6-2 .8-.7.3-1.4.5-2.1.7-.2.1-.4.1-.6.2-.6.2-1.1.3-1.7.4-.2 0-.3.1-.5.1-.7.2-1.5.3-2.2.4-.2 0-.3 0-.5.1-.6.1-1.2.1-1.8.2h-.6c-.8 0-1.5.1-2.3.1s-1.5 0-2.3-.1h-.6c-.6 0-1.2-.1-1.8-.2-.2 0-.3 0-.5-.1-.7-.1-1.5-.2-2.2-.4-.2 0-.3-.1-.5-.1-.6-.1-1.2-.3-1.7-.4-.2-.1-.4-.1-.6-.2-.7-.2-1.4-.4-2.1-.7-.7-.2-1.3-.5-2-.8l-.6-.3c-.5-.2-1.1-.5-1.6-.8-.1-.1-.3-.1-.4-.2-.6-.3-1.3-.7-1.9-1.1-.1-.1-.3-.2-.4-.3-.5-.3-1-.6-1.4-1-.2-.1-.3-.2-.5-.3-.6-.4-1.2-.9-1.7-1.3s-1.1-.9-1.6-1.4l-.4-.4-1.2-1.2-.3-.3c-.5-.5-1-1.1-1.4-1.6-.1-.1-.2-.3-.3-.4-.4-.4-.7-.9-1-1.4-.1-.2-.2-.3-.3-.5l-1.2-1.8v-.1c-.4-.6-.7-1.2-1-1.8-2.6-5-4.1-10.7-4.1-16.7s1.5-11.7 4.1-16.7z"></path>
				</svg>
			  </i>
			</div>
			<div data-selected="" data-tab="vote" class="fb-tab vote">Most Voted</div>
			<div data-selected="" data-tab="new" class="fb-tab new">Newest</div>
		  </div>
		  <div class="fb-new-post fb-button">
			<i class="anticon">
			  <svg viewBox="64 64 896 896" data-icon="plus" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">
				<path d="M482 152h60q8 0 8 8v704q0 8-8 8h-60q-8 0-8-8V160q0-8 8-8z"></path>
				<path d="M176 474h672q8 0 8 8v60q0 8-8 8H176q-8 0-8-8v-60q0-8 8-8z"></path>
			  </svg>
			</i>
			<span class="button-text"></span>
		  </div>
		</div>
		<div class="fb-content">
		  <div id="roadmap" class="content"></div>
		  <div id="vote" class="content"></div>
		  <div id="new" class="content"></div>
		</div>
	  </div>
	  <div id="details">
		<div class="details-roadmap"></div>
		<div class="details-vote"></div>
		<div class="details-new"></div>
	  </div>
	  <div id="add">
		<div class="add-post-container">
		  <div class="topBar">
			<a href="#back" class="back"></a>
			<h3 class="title"></h3>
		  </div>
		  <div class="slideBody">
			<form id="form_add_post" class="new-post-form" enctype="multipart/form-data">
			  <div class="form-control">
				<div class="form-control-title"><span>* </span>Title:</div>
				<input type="text" class="fb-input" name="post_title" placeholder="A short, descriptive title" required />
			  </div>
			  <div class="form-control">
				<div class="form-control-title"><span>* </span>Details:</div>
				<textarea required class="fb-input" style="height:auto;" name="post_details" rows="4" cols="50" placeholder="Please include only one suggestion per post" ></textarea>
			  </div>
			  <div class="form-control">
				<div class="upload-file-list"></div>
			  </div>
			  <div class="form-control button-container">
				<div class="button-upload">
				  <button class="fb-btn-upload fb-button">
					<i aria-label="icon: upload" class="anticon anticon-upload">
					  <svg viewBox="64 64 896 896" data-icon="upload" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false" class="">
						<path d="M400 317.7h73.9V656c0 4.4 3.6 8 8 8h60c4.4 0 8-3.6 8-8V317.7H624c6.7 0 10.4-7.7 6.3-12.9L518.3 163a8 8 0 0 0-12.6 0l-112 141.7c-4.1 5.3-.4 13 6.3 13zM878 626h-60c-4.4 0-8 3.6-8 8v154H214V634c0-4.4-3.6-8-8-8h-60c-4.4 0-8 3.6-8 8v198c0 17.7 14.3 32 32 32h684c17.7 0 32-14.3 32-32V634c0-4.4-3.6-8-8-8z"></path>
					  </svg>
					</i>
					<span style="padding-left:5px;">Attach file</span>
				  </button>
				  <input type="file" class="new_post_upload" name="new_post_image" multiple />
				</div>
				<button class="fb-button" type="submit">Create post</button>
			  </div>
			</form>
		  </div>
		</div>
	  </div>
	</div> 
  </div> 
</div>
<?php get_footer(); ?>
<script>
  jQuery(document).ready(function(){
	var currentTab = 'vote';
	var listVote = [];
	var listDownVote = [];
	var listLikeComment = [];
	window.bigNinjaWidgetData = {
	  downVote : "",
	  colorStatus: [],
	  boardSetting: [],
	  checkColorAccent: "",
	  checkColorHeader: "",
	  teamAvatar: [],
	  updateDownVoteAnonymous: [],
	  updateVoteAnonymous: [],
	  newpostFileList: [],
	  commentFileList: [],
	  postDetail: [],
	}
	loadBoard();
	changeTabActive();
	viewDetail(listLikeComment);
	closeDetail();
	viewAddPost();
	closeAddPost();
	buttonVoteClick(listVote,listDownVote);
	buttonSubscribeClick();
	uploadFile();
	removeFile();
	addNewPost();
	addComment(listLikeComment);
	likeComment(listLikeComment);
  });

  async function loadBoard() {
	  jQuery.ajax({
		  url: '<?php echo site_url() . '/wp-json/v1/wp_widget_get_board_content'; ?>',
		  type: 'GET',
		  dataType : "json",
		  data: {id:'<?php echo $boardId; ?>'},
		  success: function(response) {
			window.bigNinjaWidgetData.teamAvatar = response.data.avatarAllUser;
			window.bigNinjaWidgetData.colorStatus = response.data.boardInfo.status;
			let downVote = response.data.boardInfo.setting['features'].includes("downvoting");
			loadHeader(response.data.boardInfo);
			loadTheme(response.data.boardInfo.theme);
			loadContent(response.data.listMostVotePost, response.data.listNewPost, response.data.listRoadmapPost,response.data.boardInfo.status,response.data.boardInfo.setting, downVote);
		  },
		  error: function( jqXHR, textStatus, errorThrown ){
			  //Làm gì đó khi có lỗi xảy ra
			  console.log( 'The following error occured: ' + textStatus, errorThrown );
		  }
	  });
  }
  async function changeTabActive() {
	jQuery('.feedbo-outer-content .feedbo-content .inner-content .fb-menu .fb-tabs .fb-tab').click(function() {
	  jQuery(".feedbo-outer-content .feedbo-content .inner-content .fb-menu .fb-tabs .fb-tab").each(function( index ) {
		jQuery(this).attr('data-selected', '' );
	  });
	  jQuery(this).attr('data-selected', 'true' );
	  loadContentByTab(jQuery(this).attr('data-tab'));
	});
  }
  async function loadContentByTab(activeTab) {
	jQuery(".feedbo-outer-content .feedbo-content .inner-content .fb-content div").each(function() {
	  jQuery(this).removeClass('tab-active');
	});
	jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content #${activeTab}`).addClass('tab-active');
  }
  async function loadHeader(boardInfo) {
	var headerRender = renderHeader(boardInfo);
	const boardSetting = boardInfo.setting;
	window.bigNinjaWidgetData.boardSetting = boardSetting;
	window.bigNinjaWidgetData.downVote = boardSetting['features'].includes("downvoting");
	jQuery('.feedbo-outer-content .feedbo-content .inner-content #index .fb-header').html(headerRender);
	jQuery('.feedbo-outer-content .feedbo-content .inner-content .fb-menu .fb-new-post .button-text').text(boardSetting.button_text);
	jQuery('.feedbo-outer-content .feedbo-content .inner-content #add .add-post-container .topBar .title').text(boardSetting.button_text);
	loadTheme();
	if(boardSetting.default_sort == "vote") {
	  jQuery('.feedbo-outer-content .feedbo-content .inner-content .fb-menu .fb-tabs .vote').attr('data-selected', 'true' );
	  jQuery('.feedbo-outer-content .feedbo-content .inner-content .fb-content #vote').addClass('tab-active');
	} else {
	  jQuery('.feedbo-outer-content .feedbo-content .inner-content .fb-menu .fb-tabs .new').attr('data-selected','true');
	  jQuery('.feedbo-outer-content .feedbo-content .inner-content .fb-content #new').addClass('tab-active');
	}
  }
  async function loadContent(listMostVotePost, listNewPost, listRoadmapPost, colorRoadmap, boardSetting, downVote) {
	let voteRender = renderContent(listMostVotePost,downVote, colorRoadmap);
	let voteDetailRender = renderDetail(listMostVotePost,downVote);
	let newRender = renderContent(listNewPost,downVote, colorRoadmap);
	let newDetailRender = renderDetail(listNewPost,downVote);
	let roadmapRender = renderRoadmap(listRoadmapPost,colorRoadmap);
	let roadmapDetailRender = renderRoadmapDetails(listRoadmapPost,downVote);
	// Render Most Vote Post
	jQuery('.feedbo-outer-content .feedbo-content .inner-content .fb-content #vote').html(voteRender);
	jQuery('.feedbo-outer-content .feedbo-content .inner-content #details .details-vote').html(voteDetailRender);
	// Render New Post
	jQuery('.feedbo-outer-content .feedbo-content .inner-content .fb-content #new').html(newRender);
	jQuery('.feedbo-outer-content .feedbo-content .inner-content #details .details-new').html(newDetailRender);
	// Render Roadmap
	jQuery('.feedbo-outer-content .feedbo-content .inner-content .fb-content #roadmap').html(roadmapRender);
	jQuery('.feedbo-outer-content .feedbo-content .inner-content #details .details-roadmap').html(roadmapDetailRender);
	if(boardSetting.default_sort == "vote") {
	  jQuery('.feedbo-outer-content .feedbo-content .inner-content .fb-content #vote').addClass('tab-active');
	} else {
	  jQuery('.feedbo-outer-content .feedbo-content .inner-content .fb-content #new').addClass('tab-active');
	}
  }
  async function loadTheme(boardTheme) {   
	if(boardTheme != undefined) {
	  jQuery(".feedbo-outer-content .feedbo-content .inner-content .fb-header").css("background-color", boardTheme.header);
	  jQuery(".feedbo-outer-content .feedbo-content .inner-content .postItem-container .title").css("color", boardTheme.title);
	  jQuery(".feedbo-outer-content .feedbo-content .inner-content .postItem-container .description").css("color", boardTheme.text);
	  jQuery(".feedbo-outer-content .feedbo-content .inner-content .fb-menu .fb-new-post").css("background-color", boardTheme.accent);
	  jQuery(".feedbo-outer-content .feedbo-content .inner-content .fb-menu .fb-new-post").hover(function(){
		jQuery(this).css("background-color", boardTheme.accent+'99');
		}, function(){
		  jQuery(this).css("background-color", boardTheme.accent);
	  });
	  document.documentElement.style.setProperty('--fb-header', boardTheme.header);
	  document.documentElement.style.setProperty('--fb-title', boardTheme.title);
	  document.documentElement.style.setProperty('--fb-text', boardTheme.text);
	  document.documentElement.style.setProperty('--fb-accent', boardTheme.accent);
	  document.documentElement.style.setProperty('--fb-accent-hover', boardTheme.accent + 99);
	  var checkColorHeader = checkColor(boardTheme.header);
	  var checkColorAccent = checkColor(boardTheme.accent);
	  window.bigNinjaWidgetData.checkColorAccent = checkColor(boardTheme.accent);
	  if(checkColorHeader == 'dark') {
		jQuery(".feedbo-outer-content .feedbo-content .inner-content .fb-header").css("color", "#fff");
	  }
	  if(checkColorAccent == 'dark') {
		jQuery(".feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container").css("color", "#fff");
		jQuery(".feedbo-outer-content .feedbo-content .inner-content .fb-button[type='submit']").css("color", "#fff");
	  }
	}
  }
  async function loadComment(postId, element, listLikeComment) {
	jQuery.ajax({
		url: '<?php echo site_url() . '/wp-json/v1/wp_get_comments'; ?>',
		type: 'GET',
		dataType : "json",
		data: {id:postId},
		success: function(response) {
		  var commentRender = renderComment(response.data.comments,listLikeComment);
		  jQuery(element).find(".slideBody .comment-list-container").html(commentRender);
		},
		error: function( jqXHR, textStatus, errorThrown ){
			//Làm gì đó khi có lỗi xảy ra
			console.log( 'The following error occured: ' + textStatus, errorThrown );
		},
		complete : function () {
		  jQuery(element).find(".slideBody .fb-skeleton-container").remove();
		  jQuery(element).find(".slideBody .comment-list-container").css('display','block');
		  jQuery(element).find(".slideBody .new-comment-form-container").css('display','block');
		}
	});
  }
  function renderHeader(boardInfo) {
	const boardSettings = boardInfo.setting
	  var htmlRender = "";
	  var logoRender = "";
	  var classTitleWrap = "";
	  if(boardInfo.logo_favicon.logo != undefined && boardInfo.logo_favicon.logo != '') {
		logoRender = '<img style="height: 32px" src="'+boardInfo.logo_favicon.logo+'" />';
		classTitleWrap = 'has-logo';
	  }
	  if(boardInfo != false){
		htmlRender += '<div class="fb-title '+classTitleWrap+'">'+logoRender+'<div>'+boardSettings.name+'</div></div>';
		htmlRender += '<p class="fb-description">'+boardSettings.description+'</p>';
	  }
	  return htmlRender;
  }
  function renderContent(respondData,downVote, roadmapColor) {
	var htmlRender = "";
	if(respondData != false){
	  jQuery.each(respondData, function (key, value) {
		var postId = value['post_id'];
		var postTitle = value['post_title'];
		var postStatus = value['post_status'];
		var postContent = value['post_content'];
		var userVoteIds = value['vote_ids'].split(" , ");
		var voteLength = value['vote_length'];
	  var statusColor = roadmapColor[postStatus];
		htmlRender += '<a href="#" class="postItem" data-id="'+ postId+'">';
		htmlRender +=   '<div class="postItem-container" data-id="'+ postId+'">';
		htmlRender +=     '<div class="postItem-content">';
		htmlRender +=       '<strong class="title" data-label-value="'+ postTitle+'">'+ postTitle+'</strong>';
		htmlRender +=       '<span class="postItem-status" style="background-color:'+ statusColor+'" data-label-value="'+ postStatus+'">'+ postStatus+'</span>';
		htmlRender +=       '<p class="description">'+ postContent+'</p>';
		htmlRender +=     '</div>';
		htmlRender +=   '</div>';
		htmlRender +=   '<div class="button-container">';
		htmlRender +=     '<div class="fb-button button-vote" data-button="vote" data-id="'+ postId+'" data-vote="" data-vote-ids="'+ value['vote_ids']+'">';
		htmlRender +=       '<span class="vote-icon">';
		htmlRender +=         '<i class="feedbo-icon">';
		htmlRender +=           '<svg viewBox="0 0 1024 1024" data-icon="caret-up" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">';
		htmlRender +=             '<path d="M858.9 689L530.5 308.2c-9.4-10.9-27.5-10.9-37 0L165.1 689c-12.2 14.2-1.2 35 18.5 35h656.8c19.7 0 30.7-20.8 18.5-35z"></path>';
		htmlRender +=           '</svg>';
		htmlRender +=         '</i>';
		htmlRender +=       '</span>';
		htmlRender +=       '<span class="vote-count">'+ voteLength+'</span>';
		htmlRender +=     '</div>';
		if(downVote == true) {
		  var userDownVoteIds = value['down_vote_ids'] != null ? value['down_vote_ids'].split(" , ") : [];
		  var downVoteLength = value['down_vote_length'];
		  htmlRender +=   '<div class="fb-button button-down-vote" style="margin-top:5px;" data-button="down-vote" data-down-vote="" data-id="'+postId+'" data-vote-ids="'+value['down_vote_ids']+'">';
		  htmlRender +=     '<span class="vote-icon">';
		  htmlRender +=       '<i class="feedbo-icon">';
		  htmlRender +=         '<svg viewBox="0 0 1024 1024" data-icon="caret-up" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">';
		  htmlRender +=           '<path d="M840.4 300H183.6c-19.7 0-30.7 20.8-18.5 35l328.4 380.8c9.4 10.9 27.5 10.9 37 0L858.9 335c12.2-14.2 1.2-35-18.5-35z"></path>';
		  htmlRender +=         '</svg>';
		  htmlRender +=       '</i>';
		  htmlRender +=     '</span>';
		  htmlRender +=     '<span class="down-vote-count">'+downVoteLength+'</span>';
		  htmlRender +=   '</div>';
		}
		htmlRender +=   '</div>';
		htmlRender += '</a>';
	  });
	}
	return htmlRender;
  }
  function renderRoadmap(respondData, colorRoadmap) {
	
	const item = respondData;
	const group = item.reduce((r, a) => {
		r[a.post_status] = [ ...r[a.post_status] || [], a ];
		return r;
	}, {});
	var htmlRender = "";
	  if(respondData != false){
		jQuery.each(group, function (key, value) {
		  var title = key;
		  var color = colorRoadmap[key];
		  var roadmapItem = value;
		  htmlRender += '<div class="postItem-roadmap" data-id="'+ title +'">';
		  htmlRender +=   '<div class="postItem-roadmap-tail"></div>';
		  htmlRender +=   '<div class="postItem-roadmap-head" data-status="'+ title +'" style="border-color:'+ color +'"></div>';
		  htmlRender +=   '<div class="postItem-roadmap-content">'+ title +'';        
		  jQuery.each(value, function (roadmapKey, roadmapValue) {
			htmlRender +=   '<div class="roadmap-item-container" data-id="'+ roadmapValue['post_id']+'">';
			htmlRender +=     '<h3 class="roadmap-item-title"  data-label-value="'+ roadmapValue['post_id']+'">';
			htmlRender +=       '<span class="roadmap-item-badge">';
			htmlRender +=         '<span class="roadmap-item-badge-dot" data-status="'+ title +'" style="background:'+ color +'"></span>';
			htmlRender +=         '<span class="roadmap-item-title-text">'+ roadmapValue['post_title']+'</span>';
			htmlRender +=       '</span>';
			htmlRender +=     '</h3>';
			htmlRender +=   '</div>';
		  });
		  htmlRender +=   '</div>';
		  htmlRender += '</div>';
		});
	  }
	  else {
		htmlRender += '<div class="roadmap-no-item-container">';
		htmlRender +=   '<div class="roadmap-no-item-header">The roadmap will appear here</div>';
		htmlRender +=   '<div class="roadmap-no-item-footer">when a post is assigned to a status</div>';
		htmlRender += '</div>';
	  }
	return htmlRender;
  }
  function renderRoadmapDetails(respondData, downVote) {
	const item = respondData;
	const group = item.reduce((r, a) => {
		r[a.post_status] = [ ...r[a.post_status] || [], a ];
		return r;
	}, {});
	var htmlRender = "";
	if(respondData != false){
	  jQuery.each(group, function (key, value) {  
		jQuery.each(value, function (roadmapKey, roadmapValue) {
		  var postId = roadmapValue['post_id'];
		  var postTitle = roadmapValue['post_title'];
		  var postStatus = roadmapValue['post_status'];
		  var colorStatus = window.bigNinjaWidgetData.colorStatus[postStatus];
		  var userVoteIds = roadmapValue['vote_ids'].split(" , ");
		  var colorButton = window.bigNinjaWidgetData.checkColorAccent == "dark" ? '#fff' : '#000';
		  htmlRender += '<div class="logDetailsItem" data-id="'+postId+'">';
		  htmlRender +=   '<div class="topBar">';
		  htmlRender +=     '<a href="#back" class="back"></a>';
		  htmlRender +=     '<h3 class="title">'+postTitle+'</h3>';
		  htmlRender +=   '</div>';
		  htmlRender +=   '<div class="slideBody">';
		  htmlRender +=     '<div class="detail-item-status">';
		  htmlRender +=       '<span class="status-title">Status</span>';
		  htmlRender +=       '<span class="status-badge" data-color="'+postStatus+'" style="background-color: '+colorStatus+'"></span>';
		  htmlRender +=       '<span class="status-name">'+postStatus+'</span>';
		  htmlRender +=     '</div>';
		  htmlRender +=     '<div class="detail-item-button">';
		  htmlRender +=       '<div class="button-subscribe fb-button">Subscribe to updates</div>';
		  htmlRender +=       '<div class="button-vote-container" style="display:flex;color:'+colorButton+'">';
		  htmlRender +=         '<div class="fb-button button-vote" data-button="vote" data-vote="" data-id="'+postId+'" data-vote-ids="'+roadmapValue['vote_ids']+'">';
		  htmlRender +=           '<span class="vote-icon">';
		  htmlRender +=             '<i>';
		  htmlRender +=               '<svg viewBox="0 0 1024 1024" data-icon="caret-up" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">';
		  htmlRender +=                 '<path d="M858.9 689L530.5 308.2c-9.4-10.9-27.5-10.9-37 0L165.1 689c-12.2 14.2-1.2 35 18.5 35h656.8c19.7 0 30.7-20.8 18.5-35z"></path>';
		  htmlRender +=               '</svg>';
		  htmlRender +=             '</i>';
		  htmlRender +=           '</span>';
		  htmlRender +=           '<span class="vote-count">Upvoted '+userVoteIds.length+'</span>';
		  htmlRender +=         '</div>';

		  if(downVote == true) {
			var userDownVoteIds = roadmapValue['down_vote_ids'] != null ? roadmapValue['down_vote_ids'].split(" , ") : [];
			var downVoteLength =  roadmapValue['down_vote_length'];
			htmlRender +=       '<div class="fb-button button-down-vote" style="margin-left:5px;" data-button="down-vote" data-down-vote="" data-id="'+postId+'" data-vote-ids="'+roadmapValue['down_vote_ids']+'">';
			htmlRender +=         '<span class="vote-icon">';
			htmlRender +=           '<i class="feedbo-icon">';
			htmlRender +=             '<svg viewBox="0 0 1024 1024" data-icon="caret-up" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">';
			htmlRender +=               '<path d="M840.4 300H183.6c-19.7 0-30.7 20.8-18.5 35l328.4 380.8c9.4 10.9 27.5 10.9 37 0L858.9 335c12.2-14.2 1.2-35-18.5-35z"></path>';
			htmlRender +=             '</svg>';
			htmlRender +=           '</i>';
			htmlRender +=         '</span>';
			htmlRender +=         '<span class="vote-count">Downvoted '+downVoteLength+'</span>';
			htmlRender +=       '</div>';
		  }

		  htmlRender +=       '</div>';
		  htmlRender +=     '</div>';
		  htmlRender +=     '<div class="comment-list-container post_'+postId+'">'; 
		  htmlRender +=     '</div>';
		  htmlRender +=     '<div class="new-comment-form-container">';
		  htmlRender +=       '<form id="form_add_comment" class="new-comment-form" data-id="'+postId+'" >';
		  htmlRender +=         '<div class="form-control">';
		  htmlRender +=           '<input type="text" required class="fb-input" name="comment_text" placeholder="Leave a comment" />';
		  htmlRender +=         '</div>';
		  htmlRender +=         '<div class="form-control">';
		  htmlRender +=           '<div class="upload-file-list"></div>';
		  htmlRender +=         '</div>';
		  htmlRender +=         '<div class="form-control button-comment">';
		  htmlRender +=           '<div class="button-upload">';
		  htmlRender +=             '<button class="fb-btn-upload fb-button">';
		  htmlRender +=               '<i aria-label="icon: upload" class="anticon anticon-upload">';
		  htmlRender +=                 '<svg viewBox="64 64 896 896" data-icon="upload" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false" class="">';
		  htmlRender +=                   '<path d="M400 317.7h73.9V656c0 4.4 3.6 8 8 8h60c4.4 0 8-3.6 8-8V317.7H624c6.7 0 10.4-7.7 6.3-12.9L518.3 163a8 8 0 0 0-12.6 0l-112 141.7c-4.1 5.3-.4 13 6.3 13zM878 626h-60c-4.4 0-8 3.6-8 8v154H214V634c0-4.4-3.6-8-8-8h-60c-4.4 0-8 3.6-8 8v198c0 17.7 14.3 32 32 32h684c17.7 0 32-14.3 32-32V634c0-4.4-3.6-8-8-8z"></path>';
		  htmlRender +=                 '</svg>';
		  htmlRender +=               '</i>';
		  htmlRender +=               '<span style="padding-left:5px;">Attach file</span>';
		  htmlRender +=             '</button>';
		  htmlRender +=             '<input type="file" name="comment_image" multiple />';
		  htmlRender +=           '</div>';
		  htmlRender +=           '<button class="fb-button" type="submit">Comment</button>';
		  htmlRender +=         '</div>';
		  htmlRender +=       '</form>';
		  htmlRender +=     '</div>';
		  htmlRender +=   '</div>';
		  htmlRender += '</div>';  
		});
	  });
	}
	return htmlRender;
  }
  function renderDetail(respondData,downVote) {
	  var htmlRender = "";
	  if(respondData != false){
		jQuery.each(respondData, function (key, value) {
		  var postId = value['post_id'];
		  var postTitle = value['post_title'];
		  var postStatus = value['post_status'];
		  var statusColor = window.bigNinjaWidgetData.colorStatus[postStatus];
		  var userVoteIds = value['vote_ids'].split(" , ");
		  var colorButton = window.bigNinjaWidgetData.checkColorAccent == "dark" ? '#fff' : '#000';
		  htmlRender += '<div class="logDetailsItem" data-id="'+postId+'">';
		  htmlRender +=   '<div class="topBar">';
		  htmlRender +=     '<a href="#back" class="back"></a>';
		  htmlRender +=     '<h3 class="title">'+postTitle+'</h3>';
		  htmlRender +=   '</div>';
		  htmlRender +=   '<div class="slideBody">';
		  htmlRender +=     '<div class="detail-item-status">';
		  htmlRender +=       '<span class="status-title">Status:</span>';
			htmlRender +=     '<span class="status-badge" data-color="'+postStatus+'" style="background-color:'+statusColor+'"></span>';
		  htmlRender +=       '<span class="status-name">'+postStatus+'</span>';
		  htmlRender +=     '</div>';
		  htmlRender +=     '<div class="detail-item-button">';
		  htmlRender +=       '<div class="button-subscribe fb-button">Subscribe to updates</div>';
		  htmlRender +=       '<div class="button-vote-container" style="display:flex;color:'+colorButton+'">';
		  htmlRender +=         '<div class="fb-button button-vote" data-button="vote" data-vote="" data-id="'+postId+'" data-vote-ids="'+value['vote_ids']+'">';
		  htmlRender +=           '<span class="vote-icon">';
		  htmlRender +=             '<i>';
		  htmlRender +=               '<svg viewBox="0 0 1024 1024" data-icon="caret-up" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">';
		  htmlRender +=                 '<path d="M858.9 689L530.5 308.2c-9.4-10.9-27.5-10.9-37 0L165.1 689c-12.2 14.2-1.2 35 18.5 35h656.8c19.7 0 30.7-20.8 18.5-35z"></path>';
		  htmlRender +=               '</svg>';
		  htmlRender +=             '</i>';
		  htmlRender +=           '</span>';
		  htmlRender +=           '<span class="vote-count">Upvoted '+userVoteIds.length+'</span>';
		  htmlRender +=         '</div>';
		  if(downVote == true) {
			var userDownVoteIds = value['down_vote_ids'] != null ? value['down_vote_ids'].split(" , ") : [];
			var downVoteLength =  value['down_vote_length'];
			htmlRender +=       '<div class="fb-button button-down-vote" style="margin-left:5px;" data-button="down-vote" data-down-vote="" data-id="'+postId+'" data-vote-ids="'+value['down_vote_ids']+'">';
			htmlRender +=         '<span class="vote-icon">';
			htmlRender +=           '<i class="feedbo-icon">';
			htmlRender +=             '<svg viewBox="0 0 1024 1024" data-icon="caret-up" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">';
			htmlRender +=               '<path d="M840.4 300H183.6c-19.7 0-30.7 20.8-18.5 35l328.4 380.8c9.4 10.9 27.5 10.9 37 0L858.9 335c12.2-14.2 1.2-35-18.5-35z"></path>';
			htmlRender +=             '</svg>';
			htmlRender +=           '</i>';
			htmlRender +=         '</span>';
			htmlRender +=         '<span class="vote-count">Downvoted '+downVoteLength+'</span>';
			htmlRender +=       '</div>';
		  }
		  htmlRender +=       '</div>';
		  htmlRender +=     '</div>';
		  htmlRender +=     '<div class="comment-list-container post_'+postId+'">'; 
		  htmlRender +=     '</div>';
		  htmlRender +=     '<div class="new-comment-form-container">';
		  htmlRender +=       '<form id="form_add_comment" class="new-comment-form" data-id="'+postId+'" >';
		  htmlRender +=         '<div class="form-control">';
		  htmlRender +=           '<input type="text" required class="fb-input" name="comment_text" placeholder="Leave a comment" />';
		  htmlRender +=         '</div>';
		  htmlRender +=         '<div class="form-control">';
		  htmlRender +=           '<div class="upload-file-list"></div>';
		  htmlRender +=         '</div>';
		  htmlRender +=         '<div class="form-control button-comment">';
		  htmlRender +=           '<div class="button-upload">';
		  htmlRender +=             '<button class="fb-btn-upload fb-button">';
		  htmlRender +=               '<i aria-label="icon: upload" class="anticon anticon-upload">';
		  htmlRender +=                 '<svg viewBox="64 64 896 896" data-icon="upload" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false" class="">';
		  htmlRender +=                   '<path d="M400 317.7h73.9V656c0 4.4 3.6 8 8 8h60c4.4 0 8-3.6 8-8V317.7H624c6.7 0 10.4-7.7 6.3-12.9L518.3 163a8 8 0 0 0-12.6 0l-112 141.7c-4.1 5.3-.4 13 6.3 13zM878 626h-60c-4.4 0-8 3.6-8 8v154H214V634c0-4.4-3.6-8-8-8h-60c-4.4 0-8 3.6-8 8v198c0 17.7 14.3 32 32 32h684c17.7 0 32-14.3 32-32V634c0-4.4-3.6-8-8-8z"></path>';
		  htmlRender +=                 '</svg>';
		  htmlRender +=               '</i>';
		  htmlRender +=               '<span style="padding-left:5px;">Attach file</span>';
		  htmlRender +=             '</button>';
		  htmlRender +=             '<input type="file" name="comment_image" multiple/>';
		  htmlRender +=           '</div>';
		  htmlRender +=           '<button class="fb-button" type="submit">Comment</button>';
		  htmlRender +=         '</div>';
		  htmlRender +=       '</form>';
		  htmlRender +=     '</div>';
		  htmlRender +=   '</div>';
		  htmlRender += '</div>';  
		});
	  }
	return htmlRender;
  }
  function renderComment(respondData, listLikeComment) {
	var htmlRender = "";
	if(respondData != false){
	  jQuery.each(respondData, function (key, value) {
		var commentId = value['comment_ID'];
		var commentAuthor = value['comment_author'] != '' ? value['comment_author']: 'Anonymous';
		var commentAuthorAvatar = '';
		jQuery.each(window.bigNinjaWidgetData.teamAvatar, function (avatarKey, avatarValue) {
		  if(value['comment_author'] == avatarValue['user_name']) {
			commentAuthorAvatar = avatarValue['user_avatar'];
		  }
		});
		var commentPostID = value['comment_post_ID'];
		var commentContent = value['comment_content'];
		var commentDate = value['comment_date'];
		var commentDateFormat = value['comment_date_format'];
		var checkLike = listLikeComment.indexOf(commentId);
		var userLike = value['users_like'] != null ? value['users_like'] : '';
		var countLike = value['userslike_length'];
		var commentImages = value['comment_image'] != null ? value['comment_image'] : [];
		htmlRender +=     '<div class="comment-item-container" data-comment-id="'+commentId+'">';
		htmlRender +=       '<div class="comment-item">';
		htmlRender +=         '<div class="fb-comment-inner">';
		htmlRender +=           '<div class="fb-comment-avatar">';
		if(commentAuthorAvatar != '') {
		  htmlRender +=           '<img src="'+commentAuthorAvatar+'">';
		}
		else {
		  htmlRender +=             '<span class="ant-avatar ant-avatar-lg ant-avatar-circle ant-avatar-icon">';
		  htmlRender +=               '<i aria-label="icon: user" class="anticon anticon-user">';
		  htmlRender +=                 '<svg viewBox="64 64 896 896" data-icon="user" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false" class="">';
		  htmlRender +=                   '<path d="M858.5 763.6a374 374 0 0 0-80.6-119.5 375.63 375.63 0 0 0-119.5-80.6c-.4-.2-.8-.3-1.2-.5C719.5 518 760 444.7 760 362c0-137-111-248-248-248S264 225 264 362c0 82.7 40.5 156 102.8 201.1-.4.2-.8.3-1.2.5-44.8 18.9-85 46-119.5 80.6a375.63 375.63 0 0 0-80.6 119.5A371.7 371.7 0 0 0 136 901.8a8 8 0 0 0 8 8.2h60c4.4 0 7.9-3.5 8-7.8 2-77.2 33-149.5 87.8-204.3 56.7-56.7 132-87.9 212.2-87.9s155.5 31.2 212.2 87.9C779 752.7 810 825 812 902.2c.1 4.4 3.6 7.8 8 7.8h60a8 8 0 0 0 8-8.2c-1-47.8-10.9-94.3-29.5-138.2zM512 534c-45.9 0-89.1-17.9-121.6-50.4S340 407.9 340 362c0-45.9 17.9-89.1 50.4-121.6S466.1 190 512 190s89.1 17.9 121.6 50.4S684 316.1 684 362c0 45.9-17.9 89.1-50.4 121.6S557.9 534 512 534z"></path>';
		  htmlRender +=                 '</svg>';
		  htmlRender +=               '</i>';
		  htmlRender +=             '</span>';
		}
		htmlRender +=           '</div>';
		htmlRender +=           '<div class="fb-comment-content">';
		htmlRender +=             '<div class="fb-comment-author">';
		htmlRender +=               '<span class="fb-comment-author-name">'+commentAuthor+'</span>';
		htmlRender +=               '<span class="fb-comment-author-time">'+commentDateFormat+'</span>';
		htmlRender +=               '<span class="fb-comment-author-like">';
		htmlRender +=                 '<i aria-label="icon: heart" class="anticon" style="font-size: 14px;" data-id="'+commentId+'" data-like="'+userLike+'" data-post="'+commentPostID+'"  data-comment="'+commentId+'">';
		if(checkLike > -1) {
		  htmlRender +=                 '<svg viewBox="64 64 896 896" data-icon="delete" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false" style="color:#dc143c;">';
		  htmlRender +=                   '<path d="M923 283.6a260.04 260.04 0 0 0-56.9-82.8 264.4 264.4 0 0 0-84-55.5A265.34 265.34 0 0 0 679.7 125c-49.3 0-97.4 13.5-139.2 39-10 6.1-19.5 12.8-28.5 20.1-9-7.3-18.5-14-28.5-20.1-41.8-25.5-89.9-39-139.2-39-35.5 0-69.9 6.8-102.4 20.3-31.4 13-59.7 31.7-84 55.5a258.44 258.44 0 0 0-56.9 82.8c-13.9 32.3-21 66.6-21 101.9 0 33.3 6.8 68 20.3 103.3 11.3 29.5 27.5 60.1 48.2 91 32.8 48.9 77.9 99.9 133.9 151.6 92.8 85.7 184.7 144.9 188.6 147.3l23.7 15.2c10.5 6.7 24 6.7 34.5 0l23.7-15.2c3.9-2.5 95.7-61.6 188.6-147.3 56-51.7 101.1-102.7 133.9-151.6 20.7-30.9 37-61.5 48.2-91 13.5-35.3 20.3-70 20.3-103.3.1-35.3-7-69.6-20.9-101.9z"></path>';
		  htmlRender +=                 '</svg>';
		} else {
		  htmlRender +=                 '<svg viewBox="64 64 896 896" data-icon="heart" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">';
		  htmlRender +=                   '<path d="M923 283.6a260.04 260.04 0 0 0-56.9-82.8 264.4 264.4 0 0 0-84-55.5A265.34 265.34 0 0 0 679.7 125c-49.3 0-97.4 13.5-139.2 39-10 6.1-19.5 12.8-28.5 20.1-9-7.3-18.5-14-28.5-20.1-41.8-25.5-89.9-39-139.2-39-35.5 0-69.9 6.8-102.4 20.3-31.4 13-59.7 31.7-84 55.5a258.44 258.44 0 0 0-56.9 82.8c-13.9 32.3-21 66.6-21 101.9 0 33.3 6.8 68 20.3 103.3 11.3 29.5 27.5 60.1 48.2 91 32.8 48.9 77.9 99.9 133.9 151.6 92.8 85.7 184.7 144.9 188.6 147.3l23.7 15.2c10.5 6.7 24 6.7 34.5 0l23.7-15.2c3.9-2.5 95.7-61.6 188.6-147.3 56-51.7 101.1-102.7 133.9-151.6 20.7-30.9 37-61.5 48.2-91 13.5-35.3 20.3-70 20.3-103.3.1-35.3-7-69.6-20.9-101.9zM512 814.8S156 586.7 156 385.5C156 283.6 240.3 201 344.3 201c73.1 0 136.5 40.8 167.7 100.4C543.2 241.8 606.6 201 679.7 201c104 0 188.3 82.6 188.3 184.5 0 201.2-356 429.3-356 429.3z"></path>';
		  htmlRender +=                 '</svg>';
		}
		htmlRender +=                 '</i>';
		htmlRender +=                 '<span>'+countLike+'</span>';
		htmlRender +=               '</span>';
		htmlRender +=             '</div>';
		htmlRender +=             '<div class="fb-comment-detail">'+commentContent+'</div>';
		if(commentImages.length > 0){
		  htmlRender +=           '<div class="fb-comment-image-container">';
		  jQuery.each(commentImages, function (imageKey, imageValue) {
			htmlRender +=           '<div class="fb-comment-image-item">';
			htmlRender +=             '<img class="fb-comment-image" src="'+imageValue['thumb']+'" />';
			htmlRender +=           '</div>';    
		  });
		  htmlRender +=           '</div>';
		}
		htmlRender +=           '</div>';
		htmlRender +=         '</div>';
		htmlRender +=       '</div>';
		htmlRender +=     '</div>';
	  });
	}
	return htmlRender;
  }
  function checkColor (color){
	// Variables for red, green, blue values
	let r; let g; let b; let hsp;
	// Check the format of the color, HEX or RGB?
	if (color.match(/^rgb/)) {
		// If HEX --> store the red, green, blue values in separate variables
		color = color.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/);
		r = color[1];
		g = color[2];
		b = color[3];
	} 
	else {
		// If RGB --> Convert it to HEX: http://gist.github.com/983661
		color = +('0x' + color.slice(1).replace( color.length < 5 && /./g, '$&$&'));
		r = color >> 16;
		g = color >> 8 & 255;
		b = color & 255;
	}
	// HSP (Highly Sensitive Poo) equation from http://alienryderflex.com/hsp.html
	hsp = Math.sqrt(
	0.299 * (r * r) +
	0.587 * (g * g) +
	0.114 * (b * b)
	);
	// Using the HSP value, determine whether the color is light or dark 127.5
	if (hsp>160) {
		return 'light';
	} 
	else {
		return 'dark';
	}
  }
  function viewDetail(listLikeComment) {
	jQuery('body').on('click', '.feedbo-outer-content .feedbo-content .inner-content .fb-content .postItem-container, .feedbo-outer-content .feedbo-content .inner-content .fb-content .postItem-roadmap .roadmap-item-container', function(e) {
	  event.preventDefault();
	  var id = jQuery(this).attr('data-id');
	  var tab = jQuery(".feedbo-outer-content .feedbo-content .inner-content #index .fb-menu .fb-tabs .fb-tab[data-selected='true']").attr('data-tab');
		jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .details-${tab} .logDetailsItem`).each(function() {
		  if(jQuery(this).attr('data-id') == id ) {
			jQuery(this).css('display','flex');
			var skeleton = renderSkeleton();
			jQuery(this).find(".slideBody").append(skeleton);
			loadComment(id,jQuery(this),listLikeComment);
		  }
		});
	  jQuery('.feedbo-outer-content .feedbo-content .inner-content').css('left', '-100%');
	});
  }
  function closeDetail() {
	jQuery('body').on('click', '.feedbo-outer-content .feedbo-content .inner-content #details .topBar .back', function(e) {
	  event.preventDefault();
	  var parent = jQuery(this).parent().parent();
	  parent.css('display','none');
	  jQuery('.feedbo-outer-content .feedbo-content .inner-content').css('left', '0');
	  jQuery(parent).find(".slideBody .comment-list-container").css('display', 'none');
	  jQuery(parent).find(".slideBody .new-comment-form-container").css('display', 'none');
	});
  }
  function viewAddPost() {
	jQuery('body').on('click', '.feedbo-outer-content .feedbo-content .inner-content .fb-menu .fb-new-post', function(e) {
	  event.preventDefault();
	  jQuery('.feedbo-outer-content .feedbo-content .inner-content').css('left', '-200%');
	});
  }
  function closeAddPost() {
	jQuery('body').on('click', '.feedbo-outer-content .feedbo-content .inner-content #add .topBar .back', function(e) {
	  event.preventDefault();
	  jQuery('.feedbo-outer-content .feedbo-content .inner-content').css('left', '0');
	});
  }
  function htmlRenderIconVote(checkVote) {
	var htmlRender = "";
	if(checkVote == true) {
	  htmlRender +=   '<i class="feedbo-icon">';
	  htmlRender +=     '<svg viewBox="64 64 896 896" data-icon="caret-up" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">';
	  htmlRender +=       '<path d="M912 190h-69.9c-9.8 0-19.1 4.5-25.1 12.2L404.7 724.5 207 474a32 32 0 0 0-25.1-12.2H112c-6.7 0-10.4 7.7-6.3 12.9l273.9 347c12.8 16.2 37.4 16.2 50.3 0l488.4-618.9c4.1-5.1.4-12.8-6.3-12.8z"></path>';
	  htmlRender +=     '</svg>';
	  htmlRender +=   '</i>';
	}
	if(checkVote == false) {
	  htmlRender +=   '<i class="feedbo-icon">';
	  htmlRender +=     '<svg viewBox="0 0 1024 1024" data-icon="caret-up" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">';
	  htmlRender +=       '<path d="M858.9 689L530.5 308.2c-9.4-10.9-27.5-10.9-37 0L165.1 689c-12.2 14.2-1.2 35 18.5 35h656.8c19.7 0 30.7-20.8 18.5-35z"></path>';
	  htmlRender +=     '</svg>';
	  htmlRender +=   '</i>';
	}
	return htmlRender;
  }
  function buttonVoteClick(listVote, listDownVote) {
	jQuery('body').on('click', '.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .fb-button, .feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .fb-button', function(e) {
	  e.preventDefault();
	  var action = jQuery(this).attr('data-button');
	  var postId = jQuery(this).attr('data-id');
	  var checkVoted = false;
	  var userVote = jQuery(this).attr("data-vote-ids");
	  var listUserVote = jQuery(this).attr("data-vote-ids").split(" , ");
	  if(jQuery(this).attr(`data-${action}`) == "") {
		checkVoted = true;
		if(action == 'vote') {
		  listVote.push(jQuery(this).attr('data-id'));
		  if(jQuery(this).next().attr('data-down-vote') == 'true') {
			jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-${action}[data-id="${postId}"]`).next().attr('data-down-vote','');
			jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .button-${action}[data-id="${postId}"]`).next().attr('data-down-vote','');
			var downVoteIds = jQuery(this).next().attr('data-vote-ids').split(" , ");
			var downVoteIndex = downVoteIds.lastIndexOf("0");
			if (downVoteIndex > -1) {
			  downVoteIds.splice(downVoteIndex, 1);
			  jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-${action}[data-id="${postId}"]`).next().attr('data-vote-ids',downVoteIds.join(" , "));
			  jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .button-${action}[data-id="${postId}"]`).next().attr('data-vote-ids',downVoteIds.join(" , "));
			}
			jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-down-vote[data-id="${postId}"] .vote-icon`).html(htmlRenderIconVote(false));
			jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-down-vote[data-id="${postId}"] .down-vote-count`).text(downVoteIds.length);
			jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .button-down-vote[data-id="${postId}"] .vote-icon`).html(htmlRenderIconVote(false));
			jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .button-down-vote[data-id="${postId}"] .vote-count`).text('Downvoted ' +downVoteIds.length);
		  }
		  jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-vote[data-id="${postId}"] .vote-icon`).html(htmlRenderIconVote(true));
		  jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-vote[data-id="${postId}"] .vote-count`).text(listUserVote.length + 1);
		  jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .button-vote[data-id="${postId}"] .vote-icon`).html(htmlRenderIconVote(true));
		  jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .button-vote[data-id="${postId}"] .vote-count`).text('Upvoted ' +(listUserVote.length + 1));
		}
		else {
		  listDownVote.push(jQuery(this).attr('data-id'));
		  if(jQuery(this).prev().attr('data-vote') == 'true') {
			jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-${action}[data-id="${postId}"]`).prev().attr('data-vote','');
			jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .button-${action}[data-id="${postId}"]`).prev().attr('data-vote','');
			var voteIds = jQuery(this).prev().attr('data-vote-ids').split(" , ");
			var voteIndex = voteIds.indexOf("0");
			if (voteIndex > -1) {
			  voteIds.splice(voteIndex, 1);
			  jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-${action}[data-id="${postId}"]`).prev().attr('data-vote-ids',voteIds.join(" , "));
			  jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .button-${action}[data-id="${postId}"]`).prev().attr('data-vote-ids',voteIds.join(" , "));
			}
			jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-vote[data-id="${postId}"] .vote-icon`).html(htmlRenderIconVote(false));
			jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-vote[data-id="${postId}"] .vote-count`).text(voteIds.length);
			jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .button-vote[data-id="${postId}"] .vote-icon`).html(htmlRenderIconVote(false));
			jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .button-vote[data-id="${postId}"] .vote-count`).text('Upvoted ' +voteIds.length);
		  }
		  jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-down-vote[data-id="${postId}"] .vote-icon`).html(htmlRenderIconVote(true));
		  jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-down-vote[data-id="${postId}"] .down-vote-count`).text(listUserVote.length + 1);
		  jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .button-down-vote[data-id="${postId}"] .vote-icon`).html(htmlRenderIconVote(true));
		  jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .button-down-vote[data-id="${postId}"] .vote-count`).text('Downvoted ' +(listUserVote.length +1));
		}
		listUserVote.push("0");
		jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-${action}[data-id="${postId}"]`).attr(`data-${action}`,'true');
		jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-${action}[data-id="${postId}"]`).attr('data-vote-ids',listUserVote.join(" , "));
		jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .button-${action}[data-id="${postId}"]`).attr(`data-${action}`,'true');
		jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .button-${action}[data-id="${postId}"]`).attr('data-vote-ids',listUserVote.join(" , "));
		
		// Call API
		var post = {
		  term_id: '<?php echo $boardId; ?>',
		  post_id: postId,
		  vote_ids: jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-vote[data-id="${postId}"]`).attr('data-vote-ids'),
		  down_vote_ids: jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-down-vote[data-id="${postId}"]`).attr('data-vote-ids')
		};
		addVote(action,post,checkVoted);
		var idIndex = action == 'vote' ? listDownVote.indexOf(postId) : listVote.indexOf(postId);
		if(idIndex > -1) {
		  action == 'vote' ? listDownVote.splice(idIndex, 1) : listVote.splice(idIndex, 1);
		}
	  }
	  else {
		const index = action == 'vote' ? listVote.indexOf(jQuery(this).attr('data-id')) : listDownVote.indexOf(jQuery(this).attr('data-id')) ;
		if (index > -1) {
		  action == 'vote' ? listVote.splice(index, 1) : listDownVote.splice(index, 1) ;
		}
		var post = {
		  term_id: '<?php echo $boardId; ?>',
		  post_id: postId,
		  vote_ids: jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-vote[data-id="${postId}"]`).attr('data-vote-ids'),
		  down_vote_ids: jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-down-vote[data-id="${postId}"]`).attr('data-vote-ids')
		};
		addVote(action,post,checkVoted);
		jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-${action}[data-id="${postId}"]`).attr(`data-${action}`,'');
		jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-${action}[data-id="${postId}"] .vote-icon`).html(htmlRenderIconVote(false));
		jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-${action}[data-id="${postId}"] .${action}-count`).text(listUserVote.length -1);
		jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .button-${action}[data-id="${postId}"]`).attr(`data-${action}`,'');
		jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .button-${action}[data-id="${postId}"] .vote-icon`).html(htmlRenderIconVote(false));
		jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .button-${action}[data-id="${postId}"] .vote-count`).text(action == "vote"?'Upvoted '+(listUserVote.length -1) : 'Downvoted ' +(listUserVote.length -1)) ;
		const spliceIndex = listUserVote.lastIndexOf("0");
		if (spliceIndex > -1) {
		  listUserVote.splice(spliceIndex, 1);
		  jQuery(`.feedbo-outer-content .feedbo-content .inner-content .fb-content .button-container .button-${action}[data-id="${postId}"]`).attr('data-vote-ids',listUserVote.join(" , "));
		  jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .button-vote-container .button-${action}[data-id="${postId}"]`).attr('data-vote-ids',listUserVote.join(" , "));
		}
	  }
	});
  }
  function addVote(action,post,checkVote) {
	jQuery.ajax({
		  url: action == "vote" ? '<?php echo site_url() . '/wp-json/v1/wp_add_vote'; ?>' : '<?php echo site_url() . '/wp-json/v1/wp_add_down_vote'; ?>',
		  type: 'POST',
		  dataType : "json",
		  data: {post:post,userVote: "0",checkVote: !checkVote},
		  success: function(response) {
			
		  },
		  error: function( jqXHR, textStatus, errorThrown ){
			  //Làm gì đó khi có lỗi xảy ra
			  console.log( 'The following error occured: ' + textStatus, errorThrown );
		  }
	});
  }
  function buttonSubscribeClick() {
	jQuery('body').on('click' , '.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .slideBody .button-subscribe', function(e) {
	  e.preventDefault();
	  alert('Please sign in to subscribe.');
	});
	
  }
  function renderUploadList(inputName,fileName, fileUrl) {
	var htmlRender = "";
	var index = inputName == 'new_post_image' ? window.bigNinjaWidgetData.newpostFileList.length : window.bigNinjaWidgetData.commentFileList.length;
	htmlRender += '<div class="upload-list-item-wrap" data-index="'+index+'">';
	htmlRender +=   '<span>';
	htmlRender +=     '<div class="upload-list-item">';
	htmlRender +=       '<div class="ant-upload-list-item-info">';
	htmlRender +=         '<span>';
	htmlRender +=           '<img class="upload-list-item-thumbnail" src="'+fileUrl+'">';
	htmlRender +=           '<span title="'+fileName+'" class="upload-list-item-name">'+fileName+'</span>';
	htmlRender +=           '<span class="upload-list-item-card-actions" data-name="'+fileName+'" data-index="'+index+'">';
	htmlRender +=             '<a title="Remove file">';
	htmlRender +=               '<i aria-label="icon: delete" tabindex="-1" class="anticon anticon-delete" title="Remove file">';
	htmlRender +=                 '<svg viewBox="64 64 896 896" data-icon="delete" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false" class="">';
	htmlRender +=                   '<path d="M360 184h-8c4.4 0 8-3.6 8-8v8h304v-8c0 4.4 3.6 8 8 8h-8v72h72v-80c0-35.3-28.7-64-64-64H352c-35.3 0-64 28.7-64 64v80h72v-72zm504 72H160c-17.7 0-32 14.3-32 32v32c0 4.4 3.6 8 8 8h60.4l24.7 523c1.6 34.1 29.8 61 63.9 61h454c34.2 0 62.3-26.8 63.9-61l24.7-523H888c4.4 0 8-3.6 8-8v-32c0-17.7-14.3-32-32-32zM731.3 840H292.7l-24.2-512h487l-24.2 512z"></path>';
	htmlRender +=                 '</svg>';
	htmlRender +=               '</i>';
	htmlRender +=             '</a>';
	htmlRender +=           '</span>';
	htmlRender +=         '</span>';
	htmlRender +=       '</div>';
	htmlRender +=     '</div>';
	htmlRender +=   '</span>';
	htmlRender += '</div>';
	return htmlRender;
  }
  function uploadFile() {
	jQuery('body').on('change' , '.feedbo-outer-content .feedbo-content .inner-content input[type="file"]', function(e) {
	  let inputName = jQuery(this).attr('name');
	  jQuery.each(jQuery(this)[0].files, function (key, value) {
		var formData = new FormData();
		formData.append('file', value);
		jQuery.ajax({
		  url: '<?php echo site_url() . '/wp-json/v1/wp_upload_file'; ?>',
		  data: formData,
		  type: 'POST',
		  contentType: false,
		  processData: false,
		  success: function(response) {
			var uploadListRender = renderUploadList(inputName,response.name, response.thumbUrl);
			if (inputName == 'new_post_image') {
			  window.bigNinjaWidgetData.newpostFileList.push({
				fileName: response.name,
				response: response,
			  });
			  jQuery('.feedbo-outer-content .feedbo-content .inner-content #add .slideBody .new-post-form .form-control .upload-file-list').append(uploadListRender);
			} else if (inputName == 'comment_image') {
			  window.bigNinjaWidgetData.commentFileList.push({
				fileName: response.name,
				response: response,
			  });
			  jQuery('.feedbo-outer-content .feedbo-content .inner-content #details .slideBody .new-comment-form .form-control .upload-file-list').append(uploadListRender);
			}       
		  },
		  error: function( jqXHR, textStatus, errorThrown ){
			  //Làm gì đó khi có lỗi xảy ra
			  console.log( 'The following error occured: ' + textStatus, errorThrown );
		}
	  });
	});
	  
	});
  }
  function removeFile() {
	jQuery('body').on('click' , '.feedbo-outer-content .feedbo-content .inner-content #add .slideBody .upload-file-list  .upload-list-item-card-actions', function(e) {
	  e.preventDefault();
	  var index = jQuery(this).attr('data-index');
	  var fileName = jQuery(this).attr('data-name');
	  var indexDelete = -1;
	  window.bigNinjaWidgetData.newpostFileList.forEach(function (element, i) {
		if(element.fileName == fileName){
		  indexDelete = i;
		}
	  });
	  if(indexDelete > -1) {
		window.bigNinjaWidgetData.newpostFileList.splice(indexDelete, 1);
	  }
	  jQuery(`.feedbo-outer-content .feedbo-content .inner-content #add .slideBody .upload-file-list .upload-list-item-wrap[data-index="${index}"]`).remove();
	});
	jQuery('body').on('click' , '.feedbo-outer-content .feedbo-content .inner-content #details .slideBody .upload-file-list  .upload-list-item-card-actions', function(e) {
	  e.preventDefault();
	  var index = jQuery(this).attr('data-index');
	  var fileName = jQuery(this).attr('data-name');
	  var indexDelete = -1;
	  window.bigNinjaWidgetData.commentFileList.forEach(function (element, i) {
		if(element.fileName == fileName){
		  indexDelete = i;
		}
	  });
	  if(indexDelete > -1) {
		window.bigNinjaWidgetData.commentFileList.splice(index, 1);
	  }
	  jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .slideBody .upload-file-list .upload-list-item-wrap[data-index="${index}"]`).remove();
	});
  }
  function addNewPost() {
	jQuery('#form_add_post').submit(function(e) {
	  e.preventDefault();
	  var title = jQuery('.feedbo-outer-content .feedbo-content .inner-content #add .slideBody .new-post-form .form-control input[name="post_title"]').val();
	  var content = jQuery('.feedbo-outer-content .feedbo-content .inner-content #add .slideBody .new-post-form .form-control textarea[name="post_details"]').val();
	  var file = window.bigNinjaWidgetData.newpostFileList;
	  var values = {
		title: title,
		content: content,
		file: file
	  }
	  if(title != "" && content != "") {
		jQuery.ajax({
		  url: '<?php echo site_url() . '/wp-json/v1/wp_add_post'; ?>',
		  type: 'POST',
		  dataType : "json",
		  data: {id:'<?php echo $boardId; ?>',values: values},
			success: function(response) {
			  var newPost = renderNewPost(response);
			  var newPostDetail = renderNewPostDetail(response);
			  jQuery('.feedbo-outer-content .feedbo-content .inner-content .fb-content #vote').append(newPost);
			  jQuery('.feedbo-outer-content .feedbo-content .inner-content #details .details-vote').append(newPostDetail);
			  jQuery('.feedbo-outer-content .feedbo-content .inner-content .fb-content #new').prepend(newPost);
			  jQuery('.feedbo-outer-content .feedbo-content .inner-content #details .details-new').prepend(newPostDetail);
			  window.bigNinjaWidgetData.newpostFileList = [];
			  jQuery(`.feedbo-outer-content .feedbo-content .inner-content #add .slideBody .new-post-form .upload-file-list`).empty();
			  jQuery('.feedbo-outer-content .feedbo-content .inner-content #add .slideBody .new-post-form .form-control textarea[name="post_details"]').val('');
			  jQuery('.feedbo-outer-content .feedbo-content .inner-content #add .slideBody .new-post-form .form-control input[name="post_title"]').val('');
			  alert("Add new post success");
			},
			error: function( jqXHR, textStatus, errorThrown ){
			  console.log( 'The following error occured: ' + textStatus, errorThrown );
			}
		});
	  }
	});   
  }
  function addComment(listLikeComment) {
	jQuery('body').on('submit', '.feedbo-outer-content .feedbo-content .inner-content #form_add_comment', function(e) {
	  e.preventDefault();
	  var id = jQuery(this).attr('data-id');
	  var termId = '<?php echo $boardId; ?>';
	  var author = "0";
	  var content = jQuery(this).find(`.form-control input[name="comment_text"]`).val();
	  var image = window.bigNinjaWidgetData.commentFileList;
	  var req = {
		termId: termId,
		id: id,
		author: author,
		values : {
		  comment: content,
		  file: image
		}
	  }
	  if(content != "") {
		jQuery.ajax({
		  url: '<?php echo site_url() . '/wp-json/v1/wp_add_comment'; ?>',
		  type: 'POST',
		  dataType : "json",
		  data: {comment: req,img: image},
			success: function() {
			  loadComment(id,jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem[data-id="${id}"]`),listLikeComment);
			  window.bigNinjaWidgetData.commentFileList = [];
			  jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem .new-comment-form .upload-file-list`).empty();
			  jQuery(`.feedbo-outer-content .feedbo-content .inner-content #details .logDetailsItem #form_add_comment .form-control input[name="comment_text"]`).val('');
			  alert("Add new comment success");
			},
			error: function( jqXHR, textStatus, errorThrown ){
			  console.log( 'The following error occured: ' + textStatus, errorThrown );
			}
		});
	  }
	});
  }
  function likeComment(listLikeComment) {
	jQuery('body').on('click', '.feedbo-outer-content .feedbo-content .inner-content #details .slideBody .comment-item .fb-comment-author-like i', function(e) {
	  e.preventDefault();
	  var commentId = jQuery(this).attr('data-id');
	  var index = listLikeComment.indexOf(commentId);
	  var userLike = Number(jQuery(this).next().text());
	  var likeIds = jQuery(this).attr('data-like') != null ? jQuery(this).attr('data-like') : "";
	  var listLikeIds = likeIds != "" ? likeIds.split(" , ")  : [];
	  if (index > -1) {
		listLikeComment.splice(index, 1);
		jQuery(this).next().text(userLike - 1);
		jQuery(this).css('color','inherit');
		var renderIcon = renderIconLike(false);
		jQuery(this).html(renderIcon);
		var likeIndex = listLikeIds.lastIndexOf("0");
		listLikeIds.splice(likeIndex,1);
	  } else {
		listLikeComment.push(commentId);
		jQuery(this).next().text(userLike + 1);
		jQuery(this).css('color','#dc143c');
		var renderIcon = renderIconLike(true);
		jQuery(this).html(renderIcon);
		listLikeIds.push("0");
	  }
	  let checkLike = index > -1 ? true : false;
	  jQuery(this).attr('data-like',listLikeIds.join(" , "));
	  var comment = {
		  comment_ID: jQuery(this).attr('data-comment'),
		  comment_post_ID: jQuery(this).attr('data-post'),
		  users_like: jQuery(this).attr('data-like'),
		};
	  jQuery.ajax({
		url: '<?php echo site_url() . '/wp-json/v1/wp_update_users_like'; ?>',
		type: 'POST',
		dataType : "json",
		data: {boardId: '<?php echo $boardId; ?>',comment: comment, userLike: "0", checkLike: checkLike},
		success: function() {
		},
		error: function( jqXHR, textStatus, errorThrown ){
		  console.log( 'The following error occured: ' + textStatus, errorThrown );
		}
	  });
	});
  }
  function renderIconLike(like) {
	var htmlRender = "";
	if(like == true) {
	  htmlRender += '<svg viewBox="64 64 896 896" data-icon="delete" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false" class="">';
	  htmlRender +=   '<path d="M923 283.6a260.04 260.04 0 0 0-56.9-82.8 264.4 264.4 0 0 0-84-55.5A265.34 265.34 0 0 0 679.7 125c-49.3 0-97.4 13.5-139.2 39-10 6.1-19.5 12.8-28.5 20.1-9-7.3-18.5-14-28.5-20.1-41.8-25.5-89.9-39-139.2-39-35.5 0-69.9 6.8-102.4 20.3-31.4 13-59.7 31.7-84 55.5a258.44 258.44 0 0 0-56.9 82.8c-13.9 32.3-21 66.6-21 101.9 0 33.3 6.8 68 20.3 103.3 11.3 29.5 27.5 60.1 48.2 91 32.8 48.9 77.9 99.9 133.9 151.6 92.8 85.7 184.7 144.9 188.6 147.3l23.7 15.2c10.5 6.7 24 6.7 34.5 0l23.7-15.2c3.9-2.5 95.7-61.6 188.6-147.3 56-51.7 101.1-102.7 133.9-151.6 20.7-30.9 37-61.5 48.2-91 13.5-35.3 20.3-70 20.3-103.3.1-35.3-7-69.6-20.9-101.9z"></path>';
	  htmlRender += '</svg>';
	} else {
	  htmlRender += '<svg viewBox="64 64 896 896" data-icon="heart" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">';
	  htmlRender +=    '<path d="M923 283.6a260.04 260.04 0 0 0-56.9-82.8 264.4 264.4 0 0 0-84-55.5A265.34 265.34 0 0 0 679.7 125c-49.3 0-97.4 13.5-139.2 39-10 6.1-19.5 12.8-28.5 20.1-9-7.3-18.5-14-28.5-20.1-41.8-25.5-89.9-39-139.2-39-35.5 0-69.9 6.8-102.4 20.3-31.4 13-59.7 31.7-84 55.5a258.44 258.44 0 0 0-56.9 82.8c-13.9 32.3-21 66.6-21 101.9 0 33.3 6.8 68 20.3 103.3 11.3 29.5 27.5 60.1 48.2 91 32.8 48.9 77.9 99.9 133.9 151.6 92.8 85.7 184.7 144.9 188.6 147.3l23.7 15.2c10.5 6.7 24 6.7 34.5 0l23.7-15.2c3.9-2.5 95.7-61.6 188.6-147.3 56-51.7 101.1-102.7 133.9-151.6 20.7-30.9 37-61.5 48.2-91 13.5-35.3 20.3-70 20.3-103.3.1-35.3-7-69.6-20.9-101.9zM512 814.8S156 586.7 156 385.5C156 283.6 240.3 201 344.3 201c73.1 0 136.5 40.8 167.7 100.4C543.2 241.8 606.6 201 679.7 201c104 0 188.3 82.6 188.3 184.5 0 201.2-356 429.3-356 429.3z"></path>';
	  htmlRender += '</svg>';
	}
	return htmlRender;
  }
  function renderNewPost(newPost) {
	var htmlRender = "";
	if(newPost != null) {
	  var postId = newPost['post_id'];
	  var postTitle = newPost['post_title'];
	  var postStatus = newPost['post_status'];
	  var postContent = newPost['post_content'];
	  var userVoteIds = newPost['vote_ids'].split(" , ");
	  var voteLength = newPost['vote_length'];
	  htmlRender += '<a href="#" class="postItem" data-id="'+ postId+'">';
	  htmlRender +=   '<div class="postItem-container" data-id="'+ postId+'">';
	  htmlRender +=     '<div class="postItem-content">';
	  htmlRender +=       '<strong class="title" data-label-value="'+ postTitle+'">'+ postTitle+'</strong>';
	  htmlRender +=       '<span class="postItem-status" data-label-value="'+ postStatus+'">'+ postStatus+'</span>';
	  htmlRender +=       '<p class="description">'+ postContent+'</p>';
	  htmlRender +=     '</div>';
	  htmlRender +=   '</div>';
	  htmlRender +=   '<div class="button-container">';
	  htmlRender +=     '<div class="fb-button button-vote" data-button="vote" data-id="'+ postId+'" data-vote="" data-vote-ids="'+ newPost['vote_ids']+'">';
	  htmlRender +=       '<span class="vote-icon">';
	  htmlRender +=         '<i class="feedbo-icon">';
	  htmlRender +=           '<svg viewBox="0 0 1024 1024" data-icon="caret-up" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">';
	  htmlRender +=             '<path d="M858.9 689L530.5 308.2c-9.4-10.9-27.5-10.9-37 0L165.1 689c-12.2 14.2-1.2 35 18.5 35h656.8c19.7 0 30.7-20.8 18.5-35z"></path>';
	  htmlRender +=           '</svg>';
	  htmlRender +=         '</i>';
	  htmlRender +=       '</span>';
	  htmlRender +=       '<span class="vote-count">'+ voteLength+'</span>';
	  htmlRender +=     '</div>';
	  if(window.bigNinjaWidgetData.downVote == true) {
		var userDownVoteIds = newPost['down_vote_ids'] != null ? newPost['down_vote_ids'].split(" , ") : [];
		var downVoteLength = newPost['down_vote_length'];
		htmlRender +=   '<div class="fb-button button-down-vote" style="margin-top:5px;" data-button="down-vote" data-down-vote="" data-id="'+postId+'" data-vote-ids="'+newPost['down_vote_ids']+'">';
		htmlRender +=     '<span class="vote-icon">';
		htmlRender +=       '<i class="feedbo-icon">';
		htmlRender +=         '<svg viewBox="0 0 1024 1024" data-icon="caret-up" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">';
		htmlRender +=           '<path d="M840.4 300H183.6c-19.7 0-30.7 20.8-18.5 35l328.4 380.8c9.4 10.9 27.5 10.9 37 0L858.9 335c12.2-14.2 1.2-35-18.5-35z"></path>';
		htmlRender +=         '</svg>';
		htmlRender +=       '</i>';
		htmlRender +=     '</span>';
		htmlRender +=     '<span class="down-vote-count">'+downVoteLength+'</span>';
		htmlRender +=   '</div>';
	  }
	  htmlRender +=   '</div>';
	  htmlRender += '</a>';
	}
	return htmlRender;
  }
  function renderNewPostDetail(newPost) {
	var htmlRender = '';
	if(newPost != null) {
	  var postId = newPost['post_id'];
	  var postTitle = newPost['post_title'];
	  var postStatus = newPost['post_status'];
	  var statusColor = window.bigNinjaWidgetData.colorStatus[postStatus];
	  var userVoteIds = newPost['vote_ids'].split(" , ");
	  var colorButton = window.bigNinjaWidgetData.checkColorAccent == "dark" ? '#fff' : '#000';
	  htmlRender += '<div class="logDetailsItem" data-id="'+postId+'">';
	  htmlRender +=   '<div class="topBar">';
	  htmlRender +=     '<a href="#back" class="back"></a>';
	  htmlRender +=     '<h3 class="title">'+postTitle+'</h3>';
	  htmlRender +=   '</div>';
	  htmlRender +=   '<div class="slideBody">';
	  htmlRender +=     '<div class="detail-item-status">';
	  htmlRender +=       '<span class="status-title">Status:</span>';
	  htmlRender +=       '<span class="status-badge" data-color="'+postStatus+'" style="background-color:'+statusColor+'"></span>';
	  htmlRender +=       '<span class="status-name">'+postStatus+'</span>';
	  htmlRender +=     '</div>';
	  htmlRender +=     '<div class="detail-item-button">';
	  htmlRender +=       '<div class="button-subscribe fb-button">Subscribe to updates</div>';
	  htmlRender +=       '<div class="button-vote-container" style="display:flex;color:'+colorButton+'">';
	  htmlRender +=         '<div class="fb-button button-vote" data-button="vote" data-vote="" data-id="'+postId+'" data-vote-ids="'+newPost['vote_ids']+'">';
	  htmlRender +=           '<span class="vote-icon">';
	  htmlRender +=             '<i>';
	  htmlRender +=               '<svg viewBox="0 0 1024 1024" data-icon="caret-up" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">';
	  htmlRender +=                 '<path d="M858.9 689L530.5 308.2c-9.4-10.9-27.5-10.9-37 0L165.1 689c-12.2 14.2-1.2 35 18.5 35h656.8c19.7 0 30.7-20.8 18.5-35z"></path>';
	  htmlRender +=               '</svg>';
	  htmlRender +=             '</i>';
	  htmlRender +=           '</span>';
	  htmlRender +=           '<span class="vote-count">Upvoted '+userVoteIds.length+'</span>';
	  htmlRender +=         '</div>';
	  if(window.bigNinjaWidgetData.downVote == true) {
		var userDownVoteIds = newPost['down_vote_ids'] != null ? newPost['down_vote_ids'].split(" , ") : [];
		var downVoteLength =  newPost['down_vote_length'];
		htmlRender +=       '<div class="fb-button button-down-vote" style="margin-left:5px;" data-button="down-vote" data-down-vote="" data-id="'+postId+'" data-vote-ids="'+newPost['down_vote_ids']+'">';
		htmlRender +=         '<span class="vote-icon">';
		htmlRender +=           '<i class="feedbo-icon">';
		htmlRender +=             '<svg viewBox="0 0 1024 1024" data-icon="caret-up" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false">';
		htmlRender +=               '<path d="M840.4 300H183.6c-19.7 0-30.7 20.8-18.5 35l328.4 380.8c9.4 10.9 27.5 10.9 37 0L858.9 335c12.2-14.2 1.2-35-18.5-35z"></path>';
		htmlRender +=             '</svg>';
		htmlRender +=           '</i>';
		htmlRender +=         '</span>';
		htmlRender +=         '<span class="vote-count">Downvoted '+downVoteLength+'</span>';
		htmlRender +=       '</div>';
	  }
	  htmlRender +=       '</div>';
	  htmlRender +=     '</div>';
	  htmlRender +=     '<div class="comment-list-container post_'+postId+'">'; 
	  htmlRender +=     '</div>';
	  htmlRender +=     '<div class="new-comment-form-container">';
	  htmlRender +=       '<form id="form_add_comment" class="new-comment-form" data-id="'+postId+'" >';
	  htmlRender +=         '<div class="form-control">';
	  htmlRender +=           '<input type="text" required class="fb-input" name="comment_text" placeholder="Leave a comment" />';
	  htmlRender +=         '</div>';
	  htmlRender +=         '<div class="form-control">';
	  htmlRender +=           '<div class="upload-file-list"></div>';
	  htmlRender +=         '</div>';
	  htmlRender +=         '<div class="form-control button-comment">';
	  htmlRender +=           '<div class="button-upload">';
	  htmlRender +=             '<button class="fb-btn-upload fb-button">';
	  htmlRender +=               '<i aria-label="icon: upload" class="anticon anticon-upload">';
	  htmlRender +=                 '<svg viewBox="64 64 896 896" data-icon="upload" width="1em" height="1em" fill="currentColor" aria-hidden="true" focusable="false" class="">';
	  htmlRender +=                   '<path d="M400 317.7h73.9V656c0 4.4 3.6 8 8 8h60c4.4 0 8-3.6 8-8V317.7H624c6.7 0 10.4-7.7 6.3-12.9L518.3 163a8 8 0 0 0-12.6 0l-112 141.7c-4.1 5.3-.4 13 6.3 13zM878 626h-60c-4.4 0-8 3.6-8 8v154H214V634c0-4.4-3.6-8-8-8h-60c-4.4 0-8 3.6-8 8v198c0 17.7 14.3 32 32 32h684c17.7 0 32-14.3 32-32V634c0-4.4-3.6-8-8-8z"></path>';
	  htmlRender +=                 '</svg>';
	  htmlRender +=               '</i>';
	  htmlRender +=               '<span style="padding-left:5px;">Attach file</span>';
	  htmlRender +=             '</button>';
	  htmlRender +=             '<input type="file" name="comment_image" multiple />';
	  htmlRender +=           '</div>';
	  htmlRender +=           '<button class="fb-button" type="submit">Comment</button>';
	  htmlRender +=         '</div>';
	  htmlRender +=       '</form>';
	  htmlRender +=     '</div>';
	  htmlRender +=   '</div>';
	  htmlRender += '</div>';
	}
	return htmlRender;
  }
  function renderSkeleton() {
	var htmlRender = "";
	htmlRender += '<div class="fb-skeleton-container">';
	htmlRender +=   '<div class="fb-skeleton-post">';
	htmlRender +=     '<div class="fb-skeleton-avatar"></div>';
	htmlRender +=     '<div class="fb-skeleton-line"></div>';
	htmlRender +=     '<div class="fb-skeleton-line"></div>';
	htmlRender +=   '</div>';
	htmlRender += '</div>';
	return htmlRender;
  }
</script>
