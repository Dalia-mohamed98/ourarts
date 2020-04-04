<li>
    <input type="radio" name="wf_tabs" id="tab3"/>
    <label class="wf-tab-name" for="tab3"><?php _e( 'FTP / SFTP', 'woo-feed' ); ?></label>

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
                <td><?php _e( 'Enabled', 'woo-feed' ); ?></td>
                <td>
                    <select name="ftpenabled" >
                        <option value="0"><?php _e( 'Disabled', 'woo-feed' ); ?></option>
                        <option value="1"><?php _e( 'Enabled', 'woo-feed' ); ?></option>
                    </select>

                </td>
            </tr>
            <tr>
                <td><?php _e( 'Server Type', 'woo-feed' ); ?></td>
                <td>
                    <select name="ftporsftp"  class="ftporsftp">
                        <option value="ftp"><?php _e( 'FTP', 'woo-feed' ); ?></option>
                        <option value="sftp"><?php _e( 'SFTP', 'woo-feed' ); ?></option>
                    </select>
                    <span class="ssh2_status"></span>
                </td>
            </tr>
            <tr>
                <td><?php _e( 'Host Name', 'woo-feed' ); ?></td>
                <td><input type="text" name="ftphost"/></td>
            </tr>
            <tr>
                <td><?php _e( 'User Name', 'woo-feed' ); ?></td>
                <td><input type="text" name="ftpuser"/></td>
            </tr>
            <tr>
                <td><?php _e( 'Password', 'woo-feed' ); ?></td>
                <td><input type="password" name="ftppassword"/></td>
            </tr>
            <tr>
                <td><?php _e( 'Port', 'woo-feed' ); ?></td>
                <td><input type="text" name="ftpport" value="21"/></td>
            </tr>
            <tr>
                <td><?php _e( 'Path (Optional)', 'woo-feed' ); ?></td>
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