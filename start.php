<?php


/**
 * Load the Markdown library.
 */

if ( ! defined('MARKDOWN_VERSION'))
{
	require_once Bundle::path('readme').'/libraries/markdown.php';
}

/**
 * Get the root path for the documentation Markdown.
 *
 * @return string
 */
function readme_root($bundle)
{

	if(file_exists(Bundle::path($bundle).'readme.md'))
	{
		return Bundle::path($bundle);
	}

	return null;
}

/**
 * Get the parsed Markdown contents of a given page.
 *
 * @param  string  $page
 * @return string
 */
function readme_document($bundle, $page)
{
	return Markdown(file_get_contents(readme_root($bundle).$page.'.md'));
}

/**
 * Determine if a documentation page exists.
 *
 * @param  string  $page
 * @return bool
 */
function readme_document_exists($bundle, $page)
{
	return file_exists(readme_root($bundle).$page.'.md');
}

/**
 * Attach the sidebar to the documentation template.
 */
View::composer('readme::template', function($view)
{
	$sidebar = '

### Bundles
- [Available Documentation](/readme)

### '.Str::title($view->bundle).'

- [Readme.md](/readme/'.$view->bundle.'/readme)
';
	$dir = readme_root($view->bundle);
	$open = scandir($dir);
	foreach ($open as $item) {
		if ($item != '.' && $item != '..') {
			$lower = strtolower($item);
			if ((preg_match('#^.+\.md$#i', $item) ||  preg_match('#^[^\.]+$#', $item)) && $lower != 'readme.md' && !is_dir($dir.$item)) {
				$sidebar .= "- [".Str::title($item)."](/readme/".$view->bundle."/".str_ireplace('.md', '', $lower).")\n";
			} 
		}
	}
	$view->with('sidebar', Markdown($sidebar));
});