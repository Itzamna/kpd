<?php // no direct access 
	defined( '_JEXEC' ) or die( 'Restricted access' ); 
	$document = & JFactory::getDocument();
	$config = & JFactory::getConfig();
	$fulltitle = $document->title.' - '.$config->getValue('sitename');
	$document->setTitle( $fulltitle );
	
	?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<jdoc:include type="head" />
<link rel="stylesheet" href="/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="/templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Neucha' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Andika' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Kelly+Slab&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Ruslan+Display&subset=latin,cyrillic-ext,cyrillic' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Philosopher&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Lobster&subset=latin,cyrillic-ext,cyrillic' rel='stylesheet' type='text/css'>
</head>

<body>

<div id="wrapper">

	<div id="header">
<h1><jdoc:include type="modules" name="top" style="xhtml" />
<jdoc:include type="modules" name="header" style="xhtml" /></h1>
	</div>

<div id="middle">

		<div id="container">
			<div id="content">
<jdoc:include type="modules" name="hornav" style="xhtml" />
<jdoc:include type="component" />
			</div>
		</div>
					<div class="sidebar" id="sideRight">
			<jdoc:include type="modules" name="right" style="xhtml" />
			<div id="menu">
			<jdoc:include type="modules" name="user1" style="xhtml" />
			<center><a href="http://s04.flagcounter.com/more/FM4S"><img src="http://s04.flagcounter.com/count/FM4S/bg_F2ECBB/txt_3B311D/border_3B311D/columns_2/maxflags_36/viewers_0/labels_0/pageviews_1/flags_0/" alt="free counters" border="1">
			<br><br>
			<!--LiveInternet counter--><script type="text/javascript">document.write("<a href='http://www.liveinternet.ru/click' target=_blank><img src='//counter.yadro.ru/hit?t14.6;r" + escape(document.referrer) + ((typeof(screen)=="undefined")?"":";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?screen.colorDepth:screen.pixelDepth)) + ";u" + escape(document.URL) + ";" + Math.random() + "' border=0 width=88 height=31 alt='' title='LiveInternet: показано число просмотров за 24 часа, посетителей за 24 часа и за сегодня'><\/a>")</script><!--/LiveInternet--></center><br>
			</ul>
			</div>
			
		</div>

	</div>

</div>

<div id="footer">
<jdoc:include type="modules" name="footer" style="xhtml" />
</div>

</body>
</html>