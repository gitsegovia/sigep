<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>
	Pagination Helper | 	The Bakery, Everything CakePHP : Articles</title>
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link rel="alternate" type="application/rss+xml" title="The Bakery : 15 most recent articles" href="/articles/rss">
<link rel="alternate" type="application/rss+xml" title="The Bakery : 15 most recent comments" href="/comments.rss">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><link rel="stylesheet" type="text/css" href="/css/cake.basic.css" media="all" /><link rel="stylesheet" type="text/css" href="/css/cake.bakery.css" media="all" />
<script type="text/javascript" src="/js/prototype.js"></script><script type="text/javascript" src="/js/effects.js"></script><script type="text/javascript" src="/js/bakery.js"></script><script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
 <script type="text/javascript">
         _uacct = "UA-743287-1";
         urchinTracker();
 </script>
</head>
<body class="fp">
  <div id="container">

	  <div id="accountMenu">
	<ul>
		<li><a href="/" >Home</a></li>
		<li><a href="/users/login" >Login</a></li>
		<li><a href="/users/register" >Register</a></li>
	</ul>
</div>
	  <div id="header">
		  <a href="http://bakery.cakephp.org"><h1>The Bakery <span>Everything CakePHP</span></h1></a>
		</div>

		
<div id="tabs">
	<ul>
				<li><a href="/categories/view/1"  class="">News</a></li>

				<li><a href="/categories/view/2"  class="">Articles</a></li>

				<li><a href="/categories/view/3"  class="">Code</a></li>
	</ul>
</div>

<div id="search">
	<form id="SearchForm" method="post" action="/articles/search"><p style="display: inline; margin: 0px; padding: 0px;"><input type="hidden" name="data[__Token][key]" value="f7d9269b21287547bde43a0bde98b9826e45a7b0" id="SearchFormToken1135078149" /></p>		<input name="data[Search][words]" type="text" size="16" value="" id="SearchWords" />		<select name="data[Search][type]" id="SearchType">
<option value="tags">Tags</option>
<option value="1">News</option>
<option value="2">Articles</option>
<option value="3">Code</option>
</select>	<p style="display: inline; margin: 0px; padding: 0px;"><input type="hidden" name="data[__Token][fields]" value="501720d00318f4529495d467e4d438be368d3e2d" id="TokenFields229464314" /></p></form></div>
		

		<div id="content">
				    	
<div id="article" class="published">
	<h3>
		<a href="/articles/view/pagination-helper" >Pagination Helper</a>	</h3>
	
		
	<div id="sidebar">
	
		<div id="article_badges">
			<span class="helpers badge"> <a href="/categories/view/8" >Helpers</a></span>
			<span class="date"> 10/09/2006 </span>		</div>
		
  		<div id="article_details" class="info">
	  		<h4>details</h4>
	  		<ul>
	  							
								
	  			
	  			<li>version: 1.1.x</li>	

	  			<li>views: 12106</li>

	  				  				<li><a href="/articles/view/pagination-helper#comments" >comments (0)</a></li>
	  				  		</ul>
	  	</div>

		<div id="ajax_rate" class="info">
			<div id="article_rating">
	<h4>rating</h4>
		
	
		<ul>
			<li><a href="/articles/rate/68" >Login to add rating</a></li>
		</ul>
	</div>		</div>
	
		<div id="ajax_tag" class="info">
			<div id="article_tags">
	<h4>tags</h4>
			 <ul>
				      		<li><a href="/tags/view/helper" >helper</a>				      		<li><a href="/tags/view/pagination" >pagination</a>					</ul>
	
			<ul id="add_tag_button" class="actions">
			<li><a href="/articles/tag/68" >Login to add tags</a></li>
		</ul>
	</div>		</div>

		<!-- attachments -->
	  			<!-- /attachments -->
		
		<!-- leafs -->
				<!-- /leafs -->
		
	</div> <!-- /sidebar -->
	
	<div class="main">
	
		<span class="author">
	    	By <a href="/users/view/AD7six" >Andy Dawson</a> aka "AD7six"
	  	</span>
	
		<div class="intro">
			The pagination helper, for instruction on use see: <a href="http://bakery.cakephp.org/articles/view/65">http://bakery.cakephp.org/articles/view/65</a>		</div>
	
		<div class="body">
		
			<h4><strong>Helper Class:</strong></h4><a href="/articles/download_code/68/block/1" >Download code</a><code><span style="color: #000000">
<span style="color: #0000BB">&lt;?php&nbsp;
</span>
</code>
		</div>
	
			
	</div>
</div>
<!-- comments -->
<!-- /comments -->

<!-- add comments -->
		<a name="addCommentAnchor"></a>
					<div id="addComment">
				<h3>
					<a href="/comments/add/68#comments"  id="link712376674" onclick=" return false;">Login to Submit a Comment</a><script type="text/javascript">Event.observe('link712376674', 'click', function(event) { new Ajax.Updater('addComment','/comments/add/68#comments', {asynchronous:true, evalScripts:true, onLoading:function(request) {Effect.Appear('loader');}, onComplete:function(request, json) {Effect.Appear('addComment');Effect.Fade('loader'); $('addCommentAnchor').scrollTo();}, requestHeaders:['X-Update', 'addComment']}) }, false);</script>					<em id="loader" style="display:none">Loading...</em>
				</h3>
			</div>
			<div class="clear"><!----></div>
		<!-- /add comments -->
			
			<div class="clear"><!----></div>
		</div> <!-- #content -->
	</div> <!-- #container -->
	<div id="footer-container">
		<div id="footer">
			<div class="latest-container">
		<div class="latest articles">
		<h4>Latest Articles</h4>
		
		<ul>
							<li><a href="/articles/view/getting-started-quickly-with-scriptaculous-effects" >Getting started quickly with Scriptaculous effects</a></li>
							<li><a href="/articles/view/using-equalto-validation-to-compare-two-form-fields" >Using Custom Validation Rules To Compare Two Form Fields</a></li>
							<li><a href="/articles/view/using-jpgraph" >Using JpGraph</a></li>
							<li><a href="/articles/view/emailcomponent-in-a-cake-shell" >EmailComponent in a (cake) Shell</a></li>
							<li><a href="/articles/view/ajax-elements-available-anywere" >Ajax elements available anywhere </a></li>
					</ul>
	</div>
		<div class="latest code">
		<h4>Latest Code</h4>
		
		<ul>
							<li><a href="/articles/view/asset-mapper" >Asset Mapper</a></li>
							<li><a href="/articles/view/persian-date-helper" >Persian Date Helper</a></li>
							<li><a href="/articles/view/yahoo-weather-component" >Yahoo weather component</a></li>
							<li><a href="/articles/view/want-to-order-your-sql" >Want to order your SQL</a></li>
							<li><a href="/articles/view/prototip-helper" >Prototip Helper</a></li>
					</ul>
	</div>
		<div class="latest news">
		<h4>Latest News</h4>
		
		<ul>
							<li><a href="/articles/view/new-year-new-beta" >New Year, New Beta</a></li>
							<li><a href="/articles/view/cakefest-2008-02-06-orlando-fl" >CakeFest : 2008-02-06, Orlando FL</a></li>
							<li><a href="/articles/view/new-cakephp-releases" >New CakePHP Releases</a></li>
							<li><a href="/articles/view/mambo-licious" >Mambo-licious</a></li>
							<li><a href="/articles/view/the-last-alpha-cake" >The Last Alpha Cake</a></li>
					</ul>
	</div>
		<div class="latest comments">
		<h4>Latest Comments</h4>
		
		<ul>
							<li><a href="/articles/view/p28n-the-top-to-bottom-persistent-internationalization-tutorial#comment-1809" >doesnt work for me either</a></li>
							<li><a href="/articles/view/setting-up-eclipse-to-work-with-cake#comment-1807" >app in url</a></li>
							<li><a href="/articles/view/using-equalto-validation-to-compare-two-form-fields#comment-1806" >Loop is not necessary</a></li>
							<li><a href="/articles/view/using-equalto-validation-to-compare-two-form-fields#comment-1805" >No Loop</a></li>
							<li><a href="/articles/view/using-equalto-validation-to-compare-two-form-fields#comment-1804" >Calling AuthComponent in Model</a></li>
					</ul>
	</div>
</div>			<div class="copyright">
		   	<span class="left">
		   		<a href="http://cakefoundation.org" >&copy 2006 Cake Software Foundation</a>		   	</span>
	 	    <a href="http://www.w3c.org/" target="_new">
	      	<img src="/img/w3c_css.png" alt="valid css" border="0" />	      </a>
	      <a href="http://www.w3c.org/" target="_new">
	      	<img src="/img/w3c_xhtml10.png" alt="valid xhtml" border="0" />	      </a>
	      <a href="http://www.cakephp.org/" target="_new">
	   			<img src="/img/cake.power.png" alt="CakePHP : Rapid Development Framework" border="0" />	   	  </a>
	   	 </div>
    </div>
	</div>
</body>
</html>