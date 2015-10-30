jQuery(document).ready(function () {
    jQuery('.switch-data').each(function () {
        var btn = jQuery(this).closest('.control-group').find('.btn');
        var btn_active = jQuery(this).closest('.control-group').find('.active');
        var data_value = btn_active.attr('data-value');
        jQuery(this).val(data_value);
        btn.addClass('btn-default');
        btn.removeClass('btn-success');

        btn_active.removeClass('btn-default');
        btn_active.addClass('btn-success');
        jQuery(btn.attr('data-target')).collapse('hide');
        btn.each(function () {
            if (!jQuery(this).hasClass('btn-success')) {
                jQuery(jQuery(this).attr('data-target')).collapse('hide');
            }
        });
        jQuery(btn_active.attr('data-target')).collapse('show');
    });

    jQuery(".switch button").click(function () {
        var current_button = this;
        var closest_button = jQuery(this).closest('.control-group').find('.btn');
        closest_button.removeClass('btn-success');
        closest_button.removeClass('active');
        closest_button.addClass('btn-default');

        jQuery(this).removeClass('btn-default');
        jQuery(this).addClass('btn-success');
        jQuery(this).closest('.control-group').children('.switch-data').val(jQuery(this).attr('data-value'));
        closest_button.each(function () {
            if (jQuery(this).hasClass('btn-success')) {
                jQuery(jQuery(this).attr('data-target')).collapse('show');
            } else {
                jQuery(jQuery(this).attr('data-target')).collapse('hide');
            }
        });
    });

    jQuery('.confirm').on('click', function () {
        console.log('delete triggered');
        return confirm('Are you sure you want to delete this?');
    });

    jQuery('.cbAll').on('click', function () {
        var selected = this.checked;
        console.log('clicked');
        jQuery('input[name^=cbAction]').each(function () {
            this.checked = selected;
        });
    });
    jQuery('.btnAction').on('click', function () {
        var action = jQuery('.slcAction').val();
        var data = new Array();
        if (action == 'Bulk Actions') {
            alert('Please choose your action');
        } else if (jQuery('input[name^=cbAction]:checked').length == 0) {
            alert('Please select at least one record');
        }
        else {
            if (confirm('Are you sure you want to delete this?')) {
                jQuery("input[name^=cbAction]:checked").each(function () {
                    data.push(this.value);
                })

                var request = jQuery.ajax({
                    url: ajaxurl,
                    type: "POST",
                    data: {action: jQuery(this).data('action'), ids: data.join(','), ci_csrf_token: ''},
                    dataType: "json"

                });
                request.done(function (msg) {
                    location.reload();
                });
                request.fail(function (jqXHR, textStatus) {
                    alert("Request failed: " + textStatus);
                });
            }
        }
    });

});

