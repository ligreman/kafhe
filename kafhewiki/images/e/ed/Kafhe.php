<?php
/**
 * Kafhe skin, derived from monobook template.
 *
 * @todo document
 * @file
 * @ingroup Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

require( dirname(__FILE__) . '/MonoBook.php' );

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @todo document
 * @ingroup Skins
 */
class SkinKafhe extends SkinTemplate {
	var $skinname = 'kafhe', $stylename = 'kafhe',
		$template = 'KafheTemplate', $useHeadElement = true;

	/**
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ){
		global $wgHandheldStyle;
		parent::setupSkinUserCss( $out );
		$out->addModuleStyles ('skins.kafhe');
		$out->addStyle( 'kafhe/bootstrap/css/bootstrap.min.css');
		$out->addStyle( 'kafhe/bootstrap/css/bootstrap-responsive.min.css');
		$out->addStyle( 'kafhe/main.css', 'screen');
	}
	
}

/**
 * @todo document
 * @ingroup Skins
 */
class KafheTemplate extends MonoBookTemplate {

	/**
	 * Template filter callback for Kafhe skin.
	 * Takes an associative array of data set from a SkinTemplate-based
	 * class, and a wrapper for MediaWiki's localization database, and
	 * outputs a formatted page.
	 *
	 * @access private
	 */
	
	function execute() {
		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();

		$this->html( 'headelement' );
?>

	<!-- heading -->
	<header class="row-fluid">
		<h1 id="firstHeading" class="span3">
			<a href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href']) ?>">
        		<img height="72" src="<?php echo htmlspecialchars( $this->data['logopath'] ) ?>" border="0" />
  			</a>
  		</h1>
  		
  		
  		<aside class="span5 offset4">
  			<form action="<?php $this->text('wgScript') ?>" id="searchform">
				<input type='hidden' name="title" value="<?php $this->text('searchtitle') ?>"/>
				<?php echo $this->makeSearchInput(array( "id" => "searchInput", "placeholder" => "Texto a buscar" )); ?>
				
				<?php echo $this->makeSearchButton("image", array( "src" => "/wiki/skins/kafhe/button_search.png", "alt" => "Search" ));?>
			</form>
  		</aside>
  	</header>
  	  	<div class="row-fluid">
		  	<nav class="span2">
		  		<ul id="user_links">
				  	<?php if(isset($this->data['personal_urls']['anonlogin'])){?>
			  		<?php echo $this->makeListItem('anonlogin', $this->data['personal_urls']['anonlogin']);?>
			  	<?php }?>
			  	<?php if(isset($this->data['personal_urls']['logout'])){?>
			  		<?php echo $this->makeListItem('logout', $this->data['personal_urls']['logout']);?>
			  	<?php }?>
		  	</ul>
		  	<ul id="page_links"><?php
				foreach($this->data['content_actions'] as $key => $tab) {
					echo '
				' . $this->makeListItem( $key, $tab );
				} ?>
				<?php if(isset($this->data['personal_urls']['logout'])){?>
				<?php $special = "upload";?>
				<li id="t-<?php echo $special ?>"><a href="<?php echo htmlspecialchars($this->data['nav_urls'][$special]['href'])
                                ?>"><?php $this->msg($special) ?></a></li>
                <?php }?>
			</ul>
	  	</nav>
	  	<section class="span10" id="main_content">
	  		<div class='mw-topboxes'>
				<div id="mw-js-message" style="display:none;"<?php $this->html('userlangattributes')?>></div>
				<?php if($this->data['newtalk'] ) {
					?><div class="usermessage mw-topbox"><?php $this->html('newtalk')  ?></div>
				<?php } ?>
				<?php if($this->data['sitenotice']) {
					?><div class="mw-topbox" id="siteNotice"><?php $this->html('sitenotice') ?></div>
				<?php } ?>
			</div>
			<div id="contentSub"<?php $this->html('userlangattributes') ?>><?php $this->html('subtitle') ?></div>
	
			<?php if($this->data['undelete']) { ?><div id="contentSub2"><?php     $this->html('undelete') ?></div><?php } ?>
			<?php if($this->data['showjumplinks']) { ?><div id="jump-to-nav"><?php $this->msg('jumpto') ?> <a href="#mw_portlets"><?php $this->msg('jumptonavigation') ?></a>, <a href="#searchInput"><?php $this->msg('jumptosearch') ?></a></div><?php } ?>
			<h1><?php $this->html('title') ?></h1>
			<?php $this->html('bodycontent') ?>
			<?php if($this->data['catlinks']) { $this->html('catlinks'); } ?>
			<?php $this->html ('dataAfterContent') ?>
	  	</section>
  	</div>

	<footer>
	</footer>

	<?php $this->printTrail(); ?>
	<script type="text/javascript">
		$(document).ready(function(){
		    resizeContent();
	
	    $(window).resize(function() {
		        resizeContent();
		    });
		});
	
		function resizeContent() {
		    $height = $(window).height() - 77;
		    $('nav').height($height);
		    $('#main_content').height($height);
		}
	</script>
	</body>
</html>
<?php
	wfRestoreWarnings();
	} // end of execute() method
} // end of class


