<ul class="wf_tabs">
<li>
<input type="radio" name="wf_tabs" id="tab1" checked/>
<label class="wf-tab-name" for="tab1"><?php _e('Feed Config','woo-feed');?></label>
<div id="wf-tab-content1" class="wf-tab-content">
<table class="table tree widefat fixed sorted_table mtable" width="100%" id="table-1">
<thead>
<tr>
    <th></th>
    <th><?php echo ucfirst($provider); ?> <?php _e('Attributes','woo-feed');?></th>
    <th><?php _e('Prefix','woo-feed');?></th>
    <th><?php _e('Type','woo-feed');?></th>
    <th><?php _e('Value','woo-feed');?></th>
    <th><?php _e('Suffix','woo-feed');?></th>
    <th><?php _e('Output Type','woo-feed');?></th>
    <th><?php _e('Output Limit','woo-feed');?></th>
    <th></th>
</tr>
</thead>
<tbody>
<tr>
    <td>
        <i class="wf_sortedtable dashicons dashicons-menu"></i>
    </td>
    <td>
        <select name="mattributes[]"  required class="wf_mattributes">
            <?php echo $dropDown->shopmaniaAttributesDropdown('Category'); ?>
        </select>
    </td>
    <td>
        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="type[]" class="attr_type wfnoempty">
            <option value="attribute"><?php _e('Attribute','woo-feed');?></option>
            <option value="pattern"><?php _e('Pattern','woo-feed');?></option>
        </select>
    </td>
    <td>
        <select name="attributes[]"  class="wf_attr wf_attributes">
            <?php echo $product->attributeDropdown('product_type'); ?>
        </select>
        <input type="text" name="default[]" autocomplete="off" class="wf_default wf_attributes"
               style=" display: none;"/>
    </td>
    <td>
        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="output_type[][]"  class="outputType wfnoempty">
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
        <input type="text" name="limit[]" class="wf_ps"/>
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
        <select name="mattributes[]"  required class="wf_mattributes">
            <?php echo $dropDown->shopmaniaAttributesDropdown('Manufacturer'); ?>
        </select>
    </td>
    <td>
        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="type[]" class="attr_type wfnoempty">
            <option value="attribute"><?php _e('Attribute','woo-feed');?></option>
            <option value="pattern"><?php _e('Pattern','woo-feed');?></option>
        </select>
    </td>
    <td>
        <select name="attributes[]"  class="wf_attr wf_attributes">
            <?php echo $product->attributeDropdown(); ?>
        </select>
        <input type="text" name="default[]" autocomplete="off" class="wf_default wf_attributes"
               style=" display: none;"/>
    </td>
    <td>
        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="output_type[][]"  class="outputType wfnoempty">
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
        <input type="text" name="limit[]" class="wf_ps"/>
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
        <select name="mattributes[]"  required class="wf_mattributes">
            <?php echo $dropDown->shopmaniaAttributesDropdown('Model'); ?>
        </select>
    </td>
    <td>
        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="type[]" class="attr_type wfnoempty">
            <option value="attribute"><?php _e('Attribute','woo-feed');?></option>
            <option value="pattern"><?php _e('Pattern','woo-feed');?></option>
        </select>
    </td>
    <td>
        <select name="attributes[]"  class="wf_attr wf_attributes">
            <?php echo $product->attributeDropdown(); ?>
        </select>
        <input type="text" name="default[]" autocomplete="off" class="wf_default wf_attributes"
               style=" display: none;"/>
    </td>
    <td>
        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="output_type[][]"  class="outputType wfnoempty">
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
        <input type="text" name="limit[]" class="wf_ps"/>
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
        <select name="mattributes[]"  required class="wf_mattributes">
            <?php echo $dropDown->shopmaniaAttributesDropdown('MPC'); ?>
        </select>
    </td>
    <td>
        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="type[]" class="attr_type wfnoempty">
            <option value="attribute"><?php _e('Attribute','woo-feed');?></option>
            <option value="pattern"><?php _e('Pattern','woo-feed');?></option>
        </select>
    </td>
    <td>
        <select name="attributes[]"  class="wf_attr wf_attributes">
            <?php echo $product->attributeDropdown(); ?>
        </select>
        <input type="text" name="default[]" autocomplete="off" class="wf_default wf_attributes"
               style=" display: none;"/>
    </td>
    <td>
        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="output_type[][]"  class="outputType wfnoempty">
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
        <input type="text" name="limit[]" class="wf_ps"/>
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
        <select name="mattributes[]"  required class="wf_mattributes">
            <?php echo $dropDown->shopmaniaAttributesDropdown('Name'); ?>
        </select>
    </td>
    <td>
        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="type[]" class="attr_type wfnoempty">
            <option value="attribute"><?php _e('Attribute','woo-feed');?></option>
            <option value="pattern"><?php _e('Pattern','woo-feed');?></option>
        </select>
    </td>
    <td>
        <select name="attributes[]"  class="wf_attr wf_attributes">
            <?php echo $product->attributeDropdown('title'); ?>
        </select>
        <input type="text" name="default[]" autocomplete="off" class="wf_default wf_attributes"
               style=" display: none;"/>
    </td>
    <td>
        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="output_type[][]"  class="outputType wfnoempty">
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
        <input type="text" name="limit[]" class="wf_ps"/>
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
        <select name="mattributes[]"  required class="wf_mattributes">
            <?php echo $dropDown->shopmaniaAttributesDropdown('Description'); ?>
        </select>
    </td>
    <td>
        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="type[]" class="attr_type wfnoempty">
            <option value="attribute"><?php _e('Attribute','woo-feed');?></option>
            <option value="pattern"><?php _e('Pattern','woo-feed');?></option>
        </select>
    </td>
    <td>
        <select name="attributes[]"  class="wf_attr wf_attributes">
            <?php echo $product->attributeDropdown('description'); ?>
        </select>
        <input type="text" name="default[]" autocomplete="off" class="wf_default wf_attributes"
               style=" display: none;"/>
    </td>
    <td>
        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="output_type[][]"  class="outputType wfnoempty">
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
        <input type="text" name="limit[]" class="wf_ps"/>
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
        <select name="mattributes[]"  required class="wf_mattributes">
            <?php echo $dropDown->shopmaniaAttributesDropdown('URL'); ?>
        </select>
    </td>
    <td>
        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="type[]" class="attr_type wfnoempty">
            <option value="attribute"><?php _e('Attribute','woo-feed');?></option>
            <option value="pattern"><?php _e('Pattern','woo-feed');?></option>
        </select>
    </td>
    <td>
        <select name="attributes[]"  class="wf_attr wf_attributes" >
            <?php echo $product->attributeDropdown('link'); ?>
        </select>
        <input type="text" style=" display: none;" name="default[]" autocomplete="off" class="wf_default wf_attributes"
            />
    </td>
    <td>
        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="output_type[][]"  class="outputType wfnoempty">
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
        <input type="text" name="limit[]" class="wf_ps"/>
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
        <select name="mattributes[]"  required class="wf_mattributes">
            <?php echo $dropDown->shopmaniaAttributesDropdown('Image'); ?>
        </select>
    </td>
    <td>
        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="type[]" class="attr_type wfnoempty">
            <option value="attribute"><?php _e('Attribute','woo-feed');?></option>
            <option value="pattern"><?php _e('Pattern','woo-feed');?></option>
        </select>
    </td>
    <td>
        <select name="attributes[]"  class="wf_attr wf_attributes" >
            <?php echo $product->attributeDropdown('image'); ?>
        </select>
        <input type="text" style=" display: none;" name="default[]" autocomplete="off" class="wf_default wf_attributes"
            />
    </td>
    <td>
        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="output_type[][]"  class="outputType wfnoempty">
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
        <input type="text" name="limit[]" class="wf_ps"/>
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
        <select name="mattributes[]"  required class="wf_mattributes">
            <?php echo $dropDown->shopmaniaAttributesDropdown('Price'); ?>
        </select>
    </td>
    <td>
        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="type[]" class="attr_type wfnoempty">
            <option value="attribute"><?php _e('Attribute','woo-feed');?></option>
            <option value="pattern"><?php _e('Pattern','woo-feed');?></option>
        </select>
    </td>
    <td>
        <select name="attributes[]"  class="wf_attr wf_attributes" >
            <?php echo $product->attributeDropdown('price'); ?>
        </select>
        <input type="text" style=" display: none;" name="default[]" autocomplete="off" class="wf_default wf_attributes"
            />
    </td>
    <td>
        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="output_type[][]"  class="outputType wfnoempty">
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
        <input type="text" name="limit[]" class="wf_ps"/>
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
        <select name="mattributes[]"  required class="wf_mattributes">
            <?php echo $dropDown->shopmaniaAttributesDropdown('Currency'); ?>
        </select>
    </td>
    <td>
        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="type[]" class="attr_type wfnoempty">
            <option value="attribute"><?php _e('Attribute','woo-feed');?></option>
            <option value="pattern"><?php _e('Pattern','woo-feed');?></option>
        </select>
    </td>
    <td>
        <select name="attributes[]"  class="wf_attr wf_attributes" >
            <?php echo $product->attributeDropdown(); ?>
        </select>
        <input type="text" style=" display: none;" name="default[]" autocomplete="off" class="wf_default wf_attributes"
            />
    </td>
    <td>
        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="output_type[][]"  class="outputType wfnoempty">
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
        <input type="text" name="limit[]" class="wf_ps"/>
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
        <select name="mattributes[]"  required class="wf_mattributes">
            <?php echo $dropDown->shopmaniaAttributesDropdown('Shipping'); ?>
        </select>
    </td>
    <td>
        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="type[]" class="attr_type wfnoempty">
            <option value="attribute"><?php _e('Attribute','woo-feed');?></option>
            <option value="pattern"><?php _e('Pattern','woo-feed');?></option>
        </select>
    </td>
    <td>
        <select name="attributes[]"  class="wf_attr wf_attributes" >
            <?php echo $product->attributeDropdown(); ?>
        </select>
        <input type="text" style=" display: none;" name="default[]" autocomplete="off" class="wf_default wf_attributes"
            />
    </td>
    <td>
        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="output_type[][]"  class="outputType wfnoempty">
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
        <input type="text" name="limit[]" class="wf_ps"/>
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
        <select name="mattributes[]"  required class="wf_mattributes">
            <?php echo $dropDown->shopmaniaAttributesDropdown('Availability'); ?>
        </select>
    </td>
    <td>
        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="type[]" class="attr_type wfnoempty">
            <option value="attribute"><?php _e('Attribute','woo-feed');?></option>
            <option value="pattern"><?php _e('Pattern','woo-feed');?></option>
        </select>
    </td>
    <td>
        <select name="attributes[]"  class="wf_attr wf_attributes" >
            <?php echo $product->attributeDropdown('availability'); ?>
        </select>
        <input type="text" style=" display: none;" name="default[]" autocomplete="off" class="wf_default wf_attributes"
            />
    </td>
    <td>
        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="output_type[][]"  class="outputType wfnoempty">
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
        <input type="text" name="limit[]" class="wf_ps"/>
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
        <select name="mattributes[]"  required class="wf_mattributes">
            <?php echo $dropDown->shopmaniaAttributesDropdown('GTIN'); ?>
        </select>
    </td>
    <td>
        <input type="text" name="prefix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="type[]" class="attr_type wfnoempty">
            <option value="attribute"><?php _e('Attribute','woo-feed');?></option>
            <option value="pattern"><?php _e('Pattern','woo-feed');?></option>
        </select>
    </td>
    <td>
        <select name="attributes[]"  class="wf_attr wf_attributes" >
            <?php echo $product->attributeDropdown(); ?>
        </select>
        <input type="text" style=" display: none;" name="default[]" autocomplete="off" class="wf_default wf_attributes"
            />
    </td>
    <td>
        <input type="text" name="suffix[]" autocomplete="off" class="wf_ps"/>
    </td>
    <td>
        <select name="output_type[][]"  class="outputType wfnoempty">
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
        <input type="text" name="limit[]" class="wf_ps"/>
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
            <?php _e('Add New Row','woo-feed');?>
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
            <button type="submit" class="wfbtn"><?php _e('Save & Generate Feed', 'woo-feed'); ?></button>
        </td>
    </tr>
</table>
</div>
</li>
<?php include plugin_dir_path(__FILE__) . "../woo-feed-ftp-sftp-template.php"; ?>

</ul>
