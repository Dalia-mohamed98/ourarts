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
                    <th><?php _e('Command', 'woo-feed'); ?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <i class="wf_sortedtable dashicons dashicons-menu"></i>
                    </td>
                    <td>
                        <input type="text" name="mattributes[]" autocomplete="off" required
                               class="wf_validate_attr wf_mattributes" value="PRODUCT_URL"/>
                    </td>
                    <td>
                        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <select name="type[]" class="attr_type wfnoempty">
                            <option value="attribute"><?php _e('Attribute', 'woo-feed'); ?></option>
                            <option value="pattern"><?php _e('Pattern', 'woo-feed'); ?></option>
                        </select>
                    </td>
                    <td>
                        <select name="attributes[]" id="" required="required"
                                class="wf_validate_attr wf_attr wf_attributes">
                            <?php echo $product->attributeDropdown('link'); ?>
                        </select>
                        <input type="text" name="default[]" autocomplete="off" class="wf_default wf_attributes"
                               style=" display: none;"/>
                    </td>
                    <td>
                        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <select name="output_type[][]" id="" class="outputType wfnoempty">
                            <option value="1">Default</option>
                            <option value="2">Strip Tags</option>
                            <option value="3">UTF-8 Encode</option>
                            <option value="4">htmlentities</option>
                            <option value="5">Integer</option>
                            <option value="6">Price</option>
                            <option value="7">Remove Space</option><option value="10">Remove ShortCodes</option><option value="9">Remove Special Character</option>
                            <option value="8">CDATA</option>
                        </select>
                        <i class="dashicons dashicons-editor-expand expandType"></i>
                        <i style="display: none;" class="dashicons dashicons-editor-contract contractType"></i>
                    </td>
                    <td>
                        <input type="text" name="limit[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <i class="delRow dashicons dashicons-trash"></i>
                    </td>
                </tr>

                <tr>
                    <td>
                        <i class="wf_sortedtable dashicons dashicons-menu"></i>
                    </td>
                    <td>
                        <input type="text" name="mattributes[]" autocomplete="off" required
                               class="wf_validate_attr wf_mattributes" value="PRODUCT_NAME"/>
                    </td>
                    <td>
                        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <select name="type[]" class="attr_type wfnoempty">
                            <option value="attribute"><?php _e('Attribute', 'woo-feed'); ?></option>
                            <option value="pattern"><?php _e('Pattern', 'woo-feed'); ?></option>
                        </select>
                    </td>
                    <td>
                        <select name="attributes[]" id="" required="required"
                                class="wf_validate_attr wf_attr wf_attributes">
                            <?php echo $product->attributeDropdown('title'); ?>
                        </select>
                        <input type="text" name="default[]" autocomplete="off" class="wf_default wf_attributes"
                               style=" display: none;"/>
                    </td>
                    <td>
                        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <select name="output_type[][]" id="" class="outputType wfnoempty">
                            <option value="1">Default</option>
                            <option value="2">Strip Tags</option>
                            <option value="3">UTF-8 Encode</option>
                            <option value="4">htmlentities</option>
                            <option value="5">Integer</option>
                            <option value="6">Price</option>
                            <option value="7">Remove Space</option><option value="10">Remove ShortCodes</option><option value="9">Remove Special Character</option>
                            <option value="8">CDATA</option>
                        </select>
                        <i class="dashicons dashicons-editor-expand expandType"></i>
                        <i style="display: none;" class="dashicons dashicons-editor-contract contractType"></i>
                    </td>
                    <td>
                        <input type="text" name="limit[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <i class="delRow dashicons dashicons-trash"></i>
                    </td>
                </tr>

                <tr>
                    <td>
                        <i class="wf_sortedtable dashicons dashicons-menu"></i>
                    </td>
                    <td>
                        <input type="text" name="mattributes[]" autocomplete="off" required
                               class="wf_validate_attr wf_mattributes" value="DETAILS"/>
                    </td>
                    <td>
                        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <select name="type[]" class="attr_type wfnoempty">
                            <option value="attribute"><?php _e('Attribute', 'woo-feed'); ?></option>
                            <option value="pattern"><?php _e('Pattern', 'woo-feed'); ?></option>
                        </select>
                    </td>
                    <td>
                        <select name="attributes[]" id="" required="required"
                                class="wf_validate_attr wf_attr wf_attributes">
                            <?php echo $product->attributeDropdown('description'); ?>
                        </select>
                        <input type="text" name="default[]" autocomplete="off" class="wf_default wf_attributes"
                               style=" display: none;"/>
                    </td>
                    <td>
                        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <select name="output_type[][]" id="" class="outputType wfnoempty">
                            <option value="1">Default</option>
                            <option value="2">Strip Tags</option>
                            <option value="3">UTF-8 Encode</option>
                            <option value="4">htmlentities</option>
                            <option value="5">Integer</option>
                            <option value="6">Price</option>
                            <option value="7">Remove Space</option><option value="10">Remove ShortCodes</option><option value="9">Remove Special Character</option>
                            <option value="8">CDATA</option>
                        </select>
                        <i class="dashicons dashicons-editor-expand expandType"></i>
                        <i style="display: none;" class="dashicons dashicons-editor-contract contractType"></i>
                    </td>
                    <td>
                        <input type="text" name="limit[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <i class="delRow dashicons dashicons-trash"></i>
                    </td>
                </tr>

                <tr>
                    <td>
                        <i class="wf_sortedtable dashicons dashicons-menu"></i>
                    </td>
                    <td>
                        <input type="text" name="mattributes[]" autocomplete="off" required
                               class="wf_validate_attr wf_mattributes" value="PRODUCTCODE"/>
                    </td>
                    <td>
                        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <select name="type[]" class="attr_type wfnoempty">
                            <option value="attribute"><?php _e('Attribute', 'woo-feed'); ?></option>
                            <option value="pattern"><?php _e('Pattern', 'woo-feed'); ?></option>
                        </select>
                    </td>
                    <td>
                        <select name="attributes[]" id="" required="required"
                                class="wf_validate_attr wf_attr wf_attributes">
                            <?php echo $product->attributeDropdown('id'); ?>
                        </select>
                        <input type="text" name="default[]" autocomplete="off" class="wf_default wf_attributes"
                               style=" display: none;"/>
                    </td>
                    <td>
                        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <select name="output_type[][]" id="" class="outputType wfnoempty">
                            <option value="1">Default</option>
                            <option value="2">Strip Tags</option>
                            <option value="3">UTF-8 Encode</option>
                            <option value="4">htmlentities</option>
                            <option value="5">Integer</option>
                            <option value="6">Price</option>
                            <option value="7">Remove Space</option><option value="10">Remove ShortCodes</option><option value="9">Remove Special Character</option>
                            <option value="8">CDATA</option>
                        </select>
                        <i class="dashicons dashicons-editor-expand expandType"></i>
                        <i style="display: none;" class="dashicons dashicons-editor-contract contractType"></i>
                    </td>
                    <td>
                        <input type="text" name="limit[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <i class="delRow dashicons dashicons-trash"></i>
                    </td>
                </tr>

                <tr>
                    <td>
                        <i class="wf_sortedtable dashicons dashicons-menu"></i>
                    </td>
                    <td>
                        <input type="text" name="mattributes[]" autocomplete="off" required
                               class="wf_validate_attr wf_mattributes" value="CURRENCY"/>
                    </td>
                    <td>
                        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <select name="type[]" class="attr_type wfnoempty">
                            <option value="attribute"><?php _e('Attribute', 'woo-feed'); ?></option>
                            <option value="pattern"><?php _e('Pattern', 'woo-feed'); ?></option>
                        </select>
                    </td>
                    <td>
                        <select name="attributes[]" id="" required="required"
                                class="wf_validate_attr wf_attr wf_attributes">
                            <?php echo $product->attributeDropdown(''); ?>
                        </select>
                        <input type="text" name="default[]" autocomplete="off" class="wf_default wf_attributes"
                               style=" display: none;"/>
                    </td>
                    <td>
                        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <select name="output_type[][]" id="" class="outputType wfnoempty">
                            <option value="1">Default</option>
                            <option value="2">Strip Tags</option>
                            <option value="3">UTF-8 Encode</option>
                            <option value="4">htmlentities</option>
                            <option value="5">Integer</option>
                            <option value="6">Price</option>
                            <option value="7">Remove Space</option><option value="10">Remove ShortCodes</option><option value="9">Remove Special Character</option>
                            <option value="8">CDATA</option>
                        </select>
                        <i class="dashicons dashicons-editor-expand expandType"></i>
                        <i style="display: none;" class="dashicons dashicons-editor-contract contractType"></i>
                    </td>
                    <td>
                        <input type="text" name="limit[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <i class="delRow dashicons dashicons-trash"></i>
                    </td>
                </tr>

                <tr>
                    <td>
                        <i class="wf_sortedtable dashicons dashicons-menu"></i>
                    </td>
                    <td>
                        <input type="text" name="mattributes[]" autocomplete="off" required
                               class="wf_validate_attr wf_mattributes" value="PRICE"/>
                    </td>
                    <td>
                        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <select name="type[]" class="attr_type wfnoempty">
                            <option value="attribute"><?php _e('Attribute', 'woo-feed'); ?></option>
                            <option value="pattern"><?php _e('Pattern', 'woo-feed'); ?></option>
                        </select>
                    </td>
                    <td>
                        <select name="attributes[]" id="" required="required"
                                class="wf_validate_attr wf_attr wf_attributes">
                            <?php echo $product->attributeDropdown('price'); ?>
                        </select>
                        <input type="text" name="default[]" autocomplete="off" class="wf_default wf_attributes"
                               style=" display: none;"/>
                    </td>
                    <td>
                        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <select name="output_type[][]" id="" class="outputType wfnoempty">
                            <option value="1">Default</option>
                            <option value="2">Strip Tags</option>
                            <option value="3">UTF-8 Encode</option>
                            <option value="4">htmlentities</option>
                            <option value="5">Integer</option>
                            <option value="6">Price</option>
                            <option value="7">Remove Space</option><option value="10">Remove ShortCodes</option><option value="9">Remove Special Character</option>
                            <option value="8">CDATA</option>
                        </select>
                        <i class="dashicons dashicons-editor-expand expandType"></i>
                        <i style="display: none;" class="dashicons dashicons-editor-contract contractType"></i>
                    </td>
                    <td>
                        <input type="text" name="limit[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <i class="delRow dashicons dashicons-trash"></i>
                    </td>
                </tr>
                <tr>
                    <td>
                        <i class="wf_sortedtable dashicons dashicons-menu"></i>
                    </td>
                    <td>
                        <input type="text" name="mattributes[]" autocomplete="off" required
                               class="wf_validate_attr wf_mattributes" value="SHIPMENT_COST"/>
                    </td>
                    <td>
                        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <select name="type[]" class="attr_type wfnoempty">
                            <option value="attribute"><?php _e('Attribute', 'woo-feed'); ?></option>
                            <option value="pattern"><?php _e('Pattern', 'woo-feed'); ?></option>
                        </select>
                    </td>
                    <td>
                        <select name="attributes[]" id="" required="required"
                                class="wf_validate_attr wf_attr wf_attributes">
                            <?php echo $product->attributeDropdown(); ?>
                        </select>
                        <input type="text" name="default[]" autocomplete="off" class="wf_default wf_attributes"
                               style=" display: none;"/>
                    </td>
                    <td>
                        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <select name="output_type[][]" id="" class="outputType wfnoempty">
                            <option value="1">Default</option>
                            <option value="2">Strip Tags</option>
                            <option value="3">UTF-8 Encode</option>
                            <option value="4">htmlentities</option>
                            <option value="5">Integer</option>
                            <option value="6">Price</option>
                            <option value="7">Remove Space</option><option value="10">Remove ShortCodes</option><option value="9">Remove Special Character</option>
                            <option value="8">CDATA</option>
                        </select>
                        <i class="dashicons dashicons-editor-expand expandType"></i>
                        <i style="display: none;" class="dashicons dashicons-editor-contract contractType"></i>
                    </td>
                    <td>
                        <input type="text" name="limit[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <i class="delRow dashicons dashicons-trash"></i>
                    </td>
                </tr>
                <tr>
                    <td>
                        <i class="wf_sortedtable dashicons dashicons-menu"></i>
                    </td>
                    <td>
                        <input type="text" name="mattributes[]" autocomplete="off" required
                               class="wf_validate_attr wf_mattributes" value="DELIVERY_TIME"/>
                    </td>
                    <td>
                        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <select name="type[]" class="attr_type wfnoempty">
                            <option value="attribute"><?php _e('Attribute', 'woo-feed'); ?></option>
                            <option value="pattern"><?php _e('Pattern', 'woo-feed'); ?></option>
                        </select>
                    </td>
                    <td>
                        <select name="attributes[]" id="" required="required"
                                class="wf_validate_attr wf_attr wf_attributes">
                            <?php echo $product->attributeDropdown(); ?>
                        </select>
                        <input type="text" name="default[]" autocomplete="off" class="wf_default wf_attributes"
                               style=" display: none;"/>
                    </td>
                    <td>
                        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <select name="output_type[][]" id="" class="outputType wfnoempty">
                            <option value="1">Default</option>
                            <option value="2">Strip Tags</option>
                            <option value="3">UTF-8 Encode</option>
                            <option value="4">htmlentities</option>
                            <option value="5">Integer</option>
                            <option value="6">Price</option>
                            <option value="7">Remove Space</option><option value="10">Remove ShortCodes</option><option value="9">Remove Special Character</option>
                            <option value="8">CDATA</option>
                        </select>
                        <i class="dashicons dashicons-editor-expand expandType"></i>
                        <i style="display: none;" class="dashicons dashicons-editor-contract contractType"></i>
                    </td>
                    <td>
                        <input type="text" name="limit[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <i class="delRow dashicons dashicons-trash"></i>
                    </td>
                </tr>
                <tr>
                    <td>
                        <i class="wf_sortedtable dashicons dashicons-menu"></i>
                    </td>
                    <td>
                        <input type="text" name="mattributes[]" autocomplete="off" required
                               class="wf_validate_attr wf_mattributes" value="IMAGE"/>
                    </td>
                    <td>
                        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <select name="type[]" class="attr_type wfnoempty">
                            <option value="attribute"><?php _e('Attribute', 'woo-feed'); ?></option>
                            <option value="pattern"><?php _e('Pattern', 'woo-feed'); ?></option>
                        </select>
                    </td>
                    <td>
                        <select name="attributes[]" id="" required="required"
                                class="wf_validate_attr wf_attr wf_attributes">
                            <?php echo $product->attributeDropdown('image'); ?>
                        </select>
                        <input type="text" name="default[]" autocomplete="off" class="wf_default wf_attributes"
                               style=" display: none;"/>
                    </td>
                    <td>
                        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <select name="output_type[][]" id="" class="outputType wfnoempty">
                            <option value="1">Default</option>
                            <option value="2">Strip Tags</option>
                            <option value="3">UTF-8 Encode</option>
                            <option value="4">htmlentities</option>
                            <option value="5">Integer</option>
                            <option value="6">Price</option>
                            <option value="7">Remove Space</option><option value="10">Remove ShortCodes</option><option value="9">Remove Special Character</option>
                            <option value="8">CDATA</option>
                        </select>
                        <i class="dashicons dashicons-editor-expand expandType"></i>
                        <i style="display: none;" class="dashicons dashicons-editor-contract contractType"></i>
                    </td>
                    <td>
                        <input type="text" name="limit[]" autocomplete="off" class="wf_ps"/>
                    </td>
                    <td>
                        <i class="delRow dashicons dashicons-trash"></i>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td>
                        <button type="button" class="button-small button-primary" id="wf_newRow">
                            <?php _e('Add New Row', 'woo-feed'); ?>
                        </button>
                    </td>
                    <td colspan="8">

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
                        <button type="submit" class="wfbtn"><?php _e( 'Save & Generate Feed', 'woo-feed' ); ?></button>
                    </td>
                </tr>
            </table>
        </div>
    </li>
    <?php if (get_option('woocommerce_product_feed_pro_activated') && get_option('woocommerce_product_feed_pro_activated') == "Activated") { ?>
        <li>
            <input type="radio" name="wf_tabs" id="tab2"/>
            <label class="wf-tab-name" for="tab2"><?php _e( 'Filter', 'woo-feed' ); ?></label>

            <div id="wf-tab-content2" class="wf-tab-content">
                <table class="table widefat fixed sorted_table mtable" width="100%" id="table-filter">
                    <tbody>
                    <tr>
                        <td><?php _e("Composite Product Price", 'woo-feed'); ?></td>
                        <td>
                            <select name="composite_price">
                                <option value="parent_product_price"><?= _e("Parent Product Price", "woo-feed"); ?></option>
                                <option value="all_product_price"><?= _e("All Product Price", 'woo-feed'); ?></option>
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class=" widefat fixed">
                    <tr>
                        <td align="left" class="makeFeedResponse">

                        </td>
                        <td align="right">
                            <button type="submit" class="wfbtn"><?php _e( 'Save & Generate Feed', 'woo-feed' ); ?></button>
                        </td>
                    </tr>
                </table>
            </div>
        </li>

        <li>
            <input type="radio" name="wf_tabs" id="tab3"/>
            <label class="wf-tab-name" for="tab3"><?php _e('Advanced Filter', 'woo-feed'); ?></label>

            <div id="wf-tab-content3" class="wf-tab-content">
                <table class="table tree widefat fixed sorted_table mtable" width="100%" id="table-filter">
                    <thead>
                    <tr>
                        <th></th>
                        <th><?php _e('Attributes', 'woo-feed'); ?></th>
                        <th><?php _e('Condition', 'woo-feed'); ?></th>
                        <th><?php _e('Value', 'woo-feed'); ?></th>
                        <th></th>
                    </tr>
                    <tr style="border-bottom: 2px solid #ccc">
                        <td><?php _e('Filter', 'woo-feed'); ?> </td>
                        <td colspan="4">
                            <select name="filterType" class="">
                                <option value="2"><?php _e('Together', 'woo-feed'); ?></option>
                                <option value="1"><?php _e('Individually', 'woo-feed'); ?></option>
                            </select>
                        </td>
                    </tr>
                    </thead>
                    <tbody>

                    <tr style="display:none;" class="daRow">
                        <td>
                            <i class="wf_sortedtable dashicons dashicons-menu"></i>
                        </td>
                        <td>
                            <select name="fattribute[]" id="" disabled required class="fsrow">
                                <?php echo $product->attributeDropdown(); ?>
                            </select>
                        </td>
                        <td>
                            <select name="condition[]" disabled class="fsrow">
                                <option value="=="><?php _e('is / equal', 'woo-feed'); ?></option>
                                <option value="!="><?php _e('is not / not equal', 'woo-feed'); ?></option>
                                <option value=">="><?php _e('equals or greater than', 'woo-feed'); ?></option>
                                <option value=">"><?php _e('greater than', 'woo-feed'); ?></option>
                                <option value="<="><?php _e('equals or less than', 'woo-feed'); ?></option>
                                <option value="<"><?php _e('less than', 'woo-feed'); ?></option>
                                <option value="contains"><?php _e('contains', 'woo-feed'); ?></option>
                                <option value="nContains"><?php _e('does not contain', 'woo-feed'); ?></option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="filterCompare[]" disabled autocomplete="off" class="fsrow"/>
                        </td>
                        <td>
                            <i class="delRow dashicons dashicons-trash"></i>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>
                            <button type="button" class="button-small button-primary" id="wf_newFilter">
                                <?php _e('Add New Condition', 'woo-feed'); ?>
                            </button>
                        </td>
                        <td colspan="4">

                        </td>
                    </tr>
                    </tfoot>
                </table>
                <table class=" widefat fixed">
                    <tr>
                        <td align="left" class="makeFeedResponse">

                        </td>
                        <td align="right">
                            <button type="submit" class="wfbtn"><?php _e( 'Save & Generate Feed', 'woo-feed' ); ?></button>
                        </td>
                    </tr>
                </table>
            </div>
        </li>
    <?php } ?>
    <li>
        <input type="radio" name="wf_tabs" id="tab4"/>
        <label class="wf-tab-name" for="tab4"><?php _e('FTP / SFTP', 'woo-feed'); ?></label>

        <div id="wf-tab-content4" class="wf-tab-content">
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
                        <select name="ftpenabled" id="">
                            <option value="0"><?php _e('Disabled', 'woo-feed'); ?></option>
                            <option value="1"><?php _e('Enabled', 'woo-feed'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><?php _e( 'Server Type', 'woo-feed' ); ?></td>
                    <td>
                        <select name="ftporsftp" id="" class="ftporsftp">
                            <option value="ftp"><?php _e( 'FTP', 'woo-feed' ); ?></option>
                            <option value="sftp"><?php _e( 'SFTP', 'woo-feed' ); ?></option>
                        </select>
                        <span class="ssh2_status"></span>
                    </td>
                </tr>
                <tr>
                    <td><?php _e('Host Name', 'woo-feed'); ?></td>
                    <td><input type="text" name="ftphost"/></td>
                </tr>

                <tr>
                    <td><?php _e( 'Port', 'woo-feed' ); ?></td>
                    <td><input type="text" name="ftpport" value="21"/></td>
                </tr>
                <tr>
                    <td><?php _e('User Name', 'woo-feed'); ?></td>
                    <td><input type="text" name="ftpuser"/></td>
                </tr>
                <tr>
                    <td><?php _e('Password', 'woo-feed'); ?></td>
                    <td><input type="password" name="ftppassword"/></td>
                </tr>
                <tr>
                    <td><?php _e('Path (Optional)', 'woo-feed'); ?></td>
                    <td><input type="text" name="ftppath"/></td>
                </tr>
                </tbody>
	            <?php } ?>
            </table>
            <table class=" widefat fixed">
                <tr>
                    <td align="left" class="makeFeedResponse">

                    </td>
                    <td align="right">
                        <button type="submit" class="wfbtn"><?php _e( 'Save & Generate Feed', 'woo-feed' ); ?></button>
                    </td>
                </tr>
            </table>
        </div>
    </li>

</ul>
