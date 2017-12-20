<?php
/**
 * MailChimp connection class
 *
 * @package    cupg
 * @subpackage cupg/services
 *
 * Provides connection to MailChimp through API v2.0
 *
 */
class Cupg_MailChimp extends Cupg_Service {


        /**
         * Initialize class instance
         */
        public function __construct() {
            
                $available_properties = array('lists', 'double_optin', 'hidden_field');
                parent::__construct($available_properties, array('coupg_mcapikey'), 'coupg_maillists');

                $this->name = 'MailChimp';
                $this->short_name = 'mc';
                $this->api_key_info['key_help'] = 'http://kb.mailchimp.com/accounts/management/about-api-keys';
                $this->disable_double_optin_help = '';
            
        }
        
        /**
         * Subscribe with email
         * 
         * @param int $upgrade_id Content Upgrade id
         * @param string $email Email to subscribe
         * @param string $name Subscriber name
         * @return array 'status' => 'success'|'error',  'link'|'error'
         */
        public function subscribe($upgrade_id, $email, $name = '') {       

                $list_id = get_post_meta($upgrade_id, 'coupg_list', true);
                $hidden_field_text = get_post_meta($upgrade_id, 'coupg_hidden_text', true);
                
                if (!class_exists('MailChimp_v3')) {
                    require_once ('mailchimp/mailchimp_api3.0.php');
                }
                $mcapi = new MailChimp_v3($this->api_key[0]);
                $options_array = $this->get_subscribe_parameters($mcapi, $list_id, $email, $name, $hidden_field_text);
                $result = $mcapi->post("lists/$list_id/members", $options_array);
                
                 // new subscriber and already subscribed user
                if ($result !== false && key_exists('email_address', $result)) {
                    $link = $this->make_redirect_link($upgrade_id, true);
                    return array('status' => 'success', 'link' => $link);
                }
                 // already subscribed user
                if ($result['title'] === 'Member Exists') { 
                    $mcapi->put("lists/$list_id/members/" . md5(strtolower($email)), $options_array);
                    $link = $this->make_redirect_link($upgrade_id);
                    return array('status' => 'success', 'link' => $link); 
                }
                // errors
                return array('status' => 'error', 'error' => $result['title']);
        }
        
        /**
         * Get lists from email service
         * 
         * @return array Service response
         */
        public function get_lists() {
            
                if ( empty($_POST['apikey']) ) {
                    update_option($this->mail_lists_option, '');
                    return array('status' => 0, 'error' => 'API key is not set');
                }
                
                if (!class_exists('MailChimp_v3')) {
                    require_once ('mailchimp/mailchimp_api3.0.php');
                }
                $mcapi = new MailChimp_v3($_POST['apikey']);
                $result = $mcapi->get('lists', array('count' => 100));
                $listnames = '';
                $lists = array();
                
                // Got lists
                if ($result !== false && key_exists('lists', $result)) {
     
                    foreach ($result['lists'] as $list) {
                        $lists[$list['id']] = array('name' => $list['name']);
                        $listnames .= $list['name'] . "\n";
                    }
                    update_option($this->mail_lists_option, json_encode($lists));
                    return array('status' => 1, 'listnum' => $result['total_items'], 'listnames' => $listnames);
                } 
                 // Failure
                update_option($this->mail_lists_option, '');
                return array('status' => 0, 'error' => $result['title']);
        }
        
        /**
         * Check if apikey is valid
         * 
         * @param $apikey
         * @return string|boolean
         */
        protected function check_api_key($apikey) {
            
                if ( $apikey && preg_match('/^[a-zA-Z0-9]{32}-[a-zA-Z0-9]{3,4}$/', $apikey) ) {
                    return $apikey;
                } else {
                    return false;
                }
            
        }
        
        /**
         * Prepare options for subscribe request
         * 
         * @param MailChimp_v3 $mcapi MailChimp API v3 class
         * @param int $list_id List id
         * @param string $email Email to subscribe
         * @param array $name Subscriber name
         * @param string $hidden_field_text Content Upgrade value for hidden field
         * @return array
         */
        private function get_subscribe_parameters($mcapi, $list_id, $email, $name, $hidden_field_text) {
            
                $options_array = array('email_address' => $email);
                $merge_fields = (strlen($name) > 0)? array('FNAME' => $name) : array();
                $merge_fields = (strlen($hidden_field_text) > 0)? array_merge($merge_fields, array(strtoupper(self::$hidden_field) => $hidden_field_text)) : $merge_fields;
                if (count($merge_fields) > 0) {
                    $options_array['merge_fields'] = $merge_fields;
                    $this->check_fields($mcapi, $list_id, $merge_fields);
                }

                if ($this->disable_double_optin == '1') {
                    $options_array['status'] = 'subscribed';
                }
                else {
                    $options_array['status'] = 'pending';
                }
                
                return $options_array;
        }
        
        /**
         * Add field CU_Bonus to list if it is not exists
         * 
         * @param MailChimp_v3 $mcapi MailChimp API v3 class
         * @param int $list_id List id
         * @param array $merge_fields Merge Fields parameters for API v3
         */
        private function check_fields($mcapi, $list_id, $merge_fields) {
            
            foreach ($merge_fields as $key => $value) {
                
                if ($key === 'FNAME') {
                    $field_name = 'First Name';
                }
                else if ($key === 'CU_BONUS'){
                    $field_name = self::$hidden_field;
                }
                
                $mcapi->post("lists/$list_id/merge-fields", array(
                    'tag' => $key,
                    'name' => $field_name,
                    'type' => 'text'
                ));
            }
            
        }
        
}
