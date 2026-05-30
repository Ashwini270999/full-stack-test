$(document).ready(function(){
    // Configuration object to avoid repeating settings
    const slickOptions = {
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true
    };

    /* Initialize Content Slider */
    $('.content-slider').slick({
        ...slickOptions,
        dots: true,
        asNavFor: '.image-slider'
    });

    /* Initialize Image Slider */
    $('.image-slider').slick({
        ...slickOptions,
        asNavFor: '.content-slider'
    });

    /* Sync Left Tabs with Slick Slide Shifts */
    $('.tab-item').click(function(){
        const slideIndex = $(this).data('slide');
        $('.content-slider').slick('slickGoTo', parseInt(slideIndex));
    });

    /* Active Tab Class Management on Slide Change */
    $('.content-slider').on('afterChange', function(event, slick, currentSlide){
        $('.tab-item').removeClass('active');
        $('.tab-item').extra = $(`.tab-item[data-slide="${currentSlide}"]`).addClass('active');
    });

    /* Mobile Accordion Execution Logic */
    $('.mobile-tab').click(function(){
        const $this = $(this);
        const parent = $this.closest('.mobile-card');
        const contentPanel = parent.find('.mobile-content');
        
        // Target specific icons
        const currentIconContainer = $this.find('.mobile-icon');
        const plusIcon = currentIconContainer.data('plus-src');
        const minusIcon = currentIconContainer.data('minus-src');

        // If clicked item is already open, close it
        if (contentPanel.is(':visible')) {
            contentPanel.slideUp();
            currentIconContainer.find('img').attr('src', plusIcon);
            return;
        }

        // Close all other open cards completely and reset their icon graphics to a plus sign
        $('.mobile-content').slideUp();
        $('.mobile-icon').each(function() {
            const $iconBox = $(this);
            $iconBox.find('img').attr('src', $iconBox.data('plus-src'));
        });

        // Open current targeted slide smoothly and switch icon to a minus sign
        contentPanel.slideDown();
        currentIconContainer.find('img').attr('src', minusIcon);
    });
});