# laravel-solarium-vue-opensolr-search
A quick and dirty sample search engine using Laravel Solarium Vue and Opensolr

This is a tutorial project on how to create your first laravel application with solr, using a few embedded technologies.

I've also included a mixin for Vue inside Laravel 6. Perhaps that'll help someone struggling with getting mixins to work with your Laravel Vue components.

Requirements:
- Laravel 6+ bundled with Vue
- Composer
- npm / nodejs
- **memcached / pecl install memcached**
- php 7.x

This connects to a Solr index (hosted at Opensolr.com), and creates a small Search Engine UI, fully based on Vue and the Solarium library, and a few Laravel API controllers.

Feel free to 
