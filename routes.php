<?php

/**
 * Handle the documentation homepage.
 *
 * This page contains the "introduction" to Laravel.
 */

Route::get('(:bundle)', function()
{
	$bundles = array();
	
	foreach (Bundle::all() as $bundle => $config)
	{
		if ( ! is_null(readme_root($bundle))) 
		{
			$bundles[] = $bundle;
		}
	}

	// We need to get all available bundle
	return View::make('readme::page')
			->with('bundle', 'readme')
			->with('content', View::make('readme::list', array('bundles' => $bundles)));
});

Route::get('(:bundle)/(:any)', function($bundle)
{
	if (readme_document_exists($bundle, 'readme'))
	{
		return View::make('readme::page')
			->with('bundle', $bundle)
			->with('content', readme_document($bundle, 'readme'));
	}
	else
	{
		return Response::error('404');
	}
});

/**
 * Handle documentation routes for sections and pages.
 *
 * @param  string  $section
 * @param  string  $page
 * @return mixed
 */
Route::get('(:bundle)/(:any)/(:any?)', function($bundle, $page)
{

	$page = str_ireplace('.md', '', strtolower($page));
	if (readme_document_exists($bundle, $page))
	{
		return View::make('readme::page')
				->with('bundle', $bundle)
				->with('content', readme_document($bundle, $page));
	}
	else
	if(file_exists(readme_root($bundle).$page))
	{
		//Display changelog or any text file without extension
		return View::make('readme::page')
				->with('bundle', $bundle)
				->with('content', '<pre>'.file_get_contents(readme_root($bundle).$page).'</pre>');
	}
	else
	{
		return Response::error('404');
	}

});