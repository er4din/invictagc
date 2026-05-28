(function($) {

    wp.customize.bind('ready', function() {

        var fontControls = [
            {
                family: 'janey_press_logo_font_family',
                weight: 'janey_press_logo_font_weight'
            },
            {
                family: 'janey_press_site_identity_font_family',
                weight: 'janey_press_site_identity_font_weight'
            },
            {
                family: 'janey_press_menu_font_family',
                weight: 'janey_press_menu_font_weight'
            },
        ];

        var savedWeights = {};

        $.each(fontControls, function(index, controls) {

            var fontFamilyControl = '#customize-control-' + controls.family + ' select';
            var fontWeightControl = '#customize-control-' + controls.weight + ' select';

            if (!savedWeights[controls.family]) {
                savedWeights[controls.family] = {};
            }

            $(fontFamilyControl).on('change', function() {

                var selectedFont = $(this).val();
                var $weightSelect = $(fontWeightControl);

                var fontParts = selectedFont.split(':');
                var fontName = fontParts[0];
                var variants = fontParts[1] ? fontParts[1].split(',') : [];

                $weightSelect.find('option').prop('disabled', true);

                if (variants.length) {
                    $.each(variants, function(index, variant) {
                        $weightSelect.find('option[value="' + variant + '"]').prop('disabled', false);
                    });
                }

                var previousWeight = savedWeights[controls.family][fontName] || $weightSelect.val();

                if (previousWeight && variants.includes(previousWeight)) {
                    $weightSelect.val(previousWeight).trigger('change');
                } else {
                    var firstEnabledOption = $weightSelect.find('option:not([disabled])').first();
                    if (firstEnabledOption.length) {
                        $weightSelect.val(firstEnabledOption.val()).trigger('change');
                    }
                }

            });

            $(fontWeightControl).on('change', function() {

                var selectedFont = $(fontFamilyControl).val();
                var fontParts = selectedFont.split(':');
                var fontName = fontParts[0];
                var selectedWeight = $(this).val();

                savedWeights[controls.family][fontName] = selectedWeight;

            });

            $(fontFamilyControl).trigger('change');

        });

    });

})(jQuery);