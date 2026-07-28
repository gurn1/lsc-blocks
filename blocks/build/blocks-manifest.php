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
		'textdomain' => 'lsc-blocks',
		'editorScript' => 'file:./index.js'
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
