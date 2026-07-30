<?php
// This file is generated. Do not modify it manually.
return array(
	'faq-accordion' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'lsc-blocks/faq-accordion',
		'version' => '0.1.0',
		'title' => 'FAQ Accordion',
		'category' => 'widgets',
		'icon' => 'editor-help',
		'description' => 'A list of expandable questions and answers, with schema.org markup for SEO.',
		'attributes' => array(
			'allowMultipleOpen' => array(
				'type' => 'boolean',
				'default' => false
			),
			'categories' => array(
				'type' => 'array',
				'default' => array(
					
				)
			),
			'textAlign' => array(
				'type' => 'string',
				'default' => 'left'
			),
			'itemSpacing' => array(
				'type' => 'number',
				'default' => 16
			),
			'headingLevel' => array(
				'type' => 'number',
				'default' => 3
			),
			'showCategoryHeading' => array(
				'type' => 'boolean',
				'default' => false
			),
			'allCategoriesLabel' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'providesContext' => array(
			'lsc-blocks/faqCategories' => 'categories',
			'lsc-blocks/faqHeadingLevel' => 'headingLevel'
		),
		'supports' => array(
			'html' => false,
			'interactivity' => true,
			'typography' => array(
				'fontSize' => true,
				'fontFamily' => true,
				'lineHeight' => true
			),
			'color' => array(
				'text' => true,
				'background' => true
			)
		),
		'textdomain' => 'lsc-blocks',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'viewScriptModule' => 'file:./view.js'
	),
	'faq-item' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'lsc-blocks/faq-item',
		'version' => '0.1.0',
		'title' => 'FAQ Item',
		'category' => 'widgets',
		'parent' => array(
			'lsc-blocks/faq-accordion'
		),
		'attributes' => array(
			'question' => array(
				'type' => 'string',
				'default' => ''
			),
			'answer' => array(
				'type' => 'string',
				'default' => ''
			),
			'category' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'usesContext' => array(
			'lsc-blocks/faqCategories',
			'lsc-blocks/faqTextAlign',
			'lsc-blocks/faqHeadingLevel'
		),
		'textdomain' => 'lsc-blocks',
		'editorScript' => 'file:./index.js'
	),
	'post-feedback' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'lsc-blocks/post-feedback',
		'version' => '0.1.0',
		'title' => 'Was This Helpful?',
		'category' => 'widgets',
		'icon' => 'thumbs-up',
		'description' => 'A thumbs up/down feedback widget for the current post.',
		'usesContext' => array(
			'postId'
		),
		'attributes' => array(
			'question' => array(
				'type' => 'string',
				'default' => 'Was this article helpful?'
			),
			'thankYouMessage' => array(
				'type' => 'string',
				'default' => 'Thanks for your feedback!'
			)
		),
		'supports' => array(
			'html' => false,
			'interactivity' => true
		),
		'textdomain' => 'lsc-blocks',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'viewScriptModule' => 'file:./view.js'
	),
	'projects-grid' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'lsc-blocks/projects-grid',
		'version' => '0.1.0',
		'title' => 'Projects Grid',
		'category' => 'widgets',
		'icon' => 'grid-view',
		'description' => 'Add a grid of projects in a 3 column layout. The items are pulled from the Projects post type.',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false,
			'interactivity' => true
		),
		'textdomain' => 'lsc-blocks',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'viewScriptModule' => 'file:./view.js'
	)
);
