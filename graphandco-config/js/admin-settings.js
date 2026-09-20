(function ($) {
	'use strict';

	function bindPicker(buttonId, inputId, previewId, clearId) {
		var frame;

		$(buttonId).on('click', function (e) {
			e.preventDefault();
			if (frame) {
				frame.open();
				return;
			}
			frame = wp.media({
				title: graphandcoConfigI18n.chooseImage,
				button: { text: graphandcoConfigI18n.useImage },
				multiple: false,
				library: { type: 'image' },
			});
			frame.on('select', function () {
				var attachment = frame.state().get('selection').first().toJSON();
				$(inputId).val(attachment.id);
				var url = attachment.sizes && attachment.sizes.medium
					? attachment.sizes.medium.url
					: attachment.url;
				$(previewId).html('<img src="' + url + '" alt="" style="max-width:320px;height:auto;" />');
			});
			frame.open();
		});

		$(clearId).on('click', function (e) {
			e.preventDefault();
			$(inputId).val('0');
			$(previewId).empty();
		});
	}

	$(function () {
		if (typeof wp === 'undefined' || !wp.media) {
			return;
		}
		bindPicker('#gc_pick_login_bg', '#gc_login_bg_id', '#gc_login_bg_preview', '#gc_clear_login_bg');
		bindPicker('#gc_pick_login_logo', '#gc_login_logo_id', '#gc_login_logo_preview', '#gc_clear_login_logo');
	});
})(jQuery);
