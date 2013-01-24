<?php

error_reporting(1);

require 'app/includes/Factory.php';
require 'app/global.php';
require 'app/home.php';
require 'app/shows.php';
require 'app/episodes.php';
require 'app/admin.php';

require 'slim/Slim.php';
require 'views/MyView.php';

Slim::init('MyView');


/*** CALLBACKS ***/

Slim::before('init_before');
Slim::after('init_after');


/*** USER PAGES ***/

Slim::get('/', 'home_show');

Slim::get('/watch/:show', 'shows_show');

Slim::get('/watch/:show/Season/:season/Episode/:episode', 'episodes_show');
Slim::get('/watch/:show/Season/:season/Episode/:episode/:seo', 'episodes_show');


/** ADMIN PAGES **/

Slim::get('/admin/show', 'admin_showShows');
Slim::get('/admin/show/:id', 'admin_showEpisodes');


/** AJAX GET ROUTES **/

Slim::get('/get/season/:show/:season', 'shows_season');
Slim::get('/get/source/:id/:lang', 'episodes_source');


/** AJAX POST ROUTES **/

Slim::post('/get', 'admin_fetch');
Slim::post('/add/show', 'admin_addShow');
Slim::post('/add/show/:id', 'admin_addEpisodes');
Slim::post('/add/sources', 'admin_saveSources');

Slim::run();