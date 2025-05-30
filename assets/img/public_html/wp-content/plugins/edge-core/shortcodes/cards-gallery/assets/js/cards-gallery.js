/**
 * Cards Gallery shortcode
 */
(function($) {
    'use strict';

    $(window).load(function(){
        edgeCardsGallery();
    });



/**
 * Cards Gallery shortcode
 */
function edgeCardsGallery() {
    if ($('.edge-cards-gallery-holder').length) {
        $('.edge-cards-gallery-holder').each(function () {
            var gallery = $(this);
            var cards = gallery.find('.card');
            cards.each(function () {
                var card = $(this);
                card.click(function () {
                    if (!cards.last().is(card)) {
                        card.addClass('out animating').siblings().addClass('animating-siblings');
                        card.detach();
                        card.insertAfter(cards.last());
                        setTimeout(function () {
                            card.removeClass('out');
                        }, 200);
                        setTimeout(function () {
                            card.removeClass('animating').siblings().removeClass('animating-siblings');
                        }, 1200);
                        cards = gallery.find('.card');
                        return false;
                    }
                });
            });

            if (gallery.hasClass('edge-bundle-animation') && !edge.htmlEl.hasClass('touch')) {
                gallery.appear(function () {
                    gallery.addClass('edge-appeared');
                    gallery.find('img').one('animationend webkitAnimationEnd MSAnimationEnd oAnimationEnd', function () {
                        $(this).addClass('edge-animation-done');
                    });
                }, {accX: 0, accY: edgeGlobalVars.vars.edgeElementAppearAmount});
            }
        });
    }
}

})(jQuery);