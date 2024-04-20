<?php
    /*
        Plugin Name: Expersend Send SMS
        Description: Send SMS using ExperSend SMS API
        Version: 0.0.1
        Author: Team Expergen
        Author URI: https://expersend.com/
    */

    if ( !defined('ABSPATH') ){
        die;
    }

    function ExperSend_admin_menu_pages(){
        add_menu_page('ExperSend SMS', 'ExperSend SMS', 'manage_options', 'expersend-sms', 'expersend_admin_page', 'dashicons-testimonial');
    }

    add_action('admin_menu', 'ExperSend_admin_menu_pages');

    function expersend_admin_page(){
        if ( array_key_exists('expersend-user-save', $_POST ) ){
            update_option('expersend_sms_user_id', htmlspecialchars($_POST['expersend-user-id']));
            update_option('expersend_sms_user_api', htmlspecialchars($_POST['expersend-user-api']));
            update_option('expersend_sms_user_sid', htmlspecialchars($_POST['expersend-user-sid']));
            update_option('expersend_sms_enable', $_POST['expersend-enable-sms']);
            update_option('expersend_default_message', htmlspecialchars($_POST['expersend-default-message']));
            update_option('expersend_pending_message', htmlspecialchars($_POST['expersend-pending-message']));
            update_option('expersend_failed_message', htmlspecialchars($_POST['expersend-failed-message']));
            update_option('expersend_completed_message', htmlspecialchars($_POST['expersend-completed-message']));
            update_option('expersend_onhold_message', htmlspecialchars($_POST['expersend-onhold-message']));
?>
            <div class="updated settings-error notice is-dismissible">
                <p>Settings have been saved</p>
            </div>
<?php
        }
        $get_user_id    = get_option('expersend_sms_user_id');
        $get_user_api   = get_option('expersend_sms_user_api');
        $get_user_sid   = get_option('expersend_sms_user_sid');
        $get_sms_enable = get_option('expersend_sms_enable');
        $get_default_message = ( get_option('expersend_default_message') != '' ) ? get_option('expersend_default_message') : 'your order #{{order_id}} is now {{order_status}}. Thank you for shopping at {{shop_name}}.';
        $get_pending_message = get_option('expersend_pending_message');
        $get_failed_message = get_option('expersend_failed_message');
        $get_completed_message = get_option('expersend_completed_message');
        $get_onhold_message = get_option('expersend_onhold_message');
?>
        <div class="wrap">
            <img src="https://res.cloudinary.com/dw4o2gz3v/image/upload/v1713290935/Expersend/original-cropped_xqhd8o.png" style="width: 250px;">
            <h2>ExperSend SMS</h2>
<?php
            if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
?>
                <div class="notice notice-warning settings-error is-dismissible">
                    <p><strong>You need to activate Woocommerce in order to use this plugin.</strong></p>
                </div>
<?php
            }
?>
            <h3>Your account balance is <?php echo expersend_get_acc_balance(); ?></h3>
            <form action="" method="post">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th>
                                <label for="expersend-user-id">User ID</label>
                            </th>
                            <td>
                                <input type="text" name="expersend-user-id" id="" class="regular-text" value="<?php echo $get_user_id; ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="expersend-user-api">API Key</label>
                            </th>
                            <td>
                                <input type="text" name="expersend-user-api" id="" class="regular-text" value="<?php echo $get_user_api; ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="expersend-user-api">Sender ID</label>
                            </th>
                            <td>
                                <input type="text" name="expersend-user-sid" id="" class="regular-text" value="<?php echo $get_user_sid; ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="expersend-enable-sms">Enable SMS for</label>
                            </th>
                            <td>
<?php
                                $option_array = array('pending'=>'Pending', 'failed'=>'Failed', 'processing'=>'Processing', 'completed'=>'Completed', 'on-hold'=>'On-Hold');

                                foreach ( $option_array as $key => $option ) :
                                    if ( is_array($get_sms_enable) ){
                                        $is_checked = ( in_array($key, $get_sms_enable) ) ? 'checked' : '';
                                    }
?>
                                    <input type="checkbox" name="expersend-enable-sms[]" value="<?php echo $key; ?>" <?php echo $is_checked; ?>>
                                    <label><?php echo $option; ?></label><br>
<?php
                                endforeach;
?>
                            </td>
                        </tr>
                        <tr>
                            <th>Available order details for custom message.</th>
                            <td>{{shop_name}}, {{order_id}}, {{order_amount}}, {{order_status}}, {{first_name}}, {{last_name}}, {{billing_city}}, {{customer_phone}}</td>
                        </tr>
                        <tr>
                            <th>
                                <label for="expersend-default-message">Default Message</label>
                            </th>
                            <td>
                                <textarea name="expersend-default-message" rows="5" cols="100"><?php echo $get_default_message; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="expersend-pending-message">Pending Message</label>
                            </th>
                            <td>
                                <textarea name="expersend-pending-message" rows="5" cols="100"><?php echo $get_pending_message; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="expersend-failed-message">Failed Message</label>
                            </th>
                            <td>
                                <textarea name="expersend-failed-message" rows="5" cols="100"><?php echo $get_failed_message; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="expersend-completed-message">Completed Message</label>
                            </th>
                            <td>
                                <textarea name="expersend-completed-message" rows="5" cols="100"><?php echo $get_completed_message; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="expersend-onhold-message">On-hold Message</label>
                            </th>
                            <td>
                                <textarea name="expersend-onhold-message" rows="5" cols="100"><?php echo $get_onhold_message; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <input type="submit" name="expersend-user-save" value="Save" class="button button-primary">
                            </th>
                        </tr>
                    </tbody>
                </table>
            </form>
            <br/>
<!--            <div class="updated settings-error notice">-->
<!--                <a href="http://send.expersend.com/" target="_blank" style="display: inline-block;"><p><strong>Click Here to Sign In</strong></p></a> |-->
<!--                <a href="http://send.expersend.com/dist/register.php" target="_blank" style="display: inline-block;"><p><strong>Click Here to Sign Up</strong></p></a> |-->
<!--                <a href="https://expersend.com/smsgatewayapi.html" target="_blank" style="display: inline-block;"><p><strong>Find Out More</strong></p></a>-->
<!--            </div>-->
        </div>
<?php
    }

    add_action('woocommerce_order_status_changed', 'expersend_check_order_status', 10, 3);

    function expersend_check_order_status($order_id){
        $get_sms_enable = get_option('expersend_sms_enable');

        $order = wc_get_order( $order_id );
        $get_status = $order->get_status();
        $get_billing_phone = '94' . substr($order->get_billing_phone(), -9);

        if ( $get_status == 'pending' && in_array($get_status, $get_sms_enable) ){
            $message_tmp = ( get_option('expersend_pending_message') != '' ) ? get_option('expersend_pending_message') : get_option('expersend_default_message');
            $message = expersend_formatted_message($order_id, $message_tmp);
            expersend_send_sms($get_billing_phone, $message);
        }

        if ( $get_status == 'failed' && in_array($get_status, $get_sms_enable) ){
            $message_tmp = ( get_option('expersend_failed_message') != '' ) ? get_option('expersend_failed_message') : get_option('expersend_default_message');
            $message = expersend_formatted_message($order_id, $message_tmp);
            expersend_send_sms($get_billing_phone, $message);
        }

        if ( $get_status == 'processing' && in_array($get_status, $get_sms_enable) ){
            $message_tmp = get_option('expersend_default_message');
            $message = expersend_formatted_message($order_id, $message_tmp);
            expersend_send_sms($get_billing_phone, $message);
        }

        if ( $get_status == 'completed' && in_array($get_status, $get_sms_enable) ){
            $message_tmp = ( get_option('expersend_completed_message') != '' ) ? get_option('expersend_completed_message') : get_option('expersend_default_message');
            $message = expersend_formatted_message($order_id, $message_tmp);
            expersend_send_sms($get_billing_phone, $message);
        }

        if ( $get_status == 'on-hold' && in_array($get_status, $get_sms_enable) ){
            $message_tmp = ( get_option('expersend_onhold_message') != '' ) ? get_option('expersend_onhold_message') : get_option('expersend_default_message');
            $message = expersend_formatted_message($order_id, $message_tmp);
            expersend_send_sms($get_billing_phone, $message);
        }
    }

    function expersend_formatted_message($order_id, $message_tmp){
        $order = wc_get_order( $order_id );
        $replacements_string = array(
            '{{shop_name}}' => get_bloginfo('name'),
            '{{order_id}}' => $order->get_order_number(),
            '{{order_amount}}' => $order->get_total(),
            '{{order_status}}' => ucfirst($order->get_status()),
            '{{first_name}}' => ucfirst($order->billing_first_name),
            '{{last_name}}' => ucfirst($order->billing_last_name),
            '{{billing_city}}' => ucfirst($order->billing_city),
            '{{customer_phone}}' => $order->billing_phone,
        );
        return str_replace(array_keys($replacements_string), $replacements_string, $message_tmp);
    }

    function expersend_send_sms($get_billing_phone, $message){
        $get_user_id = get_option('expersend_sms_user_id');
        $get_user_api = get_option('expersend_sms_user_api');
        $get_user_sid = get_option('expersend_sms_user_sid');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api-sms.expergen.com/send");
        curl_setopt($ch, CURLOPT_GET, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"userId=".$get_user_id."&authToken=".$get_user_api."&senderName=".$get_user_sid."&to=".$get_billing_phone."&content=".$message);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);
    }

function expersend_get_acc_balance() {
    $get_user_id = get_option('expersend_sms_user_id');
    $get_user_api = get_option('expersend_sms_user_api');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api-sms.expergen.com/status");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "userId=" . $get_user_id . "&authToken=" . $get_user_api);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close($ch);

    if ($server_output === false) {
        return "Error retrieving balance. Please check your connection and API credentials.";
    }

    $acc_details = json_decode($server_output, true);
    if (is_array($acc_details) && isset($acc_details['amount'])) {
        $acc_balance = 'Rs. ' . $acc_details['amount'];
        return $acc_balance;
    } else {
        return "Failed to retrieve balance. Response was not as expected.";
    }
}

?>