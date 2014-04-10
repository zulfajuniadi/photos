// This is a manifest file that'll be compiled into application.js, which will include all the files
// listed below.
//
// Any JavaScript/Coffee file within this directory, lib/assets/javascripts, vendor/assets/javascripts,
// can be referenced here using a relative path.
//
// It's not advisable to add code directly here, but if you do, it'll appear in whatever order it
// gets included (e.g. say you have require_tree . then the code will appear after all the directories
// but before any files alphabetically greater than 'application.js'
//
// The available directives right now are require, require_directory, and require_tree
//
//= require jquery
//= require bootstrap/dist/js/bootstrap.min.js
//= require eventEmitter/EventEmitter.min.js
//= require bootbox/bootbox.js
//= require eventie/eventie.js
//= require imagesloaded/imagesloaded
//= require isotope/dist/isotope.pkgd.min.js
//= require dropzone/downloads/dropzone.min.js
//= require fastclick/lib/fastclick.js
//= require_tree .

window.addEventListener('load', function() {
    FastClick.attach(document.body);
}, false);