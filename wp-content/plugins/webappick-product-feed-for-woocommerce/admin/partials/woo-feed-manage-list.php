<?php
/**
 * Feed List View
 *
 * @link       https://webappick.com/
 * @since      1.0.0
 *
 * @package    Woo_Feed
 * @subpackage Woo_Feed/admin/partial
 * @author     Ohidul Islam <wahid@webappick.com>
 */
$myListTable = new Woo_Feed_Manage_list();
$limit = get_option( "woo_feed_per_batch", 200 );
?>
<div class="wrap">
    <h2><?php _e('Manage Feed', 'woo-feed'); ?>
        <a href="<?php echo admin_url('admin.php?page=webappick-product-feed-for-woocommerce/admin/class-woo-feed-admin.php'); ?>"
           class="page-title-action"><?php _e('New Feed', 'woo-feed'); ?></a>
    </h2>
    <?php echo WPFFWMessage()->infoMessage1(); ?>
	<table class="table widefat fixed" id="feedprogresstable" style="display: none;">
		<thead>
		<tr>
			<th><b>Generating Product Feed</b></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>
				<div class="feed-progress-container">
					<div class="feed-progress-bar" >
						<span class="feed-progress-bar-fill"></span>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div style="float: left;"><b style='color: darkblue;'><i class='dashicons dashicons-sos wpf_sos'></i></b>&nbsp;&nbsp;&nbsp;</div>
				<div class="feed-progress-status"></div>
				<div class="feed-progress-percentage"></div>
			</td>
		</tr>
		</tbody>
	</table>
    <br><br>
    <?php
    $link = filter_input( INPUT_GET, 'link', FILTER_VALIDATE_URL );
    /**
     * @TODO use gettext functions
     * @TODO use session/cookies/localstorage or transient api for this message
     * @see settings_errors()
     */
    if (isset($link) && !empty($link)) {
        $message="<b style='color: #008779;'>Feed Generated Successfully. Feed URL: <a href=".esc_url($link)." target='_blank'>".esc_url($link)."</a></b>";
        if (isset($_GET['cat']) && $_GET['cat']=='no') {
            $message.="<br/><br/><b style='color: #f49242;'>Warning:</b><ul>Google Product category is not selected. Your AdWords CPC rate will be high. Add proper Google Product Category to each product & reduce CPC rate. <a target='_blank' href='https://webappick.helpscoutdocs.com/article/19-how-to-map-store-category-with-merchant-category'>Learn more...</a> </li></ul>";
        }
        echo "<div class='updated'><p>" . __($message, 'woo-feed') . "</p></div>";
    } elseif (isset($_GET['wpf_message']) && $_GET['wpf_message'] === 'error') {
        $dir=get_option("WPF_DIRECTORY_PERMISSION_CHECK");
        if($dir && !empty($dir)){
            echo "<div class='error'><p>" . __(get_option('wpf_message').$dir, 'woo-feed') . "</p></div>";
        }else{
            echo "<div class='error'><p>" . __(get_option('wpf_message'), 'woo-feed') . "</p></div>";
        }
    }
    $myListTable->prepare_items();
    ?>
    <table class=" widefat fixed">
        <thead>
        <tr>
            <th><b><?php _e('Auto Update Feed Interval'); ?></b></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <form action="" method="post">
	                <label for="wf_schedule"><b><?php _e('Interval', 'woo-feed'); ?></b></label>
	                <select name="wf_schedule" id="wf_schedule"><?php
		                $interval = get_option('wf_schedule');
		                foreach( woo_feed_get_schedule_interval_options() as $k=>$v ) {
			                printf( '<option value="%s"%s>%s</option>', $k, selected( $interval, $k, false ), $v );
		                }
	                ?></select>
                    <button type="submit" class="button button-primary"><?php _e('Update Interval'); ?></button>
                </form>
            </td>
        </tr>
        </tbody>
    </table>
    <form id="contact-filter" method="post">
        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php //$myListTable->search_box('search', 'search_id'); ?>
        <!-- Now we can render the completed list table -->
        <?php $myListTable->display() ?>
    </form>
</div>

<script type="text/javascript">
    (function( $, window, document, opts ) {
        'use strict';
        /**
         * All of the code for your admin-facing JavaScript source
         * should reside in this file.
         *
         * Note: It has been assumed you will write jQuery code here, so the
         * $ function reference has been prepared for usage within the scope
         * of this function.
         *
         * This enables you to define handlers, for when the DOM is ready:
         *
         * $(function() {
         * });
         *
         */
        /**
         * On Window Load
         * @TODO move this to js file so we can minify this.
         */
        $( window ).load(function() {
            var feedProgress = {
                    table: $( '#feedprogresstable' ),
                    status: $( '.feed-progress-status' ),
                    percentage: $( '.feed-progress-percentage' ),
                    bar: $( '.feed-progress-bar-fill' ),
                    barProgress: 10, // Variable responsible to hold progress bar width
                },
                regenerateBtn = $( '.wpf_regenerate' ),
                fileName = "<?php echo isset( $fileName )? $fileName : ''; ?>", // wf_config+xxxx
                limit = <?php echo ( $limit ) ? $limit : 200; ?>;

            // feed delete alert
            $( '.single-feed-delete' ).click( function ( event ) {
                event.preventDefault();
                if ( confirm( '<?php _e('Are You Sure to Delete?','woo-feed');?>' ) ) {
                    var url = jQuery(this).attr('val');
                    window.location.href = url;
                }
            });

            // bulk delete alert
            $('#doaction, #doaction2').click(function () {
                return confirm('<?php _e('Are You Sure to Delete?','woo-feed'); ?>');
            });
            // generate feed
            if( fileName !== "" ) {
                feedProgress.table.show();
                generate_feed();
            }
            //==================Manage Feed==============================
            // Feed Regenerate
            regenerateBtn.on( "click", function () {
                var el = $( this );
                regenerateBtn.disabled();
                fileName = el.attr( 'id' ).replace( "wf_feed_", "wf_config" );
                el.val( 'Generating...' );
                if( fileName ) {
                    feedProgress.table.show();
                    generate_feed();
                }
            });

            /*#######################################################
             #######-------------------------------------------#######
             #######    Ajax Feed Making Functions Start       #######
             #######-------------------------------------------#######
             #########################################################
             */

            function showFeedProgress( color ){
                feedProgress.bar.css( {
                    width: feedProgress.barProgress + '%',
                    background: color || "#3DC264",
                } );
                feedProgress.percentage.text( Math.round( feedProgress.barProgress ) + '%' );
            }

            function generate_feed() {
                console.log( "Counting Total Products" );
                feedProgress.status.text( "Calculating total products." );
                $.ajax({
                    url : opts.wpf_ajax_url,
                    type : 'post',
                    data : {
                        _ajax_nonce: opts.nonce,
                        action: "get_product_information",
                        feed: fileName
                    },
                    success : function(response) {
                        console.log( response );
                        if(response.success) {
                            feedProgress.status.text( "Delivering Feed Configuration." );
                            processFeed( parseInt( response.data.product ) );
                            //feedProgress.status.text("Total "+products+" products found.");
                            feedProgress.status.text( "Processing Products..." );
                        }else{
                            feedProgress.status.text(response.data.message);
                            showFeedProgress( 'red' );
                        }
                    }
                });
            }

            function processFeed( n, offset, batch ) {
                if ( typeof( offset ) === 'undefined' ) offset = 0;
                if ( typeof( batch ) === 'undefined' ) batch = 0;
                var batches = Math.ceil( n/limit ),
                    progressBatch = 90 / batches;
                console.log( ( limit*batch ) + " out of " + n + " products processed." );
                feedProgress.status.text( "Processing products..." + Math.round( feedProgress.barProgress ) + "%" );
                if( batch < batches ) {
                    console.log( "Processing Batch " + batch + " of " + batches );
                    $.ajax({
                        url : opts.wpf_ajax_url,
                        type : 'post',
                        data : {
                            _ajax_nonce: opts.nonce, action: "make_batch_feed",
                            limit: limit, offset: offset, feed: fileName
                        },
                        success : function(response) {
                            console.log( response );
                            if( response.success ) {
                                if( response.data.products === "yes" ) {
                                    offset = offset+limit;
                                    batch++;
                                    setTimeout( function(){
                                        processFeed( n, offset, batch );
                                    }, 2000 );
                                    feedProgress.barProgress = feedProgress.barProgress + progressBatch;
                                    showFeedProgress();
                                } else if( n > offset ) {
                                    offset = offset+limit;
                                    batch++;
                                    processFeed( n, offset, batch );
                                    feedProgress.barProgress = feedProgress.barProgress + progressBatch;
                                    showFeedProgress();
                                }else{
                                    feedProgress.status.text( "Saving feed file." );
                                    save_feed_file();
                                }
                            }
                        },
                        error:function (response) {
                            if( response.status !== "200" ) {
                                offset = (offset-limit)+10;
                                batch++;
                                processFeed( n, offset, batch );
                                feedProgress.barProgress = feedProgress.barProgress + progressBatch;
                                showFeedProgress();
                            }
                            console.log(response);
                        }
                    });
                }else{
                    feedProgress.status.text("Saving feed file.");
                    save_feed_file();
                }
            }

            /**
             * Save feed file into WordPress upload directory
             * after successfully processing the feed
             */
            function save_feed_file(){
                // Polylang codes
                // var params = window.location.search.slice(1);
                // var searchParam = new URLSearchParams(params);
                // var old_lang = searchParam.get('old_lang');
                $.ajax({
                    url : opts.wpf_ajax_url,
                    type : 'post',
                    data : {
                        _ajax_nonce: opts.nonce,
                        action: "save_feed_file",
                        feed: fileName
                    },
                    success : function( response ) {
                        console.log( response );
                        if( response.success ) {
                            feedProgress.barProgress = 100;
                            showFeedProgress();
                            feedProgress.status.text( response.data.message );
                            regenerateBtn.val( 'Regenerate' );
                            regenerateBtn.disabled( false );
                            // var default_polylang = (response.data.default_polylang) ? response.data.default_polylang : '';
                            window.location.href = "<?php echo admin_url( 'admin.php?WPFP_WPML_CURLANG=yes&page=webappick-manage-feeds&link=' ); ?>" + response.data.url + "&cat=" + response.data.cat;
                            // Polylang code
                            // window.location.href = "<?php // echo admin_url('admin.php?WPFP_WPML_CURLANG=yes&page=webappick-manage-feeds&link='); ?>"+url+"&cat="+cat + "&lang=" + old_lang;
                        }else{
                            showFeedProgress( "red" );
                            feedProgress.status.text( response.data.message );
                        }
                    },
                    error:function ( response ) {
                        console.log( response );
                        feedProgress.status.text( "Failed to save feed file." );
                    }
                });
            }

            /*########################################################
             #######-------------------------------------------#######
             #######    Ajax Feed Making Functions End         #######
             #######-------------------------------------------#######
             #########################################################
             */
        });
        /**
         * ...and/or other possibilities.
         *
         * Ideally, it is not considered best practise to attach more than a
         * single DOM-ready or window-load handler for a particular page.
         * Although scripts in the WordPress core, Plugins and Themes may be
         * practising this, we should strive to set a better example in our own work.
         */
    })( jQuery, window, document, wpf_ajax_obj );
</script>