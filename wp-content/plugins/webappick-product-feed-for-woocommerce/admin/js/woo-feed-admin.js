// noinspection JSUnresolvedVariable,ES6ConvertVarToLetConst,SpellCheckingInspection
( function ($, window, document, opts) {
    "use strict";
    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     */

    /**
     * disable element utility
     * @since 3.1.32
     */
    $.fn.disabled = function( status ) {
        $(this).each( function(){
            $(this).prop( 'disabled', status === void 0 || status === true  );
        } );
        return $(this); // method chaining
    };

    function clearTooltip( event ) {
        // noinspection SpellCheckingInspection
        $(event.currentTarget).removeClass( function (index, className) {
            return (className.match (/\btooltipped-\S+/g) || []).join(' ');
        } ).removeClass('tooltipped').removeAttr('aria-label');
    }

    function showTooltip( elem, msg ) {
        // noinspection SpellCheckingInspection
        $( elem ).addClass('tooltipped tooltipped-s').attr( 'aria-label', msg );
    }

    function fallbackMessage(action) {
        // noinspection ES6ConvertVarToLetConst
        var actionMsg = '', actionKey = (action === 'cut' ? 'X' : 'C');
        if (/iPhone|iPad/i.test(navigator.userAgent)) {
            actionMsg = 'No support :(';
        } else if (/Mac/i.test(navigator.userAgent)) {
            actionMsg = 'Press ⌘-' + actionKey + ' to ' + action;
        } else {
            actionMsg = 'Press Ctrl-' + actionKey + ' to ' + action;
        }
        return actionMsg;
    }

    $(window).load(function () {
        // noinspection ES6ConvertVarToLetConst
        var $copyBtn = $('.toClipboard'),
            $adminPage = $('.wapk-admin');
        $adminPage.find('.postbox .handlediv').on( 'click', function( event ) {
            event.preventDefault();
            $(this).closest('.postbox').toggleClass('closed');
        } );

        if( ! ClipboardJS.isSupported() || /iPhone|iPad/i.test(navigator.userAgent) ) {
            $copyBtn.find('img').hide(0);
        } else {
            $copyBtn.each( function(){
                $(this).on( 'mouseleave', clearTooltip );
                $(this).on( 'blur', clearTooltip );
            } );
            // noinspection ES6ConvertVarToLetConst
            var clipboard = new ClipboardJS('.toClipboard');
            clipboard.on( 'error', function( event ) {
                showTooltip( event.trigger, fallbackMessage( event.action ) );
            } ).on( 'success', function( event ) {
                showTooltip( event.trigger, 'Copied!' );
            } );
        }
        // noinspection ES6ConvertVarToLetConst
        var sliders = $('.wapk-slider');
        if( sliders.length ) {
            sliders.slick({
                autoplay: true,
                dots: true,
                centerMode: true,
                arrows: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                lazyLoad: 'progressive'
            });
        }
    });

    $(function () {
        // noinspection ES6ConvertVarToLetConst
        var pageURL = $(location). attr("href");
        if( $(location). attr("href").match( /webappick.*feed/g ) != null ) {
            // noinspection SpellCheckingInspection
            $('#wpbody-content').addClass('woofeed-body-content');
        }

        // Category Mapping (Auto Field Populate)
        // noinspection ES6ConvertVarToLetConst,SpellCheckingInspection
        $(".treegrid-parent").on('change keyup', function () {
            // noinspection ES6ConvertVarToLetConst,SpellCheckingInspection
            var val = $(this).val(), parent = $(this).attr('classval');
            // noinspection SpellCheckingInspection
            $(".treegrid-parent-" + parent).val(val);
        });
        // Generate Feed Add Table Row
        $(document).on('click', '#wf_newRow', function () {
            $("#table-1 tbody tr:first").clone().find('input').val('').end().find("select:not('.wfnoempty')").val('').end().insertAfter("#table-1 tbody tr:last");
            $('.outputType').each(function (index) {
                //do stuff to each individually.
                $(this).attr('name', "output_type[" + index + "][]"); //sets the val to the index of the element, which, you know, is useless
            });
        });
        // XML Feed Wrapper
        $(document).on('change', '#feedType', function () {
            // noinspection ES6ConvertVarToLetConst,SpellCheckingInspection
            var type = $(this).val(), provider = $("#provider").val(),
                itemWrapper = $(".itemWrapper"),
                wf_csvtxt = $(".wf_csvtxt");
            if ( type === 'xml' ) {
                itemWrapper.show(); wf_csvtxt.hide();
            } else if ( type === 'csv' || type === 'txt' ) {
                itemWrapper.hide(); wf_csvtxt.show();
            } else if ( type === '' ) {
                itemWrapper.hide(); wf_csvtxt.hide();
            }
            if( type !== "" && helper.in_array( provider, ['google', 'facebook'] ) ) {
                itemWrapper.hide();
            }
            // noinspection SpellCheckingInspection
            if( provider === 'criteo' ) {
                itemWrapper.find('input[name="itemsWrapper"]').val("channel");
                itemWrapper.find('input[name="itemWrapper"]').val("item");
            }
        });
        // Tooltip only Text
        // noinspection SpellCheckingInspection
        $('.wfmasterTooltip').hover(function () {
            // Hover over code
            // noinspection ES6ConvertVarToLetConst,SpellCheckingInspection
            var title = $(this).attr('wftitle');
            // noinspection SpellCheckingInspection
            $(this).data('tipText', title).removeAttr('wftitle');
            // noinspection SpellCheckingInspection
            $('<p class="wftooltip"></p>').text(title).appendTo('body').fadeIn('slow');
        }, function () {
            // Hover out code
            // noinspection SpellCheckingInspection
            $(this).attr('wftitle', $(this).data('tipText'));
            // noinspection SpellCheckingInspection
            $('.wftooltip').remove();
        }).mousemove(function (e) {
            // noinspection SpellCheckingInspection
            $('.wftooltip').css({top: (e.pageY + 10), left: (e.pageX + 20) })
        });
        // Dynamic Attribute Add New Condition
        $(document).on('click', '#wf_newCon', function () {
            $("#table-1 tbody tr:first").show().clone().find('input').val('').end().insertAfter("#table-1 tbody tr:last");
            // noinspection SpellCheckingInspection
            $(".fsrow:gt(5)").prop('disabled', false);
            $(".daRow:eq(0)").hide();
        });
        // Add New Condition for Filter
        $(document).on('click', '#wf_newFilter', function () {
            $("#table-filter tbody tr:eq(0)").show().clone().find('input').val('').end().find('select').val('').end().insertAfter("#table-filter tbody tr:last");
            // noinspection SpellCheckingInspection
            $(".fsrow:gt(2)").prop('disabled', false);
            $(".daRow:eq(0)").hide();
        });
        // Attribute type selection
        $(document).on('change', '.attr_type', function () {
            // noinspection ES6ConvertVarToLetConst
            var type = $(this).val(), row = $(this).closest('tr');
            row.find('.wf_attr').prop('required',false);
            row.find('.wf_default').prop('required',false);
            if (type === 'pattern') {
                row.find('.wf_attr').hide();
                row.find('.wf_attr').val('');
                row.find('.wf_default').show();
                //$(this).closest('tr').find('.wf_default').prop('required',true);
            } else {
                //$(this).closest('tr').find('.wf_attr').prop('required',true);
                row.find('.wf_attr').show();
                row.find('.wf_default').hide();
                row.find('.wf_default').val('');
            }
        });
        // Attribute type selection for dynamic attribute
        $(document).on('change', '.dType', function () {
            // noinspection ES6ConvertVarToLetConst
            var type = $(this).val(), row = $(this).closest('tr');
            if (type === 'pattern') {
                row.find('.value_attribute').hide();
                row.find('.value_pattern').show();
            } else if (type === 'attribute') {
                row.find('.value_attribute').show();
                row.find('.value_pattern').hide();
            } else if (type === 'remove') {
                row.find('.value_attribute').hide();
                row.find('.value_pattern').hide();
            }
        });
        // Generate Feed Table Row Delete
        $(document).on('click', '.delRow', function () {
            $(this).closest('tr').remove();
        });
        //Expand output type
        $(document).on('click', '.expandType', function () {
            // noinspection ES6ConvertVarToLetConst
            var row = $(this).closest('tr');
            $('.outputType').each(function (index) {
                //do stuff to each individually.
                $(this).attr('name', "output_type[" + index + "][]");
            });
            row.find('.outputType').attr('multiple', 'multiple');
            row.find('.contractType').show();
            $(this).hide();
        });
        //Contract output type
        $(document).on('click', '.contractType', function () {
            // noinspection ES6ConvertVarToLetConst
            var row = $(this).closest('tr');
            $('.outputType').each(function (index) {
                //do stuff to each individually.
                $(this).attr('name', "output_type[" + index + "][]");
            });
            row.find('.outputType').removeAttr('multiple');
            row.find('.expandType').show();
            $(this).hide();
        });
        // Generate Feed Form Submit
        $(".generateFeed").validate();
        $(document).on('submit', '#generateFeed', function () {
            $(".makeFeedResponse").html("<b style='color: darkblue;'><i class='dashicons dashicons-sos wpf_sos'></i> Processing...</b>");
            //event.preventDefault();
            // Feed Generating form validation
            $(this).validate();
            if ($(this).valid()) {}
        });
        // Update Feed Form Submit
        // noinspection SpellCheckingInspection
        $(".updatefeed").validate();
        $(document).on('submit', '#updatefeed', function () {
            $(".makeFeedResponse").html("<b style='color: darkblue;'><i class='dashicons dashicons-sos wpf_sos'></i> Processing...</b>");
            //event.preventDefault();
            // Feed Generating form validation
            $(this).validate();
            if ($(this).valid()) {}
        });
        // helper functions
        // noinspection ES6ConvertVarToLetConst,SpellCheckingInspection
        var helper = {
            in_array: function( needle, haystack ) {
                try {
                    return haystack.indexOf( needle ) !== -1;
                } catch( e ) {
                    return false;
                }
            },
            selectize_render_item: function( data, escape ) {
                return '<div class="item webappick_selector">' + escape(data.text) + '</div>';
            },
        },
        feedEditor = { // Feed Editor Table
            init: function(){
                // noinspection ES6ConvertVarToLetConst
                var $glCat = $('#googleTaxonomyId');
                // noinspection SpellCheckingInspection,JSUnresolvedFunction Initialize Table Sorting
                $('.sorted_table').sortablesd({
                    containerSelector: 'table',
                    itemPath: '> tbody',
                    itemSelector: 'tr',
                    handle: 'i.wf_sortedtable',
                    placeholder: '<tr class="placeholder"><td colspan="100"></td></tr>',
                });
                if( $glCat.length ) {
                    $glCat.selectize({
                        render: {
                            item: helper.selectize_render_item,
                        }
                    });
                }
            },
        };
        feedEditor.init();
        // Get Merchant View
        $("#provider").on('change', function ( event ) {
            event.preventDefault();
            // noinspection ES6ConvertVarToLetConst,SpellCheckingInspection
            var merchant = $(this).val(), feedType = $("#feedType"), feedForm = $("#providerPage"),
                merchants = ["pinterest", "fruugo", "fruugo.au", "vergelijk_comparer", "spartoo.fi", "avantlink"];
            if( helper.in_array( merchant, merchants ) ) {
                feedType.val("csv");
                feedType.find('option').not('[value="csv"]').disabled( true );
                feedType.find('option').not('[value="csv"]').disabled( true );
            } else {
                feedType.val("");
                feedType.find('option').disabled( false );
            }
            feedType.trigger('change');
            feedForm.html("<h3>Loading...</h3>");
            // Get FeedForm For Selected Provider/Merchant
            // noinspection JSUnresolvedVariable
            $.post( opts.wpf_ajax_url, { _ajax_nonce: opts.nonce, action: "get_feed_merchant", merchant: merchant }, function (data) {
                feedForm.html(data); // insert server response
                feedEditor.init();
            });
        });
        // Feed Active and Inactive status change via ajax
        $('.woo_feed_status_input').on('change',function(){
            // noinspection ES6ConvertVarToLetConst
            var  $feedName = $(this).val(), counter = ( $(this)[0].checked ) ? 1 : 0;
            // noinspection JSUnresolvedVariable
            $.post( opts.wpf_ajax_url, { _ajax_nonce: opts.nonce, action: "update_feed_status", feedName: $feedName, status: counter }, function (data) {} );
        });
        // Adding for Copy-to-Clipboard functionality in the settings page
        $("#woo_feed_settings_error_copy_clipboard_button").on('click', function() {
            $('#woo_feed_settings_error_report').select();
            document.execCommand('copy');
            if (window.getSelection) {window.getSelection().removeAllRanges();}
            else if (document.selection) {document.selection.empty();}
        });
    });

    //Checking whether php ssh2 extension is added or not
    // noinspection SpellCheckingInspection
    $(document).on('change', '.ftporsftp', function () {
        // noinspection ES6ConvertVarToLetConst
        var server = $(this).val(),
            ssh2Status = $('.ssh2_status');
        if (server === 'sftp') {
            ssh2Status.show();
            ssh2Status.css('color','dodgerblue');
            ssh2Status.text('Wait! Checking Extensions ...');
            // noinspection JSUnresolvedVariable
            $.ajax({
                url: opts.wpf_ajax_url,
                type: 'post',
                data: {
                    _ajax_nonce: opts.nonce,
                    action: "get_ssh2_status",
                    server: server
                },
                success: function (response) {
                    if (response.success) {
                        if(response.data === 'exists') {
                            ssh2Status.hide();
                        } else {
                            ssh2Status.show();
                            ssh2Status.css('color','red');
                            ssh2Status.text('Warning! Enable PHP ssh2 extension to use SFTP. Contact your server administrator.');
                        }
                    }
                }
            });
        }else{
            ssh2Status.hide();
        }
    });
    $(document).on('click', '.woo-feed-review-notice ul li a', function (e) {
        e.preventDefault();
        // noinspection ES6ConvertVarToLetConst
        var notice = $(this).attr('val');
        if(notice === "given") {
            window.open('https://wordpress.org/support/plugin/webappick-product-feed-for-woocommerce/reviews/?rate=5#new-post','_blank');
        }
        $( ".woo-feed-review-notice" ).slideUp( 200, "linear");
        // noinspection JSUnresolvedVariable
        $.ajax({
            url: opts.wpf_ajax_url,
            type: 'post',
            data: { _ajax_nonce: opts.nonce, action: "woo_feed_save_review_notice", notice: notice },
            success: function (response) {}
        });
    });
    $(document).on('click', '.woo-feed-wpml-notice .notice-dismiss', function (e) {
        e.preventDefault();
        // noinspection JSUnresolvedVariable
        $.ajax({
            url: opts.wpf_ajax_url,
            type: 'post',
            data: { _ajax_nonce: opts.nonce, action: "woo_feed_save_wpml_notice", },
            success: function (response) {
                if (response.success) {
                    $( ".woo-feed-wpml-notice" ).slideUp( 200, "linear");
                }
            }
        });
    });
}( jQuery, window, document, wpf_ajax_obj ) );