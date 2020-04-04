<?php

/**
 * Common Feed Editing Template
 *
 * @link       https://webappick.com/
 * @since      1.0.0
 *
 * @package    Woo_Feed
 * @subpackage Woo_Feed/admin/partial
 * @author     Ohidul Islam <wahid@webappick.com>
 */
/** @noinspection PhpUndefinedVariableInspection */
$dropDown = new Woo_Feed_Dropdown();
$product = new Woo_Feed_Products();
$product->load_attributes();
$current_category="";
# Condition is for those merchants which support another merchant feed requirements.
if($feedRules['provider']=='adroll'){
    $feedRules['provider']='google';
}

if(!isset($feedRules['ftporsftp'])){
    $feedRules['ftporsftp']="ftp";
}

$AttributesDropdown = $feedRules['provider'] . "AttributesDropdown";
//echo "<pre>";print_r($feedRules);
?>
<!--suppress SpellCheckingInspection, HtmlFormInputWithoutLabel, HtmlDeprecatedAttribute -->
<div class="wrap" id="Feed">
<h2><?php _e('WooCommerce Product Feed', 'woo-feed'); ?></h2>
    <?php echo WPFFWMessage()->infoMessage1(); ?>

<form action="" name="feed" method="post" id="updatefeed" autocomplete="disabled" >
    <input type="hidden" name="feed_name" value="<?php echo $fname; ?>">
    <input type="hidden" name="feed_id" value="<?php echo $feedId; ?>">
    <?php wp_nonce_field('wf_edit_feed'); ?>
<table class=" widefat fixed">
    <thead>
    <tr>
        <td colspan="2"><b><?php _e('Content Settings', 'woo-feed'); ?></b></td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td width="30%"><b><?php _e('Template', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
        <td>
            <select name="provider" id="provider" class="generalInput">
                <?php echo $dropDown->merchantsDropdown(esc_attr($feedRules['provider'])); ?>
            </select>
        </td>
    </tr>
    <tr>
        <td><b><?php _e('File Name', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
        <td><input name="filename"
                   value="<?php echo isset($feedRules['filename']) ? esc_attr($feedRules['filename']) : ''; ?>"
                   type="text"
                   class="generalInput"/></td>
    </tr>
    <tr>
        <td><b><?php _e('Feed Type', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
        <td>
            <select name="feedType" id="feedType" class="generalInput">
                <option <?php echo ($feedRules['feedType'] == "xml") ? 'selected="selected"' : ''; ?> value="xml">
                    XML
                </option>
                <option <?php echo ($feedRules['feedType'] == "csv") ? 'selected="selected"' : ''; ?> value="csv">
                    CSV
                </option>
                <option <?php echo ($feedRules['feedType'] == "txt") ? 'selected="selected"' : ''; ?> value="txt">
                    TXT
                </option>
            </select>
        </td>
    </tr>

    <tr class="itemWrapper" <?php echo ($feedRules['feedType'] != "xml") ? 'style="display: none;"' : ''; ?> >
        <td><b><?php _e('Items Wrapper', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
        <td><input name="itemsWrapper" type="text"
                   value="<?php echo ($feedRules['feedType'] == "xml") && isset($feedRules['itemsWrapper']) ? esc_attr($feedRules['itemsWrapper']) : 'products'; ?>"
                   class="generalInput" required="required"/>
        </td>
    </tr>
    <tr class="itemWrapper" <?php echo ($feedRules['feedType'] != "xml") ? 'style="display: none;"' : ''; ?>>
        <td><b><?php _e('Single Item Wrapper', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
        <td><input name="itemWrapper" type="text"
                   value="<?php echo ($feedRules['feedType'] == "xml") && isset($feedRules['itemWrapper']) ? esc_attr($feedRules['itemWrapper']) : 'product'; ?>"
                   class="generalInput" required="required"/>
        </td>
    </tr>
    <tr class="itemWrapper" >
        <td><b><?php _e('Extra Header', 'woo-feed'); ?> </b></td>
        <td>
            <textarea name="extraHeader"  style="width: 100%" placeholder="Insert Extra Header value. Press enter at the end of each line."
                      rows="3"><?php echo isset($feedRules['extraHeader']) ? $feedRules['extraHeader'] : ''; ?></textarea>
        </td>
    </tr>
    <tr class="wf_csvtxt" <?php echo ($feedRules['feedType'] == "xml") ? 'style="display: none;"' : ''; ?>>
        <td><b><?php _e('Delimiter', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
        <td>
            <select name="delimiter" id="delimiter" class="generalInput">
                <option <?php echo isset($feedRules['delimiter']) && $feedRules['delimiter'] == "," ? 'selected="selected"' : ''; ?>
                    value=",">Comma
                </option>
                <option <?php echo isset($feedRules['delimiter']) && $feedRules['delimiter'] == "tab" ? 'selected="selected"' : ''; ?>
                    value="tab">Tab
                </option>
                <option <?php echo isset($feedRules['delimiter']) && $feedRules['delimiter'] == ":" ? 'selected="selected"' : ''; ?>
                    value=":">Colon
                </option>
                <option <?php echo isset($feedRules['delimiter']) && $feedRules['delimiter'] == " " ? 'selected="selected"' : ''; ?>
                    value=" ">Space
                </option>
                <option <?php echo isset($feedRules['delimiter']) && $feedRules['delimiter'] == "|" ? 'selected="selected"' : ''; ?>
                    value="|">Pipe
                </option>
                <option <?php echo isset($feedRules['delimiter']) && $feedRules['delimiter'] == ";" ? 'selected="selected"' : ''; ?>
                    value=";">Semi Colon
                </option>
            </select>
        </td>
    </tr>
    <tr class="wf_csvtxt" <?php echo ($feedRules['feedType'] == "xml") ? 'style="display: none;"' : ''; ?>>
        <td><b><?php _e('Enclosure', 'woo-feed'); ?> <span class="requiredIn">*</span></b></td>
        <td>
            <select name="enclosure" id="enclosure" class="generalInput">
                <option <?php echo isset($feedRules['enclosure']) && $feedRules['enclosure'] == " " ? 'selected="selected"' : ''; ?>
                    value=" ">None
                </option>
                <option <?php echo isset($feedRules['enclosure']) && $feedRules['enclosure'] == "double" ? 'selected="selected"' : ''; ?>
                    value='double'>"
                </option>
                <option <?php echo isset($feedRules['enclosure']) && $feedRules['enclosure'] == "single" ? 'selected="selected"' : ''; ?>
                    value="single">'
                </option>
            </select>
        </td>
    </tr>
    </tbody>
</table>
<br/><br/>
<ul class="wf_tabs">
<li>
    <input type="radio" name="wf_tabs" id="tab1" checked/>
    <label class="wf-tab-name" for="tab1"><?php _e('Feed Config', 'woo-feed'); ?></label>

    <div id="wf-tab-content1" class="wf-tab-content">
        <table class="table tree widefat fixed sorted_table mtable" width="100%" id="table-1">
            <thead>
            <tr>
                <th></th>
                <th><?php echo ucfirst($provider); ?> <?php _e('Attributes', 'woo-feed'); ?></th>
                <th><?php _e('Prefix', 'woo-feed'); ?></th>
                <th><?php _e('Type', 'woo-feed'); ?></th>
                <th><?php _e('Value', 'woo-feed'); ?></th>
                <th><?php _e('Suffix', 'woo-feed'); ?></th>
                <th><?php _e('Output Type', 'woo-feed'); ?></th>
                <th><?php _e('Output Limit', 'woo-feed'); ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (count($feedRules['mattributes']) > 0) {
                $mAttributes = $feedRules['mattributes'];
                $wooAttributes = $feedRules['attributes'];
                $type = $feedRules['type'];
                $default = $feedRules['default'];
                $prefix = $feedRules['prefix'];
                $suffix = $feedRules['suffix'];
                $outputType = $feedRules['output_type'];
                $limit = $feedRules['limit'];
                $counter = 0;
                foreach ($mAttributes as $merchant => $mAttribute) {
                    ?>
                    <tr>

                        <td>
                            <i class="wf_sortedtable dashicons dashicons-menu"></i>
                        </td>
                        <td>
                            <select name="mattributes[]"  class="wf_mattributes">
                                <?php echo $dropDown->$AttributesDropdown(esc_attr($mAttribute)); ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="prefix[]" value="<?php echo stripslashes($prefix[$merchant]); ?>" autocomplete="off" class="wf_ps"/>
                        </td>
                        <td>
                            <select name="type[]"  class="attr_type wfnoempty">
                                <option <?php echo ($type[$merchant] == "attribute") ? 'selected="selected"' : ''; ?>value="attribute"><?php _e('Attribute', 'woo-feed'); ?></option>
                                <option <?php echo ($type[$merchant] == "pattern") ? 'selected="selected"' : ''; ?>value="pattern"><?php _e('Pattern', 'woo-feed'); ?></option>
                            </select>
                        </td>
                        <td>
                            <select <?php echo ($type[$merchant] == "attribute") ? '' : 'style=" display: none;"'; ?> name="attributes[]" class="wf_attr wf_attributes">
                                <?php echo $product->attributeDropdown(esc_attr($wooAttributes[$merchant])); ?>
                            </select>
	                        <?php if( in_array( $feedRules['provider'], array( 'google', 'facebook', 'pinterest') ) && $mAttribute=="current_category") { ?>
                                <span class="wf_default wf_attributes">
                                                <select name="default[]" id="googleTaxonomyId">
                                                    <?php echo $dropDown->googleTaxonomy( esc_attr($default[$merchant]) ); ?>
                                                </select>
                                            </span>
                                <span style="font-size:x-small;"><a style="color: red" href="http://webappick.helpscoutdocs.com/article/19-how-to-map-store-category-with-merchant-category" target="_blank">Learn More..</a></span>
	                        <?php }else{ ?>
                                <input <?php echo ($type[$merchant] == "pattern") ? '' : 'style=" display: none;"'; ?>autocomplete="off" class="wf_default wf_attributes "  type="text" name="default[]" value="<?php echo esc_attr( $default[$merchant] ); ?>"/>
	                        <?php } ?>
                        </td>
                        <td>
                            <input type="text" name="suffix[]" value="<?php echo stripslashes($suffix[$merchant]); ?>" autocomplete="off" class="wf_ps"/>
                        </td>
                        <td>
                            <select name="output_type[<?php echo $counter; ?>][]" class="outputType wfnoempty" <?php echo (count($outputType[$counter]) > 1) ? 'multiple="multiple"' : ''; ?>>
                                <option <?php echo (in_array('1', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="1">Default
                                </option>
                                <option <?php echo (in_array('2', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="2">Strip Tags
                                </option>
                                <option <?php echo (in_array('3', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="3">UTF-8 Encode
                                </option>
                                <option <?php echo (in_array('4', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="4">htmlentities
                                </option>
                                <option <?php echo (in_array('5', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="5">Integer
                                </option>
                                <option <?php echo (in_array('6', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="6">Price
                                </option>
                                <option <?php echo (in_array('7', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="7">Remove Space
                                </option>
                                <option <?php echo (in_array('10', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="10">Remove ShortCodes
                                </option>
                                <option <?php echo (in_array('9', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="9">Remove Special Character
                                </option>
                                <option <?php echo (in_array('8', $outputType[$counter])) ? 'selected="selected"' : ''; ?>
                                    value="8">CDATA
                                </option>
                            </select>
                            <i class="dashicons dashicons-editor-expand expandType"></i>
                            <i style="display: none;"
                               class="dashicons dashicons-editor-contract contractType"></i>
                        </td>
                        <td>
                            <input type="text" name="limit[]" value="<?php echo esc_attr($limit[$merchant]); ?>" autocomplete="off" class="wf_ps"/>
                        </td>
                        <td>
                            <i class="delRow dashicons dashicons-trash"></i>
                        </td>
                    </tr>

                    <?php
                    $counter++;
                }
            }
            ?>

            </tbody>
            <tfoot>
            <tr>
                <td colspan="3">
                    <button type="button" class="button-small button-primary"
                            id="wf_newRow"><?php _e('Add New Row', 'woo-feed'); ?>
                    </button>
                </td>
                <td colspan="6">

                </td>
            </tr>
            </tfoot>
        </table>
        <table class=" widefat fixed">
            <tr>
                <td align="left" class="">
                    <div class="makeFeedResponse"></div>
                    <div class="makeFeedComplete"></div>
                </td>
                <td align="right">
                    <button name="<?php echo isset($_GET['action']) ? esc_attr($_GET['action']) : ''; ?>" type="submit" class="wfbtn updatefeed"><?php _e('Update and Generate Feed', 'woo-feed'); ?></button>
                </td>
            </tr>
        </table>
    </div>
</li>
<li>
    <input type="radio" name="wf_tabs" id="tab3"/>
    <label class="wf-tab-name" for="tab3"><?php _e('FTP / SFTP', 'woo-feed'); ?></label>

    <div id="wf-tab-content3" class="wf-tab-content">
        <table class="table widefat fixed mtable" width="100%">
	        <?php if( ! checkFTP_connection() && ! checkSFTP_connection() ) { ?>
		        <tbody>
		        <tr>
			        <th><?php esc_attr_e( 'FTP/SFTP module is not found in your server. Please contact your service provider or system administrator to install/enable FTP/SFTP module.', 'woo-feed' ); ?></th>
		        </tr>
		        </tbody>
	        <?php } else { ?>
            <tbody>
            <tr>
                <td><?php _e('Enabled', 'woo-feed'); ?></td>
                <td>
                    <select name="ftpenabled" >
                        <option <?php echo ($feedRules['ftpenabled'] == "0") ? 'selected="selected"' : ''; ?>
                            value="0"><?php _e('Disabled', 'woo-feed'); ?>
                        </option>
                        <option <?php echo ($feedRules['ftpenabled'] == "1") ? 'selected="selected"' : ''; ?>
                            value="1"><?php _e('Enabled', 'woo-feed'); ?>
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><?php _e( 'Server Type', 'woo-feed' ); ?></td>
                <td>
                    <select name="ftporsftp"  class="ftporsftp">
                        <option <?php echo ($feedRules['ftporsftp'] == "ftp") ? 'selected="selected"' : ''; ?> value="ftp"><?php _e( 'FTP', 'woo-feed' ); ?></option>
                        <option <?php echo ($feedRules['ftporsftp'] == "sftp") ? 'selected="selected"' : ''; ?>value="sftp"><?php _e( 'SFTP', 'woo-feed' ); ?></option>
                    </select>
                    <span class="ssh2_status"></span>
                </td>
            </tr>
            <tr>
                <td><?php _e('Host Name', 'woo-feed'); ?></td>
                <td><input type="text" value="<?php echo esc_attr($feedRules['ftphost']); ?>" name="ftphost"
                           autocomplete="off"/></td>
            </tr>
            <tr>
                <td><?php _e('User Name', 'woo-feed'); ?></td>
                <td><input type="text" value="<?php echo esc_attr($feedRules['ftpuser']); ?>" name="ftpuser"
                           autocomplete="off"/></td>
            </tr>
            <tr>
                <td><?php _e('Password', 'woo-feed'); ?></td>
                <td><input type="password" value="<?php echo esc_attr($feedRules['ftppassword']); ?>"
                           name="ftppassword" autocomplete="off"/></td>
            </tr>
            <tr>
                <td><?php _e('Port', 'woo-feed'); ?></td>
                <td><input type="text" value="<?php echo  isset($feedRules['ftpport'])?esc_attr($feedRules['ftpport']):21; ?>"
                           name="ftpport" autocomplete="off"/></td>
            </tr>
            <tr>
                <td><?php _e('Path (Optional)', 'woo-feed'); ?></td>
                <td><input type="text" value="<?php echo esc_attr($feedRules['ftppath']); ?>" name="ftppath"
                           autocomplete="off"/></td>
            </tr>
            </tbody>
	        <?php } ?>
        </table>
        <table class="widefat fixed">
            <tr>
                <td align="left" class="">
                    <div class="makeFeedResponse"></div>
                    <div class="makeFeedComplete"></div>
                </td>
                <td align="right">
                    <button name="<?php echo isset($_GET['action']) ? esc_attr($_GET['action']) : ''; ?>" type="submit" class="wfbtn updatefeed"><?php _e('Update and Generate Feed', 'woo-feed'); ?></button>
                </td>
            </tr>
        </table>
    </div>
</li>

</ul>
</form>
</div>