/**
 * @file stop14_mega_menua.js
 * @description A work in progress. Designed to create a library and configurable
 * approach to developing a "mega menu", an overlay that contains menus and
 * content blocks.
 */

(function ($, Drupal, once) {

  $.fn.extend({

    initMegaMenu: function (targetSelector) {
      if ($(targetSelector).length > 0) {
        $(targetSelector).hide().addClass('megamenu-overlay').addClass('collapsed');
        $(targetSelector).prepend('<a class="close-container" title="close this screen">x</a>');
      }
      return $(this);
    },
    registerMegaMenuItem: function(param={}) {
      var element = $(this);
      var mmid = _generateMMID(element);
      element.attr('data-mm-target-id',mmid);
      element.addClass('megamenu-trigger');
      element.parent('li').addClass('has-sub-menu');

      var targetBlocks = param.hasOwnProperty('targetBlocks') ? param.targetBlocks : null;
      var delay = param.hasOwnProperty('delay') ? param.delay : 200;
      var mode = param.hasOwnProperty('mode') ? param.mode : 'click';

      if (targetBlocks !== null) {

        var container;

        if (Array.isArray(targetBlocks) === false || targetBlocks.length === 1) {
          container = targetBlocks;
        } else {
          var selector = targetBlocks.join(',');
          container = $(selector).wrapAll("<div data-panel-id='" + mmid + "'></div>");
        }
      }

      let mmContainer = container.parents('.megamenu-overlay').first();
      var menuShowInterval = null;
      var menuHideInterval = null;

      // Tracks if a user is hovering over a “hotspot”, as a way of preventing
      // animations from triggering multiple times as the cursor moves from one element
      // to another.

      var hotspot = false;

      // Prepare close button

      var close = mmContainer.find('.close-container');

      if (close.length > 0 && close.hasClass('processed') === false) {
        close.on('click',function() {
          _hideMenu(true);
        });
        close.addClass('processed');
      }

      if (mode === 'click') {
        $(this).on('click', function (e) {
          e.preventDefault();
          mmContainer.hasClass('collapsed') === true ? _showMenu() : _hideMenu();
        });
      } else if (mode === 'mouseenter') {
        $(this).on('mouseenter', function (e) {
          // e.preventDefault();
          hotspot = true;
          menuShowInterval = setInterval(_showMenu,delay);
        });
        $(this).on('mouseleave', function (e) {
          // e.preventDefault();
          hotspot = false;
          menuHideInterval = setInterval(_hideMenu,delay);
        });

        mmContainer.on('mouseenter', function (e) {
          hotspot = true;
          menuShowInterval = setInterval(_showMenu,delay);
        });
        mmContainer.on('mouseleave', function (e) {
          hotspot = false;
          menuHideInterval = setInterval(_hideMenu,delay);
        });

      }

      function _showMenu() {
          mmContainer.slideDown(delay, function () {
            $(this).removeClass('collapsed').addClass('expanded');
          });
          setTimeout(() => {
          }, delay);

         // if (menuShowInterval !== null) {
            clearInterval(menuShowInterval);
          //}
      }

      function _hideMenu(force=false) {
        if (hotspot === false || force === true) {
          mmContainer.slideUp(delay, function () {
            $(this).removeClass('expanded').addClass('collapsed');
          });
          setTimeout(() => {
          }, delay);
        }

        //if (menuHideInterval !== null) {
          clearInterval(menuHideInterval);
        //}

      }


    },
  });

  function _generateMMID(element) {
    var text = $(element).text();
    return "mm-" + encodeURIComponent(text.replace(' ','-').toLowerCase());
  }

  /*
  Drupal.behaviors.stop14MegaMenu = {
    attach: function (context, settings) {
      once('register', '.mega-menu', context).forEach(function (element) {
        //$(element).registerMenuItem();
      });
    }
  };
  */
})(jQuery, Drupal, once);
